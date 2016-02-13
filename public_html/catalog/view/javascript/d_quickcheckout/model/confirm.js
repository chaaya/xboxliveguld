qc.Confirm = qc.Model.extend({
	defaults: {
		'confirm': '',
		'config': '',
		'account': ''
	},


	initialize: function(){
		this.set('config', config.account[this.get('account')].confirm);
	},

	changeAccount: function(account){
		this.set('account', account);
		this.set('config', config.account[this.get('account')].confirm);
	},

	updateField: function(name, value){
		this.set(name, value);
		var json = this.toJSON();
		var that = this;
		$.post('index.php?route=d_quickcheckout/confirm/updateField', { 'confirm' : json.confirm }, function(data) {
			that.updateForm(data);

		}, 'json').error(
		);

	},

	sendOrderVerificationCode: function() {
		var promise = $.post('index.php?route=d_quickcheckout/confirm/sendOrderVerificationCode', {});
		return promise;
	},

	validateVerificationCode: function(code) {
		var _this = this;
		var data = {
			'code': code
		}
		$.post('index.php?route=d_quickcheckout/confirm/validateCode', data)
			.then(function(response) {
				if (response.isValid) {
					_this.update();
				} else {
					window.alert('Your entered a incorrect verification code');
				}
		}, 'json');
	},

	update: function(){
		var that = this;
		$.post('index.php?route=d_quickcheckout/confirm/update', {}, function(data) {
			that.updateForm(data);
			qc.event.trigger('paymentConfirm');
			that.recreateOrder();
		}, 'json').error(
		);
	},

	recreateOrder: function(){
		$.post('index.php?route=d_quickcheckout/confirm/recreateOrder', '', function(data) {
		}, 'json').error();
	}

});
