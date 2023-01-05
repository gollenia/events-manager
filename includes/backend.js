/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blocks/booking/edit.js":
/*!************************************!*\
  !*** ./src/blocks/booking/edit.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var colord__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! colord */ "./node_modules/colord/index.mjs");
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/booking/inspector.js");

/**
 * Wordpress dependencies
 */




/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      buttonTitle
    },
    setAttributes,
    buttonColor
  } = props;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx-event-booking"].filter(Boolean).join(" ")
  });
  const textColor = buttonColor.color == undefined || (0,colord__WEBPACK_IMPORTED_MODULE_4__.colord)(buttonColor.color).isLight() ? "#000000" : "#ffffff";
  const style = {
    background: buttonColor.color,
    color: textColor
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_3__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: style,
    className: "events ctx-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    value: buttonTitle,
    onChange: value => setAttributes({
      buttonTitle: value
    }),
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Registration', 'events'),
    allowedFormats: ['core/bold', 'core/italic']
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/booking/icon.js":
/*!************************************!*\
  !*** ./src/blocks/booking/icon.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M4 20Q3.175 20 2.588 19.413Q2 18.825 2 18V14Q2.825 14 3.413 13.412Q4 12.825 4 12Q4 11.175 3.413 10.587Q2.825 10 2 10V6Q2 5.175 2.588 4.588Q3.175 4 4 4H20Q20.825 4 21.413 4.588Q22 5.175 22 6V10Q21.175 10 20.587 10.587Q20 11.175 20 12Q20 12.825 20.587 13.412Q21.175 14 22 14V18Q22 18.825 21.413 19.413Q20.825 20 20 20ZM4 18H20V15.45Q19.05 14.925 18.525 13.987Q18 13.05 18 12Q18 10.95 18.525 10.012Q19.05 9.075 20 8.55V6H4V8.55Q4.95 9.075 5.475 10.012Q6 10.95 6 12Q6 13.05 5.475 13.987Q4.95 14.925 4 15.45ZM9.2 16 12 13.9 14.75 16 13.7 12.6 16.5 10.4H13.1L12 7L10.9 10.4H7.5L10.25 12.6ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Z"
})));

/***/ }),

/***/ "./src/blocks/booking/index.js":
/*!*************************************!*\
  !*** ./src/blocks/booking/index.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./block.json */ "./src/blocks/booking/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./src/blocks/booking/edit.js");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/booking/editor.scss");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./icon */ "./src/blocks/booking/icon.js");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */


const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_0__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_0__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_3__["default"],
  edit: (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__.withColors)({
    buttonColor: 'buttonColor'
  })(_edit__WEBPACK_IMPORTED_MODULE_1__["default"]),
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/booking/inspector.js":
/*!*****************************************!*\
  !*** ./src/blocks/booking/inspector.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    buttonColor,
    setButtonColor,
    setAttributes,
    attributes: {
      buttonIcon,
      buttonIconSuffix,
      bookNow
    }
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.PanelColorSettings, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Color Settings', 'events'),
    colorSettings: [{
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Set a background color for the button', 'events'),
      onChange: setButtonColor,
      value: buttonColor.color,
      disableCustomColors: true
    }]
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Button Settings', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('\"Book now\" Button', 'events'),
    value: bookNow,
    onChange: value => {
      setAttributes({
        bookNow: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Button Icon', 'events'),
    value: buttonIcon,
    onChange: value => {
      setAttributes({
        buttonIcon: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Button Icon Suffix', 'events'),
    checked: buttonIconSuffix,
    onChange: value => {
      setAttributes({
        buttonIconSuffix: value
      });
    }
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/details/edit.js":
/*!************************************!*\
  !*** ./src/blocks/details/edit.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _formatDate__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./formatDate */ "./src/blocks/details/formatDate.js");
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/details/inspector.js");

/**
 * Wordpress dependencies
 */






/**
 * Internal dependencies
 */



/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  var _event$audience;
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.select)('core/editor').getCurrentPostType();
  if (postType !== 'event') return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  const [meta, setMeta] = (0,_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__.useEntityProp)('postType', postType, 'meta');
  const {
    attributes: {
      showLocation,
      showAudience,
      showDate,
      showBookingEnd,
      showTime,
      showSpeaker,
      showPrice,
      audienceIcon,
      audienceDescription,
      speakerIcon,
      priceOverwrite,
      showBookedUp
    }
  } = props;
  const [event, setEvent] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const post_id = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(select => {
    return select('core/editor').getCurrentPostId();
  }, []);
  const getUrl = function () {
    let params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    const base = window.eventBlocksLocalization?.rest_url;
    if (base === undefined) return;
    return base + (base.includes('?') ? '&' : '?') + params;
  };
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const url = getUrl(`post_id=${post_id}`);
    fetch(url).then(response => response.json()).then(data => setEvent(data[0]));
  }, []);
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ['ctx:event-details'].filter(Boolean).join(' ')
  });
  const startFormatted = () => {
    return event?.start && event?.end ? (0,_formatDate__WEBPACK_IMPORTED_MODULE_5__.formatDateRange)(event.start, event.end) : '';
  };
  const timeFormatted = () => {
    return event?.start ? (0,_formatDate__WEBPACK_IMPORTED_MODULE_5__.formatTime)(event.start) : '';
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_6__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__wrapper"
  }, showAudience && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: "material-icons"
  }, audienceIcon), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, audienceDescription != '' ? audienceDescription : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Audience', 'events')), (_event$audience = event?.audience) !== null && _event$audience !== void 0 ? _event$audience : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('no data'))), showLocation && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: "material-icons"
  }, "place"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Location', 'events')), event?.location?.address)), showDate && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: "material-icons"
  }, "today"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Date', 'events')), startFormatted())), showTime && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: "material-icons"
  }, "schedule"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Time', 'events')), timeFormatted())), showSpeaker && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__item"
  }, speakerIcon == '' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    className: "ctx:event-details__image",
    src: event?.speaker?.image?.sizes?.thumbnail?.url
  }), ' ', !speakerIcon == '' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: "material-icons"
  }, speakerIcon), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Speaker', 'events')), event?.speaker?.name)), showPrice && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: "material-icons"
  }, "euro"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Price', 'events')), priceOverwrite != '' ? priceOverwrite : event?.price?.format)), showBookingEnd && event?.bookingEnd && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: "material-icons"
  }, "event"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Booking end', 'events')), (0,_formatDate__WEBPACK_IMPORTED_MODULE_5__.formatDate)(event?.bookingEnd))), showBookedUp && event?.bookings?.has_bookings && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-details__item"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
    className: "material-icons"
  }, "report_problem"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Warning', 'events')), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('This warning is shown, if few or no bookings are available', 'events')))));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/details/formatDate.js":
/*!******************************************!*\
  !*** ./src/blocks/details/formatDate.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "formatDate": function() { return /* binding */ formatDate; },
/* harmony export */   "formatDateRange": function() { return /* binding */ formatDateRange; },
/* harmony export */   "formatTime": function() { return /* binding */ formatTime; }
/* harmony export */ });
/**
 * Formats two dates to a date range
 * @param {Date} start 
 * @param {Date} end 
 * @returns string formatted date
 */
function formatDateRange(start) {
  let end = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  const locale = window.eventBlocksLocalization?.locale;
  start = new Date(start * 1000);
  end = end ? new Date(end * 1000) : start;
  const sameDay = start.getFullYear() === end.getFullYear() && start.getMonth() === end.getMonth() && start.getDate() === end.getDate();
  let dateFormat = {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  };
  if (sameDay) {
    dateFormat = {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric'
    };
  }
  const dateFormatObject = new Intl.DateTimeFormat(locale, dateFormat);
  return dateFormatObject.formatRange(start, end);
}

/**
 * format date by goiven format object
 * @param {Date} date 
 * @param {object} format 
 * @returns string formated date
 */
function formatDate(date, format) {
  const locale = window.eventBlocksLocalization?.locale;
  const dateFormatObject = new Intl.DateTimeFormat(locale, format);
  return dateFormatObject.format(date);
}
function formatTime(time) {
  const locale = window.eventBlocksLocalization?.locale;
  const timeFormat = {
    hour: 'numeric',
    minute: 'numeric'
  };
  const timeFormatObject = new Intl.DateTimeFormat(locale, timeFormat);
  return timeFormatObject.format(time * 1000);
}


/***/ }),

/***/ "./src/blocks/details/icon.js":
/*!************************************!*\
  !*** ./src/blocks/details/icon.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M5 22q-.825 0-1.413-.587Q3 20.825 3 20V6q0-.825.587-1.412Q4.175 4 5 4h1V2h2v2h8V2h2v2h1q.825 0 1.413.588Q21 5.175 21 6v14q0 .825-.587 1.413Q19.825 22 19 22Zm0-2h14V10H5v10ZM5 8h14V6H5Zm0 0V6v2Zm2 6v-2h10v2Zm0 4v-2h7v2Z"
})));

/***/ }),

/***/ "./src/blocks/details/index.js":
/*!*************************************!*\
  !*** ./src/blocks/details/index.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./block.json */ "./src/blocks/details/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./src/blocks/details/edit.js");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/details/editor.scss");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./icon */ "./src/blocks/details/icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_0__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_0__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_3__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_1__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/details/inspector.js":
/*!*****************************************!*\
  !*** ./src/blocks/details/inspector.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      showLocation,
      showAudience,
      showDate,
      showTime,
      showBookingEnd,
      showSpeaker,
      showPrice,
      audienceDescription,
      audienceIcon,
      locationLink,
      speakerDescription,
      priceOverwrite,
      speakerIcon,
      speakerLink,
      bookedUpWarningThreshold,
      showBookedUp
    },
    setAttributes
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Audience', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show Audience', 'events'),
    checked: showAudience,
    onChange: value => setAttributes({
      showAudience: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    disabled: !showAudience,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Description', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('If empty, "Audience" is shown', 'events'),
    value: audienceDescription,
    onChange: value => setAttributes({
      audienceDescription: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    disabled: !showAudience,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Icon', 'events'),
    value: audienceIcon,
    options: [{
      value: 'groups',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('People', 'events')
    }, {
      value: 'male',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Männlich', 'events')
    }, {
      value: 'female',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Weiblich', 'events')
    }],
    onChange: value => setAttributes({
      audienceIcon: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Location', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show Location', 'events'),
    checked: showLocation,
    onChange: value => setAttributes({
      showLocation: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    disabled: !showLocation,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Add Link', 'events'),
    checked: locationLink,
    onChange: value => setAttributes({
      locationLink: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Date and Time', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show Date', 'events'),
    checked: showDate,
    onChange: value => setAttributes({
      showDate: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show Time', 'events'),
    checked: showTime,
    onChange: value => setAttributes({
      showTime: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show booking end', 'events'),
    checked: showBookingEnd,
    onChange: value => setAttributes({
      showBookingEnd: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Speaker', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show Speaker', 'events'),
    checked: showSpeaker,
    onChange: value => setAttributes({
      showSpeaker: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    disabled: !showSpeaker,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Description', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('If empty, "Speaker" is shown', 'events'),
    value: speakerDescription,
    onChange: value => setAttributes({
      speakerDescription: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    disabled: !showSpeaker,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Icon', 'events'),
    value: speakerIcon,
    options: [{
      value: '',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Photo of speaker', 'events')
    }, {
      value: 'face',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Face', 'events')
    }, {
      value: 'support_agent',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Online Speaker', 'events')
    }],
    onChange: value => setAttributes({
      speakerIcon: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    disabled: !showSpeaker,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Add email link', 'events'),
    checked: speakerLink,
    onChange: value => setAttributes({
      speakerLink: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Price', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show Price', 'events'),
    checked: showPrice,
    onChange: value => setAttributes({
      showPrice: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    disabled: !showPrice,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Overwrite Price', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("If empty, the first ticket's price is used", 'events'),
    value: priceOverwrite,
    onChange: value => setAttributes({
      priceOverwrite: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Booked up warning', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show if event is booked up or nearly booked up', 'events'),
    checked: showBookedUp,
    onChange: value => setAttributes({
      showBookedUp: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Warning threshold', 'events'),
    value: bookedUpWarningThreshold,
    onChange: value => setAttributes({
      bookedUpWarningThreshold: value
    }),
    min: 0,
    max: 10,
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show a warning that the event is nearly booked up when only this number of spaces are left', 'events')
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/featured/edit.js":
/*!*************************************!*\
  !*** ./src/blocks/featured/edit.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/featured/inspector.js");


/**
 * Wordpress dependencies
 */





