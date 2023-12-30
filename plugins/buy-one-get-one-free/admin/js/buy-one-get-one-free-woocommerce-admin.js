(function ($) {
	'use strict';

	function pisol_select_product() {
		this.init = function () {
			this.applySelectProduct("#pisol_free_product");
		}

		this.applySelectProduct = function (id) {

			jQuery(id).selectWoo({
				placeholder: "Select a product",
				allowClear: true,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: "GET",
					delay: 1000,
					data: function (params) {
						return {
							keyword: params.term,
							action: "pisol_bogo_search_product",
						};
					},
					processResults: function (data) {
						return {
							results: data
						};

					},
				}
			});

		}
	}

	jQuery(function ($) {
		var pisol_select_product_obj = new pisol_select_product();
		pisol_select_product_obj.init();
	});

})(jQuery);
