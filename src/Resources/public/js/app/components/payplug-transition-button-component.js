define(function(require) {
    'use strict';

    var PayplugTransitionButtonComponent;
    var mediator = require('oroui/js/mediator');
    var messenger = require('oroui/js/messenger');
    var __ = require('orotranslation/js/translator');
    var BaseComponent = require('oroui/js/app/components/base/component');
    var _ = require('underscore');

    PayplugTransitionButtonComponent = BaseComponent.extend({
        options: {
            minAmount: 99, // 0.99 EUR
            maxAmount: 2000000, // 20 000 EUR
        },

        /**
         * @inheritDoc
         */
        initialize: function(options) {
            this.options = _.extend({}, this.options, options);

            mediator.on('checkout:payment:before-transit', this.beforeTransit, this);
        },

        /**
         * @inheritDoc
         */
        dispose: function() {
            if (this.disposed || !this.disposable) {
                return;
            }

            this.$el.off();

            mediator.off('checkout:payment:before-transit', this.beforeTransit, this);

            PayplugTransitionButtonComponent.__super__.dispose.call(this);
        },

        /**
         * @inheritDoc
         */
        beforeTransit: function(eventData) {
            if (~eventData.data.paymentMethod.indexOf("payplug")) {
                if (this.options.maxAmount <= (this.options.totalValue * 100)) {
                    eventData.stopped = true;

                    messenger.notificationFlashMessage(
                        'warning',
                        __('synolia.frontend.payment_method.checkout.payplug.maximum_amount.label')
                    );
                }

                if (this.options.minAmount >= (this.options.totalValue * 100)) {
                    eventData.stopped = true;

                    messenger.notificationFlashMessage(
                        'warning',
                        __('synolia.frontend.payment_method.checkout.payplug.minimum_amount.label')
                    );
                }
            }
        },
    });

    return PayplugTransitionButtonComponent;
});
