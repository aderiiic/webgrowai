/* Lead tracker (MVP) */
(function () {
    try {
        var SITE_KEY = window.WEBBI_SITE_KEY || '';
        var TRACK_BASE = (window.WEBBI_TRACK_URL || '').replace(/\/+$/, ''); // utan trailing slash
        var REQUIRE_CONSENT = !!window.WEBBI_REQUIRE_CONSENT;

        if (!SITE_KEY) {
            console.warn('[lead-tracker] Saknar WEBBI_SITE_KEY');
            return;
        }

        var API = (TRACK_BASE ? TRACK_BASE : window.location.origin) + '/track';

        // Bestäm om vi är same-origin (för sendBeacon)
        var sameOrigin = false;
        try {
            var a = document.createElement('a'); a.href = API;
            sameOrigin = (a.origin === window.location.origin);
        } catch (e) {}

        // Generera/hämta visitor-id
        var VID_KEY = 'wb_vid';
        var vid = localStorage.getItem(VID_KEY);
        if (!vid) {
            vid = (self.crypto && crypto.randomUUID) ? crypto.randomUUID()
                : (Date.now() + '_' + Math.random().toString(16).slice(2));
            localStorage.setItem(VID_KEY, vid);
        }

        // Respektera DNT
        if (navigator.doNotTrack === '1') return;

        // Post helper: använd sendBeacon bara när same-origin; annars fetch JSON
        function post(evt) {
            try {
                var payload = Object.assign({}, evt, { siteKey: SITE_KEY, vid: vid, ts: Date.now() });
                if (sameOrigin && navigator.sendBeacon) {
                    // OBS: sendBeacon tvingar oftast text/plain; servern ska ändå acceptera payload
                    var blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                    navigator.sendBeacon(API, blob);
                } else {
                    fetch(API, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                        keepalive: true,
                        credentials: 'omit',
                    }).catch(function(){});
                }
            } catch (e) {}
        }

        // Starta tracking först när consent/”start”-signal är klar
        function startTracking() {
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
                post({
                    type: 'cta',
                    url: location.href,
                    id: el.getAttribute('data-lead-cta'),
                    text: (el.innerText || '').trim().slice(0, 120),
                });
            });

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

        // Vänta på consent om så önskas, annars starta direkt
        function boot() {
            if (REQUIRE_CONSENT) {
                var check = function () {
                    if (window.WebbiConsent === true) startTracking();
                    else setTimeout(check, 300);
                };
                check();
            } else {
                startTracking();
            }
        }

        // Stöd för pluginets "webbi:start"-event (startar explicit)
        window.addEventListener('webbi:start', function () {
            startTracking();
        }, { once: true });

        // Auto-boot om plugin inte används
        if (!REQUIRE_CONSENT) {
            setTimeout(function () {
                // om inget "webbi:start" har skickats – kör ändå
                startTracking();
            }, 0);
        }
    } catch (e) {
        // tyst felhantering
    }
})();
