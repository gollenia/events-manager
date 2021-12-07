jQuery(document).ready( function($){
	$('.em-coupon-code').change(function(){
		var coupon_el = $(this); 
		var formdata = coupon_el.parents('.em-booking-form').serialize().replace('action=booking_add','action=em_coupon_check'); //simple way to change action of form
		$.ajax({
			url: EM.ajaxurl,
			data: formdata,
			dataType: 'jsonp',
			type:'post',
			beforeSend: function(formData, jqForm, options) {
				$('.em-coupon-message').remove();
				if(coupon_el.val() == ''){ return false; }
				coupon_el.after('<div id="em-coupon-loading"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Suche Gutschein</div>');
			},
			success : function(response, statusText, xhr, $form) {
				if(response.result){
					coupon_el.after('<div class="em-coupon-message em-coupon-success text-green text-right font-bold">'+response.message+'</div>');
				}else{
					coupon_el.after('<div class="em-coupon-message em-coupon-error text-red text-right font-bold">'+response.message+'</div>');
				}
			},
			complete : function() {
				$('#em-coupon-loading').remove();
			}
		});
	});
});