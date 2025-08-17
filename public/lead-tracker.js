/* Lead tracker (debug-friendly) */
(function () {
    try {
        var STARTED = false;
        var DEBUG = true; // Sätt till false när allt funkar

        function log() { if (DEBUG && window.console) console.log.apply(console, ['[lead-tracker]'].concat([].slice.call(arguments))); }
        function warn() { if (window.console) console.warn.apply(console, ['[lead-tracker]'].concat([].slice.call(arguments))); }

        // Läs config från window
        var SITE_KEY = window.WEBBI_SITE_KEY || '';
        var TRACK_BASE = (window.WEBBI_TRACK_URL || '').replace(/\/+$/, ''); // utan trailing slash
        var REQUIRE_CONSENT = !!window.WEBBI_REQUIRE_CONSENT;

        // Fallback: om TRACK_BASE saknas, använd scriptets origin (om tillåtet)
        try {
            if (!TRACK_BASE && document.currentScript && document.currentScript.src) {
                var u = new URL(document.currentScript.src, window.location.origin);
                TRACK_BASE = u.origin;
            }
        } catch (e) {}

        // Bygg API
        var API = (TRACK_BASE ? TRACK_BASE : window.location.origin) + '/track';

        log('Config:', { SITE_KEY: SITE_KEY ? '(set)' : '(missing)', TRACK_BASE: TRACK_BASE || '(none)', API: API, REQUIRE_CONSENT: REQUIRE_CONSENT });

        if (!SITE_KEY) warn('Saknar WEBBI_SITE_KEY (skickar ändå för felsökning)');

        // Same-origin check
        var sameOrigin = false;
        try {
            var a = document.createElement('a'); a.href = API;
            sameOrigin = (a.origin === window.location.origin);
        } catch (e) {}

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
        } catch (e) {
            vid = 'anon_' + Date.now();
        }
        log('VID:', vid);

        // DNT
        if (navigator.doNotTrack === '1') {
            log('DNT aktivt – tracking avbryts');
            return;
        }

        function post(evt) {
            try {
                var payload = Object.assign({}, evt, {
                    siteKey: SITE_KEY || '__missing__',
                    vid: vid,
                    ts: Date.now()
                });
                log('POST =>', API, payload);

                if (sameOrigin && navigator.sendBeacon) {
                    var blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                    var ok = navigator.sendBeacon(API, blob);
                    log('sendBeacon (same-origin):', ok);
                } else {
                    fetch(API, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(payload),
                        keepalive: true,
                        credentials: 'omit',
                        mode: 'cors'
                    }).then(function (r) {
                        log('fetch status:', r.status);
                    }).catch(function (e) {
                        warn('fetch error:', e && e.message ? e.message : e);
                    });
                }
            } catch (e) {
                warn('post error:', e && e.message ? e.message : e);
            }
        }

        function startTracking() {
            if (STARTED) { log('startTracking: redan startad'); return; }
            STARTED = true;
            log('startTracking');

            // Pageview direkt
            post({ type: 'pageview', url: location.href, ref: document.referrer || null });

            // Heartbeat var 15s när sidan är synlig
            var start = Date.now();
            setInterval(function () {
                if (document.visibilityState === 'visible') {
                    var seconds = Math.round((Date.now() - start) / 1000);
                    post({ type: 'heartbeat', url: location.href, seconds: seconds });
                }
            }, 15000);

            // CTA-klick
            document.addEventListener('click', function (e) {
                var el = e.target && e.target.closest ? e.target.closest('[data-lead-cta]') : null;
                if (!el) return;
                var targetHref = (el.tagName === 'A' && el.href) ? el.href : null;
                post({
                    type: 'cta',
                    url: location.href,
                    target: targetHref,
                    id: el.getAttribute('data-lead-cta'),
                    text: (el.innerText || '').trim().slice(0, 200)
                });
                // Vi stoppar aldrig navigationen
            }, { passive: true });

            // Form-submit (generisk)
            document.addEventListener('submit', function (e) {
                try {
                    var f = e.target;
                    var emailEl = f.querySelector('input[type="email"],input[name*="email" i]');
                    var email = emailEl ? (emailEl.value || '').trim() : null;
                    var formId = f.id || null;
                    post({ type: 'form_submit', url: location.href, formId: formId, email: email || null });
                } catch (e2) {}
            });
        }

        // Lyssna på boot-eventet du skickar från WP
        window.addEventListener('webbi:start', function () {
            log('event webbi:start');
            startTracking();
        }, { once: true });

        // Om consent inte krävs och event inte kommer av någon anledning – starta ändå
        if (!REQUIRE_CONSENT) {
            setTimeout(function () {
                log('auto-boot (no consent required)');
                startTracking();
            }, 0);
        } else {
            log('invantar consent via WebbiConsent=true eller webbi:start-event');
        }
    } catch (e) {
        if (window && window.console) console.debug('[lead-tracker] fatal error (silenced):', e);
    }
})();
