/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./add-ons/blocks/backend.js":
/*!***********************************!*\
  !*** ./add-ons/blocks/backend.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "registerBlocks": function() { return /* binding */ registerBlocks; }
/* harmony export */ });
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _blocks_upcoming__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./blocks/upcoming */ "./add-ons/blocks/blocks/upcoming/index.js");
/**
 * Wordpress dependencies.
 */

/**
 * Blocks dependencies.
 */


/**
 * Stylesheets
 */
//import './common/styles/editor.scss';
//import './common/styles/style.scss';

const registerBlock = block => {
  if (!block) {
    return;
  }

  const {
    name,
    category,
    settings
  } = block;
  (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(name, {
    category: category,
    ...settings
  });
};

const registerBlocks = () => {
  [_blocks_upcoming__WEBPACK_IMPORTED_MODULE_1__].forEach(registerBlock);
};
registerBlocks();

/***/ }),

/***/ "./add-ons/blocks/blocks/upcoming/edit.js":
/*!************************************************!*\
  !*** ./add-ons/blocks/blocks/upcoming/edit.js ***!
  \************************************************/
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
/* harmony import */ var _icons_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./icons.js */ "./add-ons/blocks/blocks/upcoming/icons.js");


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

const EditUpcoming = _ref => {
  let {
    attributes,
    setAttributes
  } = _ref;
  const {
    limit,
    columnsSmall,
    columnsMedium,
    columnsLarge,
    showImages,
    dropShadow,
    style,
    textAlignment,
    showCategory,
    showLocation,
    roundImages,
    excerptLength,
    selectedCategory,
    selectedLocation,
    selectedTags,
    fromDate,
    toDate,
    order
  } = attributes;
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
        value: location.id,
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

  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({
    className: ["columns-" + columnsLarge, showImages ? "hasImage" : false, dropShadow ? "hover" : false, "style-" + style, "text-" + textAlignment, roundImages ? "round-images" : false].filter(Boolean).join(" ")
  });
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
  const inspectorControls = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
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
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("From:", 'events'),
    value: fromDate,
    type: "date",
    onChange: value => {
      setAttributes({
        fromDate: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("To:", 'events'),
    value: toDate,
    type: "date",
    onChange: value => {
      setAttributes({
        toDate: value
      });
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Location', 'events'),
    value: selectedLocation,
    options: locationList,
    onChange: value => {
      setAttributes({
        selectedLocation: value
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
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalNumberControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Limit', 'events'),
    value: limit,
    onChange: value => {
      setAttributes({
        limit: value
      });
    }
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'events'),
    initialOpen: true
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
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Events', 'events'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Hover-effect", 'events'),
    checked: dropShadow,
    onChange: value => setAttributes({
      dropShadow: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
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
    icon: _icons_js__WEBPACK_IMPORTED_MODULE_6__["default"].mini
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Minimal", 'events'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    onClick: () => setAttributes({
      style: "list"
    }),
    className: style == "list" ? "active" : ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    size: "64",
    className: "icon",
    icon: _icons_js__WEBPACK_IMPORTED_MODULE_6__["default"].list
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("List", 'events'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    onClick: () => setAttributes({
      style: "cards"
    }),
    className: style == "cards" ? "active" : ""
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Icon, {
    size: "64",
    className: "icon",
    icon: _icons_js__WEBPACK_IMPORTED_MODULE_6__["default"].cards
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Cards", 'events')))), showImages && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Round images", 'events'),
    checked: roundImages,
    onChange: value => setAttributes({
      roundImages: value
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Show category", 'events'),
    checked: showCategory,
    onChange: value => setAttributes({
      showCategory: value
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
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RangeControl, {
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
  })));
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, inspectorControls, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.BlockControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.AlignmentToolbar, {
    value: textAlignment,
    onChange: event => setAttributes({
      textAlignment: event
    })
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", blockProps, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "components-placeholder is-large"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "components-placeholder__label"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "block-editor-block-icon has-colors"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Dashicon, {
    icon: "calendar-alt"
  })), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Upcoming Events", "events")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "components-placeholder__instructions"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("See for settings in the inspector. The result can be seen in the frontend", "events")))));
};

/* harmony default export */ __webpack_exports__["default"] = (EditUpcoming);

/***/ }),

/***/ "./add-ons/blocks/blocks/upcoming/icons.js":
/*!*************************************************!*\
  !*** ./add-ons/blocks/blocks/upcoming/icons.js ***!
  \*************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);


var icons = [];
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

/***/ "./add-ons/blocks/blocks/upcoming/index.js":
/*!*************************************************!*\
  !*** ./add-ons/blocks/blocks/upcoming/index.js ***!
  \*************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "name": function() { return /* binding */ name; },
/* harmony export */   "category": function() { return /* binding */ category; },
/* harmony export */   "metadata": function() { return /* reexport default export from named module */ _block_json__WEBPACK_IMPORTED_MODULE_2__; },
/* harmony export */   "settings": function() { return /* binding */ settings; }
/* harmony export */ });
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit */ "./add-ons/blocks/blocks/upcoming/edit.js");
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icons */ "./add-ons/blocks/blocks/upcoming/icons.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./add-ons/blocks/blocks/upcoming/block.json");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./add-ons/blocks/blocks/upcoming/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./add-ons/blocks/blocks/upcoming/editor.scss");
/**
 * Internal dependencies
 */



