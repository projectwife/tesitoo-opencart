<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-prolimit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1></i> <?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
    <div class="panel-body">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-prolimit" class="form-horizontal">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-package_name"><?php echo $entry_name; ?></label>
			<div class="col-sm-10">
			<input type="text" name="package_name" value="<?php echo $package_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-package_name" class="form-control" />
			<?php if ($error_package_name) { ?>
				<div class="text-danger"><?php echo $error_package_name; ?></div>
			<?php } ?>
        </div>
      </div>
	  <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-quantity"><span data-toggle="tooltip" title="<?php echo $help_quantity; ?>"><?php echo $entry_quantity; ?></span></label>
        <div class="col-sm-10">
			<input type="text" name="product_limit" value="<?php echo $product_limit; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
			<?php if ($error_product_limit) { ?>
				<div class="text-danger"><?php echo $error_product_limit; ?></div>
			<?php } ?>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sort_order"><?php echo $entry_sort_order; ?></label>
        <div class="col-sm-10">
			<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort_order" class="form-control" />
        </div>
      </div>
    </form>
	</div>
	</div>
  </div>
</div>
<?php echo $footer; ?>