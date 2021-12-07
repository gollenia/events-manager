/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./add-ons/bookings-form/events-manager-pro-admin.css":
/*!************************************************************!*\
  !*** ./add-ons/bookings-form/events-manager-pro-admin.css ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*****************************************************!*\
  !*** ./add-ons/bookings-form/events-manager-pro.js ***!
  \*****************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _events_manager_pro_admin_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./events-manager-pro-admin.css */ "./add-ons/bookings-form/events-manager-pro-admin.css");

document.addEventListener('DOMContentLoaded', () => {
  document.body.addEventListener('click', event => {
    if (event.target.classList.contains("em-bookings-approve-offline") && !confirm(EM.offline_confirm)) {
      event.stopPropagation();
      event.stopImmediatePropagation();
      event.preventDefault();
      return false;
    }
  });
  document.body.addEventListener('click', event => {
    if (event.target.classList.contains("em-transaction-delete")) {
      const el = event.target;

      if (!confirm(EM.transaction_delete)) {
        return false;
      }

      const url = em_ajaxify(el.attr('href'));
      let td = el.parents('td').first();
      td.html(EM.txt_loading);
      td.load(url);
      return false;
    }
  });
});
}();
/******/ })()
;
//# sourceMappingURL=events-manager-pro.js.map