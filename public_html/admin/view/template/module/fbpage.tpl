<?php 
/*
* Facebookpage Module
* Developed for OpenCart 2.x
* Author Gedielson Peixoto - http://www.gepeixoto.com.br
* @03/2015
* Under GPL license.
*/
echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-fbpage" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-fbpage" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-title"><span data-toggle="tooltip" title="<?php echo $help_title; ?>"><?php echo $entry_title; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="fbpage_title" value="<?php echo $fbpage_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label for="input-page-url" class="col-sm-2 control-label"><?php echo $entry_page_url; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">https://www.facebook.com/</span>
                <input type="text" id="input-page-url" name="fbpage_page_url" value="<?php echo $fbpage_page_url; ?>" placeholder="<?php echo $entry_page_url; ?>" class="form-control">
                <?php if ($error_page_url) { ?>
                <div class="text-danger"><?php echo $error_page_url; ?></div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" id="input-width" name="fbpage_width" value="<?php echo $fbpage_width; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
              <?php if ($error_width) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" id="input-height" name="fbpage_height" value="<?php echo $fbpage_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
              <?php if ($error_height) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_show_cover; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <input type="radio" name="fbpage_show_cover" value="true" <?php echo ($fbpage_show_cover == 'true' ? 'checked="checked" ' : ''); ?>/>
                <?php echo $text_yes; ?>
              </label>
              <label class="radio-inline">
                <input type="radio" name="fbpage_show_cover" value="false" <?php echo ($fbpage_show_cover == 'false' ? 'checked="checked" ' : ''); ?>/>
                <?php echo $text_no; ?>    
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_show_faces; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <input type="radio" name="fbpage_show_faces" value="true" <?php echo ($fbpage_show_faces == 'true' ? 'checked="checked" ' : ''); ?>/>
                <?php echo $text_yes; ?>
              </label>
              <label class="radio-inline">
                <input type="radio" name="fbpage_show_faces" value="false" <?php echo ($fbpage_show_faces == 'false' ? 'checked="checked" ' : ''); ?>/>
                <?php echo $text_no; ?>    
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_show_posts; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <input type="radio" name="fbpage_show_posts" value="true" <?php echo ($fbpage_show_posts == 'true' ? 'checked="checked" ' : ''); ?>/>
                <?php echo $text_yes; ?>
              </label>
              <label class="radio-inline">
                <input type="radio" name="fbpage_show_posts" value="false" <?php echo ($fbpage_show_posts == 'false' ? 'checked="checked" ' : ''); ?>/>
                <?php echo $text_no; ?>    
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-locale"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo htmlspecialchars($help_locale); ?>"><?php echo $entry_locale; ?></span></label>
            <div class="col-sm-10">
              <input type="text" id="input-locale" name="fbpage_locale" value="<?php echo $fbpage_locale; ?>" placeholder="<?php echo $entry_locale; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="fbpage_status" class="form-control">
                <?php if ($fbpage_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>