/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./blocks/src/upcoming/frontend/cards.js":
/*!***********************************************!*\
  !*** ./blocks/src/upcoming/frontend/cards.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _truncate__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./truncate */ "./blocks/src/upcoming/frontend/truncate.js");
/* harmony import */ var _formatDate__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./formatDate */ "./blocks/src/upcoming/frontend/formatDate.js");


/**
* External Dependencies
*/


/**
* Internal Dependencies
*/




function card(props) {
  var _item$image, _item$image$sizes, _item$image$sizes$lar;

  const {
    showImages,
    showCategory,
    showLocation,
    showBookedUp,
    bookedUpWarningThreshold,
    excerptLength,
    textAlignment,
    showAudience,
    showSpeaker,
    item
  } = props;

  const bookingWarning = () => {
    var _item$bookings, _item$bookings2;

    if (!showBookedUp || !item.bookings.has_bookings) return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
    if (((_item$bookings = item.bookings) === null || _item$bookings === void 0 ? void 0 : _item$bookings.spaces) > bookedUpWarningThreshold) return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);

    if (((_item$bookings2 = item.bookings) === null || _item$bookings2 === void 0 ? void 0 : _item$bookings2.spaces) > 0) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "pills__item pills__item--warning"
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Nearly Booked up", "events"));
    }

    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "pills__item pills__item--error"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Booked up", "events"));
  };

  const cardFooter = () => {
    var _item$audience, _item$speaker, _item$location;

    if (!showAudience && !showSpeaker && !showLocation && !showBookedUp) return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "card__footer card__subtitle pills pills--small"
    }, showAudience && ((_item$audience = item.audience) === null || _item$audience === void 0 ? void 0 : _item$audience.length) > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "pills__item event__audience"
    }, item.audience), showSpeaker == 'name' && ((_item$speaker = item.speaker) === null || _item$speaker === void 0 ? void 0 : _item$speaker.id) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "pills__item event__speaker"
    }, item.speaker.name), showLocation && ((_item$location = item.location) === null || _item$location === void 0 ? void 0 : _item$location.ID) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "pills__item event__location"
    }, location), showBookedUp && item.bookings && bookingWarning());
  };

  const location = item.location && ['city', 'name'].includes(showLocation) ? item.location[showLocation] : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "card card--image-top card--primary card--shadow card--hover bg-white card--" + textAlignment
  }, showImages && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: item.link,
    className: "card__image"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: (_item$image = item.image) === null || _item$image === void 0 ? void 0 : (_item$image$sizes = _item$image.sizes) === null || _item$image$sizes === void 0 ? void 0 : (_item$image$sizes$lar = _item$image$sizes.large) === null || _item$image$sizes$lar === void 0 ? void 0 : _item$image$sizes$lar.url
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "card__content"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: item.link,
    className: "card__hidden-link"
  }), item.category && showCategory && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    class: "card__label"
  }, item.category.name), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "card__title"
  }, item.title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h4", {
    class: "card__subtitle text--primary"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_4__.formatDateRange)(item.start, item.end)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "card__text"
  }, (0,_truncate__WEBPACK_IMPORTED_MODULE_3__["default"])(item.excerpt, excerptLength)), cardFooter()));
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (card);

/***/ }),

/***/ "./blocks/src/upcoming/frontend/descriptionItem.js":
/*!*********************************************************!*\
  !*** ./blocks/src/upcoming/frontend/descriptionItem.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ descriptionItem)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _formatDate__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./formatDate */ "./blocks/src/upcoming/frontend/formatDate.js");


/**
* External Dependencies
*/

/**
* Internal Dependencies
*/


