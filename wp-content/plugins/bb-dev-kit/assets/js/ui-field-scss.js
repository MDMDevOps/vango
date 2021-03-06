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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/scripts/ui-field-scss.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof(obj) {\n  \"@babel/helpers - typeof\";\n\n  if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") {\n    module.exports = _typeof = function _typeof(obj) {\n      return typeof obj;\n    };\n  } else {\n    module.exports = _typeof = function _typeof(obj) {\n      return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj;\n    };\n  }\n\n  return _typeof(obj);\n}\n\nmodule.exports = _typeof;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/typeof.js?");

/***/ }),

/***/ "./src/scripts/ui-field-scss.js":
/*!**************************************!*\
  !*** ./src/scripts/ui-field-scss.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ \"./node_modules/@babel/runtime/helpers/typeof.js\");\n/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__);\n\njQuery(document).ready(function ($) {\n  var editors, original_mode_path;\n\n  if ((typeof FLBuilder === \"undefined\" ? \"undefined\" : _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(FLBuilder)) === 'object' && (typeof ace === \"undefined\" ? \"undefined\" : _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0___default()(ace)) === 'object') {\n    /**\n     * Get the origin mode path so we know what to reset to\n     * @type {[type]}\n     */\n    original_mode_path = ace.config.get('modePath');\n    /**\n     * Create the editors\n     */\n\n    FLBuilder.addHook('settings-form-init', function () {\n      editors = jQuery.map($('.fl-builder-settings:visible').find('.devkit-code-editor'), function (el) {\n        return new BEScssField(el);\n      });\n    });\n  }\n\n  function BEScssField(textarea) {\n    var $textarea, $editdiv, editor;\n\n    var _bindEvents = function _bindEvents() {\n      editor.getSession().on('change', function (e) {\n        $textarea.val(editor.getSession().getValue()).trigger('change');\n      });\n    };\n\n    var _setOptions = function _setOptions() {\n      editor.setOptions({\n        enableBasicAutocompletion: true,\n        enableLiveAutocompletion: true,\n        enableSnippets: false,\n        showLineNumbers: true,\n        showFoldWidgets: false,\n        minLines: 1,\n        maxLines: 30\n      }); // Set the value\n\n      editor.getSession().setValue($textarea.val()); // Set the new path for our mode files\n\n      ace.config.set('basePath', devkit_scss.baseurl + 'assets/js/ace/src-min');\n      ace.config.set('modePath', devkit_scss.baseurl + 'assets/js/ace/src-min');\n\n      ace.require('ace/ext/language_tools'); // Set teh mode\n\n\n      editor.session.setMode('ace/mode/scss'); // Reset the path\n\n      ace.config.set('basePath', original_mode_path);\n      ace.config.set('modePath', original_mode_path);\n    };\n\n    var _init = function _init() {\n      // Create object from textarea and hide\n      $textarea = $(textarea).hide(); // Create and insert div for editor to live\n\n      $editdiv = $('<div>', {\n        id: $textarea.attr('id') + '-editor'\n      }).insertAfter($textarea); // Create editor\n\n      editor = ace.edit($editdiv.attr('id')); // Set options\n\n      _setOptions(); // Bind events\n\n\n      _bindEvents(); // Set the parent for resize\n\n\n      $textarea.closest('.fl-field').data('editor', editor);\n    };\n\n    _init();\n  }\n});\n\n//# sourceURL=webpack:///./src/scripts/ui-field-scss.js?");

/***/ })

/******/ });