/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      columnsLarge,
      showImages,
      dropShadow,
      style,
      selectedCategory,
      isRootElement,
      textAlignment,
      roundImages,
      selectedTags
    },
    setAttributes
  } = props;
  const parentClientId = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.select)('core/block-editor').getBlockHierarchyRootClientId(props.clientId);
  setAttributes({
    isRootElement: parentClientId == props.clientId
  });
  const categoryList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__.store);
    const query = {
      hide_empty: true,
      per_page: -1
    };
    const list = getEntityRecords('taxonomy', 'event-categories', query);
    let categoryOptionsArray = [{
      value: 0,
      label: ""
    }];
    if (!list) {
      return categoryOptionsArray;
    }
    list.map(category => {
      categoryOptionsArray.push({
        value: category.id,
        label: category.name
      });
    });
    return categoryOptionsArray;
  }, []);
  const tagList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__.store);
    const query = {
      hide_empty: true,
      per_page: -1
    };
    const list = getEntityRecords('taxonomy', 'event-tags', query);
    if (!list) {
      return null;
    }
    return list;
  }, []);
  const locationList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__.store);
    const query = {
      per_page: -1
    };
    const list = getEntityRecords('postType', 'location', query);
    let locationOptionsArray = [{
      value: 0,
      label: ""
    }];
    if (!list) {
      return locationOptionsArray;
    }
    list.map(location => {
      locationOptionsArray.push({
        value: location.id,
        label: location.title.raw
      });
    });
    return locationOptionsArray;
  }, []);
  const currentEvent = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_3__.store);
    let query = {
      per_page: -1
    };
    if (selectedCategory !== 0) {
      query["event-categories"] = selectedCategory;
    }
    if (selectedTags !== []) {
      query["event-tags"] = selectedTags;
    }
    const events = getEntityRecords('postType', 'event', query);
    if (!events) {
      return false;
    }
    let mostRecent = events[0];
    events.forEach(event => {
      let currentEventDate = new Date(mostRecent.meta._event_start_date);
      let nextEventDate = new Date(event.meta._event_start_date);
      if (nextEventDate > currentEventDate) {
        mostRecent = event;
      }
    });
    const img = getEntityRecords('postType', 'attachment', {
      include: [mostRecent.featured_media]
    });
    if (img) {
      mostRecent.image = img[0].media_details.sizes.large.source_url;
    }
    return mostRecent;
  }, [selectedCategory, selectedTags]);
  let tagNames = [];
  let tagsFieldValue = [];
  if (tagList !== null) {
    tagNames = tagList.map(tag => tag.name);
    tagsFieldValue = selectedTags.map(tagId => {
      let wantedTag = tagList.find(tag => {
        return tag.id === tagId;
      });
      if (wantedTag === undefined || !wantedTag) {
        return false;
      }
      return wantedTag.name;
    });
  }
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps)({
    className: ["ctx-featured-event", isRootElement ? "is-root" : false, dropShadow ? "hover" : false, "style-" + style, "text-" + textAlignment, roundImages ? "round-images" : false].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_6__["default"], (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props, {
    tagList: tagList,
    categoryList: categoryList,
    tagsFieldValue: tagsFieldValue,
    tagNames: tagNames,
    locationList: locationList
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.BlockControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.AlignmentToolbar, {
    value: textAlignment,
    onChange: event => setAttributes({
      textAlignment: event
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", null, currentEvent && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, blockProps, {
    style: {
      backgroundImage: `url(${currentEvent.image})`
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "overlay"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("h1", null, currentEvent.title.raw), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("p", null, currentEvent.excerpt.raw), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("p", null, currentEvent.meta._event_start_date))), !currentEvent && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("h2", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("No events found", "events-manager"))));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/featured/icon.js":
/*!*************************************!*\
  !*** ./src/blocks/featured/icon.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24px",
  viewBox: "0 0 24 24",
  width: "24px",
  fill: "#000000"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M0 0h24v24H0z",
  fill: "none"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M16.53 11.06L15.47 10l-4.88 4.88-2.12-2.12-1.06 1.06L10.59 17l5.94-5.94zM19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"
})));

/***/ }),

/***/ "./src/blocks/featured/index.js":
/*!**************************************!*\
  !*** ./src/blocks/featured/index.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./block.json */ "./src/blocks/featured/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./src/blocks/featured/edit.js");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/featured/editor.scss");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./icon */ "./src/blocks/featured/icon.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title
} = _block_json__WEBPACK_IMPORTED_MODULE_0__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_0__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'ctx-blocks'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_3__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_1__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/featured/inspector.js":
/*!******************************************!*\
  !*** ./src/blocks/featured/inspector.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      limit,
      columnsSmall,
      columnsMedium,
      columnsLarge,
      showImages,
      dropShadow,
      style,
      showCategory,
      showLocation,
      roundImages,
      excerptLength,
      selectedCategory,
      selectedLocation,
      fromDate,
      toDate,
      order,
      showAudience,
      showSpeaker
    },
    tagList,
    categoryList,
    tagsFieldValue,
    locationList,
    tagNames,
    setAttributes
  } = props;
  const locationViewOptions = [{
    value: "",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("", 'events')
  }, {
    value: "city",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("City", 'events')
  }, {
    value: "name",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Name", 'events')
  }];
  const orderListViewOptions = [{
    value: "ASC",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Ascending", 'events')
  }, {
    value: "DESC",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Descending", 'events')
  }];
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Category', 'events'),
    value: selectedCategory,
    options: categoryList,
    onChange: value => {
      setAttributes({
        selectedCategory: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FormTokenField, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Tags', 'events'),
    value: tagsFieldValue,
    suggestions: tagNames,
    onChange: selectedTags => {
      let selectedTagsArray = [];
      selectedTags.map(tagName => {
        const matchingTag = tagList.find(tag => {
          return tag.name === tagName;
        });
        if (matchingTag !== undefined) {
          selectedTagsArray.push(matchingTag.id);
        }
      });
      setAttributes({
        selectedTags: selectedTagsArray
      });
    },
    __experimentalExpandOnFocus: true
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Location', 'events'),
    value: selectedLocation,
    options: locationList,
    onChange: value => {
      setAttributes({
        selectedLocation: value
      });
    }
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Drop shadow", 'events'),
    checked: dropShadow,
    onChange: value => setAttributes({
      dropShadow: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Location', 'events'),
    value: showLocation,
    options: locationViewOptions,
    onChange: value => {
      setAttributes({
        showLocation: value
      });
    }
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Length of preview text", 'events'),
    max: 200,
    min: 0,
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of words", 'events'),
    onChange: value => {
      setAttributes({
        excerptLength: value
      });
    },
    value: excerptLength
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show Audience", 'events'),
    checked: showAudience,
    onChange: value => setAttributes({
      showAudience: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show Speaker", 'events'),
    checked: showSpeaker,
    onChange: value => setAttributes({
      showSpeaker: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show category", 'events'),
    checked: showCategory,
    onChange: value => setAttributes({
      showCategory: value
    })
  }))));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/checkbox/edit.js":
/*!******************************************!*\
  !*** ./src/blocks/form/checkbox/edit.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/checkbox/inspector.js");

/**
 * Wordpress dependencies
 */




/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      label,
      fieldid,
      help,
      style,
      placeholder
    },
    setAttributes
  } = props;
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '-');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", "ctx:event-field--" + width, validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_4__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Slug", "events"),
    onChange: value => setFieldId(value)
  }), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Please type in a unique itentifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "label"
  }, style == "checkbox" && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    checked: placeholder,
    onChange: value => setAttributes({
      placeholder: value
    })
  }), style == "toggle" && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    checked: placeholder,
    onChange: value => setAttributes({
      placeholder: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: help,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("What should your visitor say \"yes\" to?", "events"),
    onChange: value => setAttributes({
      help: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/checkbox/icons.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/checkbox/icons.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


const icons = {
  checkbox: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    height: "24",
    width: "24"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M5 21Q4.175 21 3.587 20.413Q3 19.825 3 19V5Q3 4.175 3.587 3.587Q4.175 3 5 3H19Q19.825 3 20.413 3.587Q21 4.175 21 5V19Q21 19.825 20.413 20.413Q19.825 21 19 21ZM5 19H19Q19 19 19 19Q19 19 19 19V5Q19 5 19 5Q19 5 19 5H5Q5 5 5 5Q5 5 5 5V19Q5 19 5 19Q5 19 5 19ZM10.6 16.2 17.65 9.15 16.25 7.75 10.6 13.4 7.75 10.55 6.35 11.95ZM5 19Q5 19 5 19Q5 19 5 19V5Q5 5 5 5Q5 5 5 5Q5 5 5 5Q5 5 5 5V19Q5 19 5 19Q5 19 5 19Z"
  })),
  toggle: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    height: "24px",
    viewBox: "0 0 24 24",
    width: "24px",
    fill: "#000000"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M0 0h24v24H0z",
    fill: "none"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M17 6H7c-3.31 0-6 2.69-6 6s2.69 6 6 6h10c3.31 0 6-2.69 6-6s-2.69-6-6-6zm0 10H7c-2.21 0-4-1.79-4-4s1.79-4 4-4h10c2.21 0 4 1.79 4 4s-1.79 4-4 4zm0-7c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"
  }))
};
/* harmony default export */ __webpack_exports__["default"] = (icons);

/***/ }),

/***/ "./src/blocks/form/checkbox/index.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/checkbox/index.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/checkbox/edit.js");
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icons */ "./src/blocks/form/checkbox/icons.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/checkbox/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/checkbox/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icons__WEBPACK_IMPORTED_MODULE_1__["default"].checkbox,
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/checkbox/inspector.js":
/*!***********************************************!*\
  !*** ./src/blocks/form/checkbox/inspector.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _icons_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./icons.js */ "./src/blocks/form/checkbox/icons.js");





const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      pattern,
      label,
      options,
      style,
      help,
      error
    },
    setAttributes
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Required", 'events'),
    checked: required,
    onChange: value => setAttributes({
      required: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Error message", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Text to inform the user that this checkbox must be checked", 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    className: "components-base-control__label",
    htmlFor: "inspector-range-control-4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Style", "events")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "styleSelector"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    onClick: () => setAttributes({
      style: "checkbox"
    }),
    className: style == "checkbox" ? "active" : ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    size: "64",
    className: "icon",
    icon: _icons_js__WEBPACK_IMPORTED_MODULE_4__["default"].checkbox
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Box", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    onClick: () => setAttributes({
      style: "toggle"
    }),
    className: style == "toggle" ? "active" : ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    size: "64",
    className: "icon",
    icon: _icons_js__WEBPACK_IMPORTED_MODULE_4__["default"].toggle
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Toggle", "events"))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Width", 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of columns the input field will occupy", 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/container/edit.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/container/edit.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ Edit; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);


/**
 * Wordpress dependencies
 */



function Edit(_ref) {
  let {
    ...props
  } = _ref;
  const {
    clientId
  } = props;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps)();
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)((0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.select)('core/editor').getCurrentPostType);
  if (['bookingform', 'attendeeform'].includes(postType)) {
    console.log('registering form');
    document.getElementsByClassName('edit-post-fullscreen-mode-close')[0]?.setAttribute('href', 'edit.php?post_type=event&page=events-manager-forms');
  }
  const allowedBlocks = ['events-manager/form-text', 'events-manager/form-email', 'events-manager/form-textarea', 'events-manager/form-select', 'events-manager/form-country', 'events-manager/form-phone', 'events-manager/form-radio', 'events-manager/form-checkbox', 'events-manager/form-date', 'events-manager/form-html'];
  const innerBlocksProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useInnerBlocksProps)(blockProps, {
    allowedBlocks,
    renderAppender: false
  });
  function SectionAppender(_ref2) {
    let {
      rootClientId
    } = _ref2;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.Inserter, {
      rootClientId: rootClientId,
      renderToggle: _ref3 => {
        let {
          onToggle,
          disabled
        } = _ref3;
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("a", {
          className: "components-button is-primary",
          onClick: onToggle
        }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Add Field", "events"));
      },
      isAppender: true
    });
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("form", {
    autocomplete: "off",
    className: "ctx:event-form"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, innerBlocksProps, {
    className: "ctx:event-form__container"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "ctx:event-form__appender"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(SectionAppender, {
    rootClientId: clientId
  })));
}

/***/ }),

/***/ "./src/blocks/form/container/icon.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/container/icon.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M12 21v-2.125l5.3-5.3 2.125 2.125-5.3 5.3Zm-9-5v-2h7v2Zm17.125-1L18 12.875l.725-.725q.275-.275.7-.275.425 0 .7.275l.725.725q.275.275.275.7 0 .425-.275.7ZM3 12v-2h11v2Zm0-4V6h11v2Z"
})));

/***/ }),

/***/ "./src/blocks/form/container/index.js":
/*!********************************************!*\
  !*** ./src/blocks/form/container/index.js ***!
  \********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/container/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/container/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/container/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/container/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__);

/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */


const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_3__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_3__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_2__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_1__["default"],
  save: () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__.InnerBlocks.Content, null);
  }
};


/***/ }),

/***/ "./src/blocks/form/country/edit.js":
/*!*****************************************!*\
  !*** ./src/blocks/form/country/edit.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/country/inspector.js");

/**
 * Wordpress dependencies
 */