function descriptionItem(props) {
  var _item$image, _item$image$sizes, _item$image$sizes$lar;

  const {
    showImages,
    showCategory,
    showLocation,
    excerptLength,
    textAlignment,
    showAudience,
    showSpeaker,
    item
  } = props;
  const location = item.location && ['city', 'name'].includes(showLocation) ? item.location[showLocation] : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: item.link,
    className: "description__item " + textAlignment
  }, showImages && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    className: "description__image",
    src: (_item$image = item.image) === null || _item$image === void 0 ? void 0 : (_item$image$sizes = _item$image.sizes) === null || _item$image$sizes === void 0 ? void 0 : (_item$image$sizes$lar = _item$image$sizes.large) === null || _item$image$sizes$lar === void 0 ? void 0 : _item$image$sizes$lar.url
  }), !showImages && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "description__date"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "date__day--numeric"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_2__.formatDate)(item.start, {
    day: "numeric"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "date__day--short"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_2__.formatDate)(item.start, {
    weekday: "short"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "date__day--long"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_2__.formatDate)(item.start, {
    weekday: "long"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "date__month--long"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_2__.formatDate)(item.start, {
    month: "long"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "date__month--numeric"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_2__.formatDate)(item.start, {
    month: "numeric"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "date__month--short"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_2__.formatDate)(item.start, {
    month: "short"
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "description__text"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "description__title"
  }, item.title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    class: "description__data"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_2__.formatDateRange)(item.start, item.end), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    class: "description__subtitle"
  }, showAudience && item.audience && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, item.audience), showSpeaker == 'name' && item.speaker && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, item.speaker.name), showLocation && item.location && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: ""
  }, location)))));
}

/***/ }),

/***/ "./blocks/src/upcoming/frontend/formatDate.js":
/*!****************************************************!*\
  !*** ./blocks/src/upcoming/frontend/formatDate.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "formatDate": () => (/* binding */ formatDate),
/* harmony export */   "formatDateRange": () => (/* binding */ formatDateRange)
/* harmony export */ });
/**
 * Formats two dates to a date range
 * @param {Date} start 
 * @param {Date} end 
 * @returns string formatted date
 */
function formatDateRange(start, end) {
  const locale = navigator.language || navigator.userLanguage;
  start = new Date(start * 1000);
  end = new Date(end * 1000);
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
 * format date by given format object
 * @param {Date} date 
 * @param {object} format 
 * @returns string formated date
 */


function formatDate(date, format) {
  const locale = window.eventBlocksLocalization.locale;
  const dateFormatObject = new Intl.DateTimeFormat(locale, format);
  return dateFormatObject.format(date * 1000);
}



/***/ }),

/***/ "./blocks/src/upcoming/frontend/index.js":
/*!***********************************************!*\
  !*** ./blocks/src/upcoming/frontend/index.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _cards__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./cards */ "./blocks/src/upcoming/frontend/cards.js");
/* harmony import */ var _list__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./list */ "./blocks/src/upcoming/frontend/list.js");
/* harmony import */ var _descriptionItem__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./descriptionItem */ "./blocks/src/upcoming/frontend/descriptionItem.js");








