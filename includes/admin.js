/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/admin.scss":
/*!************************!*\
  !*** ./src/admin.scss ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/jquery-ui.min.scss":
/*!********************************!*\
  !*** ./src/jquery-ui.min.scss ***!
  \********************************/
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
/*!**********************!*\
  !*** ./src/admin.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _admin_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./admin.scss */ "./src/admin.scss");
/* harmony import */ var _jquery_ui_min_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./jquery-ui.min.scss */ "./src/jquery-ui.min.scss");


jQuery(document).ready(function ($) {
  //Meta Box Options
  let open_close = $('<a href="#" style="display:block; float:right; clear:right; margin:10px;">' + EM.open_text + '</a>');
  $('#em-options-title').before(open_close);
  open_close.on('click', function (e) {
    e.preventDefault();
    if ($(this).text() == EM.close_text) {
      $('.postbox').addClass('closed');
      $(this).text(EM.open_text);
    } else {
      $('.postbox').removeClass('closed');
      $(this).text(EM.close_text);
    }
  });
  $('.postbox > h3').on('click', function () {
    $(this).parent().toggleClass('closed');
  });
  $('.postbox').addClass('closed');
  //Navigation Tabs
  $('.tabs-active .nav-tab-wrapper .nav-tab').on('click', function () {
    let el = $(this);
    let elid = el.attr('id');
    $('.em-menu-group').hide();
    $('.' + elid).show();
    $('.postbox').addClass('closed');
    open_close.text(EM.open_text);
  });
  $('.nav-tab-wrapper .nav-tab').on('click', function () {
    $('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active').blur();
    $(this).addClass('nav-tab-active');
  });
  let navUrl = document.location.toString();
  if (navUrl.match('#')) {
    //anchor-based navigation
    let nav_tab = navUrl.split('#').pop().split('+');
    let current_tab = 'a#em-menu-' + nav_tab[0];
    $(current_tab).trigger('click');
    if (nav_tab.length > 1) {
      section = $('#em-opt-' + nav_tab[1]);
      if (section.length > 0) {
        section.children('h3').trigger('click');
        $('html, body').animate({
          scrollTop: section.offset().top - 30
        }); //sends user back to current section
      }
    }
  } else {
    //set to general tab by default, so we can also add clicked subsections
    document.location = navUrl + '#general';
  }
  $('.nav-tab-link').on('click', function () {
    $($(this).attr('rel')).trigger('click');
  }); //links to mimick tabs
  $('input[type="submit"]').on('click', function () {
    let el = $(this).parents('.postbox').first();
    let docloc = document.location.toString().split('#');
    let newloc = docloc[0];
    if (docloc.length > 1) {
      let nav_tab = docloc[1].split('+');
      let tab_path = nav_tab[0];
      if (el.attr('id')) {
        tab_path = tab_path + '+' + el.attr('id').replace('em-opt-', '');
      }
      newloc = newloc + '#' + tab_path;
    }
    document.location = newloc;
    $(this).closest('form').append('<input type="hidden" name="tab_path" value="' + tab_path + '" />');
  });
  //Page Options

  //radio triggers
  $('input[type="radio"].em-trigger').on('change', function (e) {
    let el = $(this);
    el.val() == '1' ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
  });
  $('input[type="radio"].em-trigger:checked').trigger('change');
  $('input[type="radio"].em-untrigger').on('change', function (e) {
    let el = $(this);
    el.val() == '0' ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
  });
  $('input[type="radio"].em-untrigger:checked').trigger('change');
  //checkbox triggers
  $('input[type="checkbox"].em-trigger').on('change', function (e) {
    let el = $(this);
    el.prop('checked') ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
  });
  $('input[type="checkbox"].em-trigger').trigger('change');
  $('input[type="checkbox"].em-untrigger').on('change', function (e) {
    let el = $(this);
    !el.prop('checked') ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
  });
  $('input[type="checkbox"].em-untrigger').trigger('change');
  //admin tools confirm
  $('a.admin-tools-db-cleanup').on('click', function (e) {
    if (!confirm(EM.admin_db_cleanup_warning)) {
      e.preventDefault();
      return false;
    }
  });
  //color pickers
});
}();
/******/ })()
;
//# sourceMappingURL=admin.js.map