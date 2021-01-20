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
/******/ 	return __webpack_require__(__webpack_require__.s = "./web/js/app/routes/place.update.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./web/js/app/components/Snackbar.js":
/*!*******************************************!*\
  !*** ./web/js/app/components/Snackbar.js ***!
  \*******************************************/
/*! exports provided: Snackbar, SnackbarType, SnackbarPosition */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"Snackbar\", function() { return Snackbar; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"SnackbarType\", function() { return SnackbarType; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"SnackbarPosition\", function() { return SnackbarPosition; });\nconst SnackbarType = {\n    INFO: 'info',\n    SUCCESS: 'success',\n    WARNING: 'warning',\n    ERROR: 'error',\n};\n\nconst SnackbarPosition = {\n    TOP_LEFT: 'tl',\n    TOP_RIGHT: 'tr',\n    BOTTOM_RIGHT: 'br',\n    BOTTOM_LEFT: 'bl',\n};\n\nconst defaultOptions = {\n    type: SnackbarType.INFO,\n    position: SnackbarPosition.BOTTOM_RIGHT,\n    message: 'info',\n    visibleTime: 3000,\n};\n\nconst classes = {\n    template: 'js-template-uf-snackbar',\n    baseType: '--uf-snackbar-type-',\n    basePosition: '--uf-snackbar-position-',\n    active: 'active',\n    closeBtn: 'js-button-close',\n};\n\nlet template;\n\nclass Snackbar\n{\n    static _getTemplate()\n    {\n        if (!template) {\n            template = $('.' + classes.template);\n        }\n        return template;\n    }\n\n    static removeMessage(message)\n    {\n        message.removeClass('active');\n        setTimeout(function () {\n            message.remove();\n        }, 10);\n    }\n\n    /**\n     * @param {Object} [options]\n     * @param {String} [options.type] SnackbarType\n     * @param {String} [options.position] SnackbarPosition\n     * @param {String} [options.message]\n     * @param {Function} [options.onHide]\n     * @param {Number} [options.visibleTime]\n     */\n    static show(options)\n    {\n        options = $.extend({}, defaultOptions, options || {});\n\n        let extClass = '';\n        if (options.type) {\n            extClass += ' ' + classes.baseType + options.type;\n        }\n        if (options.position) {\n            extClass += ' ' + classes.basePosition + options.position;\n        }\n\n        let template = Snackbar._getTemplate()\n            .html()\n            .replace(/{message}/g, options.message);\n\n        let message = $(template);\n        message.addClass(extClass);\n\n        $('body').append(message);\n\n        setTimeout(function () {\n            message.addClass('active');\n        }, 10);\n\n        let hideMessage = function () {\n            if (options.onHide instanceof Function) {\n                options.onHide(message, options);\n            } else {\n                Snackbar.removeMessage(message);\n            }\n        };\n\n        message.find('.' + classes.closeBtn).on('click', function() {\n            hideMessage();\n        });\n        setTimeout(function () {\n            hideMessage();\n        }, options.visibleTime);\n    }\n}\n\n\n\n//# sourceURL=webpack:///./web/js/app/components/Snackbar.js?");

/***/ }),

/***/ "./web/js/app/components/VisualComponent.js":
/*!**************************************************!*\
  !*** ./web/js/app/components/VisualComponent.js ***!
  \**************************************************/
