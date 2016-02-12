<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>


<div class="row col-xs-12">
	<div class="alert alert-info" role="alert">
		<h3>Pro tips!</h3>
		<div>
			Click the <b>Redeem code</b> link to automaticly redeem your code at live.xbox.com web page.<br>
			It automaticly fills your code in the redeem code dialog.<br><br>
			You can also get a QR-code by clicking the qr-code link and use your kinect to scan your code.
		</div>
	</div>
</div>

<div class="row col-xs-12">
<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $text_orderid; ?></th>
			<th><?php echo $text_dateoforder; ?></th>
			<th><?php echo $text_serialkey; ?></th>
			<th><?php echo $text_instructions; ?></th>
			<th>Kinect QR-code</th>
		</tr>
	</thead>
		<tbody>
			<?php foreach ($serialkeys as $serialkey) { ?>
			<tr>
				<th scope="row">
					<?php echo $serialkey['order_id']; ?>
				</th>
				<td>
					<?php echo $serialkey['date_added']; ?>
				</td>
				<td>
					<div>
						<?php echo $serialkey['productname']; ?>
					</div>
					<div>
						<b>Code</b>: <?php echo $serialkey['serialkey']; ?>
					</div>
				</td>
				<td>
					<a target="_blank" href="https://live.xbox.com/redeemtoken?token=<?php echo $serialkey['serialkey']; ?>">Redeem code</a>
				</td>
				<td>
					<a target="_blank" href="http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=<?php echo $serialkey['serialkey']; ?>&chld=H|0">Get QR-code for Kinect</a>
				</td>
			</tr>
			 <?php } ?>
		</tbody>
</table>
</div>
</div>


<!-- END OF TABLE -->







  <div class="pagination"><?php echo $pagination; ?></div>

  <div class="buttons clearfix">

    <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>

  </div>

  <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
