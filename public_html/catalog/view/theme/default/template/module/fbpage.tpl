<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo $locale; ?>/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div style="text-align:center">
  <?php if ($heading_title) { ?>
  <h2><?php echo $heading_title; ?></h2>
  <?php } ?>
  <div class="fb-page" data-href="https://www.facebook.com/<?php echo $page_url; ?>" data-width="<?php echo $width; ?>" data-height="<?php echo $height; ?>" data-hide-cover="<?php echo $show_cover; ?>" data-show-facepile="<?php echo $show_faces; ?>" data-show-posts="<?php echo $show_posts; ?>"></div>
</div>