/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      pattern,
      label,
      fieldid,
      help,
      error
    },
    setAttributes
  } = props;
  const countries = () => {};
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '-');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", "ctx:event-field--" + width, validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_5__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Slug", "events"),
    onChange: value => setFieldId(value)
  }), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Please type in a unique itentifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", null, Object.keys(window.eventBlocksLocalization?.countries).map(country => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
      value: country
    }, window.eventBlocksLocalization?.countries[country]);
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/country/icon.js":
/*!*****************************************!*\
  !*** ./src/blocks/form/country/icon.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M12 22Q9.95 22 8.125 21.212Q6.3 20.425 4.938 19.062Q3.575 17.7 2.788 15.875Q2 14.05 2 12Q2 9.925 2.788 8.113Q3.575 6.3 4.938 4.938Q6.3 3.575 8.125 2.787Q9.95 2 12 2Q14.075 2 15.887 2.787Q17.7 3.575 19.062 4.938Q20.425 6.3 21.212 8.113Q22 9.925 22 12Q22 14.05 21.212 15.875Q20.425 17.7 19.062 19.062Q17.7 20.425 15.887 21.212Q14.075 22 12 22ZM15.95 8H18.9Q18.175 6.75 17.087 5.825Q16 4.9 14.6 4.45Q15.05 5.275 15.388 6.162Q15.725 7.05 15.95 8ZM10.1 8H13.9Q13.6 6.9 13.125 5.925Q12.65 4.95 12 4.05Q11.35 4.95 10.875 5.925Q10.4 6.9 10.1 8ZM4.25 14H7.65Q7.575 13.5 7.537 13.012Q7.5 12.525 7.5 12Q7.5 11.475 7.537 10.988Q7.575 10.5 7.65 10H4.25Q4.125 10.5 4.062 10.988Q4 11.475 4 12Q4 12.525 4.062 13.012Q4.125 13.5 4.25 14ZM9.4 19.55Q8.95 18.725 8.613 17.837Q8.275 16.95 8.05 16H5.1Q5.825 17.25 6.912 18.175Q8 19.1 9.4 19.55ZM5.1 8H8.05Q8.275 7.05 8.613 6.162Q8.95 5.275 9.4 4.45Q8 4.9 6.912 5.825Q5.825 6.75 5.1 8ZM12 19.95Q12.65 19.05 13.125 18.075Q13.6 17.1 13.9 16H10.1Q10.4 17.1 10.875 18.075Q11.35 19.05 12 19.95ZM9.65 14H14.35Q14.425 13.5 14.463 13.012Q14.5 12.525 14.5 12Q14.5 11.475 14.463 10.988Q14.425 10.5 14.35 10H9.65Q9.575 10.5 9.538 10.988Q9.5 11.475 9.5 12Q9.5 12.525 9.538 13.012Q9.575 13.5 9.65 14ZM14.6 19.55Q16 19.1 17.087 18.175Q18.175 17.25 18.9 16H15.95Q15.725 16.95 15.388 17.837Q15.05 18.725 14.6 19.55ZM16.35 14H19.75Q19.875 13.5 19.938 13.012Q20 12.525 20 12Q20 11.475 19.938 10.988Q19.875 10.5 19.75 10H16.35Q16.425 10.5 16.462 10.988Q16.5 11.475 16.5 12Q16.5 12.525 16.462 13.012Q16.425 13.5 16.35 14Z"
})));

/***/ }),

/***/ "./src/blocks/form/country/index.js":
/*!******************************************!*\
  !*** ./src/blocks/form/country/index.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/country/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/country/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/country/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/country/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_1__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/country/inspector.js":
/*!**********************************************!*\
  !*** ./src/blocks/form/country/inspector.js ***!
  \**********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      error
    },
    setAttributes
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Required", 'events'),
    checked: required,
    onChange: value => setAttributes({
      required: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Error message", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Text to inform the user that a choice has to be made", 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Width", 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of columns the input field will occupy", 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/date/dateDiff.js":
/*!******************************************!*\
  !*** ./src/blocks/form/date/dateDiff.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);

function dateDiff(date1, date2) {
  const date1_time_stamp = new Date(date1).getTime();
  const date2_time_stamp = new Date(date2).getTime();
  const calc = date1_time_stamp > date2_time_stamp ? new Date(date1_time_stamp - date2_time_stamp) : new Date(date2_time_stamp - date1_time_stamp);
  const calcFormat = `${calc.getDate()}-${calc.getMonth() + 1}-${calc.getFullYear()}`.split('-');

  //Subtract each member of our array from the default date
  const days_passed = Number(Math.abs(calcFormat[0]) - 1);
  const months_passed = Number(Math.abs(calcFormat[1]) - 1);
  const years_passed = Number(Math.abs(calcFormat[2]) - 1970);

  //Convert to days and sum together
  const total_days = years_passed * 365 + months_passed * 30.417 + days_passed;

  //display result with custom text
  const result = (years_passed == 1 ? `${years_passed} ${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('year', 'events')} ` : years_passed > 1 ? `${years_passed} ${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('years', 'events')} ` : '') + (months_passed == 1 ? `${months_passed} ${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('month', 'events')} ` : months_passed > 1 ? `${months_passed} ${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('months', 'events')} ` : '') + (days_passed == 1 ? `${days_passed} ${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('day', 'events')}` : days_passed > 1 ? `${days_passed} ${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('days', 'events')}` : '');

  //return the result
  return {
    total_days: Math.round(total_days),
    years: years_passed,
    months: months_passed,
    days: days_passed,
    result: result.trim()
  };
}
/* harmony default export */ __webpack_exports__["default"] = (dateDiff);

/***/ }),

/***/ "./src/blocks/form/date/edit.js":
/*!**************************************!*\
  !*** ./src/blocks/form/date/edit.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/date/inspector.js");

/**
 * Wordpress dependencies
 */



/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      pattern,
      placeholder,
      label,
      fieldid,
      help,
      error
    },
    setAttributes
  } = props;
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '-');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", "ctx:event-field--" + width, validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_3__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Slug", "events"),
    onChange: value => setFieldId(value)
  }), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Please type in a unique itentifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    autocomplete: "off",
    value: placeholder,
    type: "date",
    onChange: event => setAttributes({
      placeholder: event.target.value
    })
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/date/icon.js":
/*!**************************************!*\
  !*** ./src/blocks/form/date/icon.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M14.5 18Q13.45 18 12.725 17.275Q12 16.55 12 15.5Q12 14.45 12.725 13.725Q13.45 13 14.5 13Q15.55 13 16.275 13.725Q17 14.45 17 15.5Q17 16.55 16.275 17.275Q15.55 18 14.5 18ZM5 22Q4.175 22 3.587 21.413Q3 20.825 3 20V6Q3 5.175 3.587 4.588Q4.175 4 5 4H6V2H8V4H16V2H18V4H19Q19.825 4 20.413 4.588Q21 5.175 21 6V20Q21 20.825 20.413 21.413Q19.825 22 19 22ZM5 20H19Q19 20 19 20Q19 20 19 20V10H5V20Q5 20 5 20Q5 20 5 20ZM5 8H19V6Q19 6 19 6Q19 6 19 6H5Q5 6 5 6Q5 6 5 6ZM5 8V6Q5 6 5 6Q5 6 5 6Q5 6 5 6Q5 6 5 6V8Z"
})));

/***/ }),

/***/ "./src/blocks/form/date/index.js":
/*!***************************************!*\
  !*** ./src/blocks/form/date/index.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/date/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/date/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/date/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/date/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_1__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/date/inspector.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/date/inspector.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _dateDiff__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./dateDiff */ "./src/blocks/form/date/dateDiff.js");






const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      min,
      max,
      help,
      error
    },
    setAttributes
  } = props;
  const [referenceDate, setReferenceDate] = (0,react__WEBPACK_IMPORTED_MODULE_4__.useState)(Date());
  const minAge = (0,_dateDiff__WEBPACK_IMPORTED_MODULE_5__["default"])(min, referenceDate);
  const maxAge = (0,_dateDiff__WEBPACK_IMPORTED_MODULE_5__["default"])(max, referenceDate);
  const ageInfo = () => {
    if (minAge === 0 && maxAge !== 0) {
      return `${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('at best', 'events')} ${maxAge.result} }`;
    }
    if (minAge !== 0 && maxAge === 0) {
      return `${(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('at least', 'events')} ${minAge.result} }`;
    }
    if (minAge === maxAge) {
      return `${minAge.result}`;
    }
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('from', 'events'), " ", maxAge.result, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('to', 'events'), " ", minAge.result);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Required', 'events'),
    checked: required,
    onChange: value => setAttributes({
      required: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Help', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Help text for the date field', 'events'),
    value: help,
    onChange: value => setAttributes({
      help: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Error message', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Text to display when the user types in invalid or insufficient data', 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Lowest Date', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('e.g. maximal age for an attendee', 'events'),
    value: min,
    onChange: value => setAttributes({
      min: value
    }),
    type: "date"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Highest Date', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('e.g. minimal age for an attendee', 'events'),
    value: max,
    onChange: value => setAttributes({
      max: value
    }),
    type: "date"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "age-info"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Reference Date', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Only for testing', 'events'),
    value: referenceDate,
    onChange: value => {
      setReferenceDate(value);
    },
    type: "date"
  }), ageInfo())), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Width', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Number of columns the input field will occupy', 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/email/edit.js":
/*!***************************************!*\
  !*** ./src/blocks/form/email/edit.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/email/inspector.js");
/* harmony import */ var _lockIcon_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./lockIcon.js */ "./src/blocks/form/email/lockIcon.js");

/**
 * Wordpress dependencies
 */








/**
 * Internal dependencies
 */



/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      placeholder,
      label,
      fieldid
    },
    setAttributes
  } = props;
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const lockFieldId = 'user_email' === fieldid;
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '_');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", "ctx:event-field--" + width, validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_6__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, !lockFieldId && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Slug", "events"),
    onChange: value => setFieldId(value)
  }), lockFieldId && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-details__label--lock"
  }, fieldid, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    icon: _lockIcon_js__WEBPACK_IMPORTED_MODULE_7__["default"],
    size: 14
  })), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Please type in a unique identifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    autocomplete: "off",
    type: "text",
    value: placeholder,
    onChange: event => setAttributes({
      placeholder: event.target.value
    })
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/email/icon.js":
/*!***************************************!*\
  !*** ./src/blocks/form/email/icon.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M12 22Q9.95 22 8.125 21.212Q6.3 20.425 4.938 19.062Q3.575 17.7 2.788 15.875Q2 14.05 2 12Q2 9.925 2.788 8.113Q3.575 6.3 4.938 4.938Q6.3 3.575 8.125 2.787Q9.95 2 12 2Q14.075 2 15.887 2.787Q17.7 3.575 19.062 4.938Q20.425 6.3 21.212 8.113Q22 9.925 22 12V13.45Q22 14.925 20.988 15.962Q19.975 17 18.5 17Q17.6 17 16.825 16.6Q16.05 16.2 15.55 15.55Q14.875 16.225 13.963 16.613Q13.05 17 12 17Q9.925 17 8.463 15.537Q7 14.075 7 12Q7 9.925 8.463 8.462Q9.925 7 12 7Q14.075 7 15.538 8.462Q17 9.925 17 12V13.45Q17 14.175 17.45 14.587Q17.9 15 18.5 15Q19.1 15 19.55 14.587Q20 14.175 20 13.45V12Q20 8.725 17.637 6.362Q15.275 4 12 4Q8.725 4 6.362 6.362Q4 8.725 4 12Q4 15.275 6.362 17.637Q8.725 20 12 20H17V22ZM12 15Q13.25 15 14.125 14.125Q15 13.25 15 12Q15 10.75 14.125 9.875Q13.25 9 12 9Q10.75 9 9.875 9.875Q9 10.75 9 12Q9 13.25 9.875 14.125Q10.75 15 12 15Z"
})));

/***/ }),

/***/ "./src/blocks/form/email/index.js":
/*!****************************************!*\
  !*** ./src/blocks/form/email/index.js ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/email/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/email/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/email/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/email/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_1__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/email/inspector.js":
/*!********************************************!*\
  !*** ./src/blocks/form/email/inspector.js ***!
  \********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      fieldid,
      pattern,
      label,
      name,
      help,
      error
    },
    setAttributes
  } = props;
  const lockFieldId = 'user_email' === fieldid;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Required", 'events'),
    checked: required,
    disabled: lockFieldId,
    onChange: value => setAttributes({
      required: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Help", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Help text for the input field", 'events'),
    value: help,
    onChange: value => setAttributes({
      help: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Error message", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Text to display when the user types in invalid or insufficient data", 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Width", 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of columns the input field will occupy", 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/email/lockIcon.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/email/lockIcon.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "14px",
  viewBox: "0 0 24 24",
  width: "14px",
  fill: "#555555"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 0h24v24H0z",
  fill: "none"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"
})));

/***/ }),

/***/ "./src/blocks/form/html/edit.js":
/*!**************************************!*\
  !*** ./src/blocks/form/html/edit.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ Edit; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/html/inspector.js");

/**
 * Wordpress dependencies
 */



