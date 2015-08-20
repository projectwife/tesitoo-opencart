<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-oauth" data-toggle="tab"><?php echo $tab_oauth; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-oauth">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-access-token-ttl"><span data-toggle="tooltip" title="<?php echo $help_access_token_ttl; ?>"><?php echo $entry_access_token_ttl; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="api_access_token_ttl" value="<?php echo $api_access_token_ttl; ?>" placeholder="<?php echo $entry_access_token_ttl; ?>" id="input-access-token-ttl" class="form-control" />
                  <?php if ($error_access_token_ttl) { ?>
                  <div class="text-danger"><?php echo $error_access_token_ttl; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>