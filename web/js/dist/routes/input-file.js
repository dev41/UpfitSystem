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
/******/ 	return __webpack_require__(__webpack_require__.s = "./web/js/app/routes/input-file.js");
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

/***/ "./web/js/app/helpers/InputFileHelper.js":
/*!***********************************************!*\
  !*** ./web/js/app/helpers/InputFileHelper.js ***!
  \***********************************************/
/*! exports provided: InputFileHelper */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"InputFileHelper\", function() { return InputFileHelper; });\n/* harmony import */ var _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/Snackbar */ \"./web/js/app/components/Snackbar.js\");\n\n\nclass InputFileHelper\n{\n\n    static init() {\n        let images = $('.js-images'),\n            logo = $('.js-logo');\n\n        images.on(\"filepredelete\", function() {\n            let input = $(this),\n                confirmMessage = input.attr('data-confirm-message'),\n                abort = true;\n\n            if (confirm(confirmMessage)) {\n                abort = false;\n            }\n            return abort;\n        });\n\n        images.on('filedeleted', function () {\n            let input = $(this),\n                notificationMessage = input.attr('data-notification-message');\n\n            if (!notificationMessage) {\n                return;\n            }\n\n            _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__[\"Snackbar\"].show({\n                type: _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__[\"SnackbarType\"].SUCCESS,\n                message: notificationMessage,\n            });\n        });\n\n        logo.on('filepredelete', function () {\n            let input = $(this),\n                confirmMessage = input.attr('data-confirm-message'),\n                abort = true;\n\n            if (confirm(confirmMessage)) {\n                abort = false;\n            }\n            return abort;\n        });\n\n        logo.on('filedeleted', function () {\n            let input = $(this),\n                notificationMessage = input.attr('data-notification-message');\n\n            if (!notificationMessage) {\n                return;\n            }\n\n            _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__[\"Snackbar\"].show({\n                type: _components_Snackbar__WEBPACK_IMPORTED_MODULE_0__[\"SnackbarType\"].SUCCESS,\n                message: notificationMessage,\n            });\n        });\n    }\n}\n\n\n\n//# sourceURL=webpack:///./web/js/app/helpers/InputFileHelper.js?");

/***/ }),

/***/ "./web/js/app/routes/input-file.js":
/*!*****************************************!*\
  !*** ./web/js/app/routes/input-file.js ***!
  \*****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _helpers_InputFileHelper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/InputFileHelper */ \"./web/js/app/helpers/InputFileHelper.js\");\n\n\n$(function() {\n    _helpers_InputFileHelper__WEBPACK_IMPORTED_MODULE_0__[\"InputFileHelper\"].init();\n});\n\n//# sourceURL=webpack:///./web/js/app/routes/input-file.js?");

/***/ })

/******/ });