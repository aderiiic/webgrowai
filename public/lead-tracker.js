/* Lead tracker (resilient + fallback paths) */
(function () {
    try {
        var STARTED = false;
        var DEBUG = true; // sätt till false när allt är grönt

        function log() { if (DEBUG && window.console) console.log.apply(console, ['[lead-tracker]'].concat([].slice.call(arguments))); }
        function warn() { if (window.console) console.warn.apply(console, ['[lead-tracker]'].concat([].slice.call(arguments))); }

        // Hämta konfig
        var SITE_KEY = window.WEBBI_SITE_KEY || '';
        var TRACK_BASE = (window.WEBBI_TRACK_URL || '').replace(/\/+$/, '');
        var REQUIRE_CONSENT = !!window.WEBBI_REQUIRE_CONSENT;

        // Läs extra från script-taggen (trackPath/debug)
        var scriptEl = document.currentScript || (function () {
            var s = document.getElementsByTagName('script'); return s[s.length - 1];
        })();

        var TRACK_PATH = (scriptEl && (scriptEl.getAttribute('data-track-path') || '')) || '';
        var qs;
        try { qs = new URL(scriptEl.src, location.origin).searchParams; } catch (e) { qs = null; }
        if (!TRACK_PATH && qs) TRACK_PATH = qs.get('trackPath') || '';

        if (!TRACK_BASE && scriptEl && scriptEl.src) {
            try { TRACK_BASE = new URL(scriptEl.src, window.location.origin).origin; } catch (e) {}
        }

        if (!SITE_KEY) warn('SITE_KEY saknas – skickar ändå för felsökning');

        // Bygg kandidat-URLer (prisordning)
        // 1) ev. explicit TRACK_PATH, t.ex. /custom-track
        // 2) standard /track
        // 3) fallback /api/track
        // 4) anti-adblock: /t/track
        var candidates = [];
        var base = (TRACK_BASE || window.location.origin).replace(/\/+$/, '');
        if (TRACK_PATH) candidates.push(base + '/' + TRACK_PATH.replace(/^\/+/, ''));
        candidates.push(base + '/track');
        candidates.push(base + '/api/track');
        candidates.push(base + '/t/track');

        log('Config:', {
            SITE_KEY: SITE_KEY ? '(set)' : '(missing)',
            TRACK_BASE: base,
            CANDIDATES: candidates,
            REQUIRE_CONSENT: REQUIRE_CONSENT
        });

        // Same-origin (endast info; vi använder fetch korsdomän)
        var sameOrigin = false;
        try { sameOrigin = (new URL(candidates[0], location.origin).origin === location.origin); } catch (e) {}

        // Visitor-id
        var VID_KEY = 'wb_vid';
        var vid = null;
        try {
            vid = localStorage.getItem(VID_KEY);
            if (!vid) {
                vid = (self.crypto && crypto.randomUUID) ? crypto.randomUUID()
                    : (Date.now() + '_' + Math.random().toString(16).slice(2));
                localStorage.setItem(VID_KEY, vid);
            }
        } catch (e) { vid = 'anon_' + Date.now(); }
        log('VID:', vid);

        // Respektera DNT
        if (navigator.doNotTrack === '1') {
            log('DNT aktiv – avbryter');
            return;
        }

        // Enkel kö + flush
        var queue = [];
        var flushing = false;

        function enqueue(evt) {
            queue.push(evt);
            if (queue.length >= 5) flush(); // flush tidigt om många events snabbt
        }

        function sendOnce(url, payload) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Track-Site-Key': SITE_KEY || '__missing__'
                },
                body: JSON.stringify(payload),
                keepalive: true,
                credentials: 'omit',
                mode: 'cors',
            }).then(function (r) {
                log('POST', url, 'status:', r.status);
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r;
            });
        }

        function tryCandidates(payload) {
            // prova i turordning tills en lyckas
            var i = 0;
            return new Promise(function (resolve, reject) {
                function next() {
                    if (i >= candidates.length) return reject(new Error('Alla endpoints misslyckades'));
                    var url = candidates[i++];
                    sendOnce(url, payload).then(resolve).catch(function (e) {
                        log('Fail på', url, e.message || e);
                        next();
                    });
                }
                next();
            });
        }

        function flush() {
            if (flushing || queue.length === 0) return;
            flushing = true;

            var toSend = queue.splice(0, queue.length);
            // Bunta events eller skicka ett och ett – här skickar vi ett och ett för tydlig logg
            (function sendNext() {
                if (toSend.length === 0) { flushing = false; return; }
                var evt = toSend.shift();
                var payload = Object.assign({}, evt, {
                    siteKey: SITE_KEY || '__missing__',
                    vid: vid,
                    ts: evt.ts || Date.now()
                });
                log('POST =>', candidates[0], payload);

                tryCandidates(payload).finally(function () {
                    // fortsätt oavsett utfall; loggen visar status
                    sendNext();
                });
            })();
        }

        // Skicka något vid sidbyte
        function sendBeaconIfPossible() {
            if (!navigator.sendBeacon || queue.length === 0) return;
            try {
                var payload = queue.map(function (evt) {
                    return Object.assign({}, evt, { siteKey: SITE_KEY || '__missing__', vid: vid, ts: evt.ts || Date.now() });
                });
                var blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                // använd första kandidat – om den 404:ar hinner vi inte fallbacka på pagehide, men bättre än inget
                navigator.sendBeacon(candidates[0], blob);
                log('sendBeacon batch size:', payload.length);
                queue = [];
            } catch (e) {}
        }

        window.addEventListener('pagehide', sendBeaconIfPossible);
        window.addEventListener('beforeunload', sendBeaconIfPossible);

        function startTracking() {
            if (STARTED) { log('startTracking: redan startad'); return; }
            STARTED = true;
            log('startTracking');

            // Pageview
            enqueue({ type: 'pageview', url: location.href, ref: document.referrer || null });

            // Heartbeat var 15s när sidan är synlig
            var start = Date.now();
            setInterval(function () {
                if (document.visibilityState === 'visible') {
                    var seconds = Math.round((Date.now() - start) / 1000);
                    enqueue({ type: 'heartbeat', url: location.href, seconds: seconds });
                    flush();
                }
            }, 15000);

            // CTA-klick
            document.addEventListener('click', function (e) {
                var el = e.target && e.target.closest ? e.target.closest('[data-lead-cta]') : null;
                if (!el) return;
                var targetHref = (el.tagName === 'A' && el.href) ? el.href : null;
                enqueue({
                    type: 'cta',
                    url: location.href,
                    target: targetHref,
                    id: el.getAttribute('data-lead-cta'),
                    text: (el.innerText || '').trim().slice(0, 200)
                });
                // låt navigationen ske
                flush();
            }, { passive: true });

            // Form-submit (generisk)
            document.addEventListener('submit', function (e) {
                try {
                    var f = e.target;
                    var emailEl = f.querySelector('input[type="email"],input[name*="email" i]');
                    var email = emailEl ? (emailEl.value || '').trim() : null;
                    var formId = f.id || null;
                    enqueue({ type: 'form_submit', url: location.href, formId: formId, email: email || null });
                    flush();
                } catch (e2) {}
            });

            // kicka igång första flushen
            setTimeout(flush, 0);
        }

        // Start via event
        window.addEventListener('webbi:start', function () {
            log('event webbi:start');
            startTracking();
        }, { once: true });

        // Auto-start om inget samtycke krävs
        if (!REQUIRE_CONSENT) {
            setTimeout(function () {
                log('auto-boot (no consent required)');
                startTracking();
            }, 0);
        } else {
            log('väntar consent (WebbiConsent=true eller webbi:start)');
        }
    } catch (e) {
        if (window && window.console) console.debug('[lead-tracker] fatal (silenced):', e);
    }
})();
