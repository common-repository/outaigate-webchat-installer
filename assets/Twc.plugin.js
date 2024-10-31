(function() {
    var w = window;
    var path = 'https://sdk.outaigate.io';
    var v;
    if (w.Twc) {
        return false;
    }

    var ch = function () {
        ch.c(arguments)
    };

    ch.q = [];
    ch.c = function (args) {
        if (args[1].version) {
            v = args[1].version
            delete args[1].version
        }
        ch.q.push(args);
    };
    w.Twc = ch;
    function load() {
        if (w.TwcInitialized) {
            return;
        }
        w.TwcInitialized = true;
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = `${path}${v ? '/version/' + v : ''}/Twc.sdk.js?timestemp=${new Date().getTime()}`;
        s.charset = 'UTF-8';
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    }

    setTimeout(function () {
        var req = new XMLHttpRequest();
        req.open('HEAD', `${path}${v ? '/version/' + v : ''}/Twc.sdk.js?timestemp=${new Date().getTime()}`, true);
        req.send();
        req.onreadystatechange = function () {
            if (req.status !== 200) {
                v = undefined;
            }
            if (document.readyState === 'complete') {
                load();
            } else if (window.attachEvent) {
                window.attachEvent('onload', load);
            } else {
                window.addEventListener('DOMContentLoaded', load, false);
                window.addEventListener('load', load, false);
            }
        }
    })
})();