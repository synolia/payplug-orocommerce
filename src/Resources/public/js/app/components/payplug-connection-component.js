define(function(require) {
    'use strict';

    var $ = require('jquery');

    var PayplugConnectionComponent;
    var BaseComponent = require('oroui/js/app/components/base/component');
    var LoadingMaskView = require('oroui/js/app/views/loading-mask-view');
    var mediator = require('oroui/js/mediator');
    var messenger = require('oroui/js/messenger');
    var systemAccessModeOrganizationProvider = require('oroorganization/js/app/tools/system-access-mode-organization-provider');

    PayplugConnectionComponent = BaseComponent.extend({
        /**
         * @property {jquery} $button
         */
        $button: null,

        /**
         * @property {jquery} $form
         */
        $form: null,

        /**
         * @property {string} backendUrl
         */
        backendUrl: '',

        /**
         * @property {LoadingMaskView} loadingMaskView
         */
        loadingMaskView: null,

        /**
         * @inheritDoc
         */
        constructor: function PayplugConnectionComponent() {
            PayplugConnectionComponent.__super__.constructor.apply(this, arguments);
        },

        /**
         * @inheritDoc
         */
        initialize: function(options) {
            this.$button = options._sourceElement;
            this.$form = $(options.formSelector);
            this.backendUrl = options.backendUrl;
            this.loadingMaskView = new LoadingMaskView({container: $('body')});

            this.initListeners();
        },

        initListeners: function() {
            this.$button.on('click', this.buttonClickHandler.bind(this));
        },

        buttonClickHandler: function() {
            this.$form.validate();

            if (this.$form.valid()) {
                this.connect();
            }
        },

        connect: function() {
            var self = this;
            var data = this.$form.serialize();

            var organizationId = systemAccessModeOrganizationProvider.getOrganizationId();

            if (organizationId) {
                data += '&_sa_org_id=' + organizationId;
            }

            $.ajax({
                url: this.backendUrl,
                type: 'POST',
                data: data,
                beforeSend: function() {
                    self.loadingMaskView.show();
                },
                success: this.successHandler.bind(this),
                complete: function() {
                    self.loadingMaskView.hide();
                }
            });
        },

        /**
         * @param {{success: bool, message: string}} response
         */
        successHandler: function(response) {
            if (response.disconnect) {
                mediator.execute('refreshPage');
                return;
            }

            if (response.api_key_live) {
                this.$form
                    .find('input[name="oro_integration_channel_form[transport][apiKeyLive]"]')
                    .val(response.api_key_live)
            }

            if (response.api_key_test) {
                this.$form
                    .find('input[name="oro_integration_channel_form[transport][apiKeyTest]"]')
                    .val(response.api_key_test)
            }

            if (response.success) {
                var actionInput = this.$form.find('input[name="input_action"]');
                actionInput.val('save_and_stay');
                this.$form.trigger('submit');
            }

            if (response.message) {
                messenger.notificationFlashMessage('error', response.message);
            }
        },

        dispose: function() {
            if (this.disposed) {
                return;
            }

            this.$button.off('click');

            PayplugConnectionComponent.__super__.dispose.call(this);
        }
    });

    return PayplugConnectionComponent;
});