/**
 * Wordpress dependencies
 */

const {
  __
} = wp.i18n;


/**
 * Block constants
 */

const {
  name,
  category,
  attributes
} = _block_json__WEBPACK_IMPORTED_MODULE_2__;
const settings = {
  title: __('Upcoming Events', 'events-manager'),
  description: __('Shows a list or cards of upcoming events', 'events-manager'),
  icon: _icons__WEBPACK_IMPORTED_MODULE_1__["default"].posts,
  apiVersion: 2,
  keywords: ['events-manager', __('events', 'events-manager'), __('list', 'events-manager')],
  attributes,
  edit: _edit__WEBPACK_IMPORTED_MODULE_0__["default"],

  save() {
    return null;
  }

};


/***/ }),

/***/ "./add-ons/blocks/blocks/upcoming/editor.scss":
/*!****************************************************!*\
  !*** ./add-ons/blocks/blocks/upcoming/editor.scss ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./add-ons/blocks/blocks/upcoming/style.scss":
/*!***************************************************!*\
  !*** ./add-ons/blocks/blocks/upcoming/style.scss ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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

/***/ "./add-ons/blocks/blocks/upcoming/block.json":
/*!***************************************************!*\
  !*** ./add-ons/blocks/blocks/upcoming/block.json ***!
  \***************************************************/
/***/ (function(module) {

module.exports = JSON.parse('{"name":"events-manager/upcoming","category":"widgets","attributes":{"columnsSmall":{"type":"number","default":1},"columnsMedium":{"type":"number","default":2},"columnsLarge":{"type":"number","default":3},"showImages":{"type":"boolean","default":true},"roundImages":{"type":"boolean","default":false},"dropShadow":{"type":"boolean","default":false},"imageSize":{"type":"number","default":100},"showCategory":{"type":"boolean","default":true},"showLocation":{"type":"string","default":""},"style":{"type":"string","default":"list"},"limit":{"type":"number","default":6},"order":{"type":"string","default":"desc"},"selectedCategory":{"type":"string","default":"0"},"selectedLocation":{"type":"string","default":"0"},"selectedTags":{"type":"array","default":[]},"fromDate":{"type":"string","default":""},"toDate":{"type":"string","default":""},"excerptLength":{"type":"number","default":20},"textAlignment":{"type":"string","default":"left"}}}');

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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	}();
/******/ 	
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"backend": 0,
/******/ 			"style-backend": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["style-backend"], function() { return __webpack_require__("./add-ons/blocks/backend.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=backend.js.map