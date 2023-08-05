/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer balance view model
 */
define([
    'jquery',
    'domReady!',
    'zokuOfflineBalance',
    'mage/url'
], function ($, dom, zokuOfflineBalance, urlBuilder) {
    'use strict';
    $('.reward-grid-block').show();
    $('.balance-history').addClass('active');
    $('.offline-history').removeClass('active');
    $(".offline-history").on( "click", function() {
        $('.offline-history').addClass('active');
        $('.balance-history').removeClass('active');
        $('body').trigger('processStart');
        var currentcustomer = $('.offline_history_customer_id').val();
        var url = urlBuilder.build("netsuiteconnector/ajax/offlineBalance");
        $.ajax({
            url: url,
            type: "POST",
            data: {currentcustomer:currentcustomer},
            showLoader: true
        }).done(function (response) {
            $('body').trigger('processStop');
            $('.offline-balance-response').show();
            $('.reward-grid-block').hide();
            $('.offline-balance-response').html(response.output);
            return true;
        });
    });

    $(".balance-history").on( "click", function() {
        $('.reward-grid-block').show();
        $('.balance-history').addClass('active');
        $('.offline-history').removeClass('active');
        $('.offline-balance-response').hide();
    });
})

