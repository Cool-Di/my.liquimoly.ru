var LM = LM || {};
LM.OilApi = {};
LM.OilApi.Widget = (function () {

    'use strict';

    var instance,
        doc = document,
        XHR = XMLHttpRequest,
        ajaxScript = '/lmoilapiajax.php',
        nameApi = 'Oil Api - Liqui Moly',
        format = 'html',
        nameWgt = 'widget',
        stepWgt = ['search', 'categories', 'makes', 'models', 'types', 'recommendations'];


    var Widget = function (u, d) {

        if (!(this instanceof Widget)) {
            return new Widget(u, d);
        }

        if (instance) {
            return instance;
        }

        instance = this;

        this.listener = null;
        this.dev = d;
        this.url = this.dev ? ajaxScript : u + ajaxScript;

        this.container = doc.querySelector('[data-view*="' + nameWgt + '"]');
        if (this.container === null) {
            this.addHtml(doc.body, {error: "Не найден контейнер виджета с тегом `data-view`!"});
            return;
        }

        this.view = this.container.getAttribute('data-view');
        this.clid = this.container.getAttribute('data-clid');
        this.prefix = this.view.replace(nameWgt, '');
        this.choicediv = doc.querySelector('[data-choice="' + this.prefix + 'choice"]');
        this.multi = false;

        this.init();
    };

    Widget.prototype.init = function () {
        this.getData([0, 1]);
    };

    Widget.prototype.getNameComponent = function (comp) {

        if (typeof comp == 'number') {
            if (comp in stepWgt) {
                return this.prefix + stepWgt[comp];
            } else {
                return 'none';
            }
        }
        if (typeof comp == 'string') {
            return this.prefix + comp;
        }
    };

    Widget.prototype.getIndexComponent = function (name) {
        name = name.replace(this.prefix, '');
        return stepWgt.indexOf(name);
    };

    Widget.prototype.findDataElement = function (block, dataValue, dataKey) {

        if ((typeof block === 'object') && ('querySelector' in block)) {
            var k = (dataKey) ? dataKey : dataValue;
            return block.querySelector('[data-' + k + '="' + this.prefix + dataValue + '"]');
        }

    };

    Widget.prototype.findElementByName = function (block, dataValue) {

        if ((typeof block === 'object') && ('querySelector' in block)) {
            return block.querySelector('[name="' + dataValue + '"]');
        }

    };

    Widget.prototype.getNextOperation = function (index) {
        return (index === 0) ? 4 : (index + 1);
    };

    Widget.prototype.addHtml = function (input, obj) {
        var erElement,
            property,
            type = ['error', 'message', 'dump'];

        for (var key in obj) {
            if (type.indexOf(key) >= 0 && obj[key]) {
                property = key;
                break;
            }
        }

        if (property !== undefined) {
            erElement = doc.createElement('div');
            erElement.setAttribute('data-' + property, this.prefix + property);
            erElement.setAttribute('style', 'color:#ff0000');
            erElement.innerHTML = nameApi + ': ' + obj[property];
            if ((typeof input === 'object') && ('appendChild' in input)) {
                input.appendChild(erElement);
            }
        }
    };

    Widget.prototype.deleteHtml = function (noteTypes, container) {
        var node;
        if (Array.isArray(noteTypes)) {
            noteTypes.forEach(function (value) {
                node = this.findDataElement(container, value);
                if (node instanceof HTMLElement) {
                    if (node.parentNode) {
                        node.parentNode.removeChild(node);
                    }
                }
            }, this);
        }
    };

    Widget.prototype.addHtmlContent = function (index, data) {
        var input = (this.choicediv === null) ? this.container : this.choicediv;

        input.insertAdjacentHTML('beforeend', data);

        if (this.choicediv === null) {

            this.choicediv = doc.querySelector('[data-choice*="' + this.prefix + 'choice"]');

            this.listener = this.widgetEvent.bind(this);
            this.addHandler(this.choicediv, 'click', this.listener);
            this.addHandler(this.choicediv, 'change', this.listener);
            this.addHandler(this.choicediv, 'keyup', this.listener);
        }

        return this.container.querySelector('[data-step="' + this.getNameComponent(index) + '"]');
    };

    Widget.prototype.refreshChoice = function (index) {

        this.deleteHtml(['error', 'message', 'dump'], this.container);
        this.deleteComponent(index);

    };

    Widget.prototype.validateComponentData = function (el) {

        switch (el.tagName) {
            case 'INPUT': {
                var s = el.value.replace(/\s/g, '');
                return (s.length !== 0);
            }
            case 'SELECT': {
                return (el.selectedIndex !== 0);
            }
        }
    };

    Widget.prototype.getData = function (index) {
        var keyJson,
            cacheData,
            data;

        this.multi = Array.isArray(index);

        if (this.multi) {

            data = index.map(function (name) {
                return this.getRequestData(name);
            }, this);

        } else {
            data = this.getRequestData(index);
            this.refreshChoice(index);
        }

        keyJson = this.getData.getKey(data);
        cacheData = this.getData.cache[keyJson];

        if (!cacheData) {
            this.changeBlockStatus(true, false);
            this.getAjaxData(data, this.url, index);
        }
        else {
            this.updateContainer(index, cacheData);
        }

        return this;
    };

    Widget.prototype.getData.cache = {};

    Widget.prototype.getData.getKey = function (request) {
        var sl = '',
            list = (Array.isArray(request)) ? request : [request];

        list.forEach(function (value) {
            sl += value.operation + value.id + value.text;
        });

        return JSON.stringify(sl);
    };

    Widget.prototype.getData.addCacheData = function (request, data) {
        var keyJson = Widget.prototype.getData.getKey(request);
        Widget.prototype.getData.cache[keyJson] = data;
    };

    Widget.prototype.getAjaxData = function (send, url, index) {

        var input = this,
            sendData,
            xhr = new XHR();

        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.onload = function () {
            if (xhr.status === 200) {
                resolve(xhr.responseText);
            } else {
                var error = new Error(xhr.statusText + ' ' + xhr.status);
                reject(error);
            }
        };

        xhr.onerror = function () {
            reject(new Error("Ошибка сети"));
        };

        sendData = 'data=' + JSON.stringify(send);

        xhr.send(sendData);

        var resolve = function (result) {
            var data, dump, patternData = /\[\{"data[\W\w]*"\}\]/;

            try {

                data = patternData.exec(result);

                dump = result.replace(data, '');

                result = JSON.parse(data);

                if (input.multi && (result.length > 1)) {

                    input.multiUpdateContainer(index, result);

                } else {
                    input.updateContainer(index, result[0]);
                    input.getData.addCacheData(send, result[0]);
                }

            } catch (e) {
                input.updateContainer(0, result);
            }

            if ((dump.length !== 0) && (input.dev)) {
                input.addHtml(input.container, {dump: dump});
            }

            input.changeBlockStatus(false);

        };

        var reject = function (error) {
            input.addHtml(input.container, {error: error});
            input.changeBlockStatus(false);
        };

    };

    Widget.prototype.getRequestData = function (index) {
        var e,
            ind = index;
        if (index > 0) {
            e = this.container.querySelector('[name="' + this.getNameComponent(index) + '"]');
            ind = (e === null) ? index : index + 1;
        }

        return {
            clid: this.clid,
            format: format,
            prefix: this.prefix,
            operation: stepWgt[ind],
            id: this.getStepParameter('id', (index === 0) ? 1 : index), // для поиска берём категорию + текст
            text: (index > 0) ? '' : this.getStepParameter('value', index) // если не поиск, достаточно id
        }
    };

    Widget.prototype.getStepParameter = function (name, index) {
        var el,
            cm = this.getNameComponent(index);

        el = doc.getElementsByName(cm)[0];

        if ((el === undefined) || (el === null))
            return '';
        // из input берём текст из select id
        return (index === 0) ? el.value : el.options[el.selectedIndex].id;
    };

    Widget.prototype.multiUpdateContainer = function (index, data) {

        index.forEach(function (value) {
            this.updateContainer(value, data[value]);
        }, this);
    };

    Widget.prototype.updateContainer = function (index, data) {

        var newIndex,
            elStep = this.container.querySelector('[data-step="' + this.getNameComponent(index) + '"]');

        if (data['error'] !== '') {
            this.refreshChoice(0);
            this.addHtml(this.container, data);
            return;
        }

        if (data['message'] !== '') {
            this.addHtml(this.choicediv, data);
            return;
        }

        newIndex = (elStep === null) ? index : this.getNextOperation(index);
        this.addComponent(newIndex, data['data']);
    };

    Widget.prototype.addComponent = function (index, data) {

        var el = this.addHtmlContent(index, data);

        // последний шаг рекомендации
        if ((el !== null) && (index === (stepWgt.length - 1))) {

            this.changeVisibleAllSection(false, 1);
        }
    };

    Widget.prototype.deleteComponent = function (index) {
        var childrenDiv,
            dl,
            el = this.container.querySelector('[data-step="' + this.getNameComponent(index) + '"]');

        if (el === null)
            return;

        childrenDiv = this.choicediv.querySelectorAll('div[data-step]');
        dl = childrenDiv.length;

        // если поиск
        if (index === 0) {
            index = 1;
        }
        // если результаты поиска сразу получаем шаг 4 ТИП
        if ((index === 4) && (dl <= index)) {
            index = 2;
        }
        // удаляется всё после текущего шага
        if (el !== null) {
            if ((dl - 1) > index) {
                while ((index + 1) < dl) {
                    dl -= 1;
                    // divChoice.removeChild(divChoice.childNodes[dl]);
                    this.choicediv.removeChild(childrenDiv[dl]);
                }
            }
        }
    };

    Widget.prototype.resetComponent = function (index) {
        var el = this.findElementByName(this.container, this.getNameComponent(index));
        if (el instanceof HTMLElement) {
            switch (el.tagName) {
                case 'INPUT': {
                    el.value = '';
                    break;
                }
                case 'SELECT': {
                    el.selectedIndex = 0;
                    break;
                }
            }
        }
        return this;
    };

    Widget.prototype.updateSearchLabel = function (value) {

        var label = this.findDataElement(this.container, 'search', 'step').firstElementChild;
        if (label instanceof HTMLElement) {
            label.innerText = 'Поиск: ' + value;
        }
    };

    Widget.prototype.changeBlockStatus = function (status, ch) {
        var bl = this.findDataElement(this.container, 'block');
        if (bl instanceof HTMLElement) {
            bl.style.visibility = (status) ? 'visible' : 'hidden';
        }
        if (!ch) {
            var sel = this.container.querySelectorAll('[name*="' + this.prefix + '"]');
            if (Array.isArray(sel)) {
                [].forEach.call(sel, function (value, index, obj) {
                    if (status)
                        obj[index].setAttribute('disabled', 'disabled');
                    else
                        obj[index].removeAttribute('disabled');
                });
            }
        }
    };

    Widget.prototype.addHandler = function (e, event, callback) {
        e.addEventListener(event, callback, false);
    };

    Widget.prototype.removeHandler = function (e, event, callback) {
        e.removeEventListener(event, callback);
    };

    Widget.prototype.widgetEvent = function (event) {
        var el;
        switch (event.target.tagName) {
            case 'INPUT': {
                if (event.type === 'keyup') {
                    if (event.keyCode !== 13) {
                        return;
                    }
                    this.keyUpEvent(event.target);
                }
                break;
            }
            case 'SELECT': {
                if (event.type === 'change') {
                    this.changeEvent(event.target);
                }
                break;
            }
            case 'BUTTON': {
                if (event.type === 'click') {
                    el = this.findElementByName(this.container, this.getNameComponent('search'));
                    this.keyUpEvent(el);
                }
                break;
            }
            case 'SPAN': {

                el = event.target.parentElement;
                if (el.getAttribute('data-component')) {
                    this.changeVisibleSection(el);
                }

                if (event.target.getAttribute('data-init')) {
                    //this.reInitEvent();
                    this.resetEvent();
                }

                if (event.target.getAttribute('data-updown')) {
                    this.expandEvent(event.target);
                }

                break;
            }
        }

    };

    Widget.prototype.changeEvent = function (el) {
        var vl,
            index = this.getIndexComponent(el.name);

        if (index === 1) {
            this.resetComponent('search');
            this.updateSearchLabel(el.value);
        }
        vl = this.validateComponentData(el);

        (vl) ? this.getData(index) : this.deleteComponent(index);

    };

    Widget.prototype.keyUpEvent = function (el) {

        var vl = this.validateComponentData(el);

        (vl) ? this.getData(this.getIndexComponent(el.name)) : this.deleteComponent(1);

    };

    Widget.prototype.reInitEvent = function () {
        this.removeHandler(this.choicediv, 'click', this.listener);
        this.removeHandler(this.choicediv, 'change', this.listener);
        this.removeHandler(this.choicediv, 'keyup', this.listener);
        this.container.innerHTML = '';
        this.choicediv = null;
        this.init();
    };

    Widget.prototype.resetEvent = function () {

        this.deleteComponent(1);
        this.resetComponent('search').resetComponent('categories');
        this.updateSearchLabel('');

    };

    Widget.prototype.expandEvent = function(el) {

        this.changeStatusExpanded(el);
        this.changeVisibleAllSection(!el.colexp);

    };

    Widget.prototype.changeStatusExpanded = function (el) {
        el.colexp = !el.colexp;
        el.innerText = (el.colexp) ? 'Развернуть всё' : 'Свернуть всё';
    };

    Widget.prototype.changeVisibleAllSection = function (status, b, e) {
        var comp = this.container.querySelectorAll('[data-component="component"]');

        for (var i = ((b !== undefined) ? b : 0), max = (e === undefined) ? comp.length : e; i < max; i += 1) {
            this.changeVisibleSection(comp[i], status);
        }
    };

    Widget.prototype.changeVisibleSection = function (el, st) {
        var style,
            e = el.lastElementChild,
            s = el.querySelector('[data-arrow="arrow"]'),
            arrUpDown = function (e) {
                e.innerText = (e.innerText === '\u25BA') ? '\u25BC' : '\u25BA';
            };

        st = (typeof st === 'boolean') ? st : ((e.style.display === 'none') ? true : false);
        style = (st) ? 'block' : 'none';
        if (e.style.display !== style){
            e.style.display = style;
            arrUpDown(s);
        }
    };

    return Widget;

}());


