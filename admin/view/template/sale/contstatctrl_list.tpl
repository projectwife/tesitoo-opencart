<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	  <button type="button" data-toggle="tooltip" title="<?php echo $button_sendEmail; ?>" class="btn btn-primary" onclick="$('#form-contract-status-modifier').attr('action', '<?php echo $sendEmail; ?>'); $('#form-contract-status-modifier').submit();"><i class="fa fa-envelope-o"></i></button>
	  <button type="button" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary" onclick="$('#form-contract-status-modifier').attr('action', '<?php echo $update; ?>'); $('#form-contract-status-modifier').submit();"><i class="fa fa-edit"></i></button>
      <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-contract-status-modifier').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
    <div class="panel-body">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-contract-status-modifier">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="text-left"><?php echo $column_contract_id; ?></td>
			  <td class="text-left"><?php echo $column_username; ?></td>
              <td class="text-left"><?php echo $column_vendor_name; ?></td>
              <td class="text-left"><?php echo $column_signup_plan; ?></td>
			  <td class="text-left"><?php echo $column_signup_amount; ?></td>
			  <td class="text-left"><?php echo $column_status; ?></td>
			  <td class="text-left"><?php echo $column_remaining_days; ?></td>
			  <td class="text-left"><?php echo $column_date_start; ?></td>
			  <td class="text-left"><?php echo $column_date_end; ?></td>				
              <td class="text-left"><?php echo $column_paid_status; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($histories) { ?>
            <?php foreach ($histories as $signup_history) { ?>
            <tr>
              <td class="text-center"><?php if ($signup_history['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $signup_history['signup_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $signup_history['signup_id']; ?>" />
                <?php } ?></td>
              <td class="text-left"><?php echo $signup_history['signup_id']; ?></td>
			  <td class="text-left"><input type="hidden" name="user_id<?php echo $signup_history['signup_id']; ?>" value="<?php echo $signup_history['user_id']; ?>" /><?php echo $signup_history['username']; ?></td>
			  <td class="text-left"><?php echo $signup_history['vendor_name']; ?></td>
			  <td class="text-left"><?php echo $signup_history['signup_plan']; ?></td>
			  <td class="text-left"><?php echo $signup_history['signup_fee']; ?></td>
			  <td class="text-left"><?php echo $signup_history['status']; ?></td>
			  <td class="text-left"><input type="hidden" name="remaining_days<?php echo $signup_history['signup_id']; ?>" value="<?php echo $signup_history['remaining_days']; ?>" /><?php echo $signup_history['remaining_days']; ?></td>
			  <td class="text-left"><?php echo $signup_history['date_start']; ?></td>
			  <td class="text-left"><input type="hidden" name="date_end<?php echo $signup_history['signup_id']; ?>" value="<?php echo $signup_history['date_end']; ?>" /><?php echo $signup_history['date_end']; ?></td>					
			  <td class="text-left"><select name="paid_status<?php echo $signup_history['signup_id']; ?>" class="form-control">
				<?php if ($signup_history['paid_status']) { ?>
					<option value="1" selected="selected"><?php echo $text_completed; ?></option>
					<option value="0"><?php echo $text_pending; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_completed; ?></option>
					<option value="0" selected="selected"><?php echo $text_pending; ?></option>
				<?php } ?>
			  </select></td>
			</tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  </div>
  </div>
</div>
<?php echo $footer; ?>