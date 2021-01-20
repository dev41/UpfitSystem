/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./web/js/app/routes/trigger.create.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./web/js/app/components/VisualComponent.js":
/*!**************************************************!*\
  !*** ./web/js/app/components/VisualComponent.js ***!
  \**************************************************/
/*! exports provided: VisualComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"VisualComponent\", function() { return VisualComponent; });\n/* harmony import */ var _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../libraries/EventManager */ \"./web/js/app/libraries/EventManager.js\");\n\n\n\n\nconst config = {\n    lockedClass: 'box-spinner'\n};\n\nclass VisualComponent\n{\n    /**\n     * @param {jQuery|string} container\n     */\n    constructor(container)\n    {\n        this.container = $(container);\n        this._locked = 0;\n\n        this.elements = {};\n\n        /** @member {EventManager} */\n        this.eventManager = new _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__[\"EventManager\"]();\n    }\n\n    /**\n     * @param {jQuery} [element]\n     */\n    lock(element)\n    {\n        if (element instanceof jQuery) {\n            element.addClass(config.lockedClass);\n            return;\n        }\n\n        this._locked++;\n        this._refreshLock();\n    };\n\n    /**\n     * @param {boolean|jQuery} [hard=false]\n     */\n    unlock(hard)\n    {\n        if (hard instanceof jQuery) {\n            hard.removeClass(config.lockedClass);\n            return;\n        }\n\n        if (this._locked < 1) {\n            return;\n        }\n\n        if (hard === undefined) {\n            hard = false;\n        }\n\n        if (hard === true) {\n            this._locked = 0;\n        } else {\n            this._locked--;\n        }\n\n        this._refreshLock();\n    };\n\n    /**\n     * @return {boolean}\n     */\n    checkLock()\n    {\n        return this._locked === 0;\n    };\n\n    _refreshLock()\n    {\n        if (this.container instanceof jQuery) {\n            if (this._locked === 0) {\n                this.container.removeClass(config.lockedClass);\n            } else {\n                this.container.addClass(config.lockedClass);\n            }\n        }\n    };\n\n    /**\n     * @param {string} event\n     * @param {*} [data]\n     */\n    trigger(event, data)\n    {\n        this.eventManager.trigger(event, data, this);\n    };\n\n    /**\n     * @param {string} event\n     * @param {*} [data]\n     */\n    globalTrigger(event, data)\n    {\n        _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__[\"EventManager\"].global.trigger(event, data, this);\n    };\n\n    /**\n     * @param {string} event\n     * @param {function} callback\n     * @returns {CallbackHandler}\n     */\n    on(event, callback)\n    {\n        return this.eventManager.listen.apply(this.eventManager, arguments);\n    };\n\n    /**\n     * @param {string} event\n     * @param {function} callback\n     * @returns {CallbackHandler}\n     */\n    static globalOn(event, callback)\n    {\n        return _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__[\"EventManager\"].global.listen(event, callback);\n    };\n\n    /**\n     * @param {CallbackHandler} callbackHandler\n     */\n    off(callbackHandler)\n    {\n        this.eventManager.detach.apply(this.eventManager, arguments);\n    };\n\n    /**\n     * @param {CallbackHandler} callbackHandler\n     */\n    static globalOff(callbackHandler)\n    {\n        _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__[\"EventManager\"].global.detach(callbackHandler);\n    };\n}\n\n\n\n//# sourceURL=webpack:///./web/js/app/components/VisualComponent.js?");

/***/ }),

/***/ "./web/js/app/components/forms/TriggerForm.js":
/*!****************************************************!*\
  !*** ./web/js/app/components/forms/TriggerForm.js ***!
  \****************************************************/
