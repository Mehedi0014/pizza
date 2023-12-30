(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(function ($) {

		function showOfferOnVariation() {
			this.init = function () {
				this.detectVariationChange();
			}

			this.detectVariationChange = function () {
				var parent = this;
				$("input[type='hidden'].variation_id").on('change', function () {
					var variation_id = parseInt($(this).val());
					if (variation_id !== "") {
						parent.getMessageBox(variation_id);
					}
				});
				$("input[type='hidden'].variation_id").trigger('change');
			}

			this.getMessageBox = function (variation_id) {
				var parent = this;
				this.hideAllMethod();
				jQuery("#pisol-variation-" + variation_id).removeClass('pisol-hidden');
			}

			this.hideAllMethod = function () {
				jQuery(".pisol-variation-handler").addClass('pisol-hidden');
			}


		}
		var show_offer_obj = new showOfferOnVariation();
		show_offer_obj.init();
	});

})(jQuery);