/*! exports provided: VisualComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"VisualComponent\", function() { return VisualComponent; });\n/* harmony import */ var _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../libraries/EventManager */ \"./web/js/app/libraries/EventManager.js\");\n\n\n\n\nconst config = {\n    lockedClass: 'box-spinner'\n};\n\nclass VisualComponent\n{\n    /**\n     * @param {jQuery|string} container\n     */\n    constructor(container)\n    {\n        this.container = $(container);\n        this._locked = 0;\n\n        this.elements = {};\n\n        /** @member {EventManager} */\n        this.eventManager = new _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__[\"EventManager\"]();\n    }\n\n    /**\n     * @param {jQuery} [element]\n     */\n    lock(element)\n    {\n        if (element instanceof jQuery) {\n            element.addClass(config.lockedClass);\n            return;\n        }\n\n        this._locked++;\n        this._refreshLock();\n    };\n\n    /**\n     * @param {boolean|jQuery} [hard=false]\n     */\n    unlock(hard)\n    {\n        if (hard instanceof jQuery) {\n            hard.removeClass(config.lockedClass);\n            return;\n        }\n\n        if (this._locked < 1) {\n            return;\n        }\n\n        if (hard === undefined) {\n            hard = false;\n        }\n\n        if (hard === true) {\n            this._locked = 0;\n        } else {\n            this._locked--;\n        }\n\n        this._refreshLock();\n    };\n\n    /**\n     * @return {boolean}\n     */\n    checkLock()\n    {\n        return this._locked === 0;\n    };\n\n    _refreshLock()\n    {\n        if (this.container instanceof jQuery) {\n            if (this._locked === 0) {\n                this.container.removeClass(config.lockedClass);\n            } else {\n                this.container.addClass(config.lockedClass);\n            }\n        }\n    };\n\n    /**\n     * @param {string} event\n     * @param {*} [data]\n     */\n    trigger(event, data)\n    {\n        this.eventManager.trigger(event, data, this);\n    };\n\n    /**\n     * @param {string} event\n     * @param {*} [data]\n     */\n    globalTrigger(event, data)\n    {\n        _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__[\"EventManager\"].global.trigger(event, data, this);\n    };\n\n    /**\n     * @param {string} event\n     * @param {function} callback\n     * @returns {CallbackHandler}\n     */\n    on(event, callback)\n    {\n        return this.eventManager.listen.apply(this.eventManager, arguments);\n    };\n\n    /**\n     * @param {string} event\n     * @param {function} callback\n     * @returns {CallbackHandler}\n     */\n    static globalOn(event, callback)\n    {\n        return _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__[\"EventManager\"].global.listen(event, callback);\n    };\n\n    /**\n     * @param {CallbackHandler} callbackHandler\n     */\n    off(callbackHandler)\n    {\n        this.eventManager.detach.apply(this.eventManager, arguments);\n    };\n\n    /**\n     * @param {CallbackHandler} callbackHandler\n     */\n    static globalOff(callbackHandler)\n    {\n        _libraries_EventManager__WEBPACK_IMPORTED_MODULE_0__[\"EventManager\"].global.detach(callbackHandler);\n    };\n}\n\n\n\n//# sourceURL=webpack:///./web/js/app/components/VisualComponent.js?");

/***/ }),

/***/ "./web/js/app/components/forms/PlaceUpdate.js":
/*!****************************************************!*\
  !*** ./web/js/app/components/forms/PlaceUpdate.js ***!
  \****************************************************/