function Upcoming(props) {
  if (!document.event_block_data) return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null);
  const {
    columnsSmall,
    columnsMedium,
    columnsLarge,
    showImages,
    showCategory,
    showLocation,
    showBookedUp,
    bookedUpWarningThreshold,
    style,
    limit,
    order,
    selectedCategory,
    selectedLocation,
    selectedTags,
    scope,
    excerptLength,
    textAlignment,
    showAudience,
    showSpeaker,
    showCategoryFilter,
    showTagFilter,
    showSearch,
    filterPosition,
    excludeCurrent
  } = document.event_block_data[props.block];
  const [events, setEvents] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)([]);
  const [categories, setCategories] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)({});
  const [tags, setTags] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)({});
  const [filter, setFilter] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)({
    category: 0,
    tags: [],
    string: ""
  });

  const changeFilter = (key, value) => {
    setFilter({ ...filter,
      [key]: value
    });
  };

  const toggleTag = tag => {
    let tagFilter = filter.tags;

    if (tagFilter.includes(tag)) {
      tagFilter.splice(tagFilter.indexOf(tag), 1);
      changeFilter('tags', tagFilter);
      return;
    }

    tagFilter.push(tag);
    changeFilter('tags', tagFilter);
  };

  const getUrl = function () {
    var _window$eventBlocksLo;

    let params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    const base = (_window$eventBlocksLo = window.eventBlocksLocalization) === null || _window$eventBlocksLo === void 0 ? void 0 : _window$eventBlocksLo.rest_url;
    if (base === undefined) return;
    if (params === '') return base;
    return base + (base.includes('?') ? '&' : '?') + params;
  };

  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    var _window$eventBlocksLo2;

    const params = [limit > 0 ? `limit=${limit}` : false, 'order=' + order, selectedCategory != 0 ? `category=${selectedCategory.join(',')}` : false, selectedTags.length ? `tag=${selectedTags.join(',')}` : false, scope != '' ? `scope=${scope}` : false, selectedLocation ? `location=${selectedLocation}` : false, excludeCurrent ? `exclude=${(_window$eventBlocksLo2 = window.eventBlocksLocalization) === null || _window$eventBlocksLo2 === void 0 ? void 0 : _window$eventBlocksLo2.current_id}` : false].filter(Boolean).join("&");
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({
      url: getUrl(params)
    }).then(posts => {
      setEvents(posts);
      let categories = {};
      let tags = {};
      posts.map(event => {
        if (!event.category) return;
        if (categories[event.category.id] == undefined) categories[event.category.id] = event.category;

        for (let tag in event.tags) {
          if (tags[tag] == undefined) {
            tags[tag] = event.tags[tag];
          }
        }
      });
      setTags(tags);
      setCategories(categories);
    });
  }, []);

  const getFilteredEvents = () => {
    let filtered = events;
    if (filter.category == 0 && filter.string == "" && filter.tags.length == 0) return filtered;

    if (filter.category !== 0) {
      filtered = filtered.filter(item => {
        var _item$category;

        return ((_item$category = item.category) === null || _item$category === void 0 ? void 0 : _item$category.id) == filter.category;
      });
    }

    if (filter.string !== "") {
      filtered = filtered.filter(item => {
        return item.title.toLowerCase().includes(filter.string);
      });
    }

    if (filter.tags.length > 0) {
      filtered = filtered.filter(item => {
        let result = false;

        for (let key of filter.tags) {
          if (key in item.tags) result = true;
        }

        return result;
      });
    }

    return filtered;
  };

  const containerClass = [style == 'mini' ? 'description' : 'grid', style == 'mini' && !showImages ? 'description--dates' : false, 'grid--gap-12', filterPosition ? 'grid__column--span-3' : false, 'xl:grid--columns-' + columnsLarge, 'md:grid--columns-' + columnsMedium, 'grid--columns-' + columnsSmall].filter(Boolean).join(" ");
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: filterPosition == 'side' ? 'grid xl:grid--columns-4 grid--gap-12' : ''
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("aside", {
    className: "filters"
  }, showSearch && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    class: "filter__search"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    class: "input"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Search', 'events')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    onChange: event => {
      changeFilter('string', event.target.value);
    }
  }))), showCategoryFilter && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "filter"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "filter__title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Select category", "events")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    class: 'filter__pill ' + (filter.category == 0 ? 'filter__pill--active' : ''),
    onClick: () => {
      changeFilter('category', 0);
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('All', 'events')), Object.keys(categories).map((item, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    className: 'filter__pill ' + (filter.category == parseInt(item) ? 'filter__pill--active' : ''),
    onClick: () => {
      changeFilter('category', parseInt(item));
    }
  }, categories[item].name))), showTagFilter && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: 'filter ' + (filterPosition == 'side' ? 'filter--columns' : '')
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "filter__title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Select tags", "events")), Object.keys(tags).map((item, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "filter__box checkbox"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "checkbox",
    name: item,
    onClick: event => {
      toggleTag(item);
    },
    checked: filter.tags.includes(item)
  }), tags[item].name))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: containerClass
  }, getFilteredEvents().map((item, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, style == 'cards' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_cards__WEBPACK_IMPORTED_MODULE_4__["default"], {
    item: item,
    showImages: showImages,
    showCategory: showCategory,
    showLocation: showLocation,
    excerptLength: excerptLength,
    showBookedUp: showBookedUp,
    bookedUpWarningThreshold: bookedUpWarningThreshold,
    textAlignment: textAlignment,
    showAudience: showAudience,
    showSpeaker: showSpeaker
  }), style == 'list' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_list__WEBPACK_IMPORTED_MODULE_5__["default"], {
    item: item,
    showImages: showImages,
    showCategory: showCategory,
    showLocation: showLocation,
    excerptLength: excerptLength,
    textAlignment: textAlignment,
    showAudience: showAudience,
    showSpeaker: showSpeaker
  }), style == 'mini' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_descriptionItem__WEBPACK_IMPORTED_MODULE_6__["default"], {
    item: item,
    showImages: showImages,
    showCategory: showCategory,
    showLocation: showLocation,
    excerptLength: excerptLength,
    textAlignment: textAlignment,
    showAudience: showAudience,
    showSpeaker: showSpeaker
  })))));
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Upcoming);

/***/ }),

