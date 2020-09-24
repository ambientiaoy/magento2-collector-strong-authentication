define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (
    Component,
    rendererList
) {
    'use strict';

    rendererList.push(
        {
            type: 'collectorcw_collectordirect',
            component: 'Ambientia_CollectorStrongAuthentication/js/view/payment/method-renderer/collectorcw_collectordirect-method'
        },
        {
            type: 'collectorcw_collectorinstallment',
            component: 'Ambientia_CollectorStrongAuthentication/js/view/payment/method-renderer/collectorcw_collectorinstallment-method'
        }
    );
    return Component.extend({});
});
