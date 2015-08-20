<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
    <form method="post" enctype="multipart/form-data" id="form-contract-history-list">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_contract_id; ?></td>
			  <td class="text-left"><?php echo $column_username; ?></td>
              <td class="text-left"><?php echo $column_vendor_name; ?></td>
              <td class="text-left"><?php echo $column_signup_plan; ?></td>
			  <td class="text-left"><?php echo $column_signup_amount; ?></td>
			  <td class="text-left"><?php echo $column_status; ?></td>
			  <td class="text-left"><?php echo $column_date_start; ?></td>
			  <td class="text-left"><?php echo $column_date_end; ?></td>				
              <td class="text-left"><?php echo $column_paid_status; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($histories) { ?>
				<?php foreach ($histories as $signup_history) { ?>
				  <tbody id="history_<?php echo $signup_history['signup_id']; ?>">
					<tr>
					  <td class="text-center"><?php echo $signup_history['signup_id']; ?></td>
					  <td class="text-left"><?php echo $signup_history['username']; ?></td>
					  <td class="text-left"><?php echo $signup_history['vendor_name']; ?></td>
					  <td class="text-left"><?php echo $signup_history['signup_plan']; ?></td>
					  <td class="text-left"><?php echo $signup_history['signup_fee']; ?></td>
					  <td class="text-left"><?php echo $signup_history['status']; ?></td>
					  <td class="text-left"><?php echo $signup_history['date_start']; ?></td>
					  <td class="text-left"><?php echo $signup_history['date_end']; ?></td>					
					  <td class="text-left"><?php if ($signup_history['paid_status']) { echo $text_completed; } else { echo $text_pending; } ?></td>
					</tr>
				  </tbody>
				<?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
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