<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-commission" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-check-circle"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
    <div class="panel-body">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-commission" class="form-horizontal">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-commission_name"><?php echo $entry_name; ?></label>
			<div class="col-sm-10">
			<input type="text" name="commission_name" value="<?php echo $commission_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-commission_name" class="form-control" />
			<?php if ($error_commission_name) { ?>
				<div class="text-danger"><?php echo $error_commission_name; ?></div>
			<?php } ?>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label"><?php echo $entry_type; ?></label>
         <div class="col-sm-10">
			<select name="commission_type">
				<?php if (!$commission_type) { ?>
					<option value="0" selected="selected"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
                <?php } elseif ($commission_type == '1') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1" selected="selected"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
				<?php } elseif ($commission_type == '2') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"  selected="selected"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
				<?php } elseif ($commission_type == '3') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3" selected="selected"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
				<?php } elseif ($commission_type == '4') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4" selected="selected"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
				<?php } elseif ($commission_type == '5') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5" selected="selected"><?php echo $text_year; ?></option>
                <?php } ?>
            </select></div>
	   </div>
	  <div class="form-group required">
        <label id="commission" class="col-sm-2 control-label" for="input-commission"><?php echo $entry_commission; ?></label>
        <div class="col-sm-10">
			<input type="text" name="commission" value="<?php echo $commission; ?>" placeholder="<?php echo $entry_commission; ?>" id="input-commission" class="form-control" />
			<span class="help-block"><?php echo $help_commission; ?></span>
			<?php if ($error_commission) { ?>
				<div class="text-danger"><?php echo $error_commission; ?></div>
			<?php } ?>
        </div>
      </div>
	  <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-duration"><span data-toggle="tooltip" title="<?php echo $help_duration; ?>"><?php echo $entry_duration; ?></span></label>
        <div class="col-sm-10">
			<input type="text" name="duration" value="<?php echo $duration; ?>" placeholder="<?php echo $entry_duration; ?>" id="input-duration" class="form-control" />
        </div>
      </div>
	  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
			  <select name="product_limit_id" class="form-control">
				<?php foreach($product_limits as $product_limit) { ?>
				  <?php if ($product_limit['product_limit_id'] == $product_limit_id) { ?>
					<option value="<?php echo $product_limit['product_limit_id']; ?>" selected="selected"><?php echo $product_limit['package_name'] . ' (' . $product_limit['product_limit'] . ')'; ?></option>
				  <?php } else { ?>
					<option value="<?php echo $product_limit['product_limit_id']; ?>"><?php echo $product_limit['package_name']  . ' (' . $product_limit['product_limit'] . ')'; ?></option>
				  <?php } ?>
				<?php } ?>
              </select>
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
<script type="text/javascript"><!--
$('select[name=\'commission_type\']').bind('change', function() {
	if (this.value == '4' || this.value == '5') {
		$('#commission').html('<?php echo $entry_subscription; ?>');
		$('#commission').next().find('span').html('<?php echo $help_subscription; ?>');
	} else {
		$('#commission').html('<?php echo $entry_commission; ?>');
		$('#commission').next().find('span').html('<?php echo $help_commission; ?>');
	}
});
$('select[name=\'commission_type\']').trigger('change');
//--></script>
<?php echo $footer; ?>