function Edit(_ref) {
  let {
    ...props
  } = _ref;
  const {
    attributes: {
      width
    }
  } = props;
  const template = [['core/paragraph']];
  const allowedBlocks = ['core/paragraph', 'core/heading', 'core/list', 'core/quote', 'core/image', 'core/group'];
  const innerBlocksProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useInnerBlocksProps)({}, {
    allowedBlocks,
    template,
    templateLock: false
  });
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps)({
    className: ["ctx:event-html", "ctx:event-html--" + width].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_3__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", innerBlocksProps));
}

/***/ }),

/***/ "./src/blocks/form/html/icon.js":
/*!**************************************!*\
  !*** ./src/blocks/form/html/icon.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M3 18V16H15V18ZM3 13V11H21V13ZM3 8V6H21V8Z"
})));

/***/ }),

/***/ "./src/blocks/form/html/index.js":
/*!***************************************!*\
  !*** ./src/blocks/form/html/index.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/html/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/html/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/html/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/html/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__);

/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */


const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_3__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_3__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_2__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_1__["default"],
  save: () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__.InnerBlocks.Content, null);
  }
};


/***/ }),

/***/ "./src/blocks/form/html/inspector.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/html/inspector.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      width
    },
    setAttributes
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Width", 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of columns the input field will occupy", 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/phone/edit.js":
/*!***************************************!*\
  !*** ./src/blocks/form/phone/edit.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/phone/inspector.js");

/**
 * Wordpress dependencies
 */



/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      label,
      fieldid
    },
    setAttributes
  } = props;
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '-');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", "ctx:event-field--" + width, validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_3__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Slug", "events"),
    onChange: value => setFieldId(value)
  }), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Please type in a unique itentifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    autocomplete: "off",
    type: "tel",
    onChange: event => setName({
      placeholder: event.target.value
    })
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/phone/icon.js":
/*!***************************************!*\
  !*** ./src/blocks/form/phone/icon.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M19 12Q19 9.075 16.962 7.037Q14.925 5 12 5V3Q13.875 3 15.513 3.712Q17.15 4.425 18.363 5.637Q19.575 6.85 20.288 8.487Q21 10.125 21 12ZM15 12Q15 10.75 14.125 9.875Q13.25 9 12 9V7Q14.075 7 15.538 8.462Q17 9.925 17 12ZM19.95 21Q16.725 21 13.663 19.562Q10.6 18.125 8.238 15.762Q5.875 13.4 4.438 10.337Q3 7.275 3 4.05Q3 3.6 3.3 3.3Q3.6 3 4.05 3H8.1Q8.45 3 8.725 3.225Q9 3.45 9.05 3.8L9.7 7.3Q9.75 7.65 9.688 7.937Q9.625 8.225 9.4 8.45L7 10.9Q8.05 12.7 9.625 14.275Q11.2 15.85 13.1 17L15.45 14.65Q15.675 14.425 16.038 14.312Q16.4 14.2 16.75 14.25L20.2 14.95Q20.55 15.025 20.775 15.287Q21 15.55 21 15.9V19.95Q21 20.4 20.7 20.7Q20.4 21 19.95 21ZM6.05 9 7.7 7.35Q7.7 7.35 7.7 7.35Q7.7 7.35 7.7 7.35L7.25 5Q7.25 5 7.25 5Q7.25 5 7.25 5H5.05Q5.05 5 5.05 5Q5.05 5 5.05 5Q5.175 6.025 5.4 7.025Q5.625 8.025 6.05 9ZM19 18.95Q19 18.95 19 18.95Q19 18.95 19 18.95V16.75Q19 16.75 19 16.75Q19 16.75 19 16.75L16.65 16.25Q16.65 16.25 16.65 16.25Q16.65 16.25 16.65 16.25L15 17.9Q15.975 18.325 16.975 18.6Q17.975 18.875 19 18.95ZM15 17.9Q15 17.9 15 17.9Q15 17.9 15 17.9Q15 17.9 15 17.9Q15 17.9 15 17.9Q15 17.9 15 17.9Q15 17.9 15 17.9Q15 17.9 15 17.9Q15 17.9 15 17.9ZM6.05 9Q6.05 9 6.05 9Q6.05 9 6.05 9Q6.05 9 6.05 9Q6.05 9 6.05 9Q6.05 9 6.05 9Q6.05 9 6.05 9Q6.05 9 6.05 9Q6.05 9 6.05 9Z"
})));

/***/ }),

/***/ "./src/blocks/form/phone/index.js":
/*!****************************************!*\
  !*** ./src/blocks/form/phone/index.js ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/phone/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/phone/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/phone/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/phone/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_1__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/phone/inspector.js":
/*!********************************************!*\
  !*** ./src/blocks/form/phone/inspector.js ***!
  \********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      help,
      error
    },
    setAttributes
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Required", 'events'),
    checked: required,
    onChange: value => setAttributes({
      required: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Pattern", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Help text for the input field", 'events'),
    value: help,
    onChange: value => setAttributes({
      help: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Error message", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Text to display when the user types in invalid or insufficient data", 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Width", 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of columns the input field will occupy", 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/radio/edit.js":
/*!***************************************!*\
  !*** ./src/blocks/form/radio/edit.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/radio/inspector.js");

/**
 * Wordpress dependencies
 */





/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      placeholder,
      label,
      fieldid,
      options
    },
    setAttributes
  } = props;
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '-');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", "ctx:event-field--" + width, validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  console.log(placeholder);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_4__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Slug", "events"),
    onChange: value => setFieldId(value)
  }), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Please type in a unique itentifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__select"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("fieldset", null, options.map((option, index) => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      type: "radio",
      name: index,
      value: index,
      checked: placeholder == index,
      onChange: () => {
        setAttributes({
          placeholder: index
        });
      }
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, option));
  }))));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/radio/icon.js":
/*!***************************************!*\
  !*** ./src/blocks/form/radio/icon.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M12 17Q14.075 17 15.538 15.537Q17 14.075 17 12Q17 9.925 15.538 8.462Q14.075 7 12 7Q9.925 7 8.463 8.462Q7 9.925 7 12Q7 14.075 8.463 15.537Q9.925 17 12 17ZM12 22Q9.925 22 8.1 21.212Q6.275 20.425 4.925 19.075Q3.575 17.725 2.788 15.9Q2 14.075 2 12Q2 9.925 2.788 8.1Q3.575 6.275 4.925 4.925Q6.275 3.575 8.1 2.787Q9.925 2 12 2Q14.075 2 15.9 2.787Q17.725 3.575 19.075 4.925Q20.425 6.275 21.212 8.1Q22 9.925 22 12Q22 14.075 21.212 15.9Q20.425 17.725 19.075 19.075Q17.725 20.425 15.9 21.212Q14.075 22 12 22ZM12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12Q12 12 12 12ZM12 20Q15.325 20 17.663 17.663Q20 15.325 20 12Q20 8.675 17.663 6.337Q15.325 4 12 4Q8.675 4 6.338 6.337Q4 8.675 4 12Q4 15.325 6.338 17.663Q8.675 20 12 20Z"
})));

/***/ }),

/***/ "./src/blocks/form/radio/index.js":
/*!****************************************!*\
  !*** ./src/blocks/form/radio/index.js ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/radio/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/radio/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/radio/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/radio/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_1__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/radio/inspector.js":
/*!********************************************!*\
  !*** ./src/blocks/form/radio/inspector.js ***!
  \********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      options,
      error
    },
    setAttributes
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Required", 'events'),
    checked: required,
    onChange: value => setAttributes({
      required: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Error message", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Text to inform the user that a choice has to be made", 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Options", "events"),
    value: options.join("\n"),
    onChange: value => setAttributes({
      options: value.split("\n")
    }),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Options for the radio control. Each line represents one option", 'events')
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Width", 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of columns the input field will occupy", 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/select/edit.js":
/*!****************************************!*\
  !*** ./src/blocks/form/select/edit.js ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/select/inspector.js");

/**
 * Wordpress dependencies
 */





/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      pattern,
      label,
      fieldid,
      help,
      error,
      options,
      hasEmptyOption
    },
    setAttributes
  } = props;
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '_');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", "ctx:event-field--" + width, validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_4__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Slug", "events"),
    onChange: value => setFieldId(value)
  }), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Please type in a unique itentifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__select"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", null, hasEmptyOption && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
    value: ""
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Make a selection", "events")), options.map((option, index) => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
      key: index,
      value: index
    }, option);
  }))));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/select/icon.js":
/*!****************************************!*\
  !*** ./src/blocks/form/select/icon.js ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M5 9H10V7H5ZM5 13H10V11H5ZM5 17H10V15H5ZM14.55 15 19.5 10.05 18.075 8.625 14.55 12.175 13.125 10.75 11.725 12.175ZM4 21Q3.175 21 2.588 20.413Q2 19.825 2 19V5Q2 4.175 2.588 3.587Q3.175 3 4 3H20Q20.825 3 21.413 3.587Q22 4.175 22 5V19Q22 19.825 21.413 20.413Q20.825 21 20 21ZM4 19H20Q20 19 20 19Q20 19 20 19V5Q20 5 20 5Q20 5 20 5H4Q4 5 4 5Q4 5 4 5V19Q4 19 4 19Q4 19 4 19ZM4 19Q4 19 4 19Q4 19 4 19V5Q4 5 4 5Q4 5 4 5Q4 5 4 5Q4 5 4 5V19Q4 19 4 19Q4 19 4 19Z"
})));

/***/ }),

/***/ "./src/blocks/form/select/index.js":
/*!*****************************************!*\
  !*** ./src/blocks/form/select/index.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/select/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/select/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/select/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/select/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_1__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/select/inspector.js":
/*!*********************************************!*\
  !*** ./src/blocks/form/select/inspector.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      help,
      error,
      options,
      hasEmptyOption
    },
    setAttributes
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Required', 'events'),
    checked: required,
    onChange: value => setAttributes({
      required: value,
      hasEmptyOption: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Empty option', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('An empty option ist shown and selected as default', 'events'),
    checked: hasEmptyOption,
    disabled: required,
    onChange: value => setAttributes({
      hasEmptyOption: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Help', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Alternate text for the empty option', 'events'),
    value: help,
    disabled: !hasEmptyOption,
    onChange: value => setAttributes({
      help: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Error message', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Text to inform the user that a choice has to be made', 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextareaControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Options', 'events'),
    value: options.join('\n'),
    onChange: value => setAttributes({
      options: value.split('\n')
    }),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Options for the select control. Each line represents one option', 'events')
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Width', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Number of columns the input field will occupy', 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/text/edit.js":
/*!**************************************!*\
  !*** ./src/blocks/form/text/edit.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/text/inspector.js");
/* harmony import */ var _lockIcon_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./lockIcon.js */ "./src/blocks/form/text/lockIcon.js");

/**
 * Wordpress dependencies
 */








/**
 * Internal dependencies
 */



/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      pattern,
      placeholder,
      label,
      fieldid,
      help,
      error
    },
    setAttributes
  } = props;
  const lockFieldId = ['first_name', 'last_name'].includes(fieldid);
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '-');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", "ctx:event-field--" + width, validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_6__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, !lockFieldId && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Slug", "events"),
    onChange: value => setFieldId(value)
  }), lockFieldId && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-details__label--lock"
  }, fieldid, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    icon: _lockIcon_js__WEBPACK_IMPORTED_MODULE_7__["default"],
    size: 14
  })), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Please type in a unique itentifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    autocomplete: "off",
    value: placeholder,
    type: "text",
    onChange: event => setAttributes({
      placeholder: event.target.value
    })
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/text/icon.js":
/*!**************************************!*\
  !*** ./src/blocks/form/text/icon.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M10 21 14 17H22V21ZM4 19H5.4L14.025 10.375L12.625 8.975L4 17.6ZM18.3 8.925 14.05 4.725 15.45 3.325Q16.025 2.75 16.863 2.75Q17.7 2.75 18.275 3.325L19.675 4.725Q20.25 5.3 20.275 6.113Q20.3 6.925 19.725 7.5ZM16.85 10.4 6.25 21H2V16.75L12.6 6.15ZM13.325 9.675 12.625 8.975 14.025 10.375Z"
})));

/***/ }),

/***/ "./src/blocks/form/text/index.js":
/*!***************************************!*\
  !*** ./src/blocks/form/text/index.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/text/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/text/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/text/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/text/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_1__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/text/inspector.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/text/inspector.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      pattern,
      fieldid,
      label,
      name,
      help,
      error
    },
    setAttributes
  } = props;
  const lockFieldId = ['first_name', 'last_name'].includes(fieldid);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Required", 'events'),
    checked: required,
    disabled: lockFieldId,
    onChange: value => setAttributes({
      required: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Pattern", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Regular expression to prevent wrong or illegal input", 'events'),
    value: pattern,
    onChange: value => setAttributes({
      pattern: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Help", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Details about how to fill this field", 'events'),
    value: help,
    onChange: value => setAttributes({
      help: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Error message", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Text to display when the user types in invalid or insufficient data", 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Width", 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of columns the input field will occupy", 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/form/text/lockIcon.js":
/*!******************************************!*\
  !*** ./src/blocks/form/text/lockIcon.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "14px",
  viewBox: "0 0 24 24",
  width: "14px",
  fill: "#555555"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 0h24v24H0z",
  fill: "none"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"
})));

/***/ }),