/*! exports provided: TriggerForm */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"TriggerForm\", function() { return TriggerForm; });\n/* harmony import */ var _VisualComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../VisualComponent */ \"./web/js/app/components/VisualComponent.js\");\n\n\nconst typeEmailNotification = 3;\n\nclass TriggerForm extends _VisualComponent__WEBPACK_IMPORTED_MODULE_0__[\"VisualComponent\"]\n{\n    constructor(container)\n    {\n        super(container);\n        this.initElements();\n        this.initEvents();\n    }\n\n    initElements()\n    {\n        this.elements.eventSelect = this.container.find('.js-event-select');\n        this.elements.typeSortableInput = this.container.find('.js-trigger-type-sortable-input');\n        this.elements.typeSortableInputAvailable = this.container.find('.js-trigger-type-sortable-input-available');\n        this.elements.senderInput = this.container.find('.js-sender-email');\n        this.elements.templateTextarea = this.container.find('.js-template textarea');\n        this.elements.templateTranslateTextarea = this.container.find('.js-template-translate textarea');\n        this.elements.templateHint = this.container.find('.js-template-hint');\n        this.elements.templateTranslateHint = this.container.find('.js-template-translate-hint');\n        this.elements.checkboxAdvancedFilters = this.container.find('.js-checkbox-advanced_filters');\n        this.elements.accordionAdvancedFilters = this.container.find('.js-advanced_filters-accordion');\n        this.triggerCodes = this.elements.eventSelect.data('codes');\n        this.defaultCodes = this.elements.eventSelect.data('default-codes');\n    }\n\n    refreshCodes()\n    {\n        let trigger = parseInt(this.elements.eventSelect.val());\n\n        if (!trigger) {\n            return;\n        }\n\n        let codes = this.triggerCodes.hasOwnProperty(trigger) ? this.triggerCodes[trigger] : [],\n            hint = '';\n\n        codes = codes.concat(this.defaultCodes);\n\n        codes.forEach(function(code) {\n            hint += '<span>[' + code + ']</span>';\n        });\n\n        this.elements.templateHint.html(hint);\n        this.elements.templateTranslateHint.html(hint);\n    }\n\n    refreshEmailField()\n    {\n        let typeIds = this.elements.typeSortableInput.val(),\n            isEmailNotification = false;\n            self = this;\n\n        if (typeIds !== '') {\n            typeIds = typeIds.split(',');\n            typeIds.forEach(function (type) {\n                type = parseInt(type);\n                if (type === typeEmailNotification) {\n                    isEmailNotification = true;\n                    self.elements.senderInput.removeClass('hide');\n                }\n            });\n        }\n\n        if (!isEmailNotification) {\n            self.elements.senderInput.addClass('hide');\n            self.elements.senderInput.find('input').val('');\n        }\n    }\n\n    refreshAdvancedFilters()\n    {\n        let inputs = this.elements.accordionAdvancedFilters.find('select'),\n            checked = this.elements.checkboxAdvancedFilters.is(\":checked\");\n\n        if (checked) {\n            inputs.removeAttr('disabled');\n        } else {\n            inputs.prop('disabled', true);\n        }\n    }\n\n    initEvents()\n    {\n        let self = this;\n\n        this.elements.eventSelect.on('change', function () {\n            self.refreshCodes();\n        });\n\n        this.elements.templateHint.on('click', 'span', function () {\n            self.elements.templateTextarea.val(self.elements.templateTextarea.val() + $(this).html());\n        });\n\n        this.elements.templateTranslateHint.on('click', 'span', function () {\n            self.elements.templateTranslateTextarea.val(self.elements.templateTranslateTextarea.val() + $(this).html());\n        });\n\n        this.refreshCodes();\n        this.refreshAdvancedFilters();\n\n        this.elements.typeSortableInput.on('change', function (e) {\n            e.preventDefault();\n            self.refreshEmailField();\n        });\n\n        this.elements.checkboxAdvancedFilters.on('change', function () {\n            self.refreshAdvancedFilters();\n        });\n\n        this.elements.typeSortableInputAvailable.on('change', function (e) {\n            e.preventDefault();\n            self.refreshEmailField();\n        });\n    }\n}\n\n\n\n//# sourceURL=webpack:///./web/js/app/components/forms/TriggerForm.js?");

/***/ }),

/***/ "./web/js/app/libraries/EventManager.js":
/*!**********************************************!*\
  !*** ./web/js/app/libraries/EventManager.js ***!
  \**********************************************/
