/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./add-ons/bookings-form/form-editor.js ***!
  \**********************************************/
jQuery(document).ready(function ($) {
  $('.bct-options').hide(); //Booking Form

  $('form.em-form-custom').each(function (i, myform) {
    myform = $(myform);
    let booking_template = myform.find('#booking-custom-item-template').detach();
    myform.on('click', '.booking-form-custom-field-remove', function (e) {
      e.preventDefault();
      $(this).parents('.booking-custom-item').remove();
      reserve_selected_userfields();
    });
    myform.find('.booking-form-custom-field-add').click(function (e) {
      e.preventDefault();
      booking_template.clone().appendTo($(this).parents('.em-form-custom').find('ul.booking-custom-body').first());
    });
    myform.on('click', '.booking-form-custom-field-options', function (e) {
      $(this).blur();
      e.preventDefault();

      if ($(this).attr('rel') != '1') {
        $(this).parents('.em-form-custom').find('.booking-form-custom-field-options').attr('rel', '0');
        $(this).parents('.booking-custom-item').find('.booking-form-custom-type').trigger('change');
      } else {
        $(this).parents('.booking-custom-item').find('.bct-options, .bct-options-toggle').slideUp();
        $(this).attr('rel', '0');
      }
    });
    myform.on('click', '.bct-options-toggle', function (e) {
      e.preventDefault();
      $(this).blur().parents('.booking-custom-item').find('.booking-form-custom-field-options').trigger('click');
    }); //specifics

    myform.on('change', '.booking-form-custom-label', function (e) {
      let parent_div = $(this).parents('.booking-custom-item').first();
      let field_id = parent_div.find('input.booking-form-custom-fieldid').first();

      if (field_id.val() == '') {
        field_id.val(escape($(this).val()).replace(/%[0-9]+/g, '_').toLowerCase());
      }
    });
    myform.on('change', 'input[type="checkbox"]', function () {
      let checkbox = $(this);

      if (checkbox.next().attr('type') == 'hidden') {
        if (checkbox.is(':checked')) {
          checkbox.next().val(1);
        } else {
          checkbox.next().val(0);
        }
      }
    });

    let reserve_selected_userfields = function () {
      myform.find('.booking-form-custom-type optgroup.bc-custom-user-fields option:disabled, .booking-form-custom-type optgroup.bc-core-user-fields option:disabled').prop('disabled', false);
      myform.find('.booking-form-custom-type optgroup.bc-custom-user-fields option:selected, .booking-form-custom-type optgroup.bc-core-user-fields option:selected').each(function (i, item) {
        item = $(item);
        let item_val = item.val();
        let filter = '.booking-form-custom-type optgroup.bc-custom-user-fields option[value="' + item_val + '"], .booking-form-custom-type optgroup.bc-core-user-fields option[value="' + item_val + '"]';
        let found_items = myform.find(filter).add(booking_template.find(filter));
        found_items.each(function (i_2, taken_item) {
          taken_item = $(taken_item);

          if (!taken_item.is(item)) {
            taken_item.prop('disabled', true);
          }
        });
      });
    };

    reserve_selected_userfields();
    myform.on('change', '.booking-form-custom-type', function () {
      $('.bct-options').slideUp();
      $('.bct-options-toggle').hide();
      let reg_keys = []; //get reg keys from booking template for use in type_keys

      booking_template.find('.bc-custom-user-fields option, .bc-core-user-fields option').each(function (i, field) {
        reg_keys.push(field.value);
      });
      let type_keys = {
        select: ['select', 'multiselect'],
        country: ['country'],
        date: ['date'],
        time: ['time'],
        html: ['html'],
        selection: ['checkboxes', 'radio'],
        checkbox: ['checkbox'],
        text: ['text', 'textarea', 'email'],
        registration: reg_keys,
        captcha: ['captcha'],
        email: ['email']
      };
      let select_box = $(this);
      let selected_value = select_box.val();
      $.each(type_keys, function (option, types) {
        if ($.inArray(selected_value, types) > -1) {
          //get parent div
          parent_div = select_box.parents('.booking-custom-item').first(); //slide the right divs in/out

          parent_div.find('.bct-' + option).slideDown();
          parent_div.find('.bct-options-toggle').show();
          parent_div.find('.booking-form-custom-field-options').attr('rel', '1');
        }
      });
      reserve_selected_userfields();
    });
    myform.on('click', '.bc-link-up, .bc-link-down', function (e) {
      e.preventDefault();
      item = $(this).parents('.booking-custom-item').first();

      if ($(this).hasClass('bc-link-up')) {
        if (item.prev().length > 0) {
          item.prev().before(item);
        }
      } else {
        if (item.next().length > 0) {
          item.next().after(item);
        }
      }
    });
    myform.on('mousedown', '.bc-col-sort', function () {
      parent_div = $(this).parents('.booking-custom-item').first();
      parent_div.find('.bct-options').hide();
      parent_div.find('.booking-form-custom-field-options').attr('rel', '0');
    });
    myform.find('.booking-custom-body').sortable({
      placeholder: "bc-highlight",
      handle: '.bc-col-sort'
    }); //ML Stuff

    myform.find('.bc-translatable').click(function () {
      $(this).closest('li.booking-custom-item').find('.' + $(this).attr('rel')).slideToggle();
    });
  });
});
/******/ })()
;
//# sourceMappingURL=form-editor.js.map