<?php echo $header; ?>
<div class="container">
  <h1 class="text-center text-muted how-to hidden-xs">How does it work?</h1>
  <div class="process">
    <div class="process-row">
        <div class="process-step">
            <button type="button" class="btn btn-success btn-circle"><i class="fa fa-shopping-cart fa-3x"></i></button>
            <p>Choose your product</p>
        </div>
        <div class="process-step">
            <button type="button" class="btn btn-warning btn-circle"><i class="fa fa-paypal fa-3x"></i></button>
            <p>Pay via Paypal/Credit card</p>
        </div>
        <div class="process-step">
            <button type="button" class="btn btn-primary btn-circle"><i class="fa fa-envelope-o fa-3x"></i></button>
            <p>Instantly recieve your code</p>
        </div>
    </div>
  </div>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?><?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