/*! exports provided: EventManager, Event, CallbackHandler */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"EventManager\", function() { return EventManager; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"Event\", function() { return Event; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"CallbackHandler\", function() { return CallbackHandler; });\n\n\n/**\n * @param {string} event\n * @param {number} callbackId\n * @constructor\n */\nfunction CallbackHandler(event, callbackId)\n{\n    this.event = event;\n    this.callbackId = callbackId;\n}\n\n/**\n * @param {string} eventType\n * @param {Object} target\n * @param {*} data\n * @param {number} counter\n * @constructor\n */\nfunction Event(eventType, target, data, counter)\n{\n    this.eventType = eventType;\n    this.target = target;\n    this.data = data;\n    this.counter = counter;\n}\n\nclass EventManager\n{\n    constructor()\n    {\n        this.listeners = {};\n        this.triggerCounter = {};\n    }\n\n    /**\n     * @param {string} eventType\n     * @param {Object} [data]\n     * @param {Object} [target]\n     * @returns {boolean|null}\n     */\n    trigger(eventType, data, target)\n    {\n        let event = new Event(\n            eventType, target, data, this._addTriggerCounter(eventType)\n        );\n\n        if (\n            (this.listeners.hasOwnProperty('*') === true) &&\n            (eventType !== '*')\n        ) {\n            this._addTriggerCounter('*');\n            EventManager._triggerProcess(this.listeners['*'], event);\n        }\n\n        if (this.listeners.hasOwnProperty(eventType) === false) {\n            return null;\n        }\n\n        return EventManager._triggerProcess(this.listeners[eventType], event);\n    };\n\n    /**\n     * @param {string} event\n     * @param {function} callback\n     * @returns {CallbackHandler}\n     */\n    listen(event, callback)\n    {\n        if (this.listeners.hasOwnProperty(event) === false) {\n            this.listeners[event] = [];\n        }\n\n        let callbackId = this.listeners[event].push(callback);\n        return new CallbackHandler(event, callbackId);\n    };\n\n    /**\n     * @param {CallbackHandler} callbackHandler\n     */\n    detach(callbackHandler)\n    {\n        delete this.listeners[callbackHandler.event][callbackHandler.callbackId];\n    };\n\n    /**\n     * @param {string} event\n     * @return {number|boolean}\n     */\n    checkEvent(event)\n    {\n        return this.triggerCounter.hasOwnProperty(event) ? this.triggerCounter[event] : false;\n    };\n\n    /**\n     * @param {string} event\n     * @return {number}\n     */\n    _addTriggerCounter(event)\n    {\n        let tg = this.triggerCounter;\n\n        if (tg.hasOwnProperty(event) === false) {\n            tg[event] = 0;\n        }\n\n        return ++tg[event];\n    }\n\n    /**\n     * @param {Function} callbacks\n     * @param {Event} event\n     * @returns {boolean}\n     */\n    static _triggerProcess(callbacks, event)\n    {\n        let i, r, response = true;\n\n        for (i = 0; i < callbacks.length; i++) {\n            if (callbacks[i] !== undefined) {\n                r = callbacks[i](event.data, event);\n                if (r !== undefined) {\n                    response = response && r;\n                }\n            }\n        }\n\n        return response;\n    }\n}\n\nEventManager.global = new EventManager();\n\n\n\n//# sourceURL=webpack:///./web/js/app/libraries/EventManager.js?");

/***/ }),

/***/ "./web/js/app/routes/trigger.create.js":
/*!*********************************************!*\
  !*** ./web/js/app/routes/trigger.create.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _components_forms_TriggerForm__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/forms/TriggerForm */ \"./web/js/app/components/forms/TriggerForm.js\");\n\n\n$(function () {\n    new _components_forms_TriggerForm__WEBPACK_IMPORTED_MODULE_0__[\"TriggerForm\"]($('.js-trigger-form'));\n});\n\n//# sourceURL=webpack:///./web/js/app/routes/trigger.create.js?");

/***/ })

/******/ });