/***/ "./src/blocks/form/textarea/edit.js":
/*!******************************************!*\
  !*** ./src/blocks/form/textarea/edit.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/form/textarea/inspector.js");

/**
 * Wordpress dependencies
 */








/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = props => {
  const {
    attributes: {
      width,
      required,
      pattern,
      placeholder,
      label,
      fieldid,
      rows,
      help,
      error
    },
    setAttributes
  } = props;
  const validFieldId = () => {
    const validPattern = new RegExp('([a-zA-Z0-9_]){3,40}');
    return validPattern.test(fieldid);
  };
  const setFieldId = value => {
    value = value.toLowerCase();
    value = value.replace(/\s/g, '-');
    setAttributes({
      fieldid: value.toLowerCase()
    });
  };
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["ctx:event-field", validFieldId() == false ? "ctx:event-field--error" : ""].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_6__["default"], props), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__caption"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "span",
    className: "ctx:event-details__label",
    value: label,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label", "events"),
    onChange: value => setAttributes({
      label: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, required ? "*" : ""), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Label for the field", "events"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ctx:event-field__name"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {
    tagName: "p",
    className: "ctx:event-details__label",
    value: fieldid,
    placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Slug", "ctx-blocks"),
    onChange: value => setFieldId(value)
  }), validFieldId() == false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__error-message"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Please type in a unique itentifier for the field", "events")), validFieldId() && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "ctx:event-field__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Unique identifier", "events")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("textarea", {
    rows: rows,
    autocomplete: "off",
    type: "text",
    onChange: event => setAttributes({
      placeholder: event.target.value
    })
  }, placeholder));
};
/* harmony default export */ __webpack_exports__["default"] = (edit);

/***/ }),

/***/ "./src/blocks/form/textarea/icon.js":
/*!******************************************!*\
  !*** ./src/blocks/form/textarea/icon.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  height: "24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M12 21V18.875L17.3 13.575L19.425 15.7L14.125 21ZM3 16V14H10V16ZM20.125 15 18 12.875 18.725 12.15Q19 11.875 19.425 11.875Q19.85 11.875 20.125 12.15L20.85 12.875Q21.125 13.15 21.125 13.575Q21.125 14 20.85 14.275ZM3 12V10H14V12ZM3 8V6H14V8Z"
})));

/***/ }),

/***/ "./src/blocks/form/textarea/index.js":
/*!*******************************************!*\
  !*** ./src/blocks/form/textarea/index.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/form/textarea/edit.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icon */ "./src/blocks/form/textarea/icon.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/form/textarea/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/form/textarea/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title,
  description
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'events'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(description, 'events'),
  icon: _icon__WEBPACK_IMPORTED_MODULE_1__["default"],
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/form/textarea/inspector.js":
/*!***********************************************!*\
  !*** ./src/blocks/form/textarea/inspector.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




const Inspector = props => {
  const {
    attributes: {
      width,
      required,
      pattern,
      label,
      rows,
      name,
      help,
      error
    },
    setAttributes
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Required", 'events'),
    checked: required,
    onChange: value => setAttributes({
      required: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Pattern", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Help text for the input field", 'events'),
    value: help,
    onChange: value => setAttributes({
      help: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Error message", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Text to display when the user types in invalid or insufficient data", 'events'),
    value: error,
    onChange: value => setAttributes({
      error: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Width", 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of columns the input field will occupy", 'events'),
    value: width,
    max: 4,
    min: 1,
    onChange: value => setAttributes({
      width: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Height", "events"),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of text rows", 'events'),
    value: rows,
    onChange: value => setAttributes({
      rows: value
    }),
    min: 1,
    max: 12
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/blocks/upcoming/edit.js":
/*!*************************************!*\
  !*** ./src/blocks/upcoming/edit.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _inspector_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./inspector.js */ "./src/blocks/upcoming/inspector.js");


/**
 * Wordpress dependencies
 */





/**
 * Internal dependencies
 */


/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const EditUpcoming = props => {
  const {
    attributes: {
      columnsLarge,
      showImages,
      style,
      textAlignment,
      selectedCategory,
      roundImages,
      selectedTags
    },
    setAttributes
  } = props;
  const categoryList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__.store);
    const query = {
      hide_empty: true
    };
    const list = getEntityRecords('taxonomy', 'event-categories', query);
    let categoryOptionsArray = [{
      value: 0,
      label: ""
    }];
    if (!list) {
      return categoryOptionsArray;
    }
    list.map(category => {
      categoryOptionsArray.push({
        value: category.id,
        label: category.name
      });
    });
    return categoryOptionsArray;
  }, []);
  const tagList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__.store);
    const query = {
      hide_empty: true
    };
    const list = getEntityRecords('taxonomy', 'event-tags', query);
    if (!list) {
      return null;
    }
    return list;
  }, []);
  const locationList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_4__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__.store);
    const query = {
      per_page: -1
    };
    const list = getEntityRecords('postType', 'location', query);
    let locationOptionsArray = [{
      value: 0,
      label: ""
    }];
    if (!list) {
      return locationOptionsArray;
    }
    list.map(location => {
      locationOptionsArray.push({
        value: location.location_id,
        label: location.title.raw
      });
    });
    return locationOptionsArray;
  }, []);
  let tagNames = [];
  let tagsFieldValue = [];
  if (tagList !== null) {
    tagNames = tagList.map(tag => tag.name);
    tagsFieldValue = selectedTags.map(tagId => {
      let wantedTag = tagList.find(tag => {
        return tag.id === tagId;
      });
      if (wantedTag === undefined || !wantedTag) {
        return false;
      }
      return wantedTag.name;
    });
  }
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps)({
    className: ["columns-" + columnsLarge, showImages ? "hasImage" : false, "style-" + style, "text-" + textAlignment, roundImages ? "round-images" : false].filter(Boolean).join(" ")
  });
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_inspector_js__WEBPACK_IMPORTED_MODULE_6__["default"], (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props, {
    tagList: tagList,
    categoryList: categoryList,
    tagsFieldValue: tagsFieldValue,
    tagNames: tagNames,
    locationList: locationList
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.BlockControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.AlignmentToolbar, {
    value: textAlignment,
    onChange: event => setAttributes({
      textAlignment: event
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "components-placeholder is-large"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "components-placeholder__label"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Upcoming Events", "events")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "components-placeholder__instructions"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("See for settings in the inspector. The result can be seen in the frontend", "events")))));
};
/* harmony default export */ __webpack_exports__["default"] = (EditUpcoming);

/***/ }),

/***/ "./src/blocks/upcoming/icons.js":
/*!**************************************!*\
  !*** ./src/blocks/upcoming/icons.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


let icons = [];
icons.posts = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  enableBackground: "new 0 0 24 24",
  height: "24",
  viewBox: "0 0 24 24",
  width: "24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  fill: "none",
  height: "24",
  width: "24"
})), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M21,5c-1.11-0.35-2.33-0.5-3.5-0.5c-1.95,0-4.05,0.4-5.5,1.5c-1.45-1.1-3.55-1.5-5.5-1.5S2.45,4.9,1,6v14.65 c0,0.25,0.25,0.5,0.5,0.5c0.1,0,0.15-0.05,0.25-0.05C3.1,20.45,5.05,20,6.5,20c1.95,0,4.05,0.4,5.5,1.5c1.35-0.85,3.8-1.5,5.5-1.5 c1.65,0,3.35,0.3,4.75,1.05c0.1,0.05,0.15,0.05,0.25,0.05c0.25,0,0.5-0.25,0.5-0.5V6C22.4,5.55,21.75,5.25,21,5z M21,18.5 c-1.1-0.35-2.3-0.5-3.5-0.5c-1.7,0-4.15,0.65-5.5,1.5V8c1.35-0.85,3.8-1.5,5.5-1.5c1.2,0,2.4,0.15,3.5,0.5V18.5z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M17.5,10.5c0.88,0,1.73,0.09,2.5,0.26V9.24C19.21,9.09,18.36,9,17.5,9c-1.7,0-3.24,0.29-4.5,0.83v1.66 C14.13,10.85,15.7,10.5,17.5,10.5z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M13,12.49v1.66c1.13-0.64,2.7-0.99,4.5-0.99c0.88,0,1.73,0.09,2.5,0.26V11.9c-0.79-0.15-1.64-0.24-2.5-0.24 C15.8,11.66,14.26,11.96,13,12.49z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M17.5,14.33c-1.7,0-3.24,0.29-4.5,0.83v1.66c1.13-0.64,2.7-0.99,4.5-0.99c0.88,0,1.73,0.09,2.5,0.26v-1.52 C19.21,14.41,18.36,14.33,17.5,14.33z"
})))));
icons.mini = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  viewBox: "0 0 32 32",
  xmlns: "http://www.w3.org/2000/svg",
  fillRule: "evenodd",
  clipRule: "evenodd",
  strokeLinejoin: "round",
  strokeMiterlimit: 2
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M30 24v2H2.429v-2h27.57zm0-10v2H2.429v-2h27.57zm0-10v2H2.429V4h27.57z",
  fillRule: "nonzero"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fill: "#919191",
  fillRule: "nonzero",
  d: "M2.429 7h24.043v3H2.429zM2.429 17h24.043v3H2.429zM2.429 27h24.043v3H2.429z"
}));
icons.list = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fillRule: "evenodd",
  strokeLinejoin: "round",
  strokeMiterlimit: "2",
  clipRule: "evenodd",
  viewBox: "0 0 32 32"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M30 24v2H10v-2h20zm0-10v2H10v-2h20zm0-10v2H10V4h20z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
  cx: "4.773",
  cy: "7",
  r: "3"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
  cx: "4.773",
  cy: "7",
  r: "3",
  transform: "translate(0 10)"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
  cx: "4.773",
  cy: "7",
  r: "3",
  transform: "translate(0 20)"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fill: "#919191",
  d: "M10 7H26.472V10H10z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fill: "#919191",
  d: "M10 7H26.472V10H10z",
  transform: "translate(0 10)"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fill: "#919191",
  d: "M10 7H26.472V10H10z",
  transform: "translate(0 20)"
}));
icons.cards = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  fillRule: "evenodd",
  strokeLinejoin: "round",
  strokeMiterlimit: "2",
  clipRule: "evenodd",
  viewBox: "0 0 32 32"
}, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fill: "#E5E5E5",
  d: "M2.274 1.794H10.702000000000002V16.772H2.274z",
  transform: "matrix(1.52569 0 0 1.59368 -1.304 1.206)"
}), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M10 24H30V26H10z",
  transform: "matrix(.4577 0 0 .96245 -.566 -6.375)"
}), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
  cx: "4.773",
  cy: "7",
  r: "3",
  transform: "translate(1.306 -.773) scale(1.52569)"
}), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fill: "#919191",
  d: "M10 7H26.472V10H10z",
  transform: "matrix(.55574 0 0 1.52569 -1.546 10.482)"
}), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fill: "#E5E5E5",
  d: "M2.274 1.794H10.702000000000002V16.772H2.274z",
  transform: "matrix(1.52569 0 0 1.59368 13.507 1.206)"
}), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M10 24H30V26H10z",
  transform: "matrix(.4577 0 0 .96245 14.245 -6.375)"
}), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
  cx: "4.773",
  cy: "7",
  r: "3",
  transform: "translate(16.117 -.773) scale(1.52569)"
}), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fill: "#919191",
  d: "M10 7H26.472V10H10z",
  transform: "matrix(.55574 0 0 1.52569 13.265 10.482)"
}), " ");
/* harmony default export */ __webpack_exports__["default"] = (icons);

/***/ }),

/***/ "./src/blocks/upcoming/index.js":
/*!**************************************!*\
  !*** ./src/blocks/upcoming/index.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./src/blocks/upcoming/edit.js");
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icons */ "./src/blocks/upcoming/icons.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/blocks/upcoming/block.json");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/upcoming/editor.scss");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/**
 * Internal dependencies
 */





/**
 * Wordpress dependencies
 */

const {
  name,
  title
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  ..._block_json__WEBPACK_IMPORTED_MODULE_2__,
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)(title, 'ctx-blocks'),
  icon: _icons__WEBPACK_IMPORTED_MODULE_1__["default"].posts,
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: () => {
    return null;
  }
};


/***/ }),

/***/ "./src/blocks/upcoming/inspector.js":
/*!******************************************!*\
  !*** ./src/blocks/upcoming/inspector.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _icons_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./icons.js */ "./src/blocks/upcoming/icons.js");





