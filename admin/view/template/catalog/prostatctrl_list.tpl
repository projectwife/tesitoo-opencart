<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><button type="submit" form="form-quicker-status-modifier" formaction="<?php echo $update; ?>" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary"><i class="fa fa-edit"></i></button>
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
    <form method="post" enctype="multipart/form-data" id="form-quicker-status-modifier">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="text-left"><?php echo $column_username; ?></td>
              <td class="text-left"><?php echo $column_vendor_name; ?></td>
			  <td class="text-left"><?php echo $column_company; ?></td>
              <td class="text-left"><?php echo $column_flname; ?></td>
			  <td class="text-left"><?php echo $column_telephone; ?></td>
			  <td class="text-left"><?php echo $column_email; ?></td>
			  <td class="text-left"><?php echo $column_due_date; ?></td>
			  <td class="text-left"><?php echo $column_total; ?></td>			
			  <td class="text-left"><label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="<?php echo $help_status; ?>"><?php echo $column_status; ?></span></label></td>	
            </tr>
          </thead>
          <tbody>
            <?php if ($users_info) { ?>
            <?php foreach ($users_info as $user_info) { ?>
            <tr>
              <td class="text-center"><?php if ($user_info['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $user_info['user_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $user_info['user_id']; ?>" />
                <?php } ?></td>
              <td class="text-left"><?php echo $user_info['username']; ?></td>
			  <td class="text-left"><?php echo $user_info['vendor_name']; ?></td>
			  <td class="text-left"><?php echo $user_info['company']; ?></td>
			  <td class="text-left"><?php echo $user_info['flname']; ?></td>
			  <td class="text-left"><?php echo $user_info['telephone']; ?></td>
			  <td class="text-left"><?php echo $user_info['email']; ?></td>
			  <td class="text-left"><?php echo $user_info['due_date']; ?></td>
			  <td class="text-left"><?php echo $user_info['total_products']; ?></td>
			  <td class="text-left"><select name="user_status<?php echo $user_info['user_id']; ?>" class="form-control">
				<?php if ($user_info['status']) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			  </select></td>
			</tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
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