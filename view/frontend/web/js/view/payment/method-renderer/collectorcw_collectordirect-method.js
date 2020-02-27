define([
	'Customweb_CollectorCw/js/view/payment/method-renderer/collectorcw_collectordirect-method',
	'Magento_Customer/js/model/customer'
], function(
	Component,
	customer
) {
	'use strict';

	return Component.extend({
		defaults: {
			template: 'Ambientia_CollectorStrongAuthentication/payment/collectorcw_collectordirect',
		},

		/**
		 * Retrieve the saved customer SSN if available.
		 *
		 * @return string
		 */
		getSsn: function() {
			if (!customer.isLoggedIn) {
				return null;
			}
			if (!customer.customerData.custom_attributes) {
				return null;
			}
			if (!customer.customerData.custom_attributes.ssn) {
				return null;
			}
			return customer.customerData.custom_attributes.ssn.value;
		}
	});
});