const Inspector = props => {
  const {
    attributes: {
      limit,
      columnsSmall,
      columnsMedium,
      columnsLarge,
      showImages,
      style,
      scope,
      showCategory,
      showLocation,
      excerptLength,
      selectedCategory,
      selectedLocation,
      order,
      showAudience,
      showSpeaker,
      showTagFilter,
      showCategoryFilter,
      showSearch,
      filterPosition,
      showBookedUp,
      bookedUpWarningThreshold,
      excludeCurrent
    },
    tagList,
    categoryList,
    tagsFieldValue,
    locationList,
    tagNames,
    setAttributes
  } = props;
  const postType = window.eventBlocksLocalization?.post_type;
  const locationViewOptions = [{
    value: "",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Don't show", 'events')
  }, {
    value: "name",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Name", 'events')
  }, {
    value: "city",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("City", 'events')
  }, {
    value: "country",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Country", 'events')
  }, {
    value: "state",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("State", 'events')
  }];
  const speakerViewOptions = [{
    value: "",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Don't show", 'events')
  }, {
    value: "name",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Name only", 'events')
  }, {
    value: "image",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Name and image", 'events')
  }];
  const scopeOptions = [{
    value: "future",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Future", 'events')
  }, {
    value: "past",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Past", 'events')
  }, {
    value: "today",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Today", 'events')
  }, {
    value: "tomorrow",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Tomorrow", 'events')
  }, {
    value: "month",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("This month", 'events')
  }, {
    value: "next-month",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Next month", 'events')
  }];
  const orderListViewOptions = [{
    value: "ASC",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Ascending", 'events')
  }, {
    value: "DESC",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Descending", 'events')
  }];
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Data', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    multiple: true,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Category', 'events'),
    value: selectedCategory,
    options: categoryList,
    onChange: value => {
      setAttributes({
        selectedCategory: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.FormTokenField, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Tags', 'events'),
    value: tagsFieldValue,
    suggestions: tagNames,
    onChange: selectedTags => {
      let selectedTagsArray = [];
      selectedTags.map(tagName => {
        const matchingTag = tagList.find(tag => {
          return tag.name === tagName;
        });
        if (matchingTag !== undefined) {
          selectedTagsArray.push(matchingTag.id);
        }
      });
      setAttributes({
        selectedTags: selectedTagsArray
      });
    },
    __experimentalExpandOnFocus: true
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Location', 'events'),
    value: selectedLocation,
    options: locationList,
    onChange: value => {
      setAttributes({
        selectedLocation: parseInt(value)
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Scope', 'events'),
    value: scope,
    options: scopeOptions,
    onChange: value => {
      setAttributes({
        scope: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Sorting', 'events'),
    value: order,
    options: orderListViewOptions,
    onChange: value => {
      setAttributes({
        order: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Limit', 'events'),
    max: 100,
    min: 1,
    value: limit,
    onChange: value => {
      setAttributes({
        limit: value
      });
    }
  }), postType === 'event' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Exclude current event", 'events'),
    checked: excludeCurrent,
    onChange: value => setAttributes({
      excludeCurrent: value
    }),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("If applicable, exclude the current event from the list", 'events')
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Filter', 'events')
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show category filter", 'events'),
    checked: showCategoryFilter,
    onChange: value => setAttributes({
      showCategoryFilter: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show tag filter", 'events'),
    checked: showTagFilter,
    onChange: value => setAttributes({
      showTagFilter: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show search bar", 'events'),
    checked: showSearch,
    onChange: value => setAttributes({
      showSearch: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RadioControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Position', 'events'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('May not apply on mobile phones', 'events'),
    options: [{
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Top", "events"),
      value: "top"
    }, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Side", "events"),
      value: "side"
    }],
    selected: filterPosition,
    onChange: value => setAttributes({
      filterPosition: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Design', 'events'),
    initialOpen: false
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Columns on small screens", 'events'),
    max: 6,
    min: 1,
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("ex. Smartphones", 'events'),
    onChange: value => {
      setAttributes({
        columnsSmall: value
      });
    },
    value: columnsSmall
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Columns on medium screens", 'events'),
    max: 6,
    min: 1,
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Tablets and smaller screens", 'events'),
    onChange: value => {
      setAttributes({
        columnsMedium: value
      });
    },
    value: columnsMedium
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Columns on large screens", 'events'),
    max: 6,
    min: 1,
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Desktop screens", 'events'),
    onChange: value => {
      setAttributes({
        columnsLarge: value
      });
    },
    value: columnsLarge
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    className: "components-base-control__label",
    htmlFor: "inspector-range-control-4"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Style", 'events')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "styleSelector"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    onClick: () => setAttributes({
      style: "mini"
    }),
    className: style == "mini" ? "active" : ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    size: "64",
    className: "icon",
    icon: _icons_js__WEBPACK_IMPORTED_MODULE_4__["default"].mini
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Minimal", 'events'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    onClick: () => setAttributes({
      style: "list"
    }),
    className: style == "list" ? "active" : ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    size: "64",
    className: "icon",
    icon: _icons_js__WEBPACK_IMPORTED_MODULE_4__["default"].list
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("List", 'events'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    onClick: () => setAttributes({
      style: "cards"
    }),
    className: style == "cards" ? "active" : ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    size: "64",
    className: "icon",
    icon: _icons_js__WEBPACK_IMPORTED_MODULE_4__["default"].cards
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Cards", 'events')))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Location', 'events'),
    value: showLocation,
    options: locationViewOptions,
    onChange: value => {
      setAttributes({
        showLocation: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show Speaker', 'events'),
    value: showSpeaker,
    options: speakerViewOptions,
    onChange: value => {
      setAttributes({
        showSpeaker: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Length of preview text", 'events'),
    max: 200,
    min: 0,
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Number of words", 'events'),
    onChange: value => {
      setAttributes({
        excerptLength: value
      });
    },
    value: excerptLength
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show audience", 'events'),
    checked: showAudience,
    onChange: value => setAttributes({
      showAudience: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show image", 'events'),
    checked: showImages,
    onChange: value => setAttributes({
      showImages: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show category", 'events'),
    checked: showCategory,
    onChange: value => setAttributes({
      showCategory: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show if event is booked up or nearly booked up", 'events'),
    checked: showBookedUp,
    onChange: value => setAttributes({
      showBookedUp: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Warning threshold", 'events'),
    value: bookedUpWarningThreshold,
    onChange: value => setAttributes({
      bookedUpWarningThreshold: value
    }),
    min: 0,
    max: 10,
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show a warning that the event is nearly booked up when only this number of spaces are left", 'events')
  })));
};
/* harmony default export */ __webpack_exports__["default"] = (Inspector);

/***/ }),

/***/ "./src/plugins/event/booking.js":
/*!**************************************!*\
  !*** ./src/plugins/event/booking.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _booking_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./booking.scss */ "./src/plugins/event/booking.scss");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);

/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */






const bookingOptions = () => {
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.select)('core/editor').getCurrentPostType();
  if (postType !== 'event') return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  const [meta, setMeta] = (0,_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__.useEntityProp)('postType', postType, 'meta');
  const bookingFormList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__.store);
    const query = {
      per_page: -1
    };
    const list = getEntityRecords('postType', 'bookingform', query);
    let formsArray = [{
      value: 0,
      label: ""
    }];
    if (!list) {
      return formsArray;
    }
    list.map(form => {
      formsArray.push({
        value: form.id,
        label: form.title.raw
      });
    });
    return formsArray;
  }, []);
  console.log(meta);
  const attendeeFormList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__.store);
    const query = {
      per_page: -1
    };
    const list = getEntityRecords('postType', 'attendeeform', query);
    let formsArray = [{
      value: 0,
      label: ""
    }];
    if (!list) {
      return formsArray;
    }
    list.map(form => {
      formsArray.push({
        value: form.id,
        label: form.title.raw
      });
    });
    return formsArray;
  }, []);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__.PluginDocumentSettingPanel, {
    name: "events-booking-options",
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('Bookings', 'events'),
    className: "events-form-settings"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('Registration Form', 'events'),
    value: meta._booking_form,
    onChange: value => {
      setMeta({
        _booking_form: value
      });
    },
    options: bookingFormList,
    disableCustomColors: true
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('Attendee Form', 'events'),
    value: meta._attendee_form,
    onChange: value => {
      setMeta({
        _attendee_form: value
      });
    },
    options: attendeeFormList,
    disableCustomColors: true
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (bookingOptions);

/***/ }),

/***/ "./src/plugins/event/datetime.js":
/*!***************************************!*\
  !*** ./src/plugins/event/datetime.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _datetime_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./datetime.scss */ "./src/plugins/event/datetime.scss");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);

/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */






const datetimeSelector = () => {
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.select)('core/editor').getCurrentPostType();
  if (postType !== 'event') return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  const [meta, setMeta] = (0,_wordpress_core_data__WEBPACK_IMPORTED_MODULE_5__.useEntityProp)('postType', postType, 'meta');
  const getNow = () => {
    let endDate = new Date();
    console.log('getNow');
    return endDate.toISOString().split('T')[0];
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__.PluginDocumentSettingPanel, {
    name: "events-datetime-settings",
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('Time and Date', 'events'),
    className: "events-datetime-settings"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "em-date-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('Date', 'events')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    for: "em-from-date"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('From', 'events')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
    value: meta._event_start_date ? meta._event_start_date : getNow(),
    onChange: value => {
      setMeta({
        _event_start_date: value
      });
    },
    name: "em-from-date",
    type: "date",
    className: "em-date-input"
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    for: "em-to-date"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('To', 'events')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
    value: meta._event_end_date ? meta._event_end_date : meta._event_start_date,
    onChange: value => {
      setMeta({
        _event_end_date: value
      });
    },
    name: "em-to-date",
    min: meta._event_start_date,
    type: "date",
    className: "em-date-input"
  })))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('Time', 'events')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelRow, {
    className: "em-time-row"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
    value: meta._event_start_time,
    onChange: value => {
      setMeta({
        _event_start_time: value
      });
    },
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('Starts at', 'events'),
    disabled: meta._event_all_day,
    type: "time"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
    value: meta._event_end_time,
    onChange: value => {
      setMeta({
        _event_end_time: value
      });
    },
    disabled: meta._event_all_day,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('Ends at', 'events'),
    type: "time"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.CheckboxControl, {
    checked: meta._event_all_day == 1,
    onChange: value => {
      setMeta({
        _event_all_day: value ? 1 : 0
      });
    },
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__)('All day', 'events')
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (datetimeSelector);

/***/ }),

/***/ "./src/plugins/event/location.js":
/*!***************************************!*\
  !*** ./src/plugins/event/location.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);

/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */





const locationSelector = () => {
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.select)('core/editor').getCurrentPostType();
  if (postType !== 'event') return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  const [meta, setMeta] = (0,_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__.useEntityProp)('postType', postType, 'meta');
  const locationList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__.store);
    const query = {
      per_page: -1
    };
    const list = getEntityRecords('postType', 'location', query);
    let formsArray = [{
      value: 0,
      label: ""
    }];
    if (!list) {
      return formsArray;
    }
    list.map(form => {
      formsArray.push({
        value: form.location_id,
        label: form.title.raw
      });
    });
    return formsArray;
  }, []);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__.PluginDocumentSettingPanel, {
    name: "events-location-settings",
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Location', 'events'),
    className: "events-location-settings"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Select a location', 'events'),
    value: meta._location_id,
    onChange: value => {
      setMeta({
        _location_id: value
      });
    },
    options: locationList
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (locationSelector);

/***/ }),

/***/ "./src/plugins/event/people.js":
/*!*************************************!*\
  !*** ./src/plugins/event/people.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/core-data */ "@wordpress/core-data");
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);

/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */





const peopleSelector = () => {
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.select)('core/editor').getCurrentPostType();
  if (postType !== 'event') return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  const [meta, setMeta] = (0,_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__.useEntityProp)('postType', postType, 'meta');
  const speakerList = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(select => {
    const {
      getEntityRecords
    } = select(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_4__.store);
    const query = {
      per_page: -1
    };
    const list = getEntityRecords('postType', 'event-speaker', query);
    let speakerArray = [{
      value: 0,
      label: ''
    }];
    if (!list) {
      return speakerArray;
    }
    list.map(speaker => {
      speakerArray.push({
        value: speaker.id,
        label: speaker.title.raw
      });
    });
    return speakerArray;
  }, []);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__.PluginDocumentSettingPanel, {
    name: "events-location-settings",
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Persons', 'events'),
    className: "events-location-settings"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Select a speaker', 'events'),
    value: meta._speaker_id,
    onChange: value => {
      setMeta({
        _speaker_id: value
      });
    },
    options: speakerList
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Audience', 'events'),
    value: meta._event_audience,
    onChange: value => {
      setMeta({
        _event_audience: value
      });
    }
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (peopleSelector);

/***/ }),

