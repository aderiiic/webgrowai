/* Lead tracker (enhanced, self-config) */
(function () {
    try {
        var STARTED = false;

        function parseBool(v) {
            if (typeof v === 'boolean') return v;
            if (typeof v !== 'string') return false;
            v = v.toLowerCase();
            return v === '1' || v === 'true' || v === 'yes';
        }

        function getScriptEl() {
            // currentScript funkar i moderna browsers
            if (document.currentScript) return document.currentScript;
            // fallback: hitta sista script vars src innehåller "lead-tracker.js"
            var scripts = document.getElementsByTagName('script');
            for (var i = scripts.length - 1; i >= 0; i--) {
                var s = scripts[i];
                if ((s.src || '').indexOf('lead-tracker.js') !== -1) return s;
            }
            return null;
        }

        var scriptEl = getScriptEl();

        // Läs konfig i priority-ordning: global -> data-attribut -> querystring -> fallback
        var SITE_KEY = window.WEBBI_SITE_KEY || '';
        var TRACK_BASE = (window.WEBBI_TRACK_URL || '').replace(/\/+$/, '');
        var REQUIRE_CONSENT = !!window.WEBBI_REQUIRE_CONSENT;
        var DEBUG = !!window.WEBBI_DEBUG;

        // Från data-attribut på script-taggen
        if (scriptEl) {
            SITE_KEY = SITE_KEY || (scriptEl.getAttribute('data-site-key') || '');
            TRACK_BASE = TRACK_BASE || (scriptEl.getAttribute('data-track-url') || '');
            if (!window.WEBBI_REQUIRE_CONSENT && scriptEl.hasAttribute('data-require-consent')) {
                REQUIRE_CONSENT = parseBool(scriptEl.getAttribute('data-require-consent'));
            }
            if (!window.WEBBI_DEBUG && scriptEl.hasAttribute('data-debug')) {
                DEBUG = parseBool(scriptEl.getAttribute('data-debug'));
            }
        }

        // Från querystring på script-src
        try {
            if (scriptEl && scriptEl.src) {
                var u = new URL(scriptEl.src, window.location.origin);
                var qsSite = u.searchParams.get('siteKey');
                var qsTrack = u.searchParams.get('trackUrl');
                var qsConsent = u.searchParams.get('requireConsent');
                var qsDebug = u.searchParams.get('debug');
                if (!SITE_KEY && qsSite) SITE_KEY = qsSite;
                if (!TRACK_BASE && qsTrack) TRACK_BASE = qsTrack;
                if (!window.WEBBI_REQUIRE_CONSENT && qsConsent != null) REQUIRE_CONSENT = parseBool(qsConsent);
                if (!window.WEBBI_DEBUG && qsDebug != null) DEBUG = parseBool(qsDebug);
            }
        } catch (e) {}

        // Fallback för TRACK_BASE: använd scriptets origin (så POST går till samma backend som hostar scriptet)
        try {
            if (!TRACK_BASE && scriptEl && scriptEl.src) {
                var sUrl = new URL(scriptEl.src, window.location.origin);
                TRACK_BASE = sUrl.origin;
            }
        } catch (e) {}

        // Om SITE_KEY saknas: varna men fortsätt (skicka "__missing__" så du ser POST i Network)
        if (!SITE_KEY && window.console) {
            console.warn('[lead-tracker] WEBBI_SITE_KEY saknas – skickar ändå för felsökning');
        }

        var API = (TRACK_BASE || window.location.origin).replace(/\/+$/, '') + '/track';

        // Bestäm om vi är same-origin (för sendBeacon)
        var sameOrigin = false;
        try {
            var a = document.createElement('a'); a.href = API;
            sameOrigin = (a.origin === window.location.origin);
        } catch (e) {}

        // Generera/hämta visitor-id
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
            // localStorage kan vara blockerad
            vid = 'anon_' + Date.now();
        }

        // Respektera DNT
        if (navigator.doNotTrack === '1') {
            if (DEBUG) console.log('[lead-tracker] DNT aktivt – avbryter tracking');
            return;
        }

        function post(evt) {
            try {
                var payload = Object.assign({}, evt, {
                    siteKey: SITE_KEY || '__missing__',
                    vid: vid,
                    ts: Date.now()
                });

                if (DEBUG) console.log('[lead-tracker] POST', API, payload);

                if (sameOrigin && navigator.sendBeacon) {
                    var blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                    navigator.sendBeacon(API, blob);
                } else {
                    fetch(API, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload),
                        keepalive: true,
                        credentials: 'omit',
                        mode: 'cors'
                    }).catch(function(){});
                }
            } catch (e) {
                if (DEBUG) console.log('[lead-tracker] post error', e);
            }
        }

        function startTracking() {
            if (STARTED) return;
            STARTED = true;
            if (DEBUG) console.log('[lead-tracker] startTracking');

            // Pageview
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
                // Obs: vi stoppar aldrig navigationen
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

        // Exponera manuell start för tema/plugin och konsol
        window.Webbi = window.Webbi || {};
        window.Webbi.start = function () { startTracking(); };

        // Event-baserad start (om plugin triggar)
        window.addEventListener('webbi:start', function () {
            startTracking();
        }, { once: true });

        // Auto-start beroende på consent
        if (REQUIRE_CONSENT) {
            // Pollar en global flagga tills samtycke givits
            var check = function () {
                if (window.WebbiConsent === true) startTracking();
                else setTimeout(check, 300);
            };
            check();
        } else {
            // Starta omgående
            setTimeout(function () { startTracking(); }, 0);
        }
    } catch (e) {
        // tyst felhantering
        if (window && window.console && console.debug) {
            console.debug('[lead-tracker] silent error', e);
        }
    }
})();