/***/ "./blocks/src/upcoming/frontend/list.js":
/*!**********************************************!*\
  !*** ./blocks/src/upcoming/frontend/list.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _truncate__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./truncate */ "./blocks/src/upcoming/frontend/truncate.js");
/* harmony import */ var _formatDate__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./formatDate */ "./blocks/src/upcoming/frontend/formatDate.js");


/** 
* External Dependencies
*/

/** 
* External Dependencies
*/




function list(props) {
  var _item$image, _item$image$sizes, _item$image$sizes$lar, _item$speaker$image, _item$speaker$image$s, _item$speaker$image$s2;

  const {
    showImages,
    showCategory,
    showLocation,
    excerptLength,
    textAlignment,
    showAudience,
    showSpeaker,
    //showBooking // for later usage
    item
  } = props;
  const location = item.location && ['city', 'name'].includes(showLocation) ? item.location[showLocation] : '';
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "card card--image-left has-white-background card--shadow card--primary card--" + textAlignment
  }, showImages && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: item.link,
    className: "card__image"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: (_item$image = item.image) === null || _item$image === void 0 ? void 0 : (_item$image$sizes = _item$image.sizes) === null || _item$image$sizes === void 0 ? void 0 : (_item$image$sizes$lar = _item$image$sizes.large) === null || _item$image$sizes$lar === void 0 ? void 0 : _item$image$sizes$lar.url
  }), showSpeaker == 'image' && item.speaker && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "card__label card__label--image"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: (_item$speaker$image = item.speaker.image) === null || _item$speaker$image === void 0 ? void 0 : (_item$speaker$image$s = _item$speaker$image.sizes) === null || _item$speaker$image$s === void 0 ? void 0 : (_item$speaker$image$s2 = _item$speaker$image$s.thumbnail) === null || _item$speaker$image$s2 === void 0 ? void 0 : _item$speaker$image$s2.url
  }), item.speaker.name)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "card__content"
  }, item.category && showCategory && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    class: "card__label"
  }, item.category.name), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: item.link,
    className: "card__title"
  }, item.title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: item.link,
    class: "card__subtitle text--primary"
  }, (0,_formatDate__WEBPACK_IMPORTED_MODULE_3__.formatDateRange)(item.start, item.end)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: item.link,
    className: "card__text"
  }, (0,_truncate__WEBPACK_IMPORTED_MODULE_2__["default"])(item.excerpt, excerptLength)), (showAudience || showSpeaker || showLocation) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    class: "card__footer card__subtitle card__pills"
  }, showAudience && item.audience && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "card__pill event__audience"
  }, item.audience), showSpeaker == 'name' && item.speaker && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "card__pill event__audience"
  }, item.speaker.name), showLocation && item.location && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "card__pill event__audience"
  }, location))));
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (list);

/***/ }),

/***/ "./blocks/src/upcoming/frontend/truncate.js":
/*!**************************************************!*\
  !*** ./blocks/src/upcoming/frontend/truncate.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ truncate)
/* harmony export */ });
/**
 * Truncate a text string to a number of words and add an ellipsis to it.
 * @param {string} text 
 * @param {int} maxWords 
 * @returns string 
 */
function truncate(text, maxWords) {
  let ellipsis = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "...";
  if (text.length == 0 || maxWords == 0) return '';
  const textArray = text.split(" ");
  if (textArray.length <= maxWords) return text;
  return textArray.splice(0, maxWords).join(" ") + ellipsis;
}

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "react-dom":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
/***/ ((module) => {

module.exports = window["ReactDOM"];

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

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
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!********************************!*\
  !*** ./blocks/src/frontend.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _upcoming_frontend__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./upcoming/frontend */ "./blocks/src/upcoming/frontend/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var react_dom__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react-dom */ "react-dom");
/* harmony import */ var react_dom__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_dom__WEBPACK_IMPORTED_MODULE_3__);




document.addEventListener('DOMContentLoaded', () => {
  const upcomingBlocks = document.getElementsByClassName('events-upcoming-block');
  if (!upcomingBlocks) return;
  Array.from(upcomingBlocks).forEach(element => {
    react_dom__WEBPACK_IMPORTED_MODULE_3___default().render((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_upcoming_frontend__WEBPACK_IMPORTED_MODULE_1__["default"], {
      block: element.dataset.id
    }), element);
  });
});
})();

/******/ })()
;
//# sourceMappingURL=frontend.js.map