/*! exports provided: PlaceUpdate */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"PlaceUpdate\", function() { return PlaceUpdate; });\n/* harmony import */ var _VisualComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../VisualComponent */ \"./web/js/app/components/VisualComponent.js\");\n/* harmony import */ var _Snackbar__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Snackbar */ \"./web/js/app/components/Snackbar.js\");\n\n\n\nconst selector = {\n    BUTTON_COPY: '.js-button-copy',\n};\n\nclass PlaceUpdate extends _VisualComponent__WEBPACK_IMPORTED_MODULE_0__[\"VisualComponent\"]\n{\n    constructor(container)\n    {\n        super(container);\n\n        this.initEvents();\n    }\n\n    initEvents() {\n        let self = this;\n        this.container.on('click', selector.BUTTON_COPY, function () {\n            let button = $(this),\n                url = button.attr('data-url'),\n                notificationMessage = JSON.parse(button.attr('data-notification-message'));\n\n            self.lock();\n\n            $.ajax({\n                url: url,\n            }).done(function (response) {\n                let redirectUrl = response.url;\n\n                if (notificationMessage) {\n                    _Snackbar__WEBPACK_IMPORTED_MODULE_1__[\"Snackbar\"].show({\n                        type: _Snackbar__WEBPACK_IMPORTED_MODULE_1__[\"SnackbarType\"].SUCCESS,\n                        message: notificationMessage.success,\n                    });\n\n                    setTimeout(function () {\n                        window.location.assign(redirectUrl);\n                        self.unlock();\n                    }, 1000);\n                } else {\n                    window.location.assign(redirectUrl);\n                }\n            }).fail(function (xhr) {\n                if (notificationMessage) {\n                    _Snackbar__WEBPACK_IMPORTED_MODULE_1__[\"Snackbar\"].show({\n                        type: _Snackbar__WEBPACK_IMPORTED_MODULE_1__[\"SnackbarType\"].ERROR,\n                        message: notificationMessage.error,\n                    });\n                }\n            });\n        });\n    }\n}\n\n\n\n//# sourceURL=webpack:///./web/js/app/components/forms/PlaceUpdate.js?");

/***/ }),

/***/ "./web/js/app/helpers/InputFileHelper.js":
/*!***********************************************!*\
  !*** ./web/js/app/helpers/InputFileHelper.js ***!
  \***********************************************/
/*! exports provided: InputFileHelper */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"InputFileHelper\", function() { return InputFileHelper; });\n/* harmony import */ var _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/Snackbar */ \"./web/js/app/components/Snackbar.js\");\n\n\nclass InputFileHelper\n{\n\n    static init() {\n        let images = $('.js-images'),\n            logo = $('.js-logo');\n\n        images.on(\"filepredelete\", function() {\n            let input = $(this),\n                confirmMessage = input.attr('data-confirm-message'),\n                abort = true;\n\n            if (confirm(confirmMessage)) {\n                abort = false;\n            }\n            return abort;\n        });\n\n        images.on('filedeleted', function () {\n            let input = $(this),\n                notificationMessage = input.attr('data-notification-message');\n\n            if (!notificationMessage) {\n                return;\n            }\n\n            _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__[\"Snackbar\"].show({\n                type: _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__[\"SnackbarType\"].SUCCESS,\n                message: notificationMessage,\n            });\n        });\n\n        logo.on('filepredelete', function () {\n            let input = $(this),\n                confirmMessage = input.attr('data-confirm-message'),\n                abort = true;\n\n            if (confirm(confirmMessage)) {\n                abort = false;\n            }\n            return abort;\n        });\n\n        logo.on('filedeleted', function () {\n            let input = $(this),\n                notificationMessage = input.attr('data-notification-message');\n\n            if (!notificationMessage) {\n                return;\n            }\n\n            _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__[\"Snackbar\"].show({\n                type: _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__[\"SnackbarType\"].SUCCESS,\n                message: notificationMessage,\n            });\n        });\n    }\n}\n\n\n\n//# sourceURL=webpack:///./web/js/app/helpers/InputFileHelper.js?");

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

/***/ "./web/js/app/routes/place.update.js":
/*!*******************************************!*\
  !*** ./web/js/app/routes/place.update.js ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _helpers_InputFileHelper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/InputFileHelper */ \"./web/js/app/helpers/InputFileHelper.js\");\n/* harmony import */ var _components_forms_PlaceUpdate__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/forms/PlaceUpdate */ \"./web/js/app/components/forms/PlaceUpdate.js\");\n\n\n\n$(function() {\n    new _components_forms_PlaceUpdate__WEBPACK_IMPORTED_MODULE_1__[\"PlaceUpdate\"]('.uf-form-create');\n    _helpers_InputFileHelper__WEBPACK_IMPORTED_MODULE_0__[\"InputFileHelper\"].init();\n});\n\n//# sourceURL=webpack:///./web/js/app/routes/place.update.js?");

/***/ })

/******/ });