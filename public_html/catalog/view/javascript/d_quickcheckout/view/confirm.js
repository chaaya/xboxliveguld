qc.ConfirmView = qc.View.extend({
	initialize: function(e){
		this.template = e.template;
		qc.event.bind("update", this.update, this);
		qc.event.bind("changeAccount", this.changeAccount, this);
		this.render();
	},

	events: {
		'click button#qc_confirm_order': 'confirm',
		'click button#qc_verfiy_order': 'validateVerificationCode'
	},

	template: '',

	confirm: function(){
		preloaderStart();
		var valid = true;
		$("#d_quickcheckout form").each(function(){
			if(!$(this).valid()){
				valid = false;
				preloaderStop();
				$('html,body').animate({ scrollTop: $(".has-error").offset().top-60}, 'slow');
			}
		});

		if(valid) {
			this.model.sendOrderVerificationCode().then(function() {
				$('div#anti_fraud_modal').modal('show');
			});
		}

		if(parseInt(config.general.analytics_event)){
			ga('send', 'event', config.name, 'click', 'confirm.confirm');
		}
	},

	validateVerificationCode: function() {
		var code = $('input#fraud_protection_code').val();
		this.model.validateVerificationCode(code);
	},

	changeAccount: function(account){

		if(this.model.get('account') !== account){
			this.model.changeAccount(account);
			this.render();
		}
	},

	verifiyOrder: function() {
			this.model.update();
	},

	update: function(data){
		if(data.confirm){
			this.model.set('confirm', data.confirm);
		}

		if(typeof(data.show_confirm) !== 'undefined'){
			this.model.set('show_confirm', data.show_confirm);
			this.render();
		}

		if(typeof(data.payment_popup) !== 'undefined'){
			this.model.set('payment_popup', data.payment_popup);
			this.render();
		}

		if(data.account && data.account !== this.model.get('account')){
			this.changeAccount(data.account);
		}
	},

	render: function(){
		$(this.el).html(this.template({'model': this.model.toJSON()}));
		this.fields = new qc.FieldView({el:$("#confirm_form"), model: this.model, template: _.template($("#field_template").html())});
		this.fields.render();
	},
});
