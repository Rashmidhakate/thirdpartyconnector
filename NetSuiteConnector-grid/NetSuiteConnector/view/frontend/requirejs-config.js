/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*':{
            zokuOfflineBalance:'Zoku_NetSuiteConnector/js/zoku-offline-balance'
        }
    },
    shim:{
        'zokuOfflineBalance':{
            deps: ['jquery']
        }
    }
};

