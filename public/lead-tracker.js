/* Lead tracker (resilient, minimal logs) */
(function () {
    try {
        var STARTED = false;

        // Debug kan aktiveras via ?debug=1 eller data-debug="true"
        var scriptEl = document.currentScript || (function () {
            var s = document.getElementsByTagName('script'); return s[s.length - 1];
        })();
        var DEBUG = false;
        try {
            var qs = new URL(scriptEl.src, location.origin).searchParams;
            if (qs.get('debug') === '1' || qs.get('debug') === 'true') DEBUG = true;
        } catch (e) {}
        if (!DEBUG && scriptEl && scriptEl.hasAttribute('data-debug')) {
            var v = (scriptEl.getAttribute('data-debug') || '').toLowerCase();
            DEBUG = v === '1' || v === 'true' || v === 'yes';
        }
        function log(){ if(DEBUG && console) console.log.apply(console, ['[lead-tracker]'].concat([].slice.call(arguments))); }

        var SITE_KEY = window.WEBBI_SITE_KEY || '';
        var TRACK_BASE = (window.WEBBI_TRACK_URL || '').replace(/\/+$/, '');
        var REQUIRE_CONSENT = !!window.WEBBI_REQUIRE_CONSENT;

        var TRACK_PATH = (scriptEl && (scriptEl.getAttribute('data-track-path') || '')) || '';
        try {
            var u = new URL(scriptEl.src, location.origin);
            var qTrackPath = u.searchParams.get('trackPath');
            if (!TRACK_PATH && qTrackPath) TRACK_PATH = qTrackPath;
        } catch (e) {}

        if (!TRACK_BASE && scriptEl && scriptEl.src) {
            try { TRACK_BASE = new URL(scriptEl.src, window.location.origin).origin; } catch (e) {}
        }

        var base = (TRACK_BASE || window.location.origin).replace(/\/+$/, '');
        var candidates = [];
        if (TRACK_PATH) candidates.push(base + '/' + TRACK_PATH.replace(/^\/+/, ''));
        candidates.push(base + '/track');
        candidates.push(base + '/api/track');
        candidates.push(base + '/t/track');

        var VID_KEY='wb_vid', vid=null;
        try{
            vid=localStorage.getItem(VID_KEY);
            if(!vid){ vid=(self.crypto&&crypto.randomUUID)?crypto.randomUUID():(Date.now()+'_'+Math.random().toString(16).slice(2)); localStorage.setItem(VID_KEY,vid); }
        }catch(e){ vid='anon_'+Date.now(); }

        if (navigator.doNotTrack === '1') { return; }

        var queue=[], flushing=false;
        function enqueue(evt){ queue.push(evt); if(queue.length>=5) flush(); }

        function sendWithFetch(url, payload, useNoCors){
            var opts = {
                method: 'POST',
                headers: useNoCors ? {} : { 'Content-Type':'application/json', 'Accept':'application/json', 'X-Track-Site-Key': SITE_KEY||'__missing__' },
                body: JSON.stringify(payload),
                keepalive: true,
                credentials: 'omit',
                mode: useNoCors ? 'no-cors' : 'cors'
            };
            return fetch(url, opts);
        }

        function tryCandidates(payload){
            var i=0;
            return new Promise(function(resolve,reject){
                function next(){
                    if(i>=candidates.length){
                        var last = candidates[0];
                        sendWithFetch(last, payload, true).then(resolve).catch(function(){
                            reject(new Error('endpoints failed'));
                        });
                        return;
                    }
                    var url=candidates[i++];
                    sendWithFetch(url, payload, false).then(function(r){
                        if (!r.ok) throw new Error('HTTP '+r.status);
                        resolve(r);
                    }).catch(function(){ next(); });
                }
                next();
            });
        }

        function flush(){
            if(flushing || queue.length===0) return;
            flushing=true;
            var toSend = queue.splice(0, queue.length);
            (function sendNext(){
                if(toSend.length===0){ flushing=false; return; }
                var evt=toSend.shift();
                var payload=Object.assign({}, evt, { siteKey: SITE_KEY||'__missing__', vid: vid, ts: evt.ts||Date.now() });
                tryCandidates(payload).finally(function(){ sendNext(); });
            })();
        }

        function sendBeaconIfPossible(){
            if(!navigator.sendBeacon || queue.length===0) return;
            try{
                var payload=queue.map(function(evt){ return Object.assign({}, evt, { siteKey: SITE_KEY||'__missing__', vid: vid, ts: evt.ts||Date.now() }); });
                var blob=new Blob([JSON.stringify(payload)],{type:'application/json'});
                navigator.sendBeacon(candidates[0], blob);
                queue=[];
            }catch(e){}
        }
        window.addEventListener('pagehide', sendBeaconIfPossible);
        window.addEventListener('beforeunload', sendBeaconIfPossible);

        function startTracking(){
            if(STARTED) return;
            STARTED=true;

            enqueue({ type:'pageview', url:location.href, ref:document.referrer||null });

            var start=Date.now();
            setInterval(function(){
                if(document.visibilityState==='visible'){
                    var seconds=Math.round((Date.now()-start)/1000);
                    enqueue({ type:'heartbeat', url:location.href, seconds:seconds });
                    flush();
                }
            }, 15000);

            document.addEventListener('click', function(e){
                var el=e.target && e.target.closest ? e.target.closest('[data-lead-cta]') : null;
                if(!el) return;
                var targetHref=(el.tagName==='A' && el.href)?el.href:null;
                enqueue({ type:'cta', url:location.href, target:targetHref, id:el.getAttribute('data-lead-cta'), text:(el.innerText||'').trim().slice(0,200) });
                flush();
            }, { passive:true });

            document.addEventListener('submit', function(e){
                try{
                    var f=e.target;
                    var emailEl=f.querySelector('input[type="email"],input[name*="email" i]');
                    var email=emailEl?(emailEl.value||'').trim():null;
                    var formId=f.id||null;
                    enqueue({ type:'form_submit', url:location.href, formId:formId, email:email||null });
                    flush();
                }catch(e2){}
            });

            setTimeout(flush, 0);
        }

        window.addEventListener('webbi:start', function(){ startTracking(); }, { once:true });
        if(!REQUIRE_CONSENT){ setTimeout(function(){ startTracking(); }, 0); }
    } catch (e) {
        if (window && window.console && console.debug) console.debug('[lead-tracker] silent error', e);
    }
})();