/***/ "./src/blocks/booking/editor.scss":
/*!****************************************!*\
  !*** ./src/blocks/booking/editor.scss ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/details/editor.scss":
/*!****************************************!*\
  !*** ./src/blocks/details/editor.scss ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/featured/editor.scss":
/*!*****************************************!*\
  !*** ./src/blocks/featured/editor.scss ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/checkbox/editor.scss":
/*!**********************************************!*\
  !*** ./src/blocks/form/checkbox/editor.scss ***!
  \**********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/container/editor.scss":
/*!***********************************************!*\
  !*** ./src/blocks/form/container/editor.scss ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/country/editor.scss":
/*!*********************************************!*\
  !*** ./src/blocks/form/country/editor.scss ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/date/editor.scss":
/*!******************************************!*\
  !*** ./src/blocks/form/date/editor.scss ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/email/editor.scss":
/*!*******************************************!*\
  !*** ./src/blocks/form/email/editor.scss ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/html/editor.scss":
/*!******************************************!*\
  !*** ./src/blocks/form/html/editor.scss ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/phone/editor.scss":
/*!*******************************************!*\
  !*** ./src/blocks/form/phone/editor.scss ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/radio/editor.scss":
/*!*******************************************!*\
  !*** ./src/blocks/form/radio/editor.scss ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/select/editor.scss":
/*!********************************************!*\
  !*** ./src/blocks/form/select/editor.scss ***!
  \********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/text/editor.scss":
/*!******************************************!*\
  !*** ./src/blocks/form/text/editor.scss ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/form/textarea/editor.scss":
/*!**********************************************!*\
  !*** ./src/blocks/form/textarea/editor.scss ***!
  \**********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/upcoming/editor.scss":
/*!*****************************************!*\
  !*** ./src/blocks/upcoming/editor.scss ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/plugins/event/booking.scss":
/*!****************************************!*\
  !*** ./src/plugins/event/booking.scss ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/plugins/event/datetime.scss":
/*!*****************************************!*\
  !*** ./src/plugins/event/datetime.scss ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/core-data":
/*!**********************************!*\
  !*** external ["wp","coreData"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["coreData"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/edit-post":
/*!**********************************!*\
  !*** external ["wp","editPost"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["editPost"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["plugins"];

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/extends.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/extends.js ***!
  \************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _extends; }
/* harmony export */ });
function _extends() {
  _extends = Object.assign ? Object.assign.bind() : function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}

/***/ }),

/***/ "./node_modules/colord/index.mjs":
/*!***************************************!*\
  !*** ./node_modules/colord/index.mjs ***!
  \***************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Colord": function() { return /* binding */ j; },
/* harmony export */   "colord": function() { return /* binding */ w; },
/* harmony export */   "extend": function() { return /* binding */ k; },
/* harmony export */   "getFormat": function() { return /* binding */ I; },
/* harmony export */   "random": function() { return /* binding */ E; }
/* harmony export */ });
var r={grad:.9,turn:360,rad:360/(2*Math.PI)},t=function(r){return"string"==typeof r?r.length>0:"number"==typeof r},n=function(r,t,n){return void 0===t&&(t=0),void 0===n&&(n=Math.pow(10,t)),Math.round(n*r)/n+0},e=function(r,t,n){return void 0===t&&(t=0),void 0===n&&(n=1),r>n?n:r>t?r:t},u=function(r){return(r=isFinite(r)?r%360:0)>0?r:r+360},a=function(r){return{r:e(r.r,0,255),g:e(r.g,0,255),b:e(r.b,0,255),a:e(r.a)}},o=function(r){return{r:n(r.r),g:n(r.g),b:n(r.b),a:n(r.a,3)}},i=/^#([0-9a-f]{3,8})$/i,s=function(r){var t=r.toString(16);return t.length<2?"0"+t:t},h=function(r){var t=r.r,n=r.g,e=r.b,u=r.a,a=Math.max(t,n,e),o=a-Math.min(t,n,e),i=o?a===t?(n-e)/o:a===n?2+(e-t)/o:4+(t-n)/o:0;return{h:60*(i<0?i+6:i),s:a?o/a*100:0,v:a/255*100,a:u}},b=function(r){var t=r.h,n=r.s,e=r.v,u=r.a;t=t/360*6,n/=100,e/=100;var a=Math.floor(t),o=e*(1-n),i=e*(1-(t-a)*n),s=e*(1-(1-t+a)*n),h=a%6;return{r:255*[e,i,o,o,s,e][h],g:255*[s,e,e,i,o,o][h],b:255*[o,o,s,e,e,i][h],a:u}},g=function(r){return{h:u(r.h),s:e(r.s,0,100),l:e(r.l,0,100),a:e(r.a)}},d=function(r){return{h:n(r.h),s:n(r.s),l:n(r.l),a:n(r.a,3)}},f=function(r){return b((n=(t=r).s,{h:t.h,s:(n*=((e=t.l)<50?e:100-e)/100)>0?2*n/(e+n)*100:0,v:e+n,a:t.a}));var t,n,e},c=function(r){return{h:(t=h(r)).h,s:(u=(200-(n=t.s))*(e=t.v)/100)>0&&u<200?n*e/100/(u<=100?u:200-u)*100:0,l:u/2,a:t.a};var t,n,e,u},l=/^hsla?\(\s*([+-]?\d*\.?\d+)(deg|rad|grad|turn)?\s*,\s*([+-]?\d*\.?\d+)%\s*,\s*([+-]?\d*\.?\d+)%\s*(?:,\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i,p=/^hsla?\(\s*([+-]?\d*\.?\d+)(deg|rad|grad|turn)?\s+([+-]?\d*\.?\d+)%\s+([+-]?\d*\.?\d+)%\s*(?:\/\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i,v=/^rgba?\(\s*([+-]?\d*\.?\d+)(%)?\s*,\s*([+-]?\d*\.?\d+)(%)?\s*,\s*([+-]?\d*\.?\d+)(%)?\s*(?:,\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i,m=/^rgba?\(\s*([+-]?\d*\.?\d+)(%)?\s+([+-]?\d*\.?\d+)(%)?\s+([+-]?\d*\.?\d+)(%)?\s*(?:\/\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i,y={string:[[function(r){var t=i.exec(r);return t?(r=t[1]).length<=4?{r:parseInt(r[0]+r[0],16),g:parseInt(r[1]+r[1],16),b:parseInt(r[2]+r[2],16),a:4===r.length?n(parseInt(r[3]+r[3],16)/255,2):1}:6===r.length||8===r.length?{r:parseInt(r.substr(0,2),16),g:parseInt(r.substr(2,2),16),b:parseInt(r.substr(4,2),16),a:8===r.length?n(parseInt(r.substr(6,2),16)/255,2):1}:null:null},"hex"],[function(r){var t=v.exec(r)||m.exec(r);return t?t[2]!==t[4]||t[4]!==t[6]?null:a({r:Number(t[1])/(t[2]?100/255:1),g:Number(t[3])/(t[4]?100/255:1),b:Number(t[5])/(t[6]?100/255:1),a:void 0===t[7]?1:Number(t[7])/(t[8]?100:1)}):null},"rgb"],[function(t){var n=l.exec(t)||p.exec(t);if(!n)return null;var e,u,a=g({h:(e=n[1],u=n[2],void 0===u&&(u="deg"),Number(e)*(r[u]||1)),s:Number(n[3]),l:Number(n[4]),a:void 0===n[5]?1:Number(n[5])/(n[6]?100:1)});return f(a)},"hsl"]],object:[[function(r){var n=r.r,e=r.g,u=r.b,o=r.a,i=void 0===o?1:o;return t(n)&&t(e)&&t(u)?a({r:Number(n),g:Number(e),b:Number(u),a:Number(i)}):null},"rgb"],[function(r){var n=r.h,e=r.s,u=r.l,a=r.a,o=void 0===a?1:a;if(!t(n)||!t(e)||!t(u))return null;var i=g({h:Number(n),s:Number(e),l:Number(u),a:Number(o)});return f(i)},"hsl"],[function(r){var n=r.h,a=r.s,o=r.v,i=r.a,s=void 0===i?1:i;if(!t(n)||!t(a)||!t(o))return null;var h=function(r){return{h:u(r.h),s:e(r.s,0,100),v:e(r.v,0,100),a:e(r.a)}}({h:Number(n),s:Number(a),v:Number(o),a:Number(s)});return b(h)},"hsv"]]},N=function(r,t){for(var n=0;n<t.length;n++){var e=t[n][0](r);if(e)return[e,t[n][1]]}return[null,void 0]},x=function(r){return"string"==typeof r?N(r.trim(),y.string):"object"==typeof r&&null!==r?N(r,y.object):[null,void 0]},I=function(r){return x(r)[1]},M=function(r,t){var n=c(r);return{h:n.h,s:e(n.s+100*t,0,100),l:n.l,a:n.a}},H=function(r){return(299*r.r+587*r.g+114*r.b)/1e3/255},$=function(r,t){var n=c(r);return{h:n.h,s:n.s,l:e(n.l+100*t,0,100),a:n.a}},j=function(){function r(r){this.parsed=x(r)[0],this.rgba=this.parsed||{r:0,g:0,b:0,a:1}}return r.prototype.isValid=function(){return null!==this.parsed},r.prototype.brightness=function(){return n(H(this.rgba),2)},r.prototype.isDark=function(){return H(this.rgba)<.5},r.prototype.isLight=function(){return H(this.rgba)>=.5},r.prototype.toHex=function(){return r=o(this.rgba),t=r.r,e=r.g,u=r.b,i=(a=r.a)<1?s(n(255*a)):"","#"+s(t)+s(e)+s(u)+i;var r,t,e,u,a,i},r.prototype.toRgb=function(){return o(this.rgba)},r.prototype.toRgbString=function(){return r=o(this.rgba),t=r.r,n=r.g,e=r.b,(u=r.a)<1?"rgba("+t+", "+n+", "+e+", "+u+")":"rgb("+t+", "+n+", "+e+")";var r,t,n,e,u},r.prototype.toHsl=function(){return d(c(this.rgba))},r.prototype.toHslString=function(){return r=d(c(this.rgba)),t=r.h,n=r.s,e=r.l,(u=r.a)<1?"hsla("+t+", "+n+"%, "+e+"%, "+u+")":"hsl("+t+", "+n+"%, "+e+"%)";var r,t,n,e,u},r.prototype.toHsv=function(){return r=h(this.rgba),{h:n(r.h),s:n(r.s),v:n(r.v),a:n(r.a,3)};var r},r.prototype.invert=function(){return w({r:255-(r=this.rgba).r,g:255-r.g,b:255-r.b,a:r.a});var r},r.prototype.saturate=function(r){return void 0===r&&(r=.1),w(M(this.rgba,r))},r.prototype.desaturate=function(r){return void 0===r&&(r=.1),w(M(this.rgba,-r))},r.prototype.grayscale=function(){return w(M(this.rgba,-1))},r.prototype.lighten=function(r){return void 0===r&&(r=.1),w($(this.rgba,r))},r.prototype.darken=function(r){return void 0===r&&(r=.1),w($(this.rgba,-r))},r.prototype.rotate=function(r){return void 0===r&&(r=15),this.hue(this.hue()+r)},r.prototype.alpha=function(r){return"number"==typeof r?w({r:(t=this.rgba).r,g:t.g,b:t.b,a:r}):n(this.rgba.a,3);var t},r.prototype.hue=function(r){var t=c(this.rgba);return"number"==typeof r?w({h:r,s:t.s,l:t.l,a:t.a}):n(t.h)},r.prototype.isEqual=function(r){return this.toHex()===w(r).toHex()},r}(),w=function(r){return r instanceof j?r:new j(r)},S=[],k=function(r){r.forEach(function(r){S.indexOf(r)<0&&(r(j,y),S.push(r))})},E=function(){return new j({r:255*Math.random(),g:255*Math.random(),b:255*Math.random()})};


/***/ }),

/***/ "./src/blocks/booking/block.json":
/*!***************************************!*\
  !*** ./src/blocks/booking/block.json ***!
  \***************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/booking","apiVersion":2,"title":"Event Booking","description":"Add a button that allows the user to book tickets for an event.","category":"widgets","attributes":{"buttonTitle":{"type":"string","default":""},"buttonColor":{"type":"string","default":"primary"},"bookNow":{"type":"string","default":""},"buttonIcon":{"type":"string","default":""}},"usesContext":["postType"]}');

/***/ }),

/***/ "./src/blocks/details/block.json":
/*!***************************************!*\
  !*** ./src/blocks/details/block.json ***!
  \***************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/details","apiVersion":2,"title":"Event Details","description":"Show a list with selected details of the event","category":"widgets","attributes":{"showLocation":{"type":"boolean","default":""},"showAudience":{"type":"boolean","default":true},"showSpeaker":{"type":"boolean","default":true},"showDate":{"type":"boolean","default":true},"showTime":{"type":"boolean","default":true},"showPrice":{"type":"boolean","default":false},"showBookingEnd":{"type":"boolean","default":false},"audienceDescription":{"type":"string","default":""},"audienceIcon":{"type":"string","default":"groups"},"locationLink":{"type":"boolean","default":true},"speakerDescription":{"type":"string","default":""},"speakerIcon":{"type":"string","default":""},"speakerLink":{"type":"boolean","default":true},"priceOverwrite":{"type":"string","default":""},"bookedUpWarningThreshold":{"type":"number","default":5},"showBookedUp":{"type":"boolean","default":true}},"usesContext":["postType"]}');

/***/ }),

