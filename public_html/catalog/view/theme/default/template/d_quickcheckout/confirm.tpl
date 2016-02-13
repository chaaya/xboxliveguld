<!--
	Ajax Quick Checkout
	v6.0.0
	Dreamvention.com
	d_quickcheckout/confirm.tpl
-->
<div id="confirm_view" class="qc-step" data-col="<?php echo $col; ?>" data-row="<?php echo $row; ?>"></div>
<script type="text/html" id="confirm_template">
<div id="confirm_wrap">
	<div class="panel panel-default">
		<div class="panel-body">
			<form id="confirm_form" class="form-horizontal">
			</form>

			<button id="qc_confirm_order" class="btn btn-primary btn-lg btn-block" <%= model.show_confirm ? '' : 'disabled="disabled"' %>><% if(Number(model.payment_popup)) { %><?php echo $button_continue; ?><% }else{ %><?php echo $button_confirm; ?><% } %></span></button>

		</div>
	</div>
</div>
<!--
	Order fraud modal
-->
<div id="anti_fraud_modal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
			<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope-o"></i> Verify your order</h4>
			      </div>
			      <div class="modal-body">

							<div style="margin-top: 5px;">
								A verification code for this order has been sent to your registered email.
							</div>
							<div style="margin-top: 20px;">
								<input id="fraud_protection_code" class="form-control" placeholder="Enter your 6 digit verification code" type="text" maxlength="6">
							</div>
							<p style="font-size: 11px; margin-top: 5px;" class="text-muted text-center">
								<i style="color: red;" class="fa fa-exclamation-triangle"></i> Anti fraud system protection</p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
			        <button id="qc_verfiy_order" type="button" class="btn btn-primary"><i class="fa fa-check"></i> Verify code</button>
			      </div>
    </div>
  </div>
</div>
</script>
<script>

$(function() {
	qc.confirm = $.extend(true, {}, new qc.Confirm(<?php echo $json; ?>));
	qc.confirmView = $.extend(true, {}, new qc.ConfirmView({
		el:$("#confirm_view"),
		model: qc.confirm,
		template: _.template($("#confirm_template").html())
	}));
});

</script>
