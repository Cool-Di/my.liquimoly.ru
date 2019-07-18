(function () {
    var
        d = this.document,
        url = 'http://my.liquimoly.ru/oilapi',
        dev = (location.host.match(/^[^my].*\.local$/)),
        src =  dev ? '/lmwidget.js' : url + '/lmwidget-min.js',
        addError = function (txt) {
            var c = ('querySelector' in this) ? this.querySelector('[data-view*="widget"]') : null,
                r = this.createElement('div');
            r.style.color = '#ff0000';
            r.innerText = txt;
            (c === null) ? this.body.appendChild(r) : c.appendChild(r);

        },
        addScript = function (doc, src, load, error) {
            var s = doc.createElement('script');
            s.src = src;
            s.type = 'text/javascript';
            s.async = true;
            s.onerror = error;
            s.onload = load;
            doc.body.appendChild(s);
        };

    if (!!this.navigator.userAgent.match(/MSIE [678]/)) {
        addError.call(d, 'Браузер не поддерживается.\t\rИспользуйте IE9 и выше.');
    }
    else {
        addScript(d, src,
            function () {
                new LM.OilApi.Widget(url, dev);
            },
            function () {
                addError.call(d, 'Ошибка загрузки виджета.');
            });
    }
})();