/***/ "./src/blocks/featured/block.json":
/*!****************************************!*\
  !*** ./src/blocks/featured/block.json ***!
  \****************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/featured","apiVersion":2,"title":"Featured","category":"widgets","attributes":{"dropShadow":{"type":"boolean","default":false},"eventId":{"type":"number","default":100},"showCategory":{"type":"boolean","default":true},"showLocation":{"type":"string","default":""},"selectedCategory":{"type":"string","default":""},"selectedTags":{"type":"array","default":[]},"excerptLength":{"type":"number","default":20},"textAlignment":{"type":"string","default":"left"},"showAudience":{"type":"boolean","default":false},"showSpeaker":{"type":"boolean","default":false},"showImage":{"type":"boolean","default":true},"isRootElement":{"type":"boolean","default":false}}}');

/***/ }),

/***/ "./src/blocks/form/checkbox/block.json":
/*!*********************************************!*\
  !*** ./src/blocks/form/checkbox/block.json ***!
  \*********************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-checkbox","apiVersion":2,"title":"Checkbox","description":"Lets the user check something","category":"widgets","attributes":{"fieldid":{"type":"string","default":""},"width":{"type":"integer","default":4},"required":{"type":"boolean","default":false},"label":{"type":"string","default":""},"placeholder":{"type":"boolean","default":false},"help":{"type":"string","default":""},"error":{"type":"string","default":""},"style":{"type":"string","default":"checkbox"}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/container/block.json":
/*!**********************************************!*\
  !*** ./src/blocks/form/container/block.json ***!
  \**********************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-container","apiVersion":2,"title":"Form Container","description":"Show a list with selected details of the event","category":"layout","attributes":{},"usesContext":["postType"]}');

/***/ }),

/***/ "./src/blocks/form/country/block.json":
/*!********************************************!*\
  !*** ./src/blocks/form/country/block.json ***!
  \********************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-country","apiVersion":2,"title":"Country","description":"Lets the user select a country","category":"widgets","attributes":{"fieldid":{"type":"string","default":""},"width":{"type":"integer","default":4},"required":{"type":"boolean","default":false},"label":{"type":"string","default":""},"defaultValue":{"type":"string","default":""},"help":{"type":"string","default":""},"error":{"type":"string","default":""}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/date/block.json":
/*!*****************************************!*\
  !*** ./src/blocks/form/date/block.json ***!
  \*****************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-date","apiVersion":2,"title":"Date Field","description":"Lets the user specify a date","category":"widgets","attributes":{"fieldid":{"type":"string","default":""},"width":{"type":"string","default":true},"required":{"type":"boolean","default":false},"min":{"type":"datetime","default":""},"max":{"type":"datetime","default":""},"label":{"type":"string","default":""},"placeholder":{"type":"string","default":""},"help":{"type":"string","default":""},"error":{"type":"string","default":""}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/email/block.json":
/*!******************************************!*\
  !*** ./src/blocks/form/email/block.json ***!
  \******************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-email","apiVersion":2,"title":"Email Field","description":"Lets the user type an email address.","category":"widgets","attributes":{"fieldid":{"type":"string","default":""},"width":{"type":"integer","default":4},"required":{"type":"boolean","default":true},"label":{"type":"string","default":""},"placeholder":{"type":"string","default":""},"help":{"type":"string","default":""},"error":{"type":"string","default":""}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/html/block.json":
/*!*****************************************!*\
  !*** ./src/blocks/form/html/block.json ***!
  \*****************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-html","apiVersion":2,"title":"Text Block","description":"Headline or text block","category":"widgets","attributes":{"width":{"type":"integer","default":4}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/phone/block.json":
/*!******************************************!*\
  !*** ./src/blocks/form/phone/block.json ***!
  \******************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-phone","apiVersion":2,"title":"Phone Field","description":"Lets the user type a phone number.","category":"widgets","attributes":{"fieldid":{"type":"string","default":"email"},"width":{"type":"integer","default":4},"required":{"type":"boolean","default":true},"label":{"type":"string","default":"Phone"},"placeholder":{"type":"string","default":""},"help":{"type":"string","default":""},"error":{"type":"string","default":""}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/radio/block.json":
/*!******************************************!*\
  !*** ./src/blocks/form/radio/block.json ***!
  \******************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-radio","apiVersion":2,"title":"Radio Select","description":"Lets the user check something","category":"widgets","attributes":{"fieldid":{"type":"string","default":""},"width":{"type":"integer","default":4},"required":{"type":"boolean","default":false},"label":{"type":"string","default":""},"placeholder":{"type":"integer","default":0},"help":{"type":"string","default":""},"error":{"type":"string","default":""},"options":{"type":"array","default":[]}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/select/block.json":
/*!*******************************************!*\
  !*** ./src/blocks/form/select/block.json ***!
  \*******************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-select","apiVersion":2,"title":"Select","description":"Lets the user select something","category":"widgets","attributes":{"fieldid":{"type":"string","default":""},"width":{"type":"string","default":true},"required":{"type":"boolean","default":false},"options":{"type":"array","default":[]},"hasEmptyOption":{"type":"boolean","default":false},"label":{"type":"string","default":""},"placeholder":{"type":"boolean","default":false},"help":{"type":"string","default":""},"error":{"type":"string","default":""}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/text/block.json":
/*!*****************************************!*\
  !*** ./src/blocks/form/text/block.json ***!
  \*****************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-text","apiVersion":2,"title":"Text Field","description":"Lets the user type a short text","category":"widgets","attributes":{"fieldid":{"type":"string","default":""},"width":{"type":"integer","default":4},"required":{"type":"boolean","default":false},"pattern":{"type":"string","default":""},"label":{"type":"string","default":""},"placeholder":{"type":"string","default":""},"help":{"type":"string","default":""},"error":{"type":"string","default":""}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/form/textarea/block.json":
/*!*********************************************!*\
  !*** ./src/blocks/form/textarea/block.json ***!
  \*********************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/form-textarea","apiVersion":2,"title":"Text Area","description":"Lets the user type a longer text","category":"widgets","attributes":{"fieldid":{"type":"string","default":""},"width":{"type":"integer","default":4},"required":{"type":"boolean","default":false},"pattern":{"type":"string","default":""},"label":{"type":"string","default":""},"placeholder":{"type":"string","default":""},"help":{"type":"string","default":""},"rows":{"type":"number","default":3},"error":{"type":"string","default":""}},"parent":["events-manager/form-container"]}');

/***/ }),

/***/ "./src/blocks/upcoming/block.json":
/*!****************************************!*\
  !*** ./src/blocks/upcoming/block.json ***!
  \****************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/upcoming","title":"Upcoming Events","description":"Shows a list or cards of upcoming events","text-domain":"events-manager","apiVersion":2,"category":"widgets","attributes":{"columnsSmall":{"type":"number","default":1},"columnsMedium":{"type":"number","default":2},"columnsLarge":{"type":"number","default":3},"showImages":{"type":"boolean","default":true},"roundImages":{"type":"boolean","default":false},"imageSize":{"type":"number","default":100},"showCategory":{"type":"boolean","default":true},"showLocation":{"type":"string","default":""},"style":{"type":"string","default":"cards"},"limit":{"type":"number","default":12},"order":{"type":"string","default":"ASC"},"selectedCategory":{"type":"array","default":[]},"selectedLocation":{"type":"number","default":0},"selectedTags":{"type":"array","default":[]},"excerptLength":{"type":"number","default":20},"textAlignment":{"type":"string","default":"left"},"showAudience":{"type":"boolean","default":false},"showSpeaker":{"type":"string","default":""},"showCategoryFilter":{"type":"boolean","default":false},"showTagFilter":{"type":"boolean","default":false},"showSearch":{"type":"boolean","default":false},"filterStyle":{"type":"string","default":"pills"},"filterPosition":{"type":"string","default":"top"},"scope":{"type":"string","default":"future"},"bookedUpWarningThreshold":{"type":"number","default":0},"showBookedUp":{"type":"boolean","default":true},"excludeCurrent":{"type":"boolean","default":true}}}');

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
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
/*!************************!*\
  !*** ./src/backend.js ***!
  \************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "registerBlocks": function() { return /* binding */ registerBlocks; }
/* harmony export */ });
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _blocks_booking_index_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./blocks/booking/index.js */ "./src/blocks/booking/index.js");
/* harmony import */ var _blocks_details__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./blocks/details */ "./src/blocks/details/index.js");
/* harmony import */ var _blocks_featured__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./blocks/featured */ "./src/blocks/featured/index.js");
/* harmony import */ var _blocks_form_checkbox_index_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./blocks/form/checkbox/index.js */ "./src/blocks/form/checkbox/index.js");
/* harmony import */ var _blocks_form_container_index_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./blocks/form/container/index.js */ "./src/blocks/form/container/index.js");
/* harmony import */ var _blocks_form_country_index_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./blocks/form/country/index.js */ "./src/blocks/form/country/index.js");
/* harmony import */ var _blocks_form_date_index_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./blocks/form/date/index.js */ "./src/blocks/form/date/index.js");
/* harmony import */ var _blocks_form_email_index_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./blocks/form/email/index.js */ "./src/blocks/form/email/index.js");
/* harmony import */ var _blocks_form_html_index_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./blocks/form/html/index.js */ "./src/blocks/form/html/index.js");
/* harmony import */ var _blocks_form_phone_index_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./blocks/form/phone/index.js */ "./src/blocks/form/phone/index.js");
/* harmony import */ var _blocks_form_radio_index_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./blocks/form/radio/index.js */ "./src/blocks/form/radio/index.js");
/* harmony import */ var _blocks_form_select_index_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./blocks/form/select/index.js */ "./src/blocks/form/select/index.js");
/* harmony import */ var _blocks_form_text_index_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./blocks/form/text/index.js */ "./src/blocks/form/text/index.js");
/* harmony import */ var _blocks_form_textarea_index_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./blocks/form/textarea/index.js */ "./src/blocks/form/textarea/index.js");
/* harmony import */ var _blocks_upcoming__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./blocks/upcoming */ "./src/blocks/upcoming/index.js");
/* harmony import */ var _plugins_event_booking__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./plugins/event/booking */ "./src/plugins/event/booking.js");
/* harmony import */ var _plugins_event_datetime__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./plugins/event/datetime */ "./src/plugins/event/datetime.js");
/* harmony import */ var _plugins_event_location__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./plugins/event/location */ "./src/plugins/event/location.js");
/* harmony import */ var _plugins_event_people__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./plugins/event/people */ "./src/plugins/event/people.js");
/**
 * WordPress dependencies
 */



/**
 * Blocks dependencies.
 */



















const registerBlock = block => {
  if (!block) return;
  const {
    name,
    settings
  } = block;
  (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(name, settings);
};
let blocks = [_blocks_upcoming__WEBPACK_IMPORTED_MODULE_16__, _blocks_featured__WEBPACK_IMPORTED_MODULE_4__, _blocks_form_container_index_js__WEBPACK_IMPORTED_MODULE_6__, _blocks_form_text_index_js__WEBPACK_IMPORTED_MODULE_14__, _blocks_form_email_index_js__WEBPACK_IMPORTED_MODULE_9__, _blocks_form_textarea_index_js__WEBPACK_IMPORTED_MODULE_15__, _blocks_form_date_index_js__WEBPACK_IMPORTED_MODULE_8__, _blocks_form_checkbox_index_js__WEBPACK_IMPORTED_MODULE_5__, _blocks_form_select_index_js__WEBPACK_IMPORTED_MODULE_13__, _blocks_form_country_index_js__WEBPACK_IMPORTED_MODULE_7__, _blocks_form_phone_index_js__WEBPACK_IMPORTED_MODULE_11__, _blocks_form_radio_index_js__WEBPACK_IMPORTED_MODULE_12__, _blocks_form_html_index_js__WEBPACK_IMPORTED_MODULE_10__];
let plugins = [];
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__.registerPlugin)('plugin-location-datetime', {
  icon: null,
  render: _plugins_event_location__WEBPACK_IMPORTED_MODULE_19__["default"]
});
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__.registerPlugin)('plugin-select-datetime', {
  icon: null,
  render: _plugins_event_datetime__WEBPACK_IMPORTED_MODULE_18__["default"]
});
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__.registerPlugin)('plugin-select-people', {
  icon: null,
  render: _plugins_event_people__WEBPACK_IMPORTED_MODULE_20__["default"]
});
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__.registerPlugin)('plugin-booking-options', {
  icon: null,
  render: _plugins_event_booking__WEBPACK_IMPORTED_MODULE_17__["default"]
});
if (window.eventBlocksLocalization?.post_type === 'event') {
  blocks = [...blocks, _blocks_details__WEBPACK_IMPORTED_MODULE_3__, _blocks_booking_index_js__WEBPACK_IMPORTED_MODULE_2__];
}
const registerBlocks = () => {
  blocks.forEach(registerBlock);
};
registerBlocks();
}();
/******/ })()
;
//# sourceMappingURL=backend.js.map