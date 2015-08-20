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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
	    <div class="panel panel-default">
		  <form method="post" enctype="multipart/form-data" id="form">
          <div class="table-responsive">
            <table class="table table-bordered" style="margin-bottom:0px;">
              <tbody>
              <?php foreach ($store_revenue AS $income) { ?>
			  <td><i class="fa fa-shopping-cart"></i></span>&nbsp;<b><?php echo $text_vendor_earning; ?></b>   <?php echo $income['vendor_amount']; ?></td>
			  <td><i class="fa fa-truck"></i></span>&nbsp;<b><?php echo $text_shipping; ?> :</b> <?php echo $income['shipping_charged']; ?></td>
			  <td><i class="fa fa-tags"></i></span>&nbsp;<b><?php echo $text_coupon; ?> :</b> <?php echo '-' . $income['coupon_amount']; ?></td>
			  <td><i class="fa fa-money"></i></span>&nbsp;<b><?php echo $text_vendor_revenue; ?> :</b> <?php echo $income['amount_pay_vendor']; ?></td>
			  <?php } ?>
              </tbody>
            </table>
          </div>
		</div>
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
			  <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-vendor-group"><?php echo $entry_group; ?></label>
                <select name="filter_vendor_group" id="input-vendor-group" class="form-control">
				  <?php foreach ($vendors_name as $vendors_name) { ?>
				  <?php if ($vendors_name['vendor_id'] == $login_vendor_id) { ?>
				  <option value="<?php echo $vendors_name['vendor_id']; ?>" selected="selected"><?php echo $vendors_name['name']; ?></option>
				  <?php } ?>
				  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-order-status-id"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status_id" id="input-order-status-id" class="form-control">
                  <option value="0"><?php echo $text_all_status; ?></option>
				  <?php foreach ($order_statuses as $order_status) { ?>
				  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
				  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				  <?php } else { ?>
				  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				  <?php } ?>
				  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_paid_status" id="input-status" class="form-control">
                 <?php if ($filter_paid_status) { ?>
				  <option value="0"><?php echo $text_no; ?></option>
				  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<?php } else { ?>
				  <option value="0" selected="selected"><?php echo $text_no; ?></option>
				  <option value="1"><?php echo $text_yes; ?></option>
				<?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-mini btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
		</form>

		<div class="panel panel-default">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                    <td class="text-right"><?php echo $column_order_id; ?></td>
				    <td class="text-left"><?php echo $column_product_name; ?></td>
					<td class="text-left"><?php echo $column_date_added; ?></td>
					<td class="text-left"><?php echo $column_transaction_status; ?></td>
					<td class="text-right"><?php echo $column_unit_price; ?><?php echo ' (' . $config_currency . ')'; ?></td>
					<td class="text-right"><?php echo $column_quantity; ?></td>
					<td class="text-right"><?php echo $column_total; ?><?php echo ' (' . $config_currency . ')'; ?></td>
					<td class="text-right"><?php echo $column_commission; ?><?php echo ' (' . $config_currency . ')'; ?></td>
					<td class="text-right"><?php echo $column_amount; ?><?php echo ' (' . $config_currency . ')'; ?></td>
					<td class="text-left"><?php echo $column_paid_status; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                  <td class="text-center"><?php echo $order['order_id']; ?></td>
				  <td class="text-left"><?php echo $order['product_name']; ?></td>
				  <td class="text-left"><?php echo $order['date']; ?></td>
				  <td class="text-left">
				  <?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $order['order_status']) { ?>
					  <?php echo $order_status['name']; ?>
					<?php } ?>
				  <?php } ?></td>
				  <td class="text-right"><?php echo $order['price']; ?></td>
				  <td class="text-center"><?php echo $order['quantity']; ?></td>
				  <td class="text-right"><?php echo $order['total']; ?><br /><?php echo $order['title']; ?></td>
				  <td class="text-right"><?php echo $order['commission']; ?></td>
				  <td class="text-right"><?php echo $order['amount']; ?></td>
				  <td class="text-left">
				  <?php if ($order['paid_status']) { ?>
					<span">&nbsp;<i class="fa fa-check-circle"></i></span>
					<?php } else { ?>
					<span">&nbsp;<i class="fa fa-times-circle"></i></span>
					<?php } ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div></div>
		  <div class="row">
			<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
			<div class="col-sm-6 text-right"><?php echo $results; ?></div>
		  </div>
		</div>
    </div>
  </div>
  
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-history"></i> <?php echo $text_payment_history; ?></h3>
      </div>
	  <div class="panel-body">
	    <form method="post" enctype="multipart/form-data" target="_blank" id="form-history">
		<div class="panel panel-default" id="history">
          <div class="table-responsive">
            <table table class="table table-bordered table-hover">
              <thead>
                <tr>
				  <td><?php echo $column_vendor_name; ?></td>
				  <td><?php echo $column_order_product; ?></td>
				  <td><?php echo $column_payment_type; ?></td>
				  <td><?php echo $column_payment_amount; ?> (<?php echo $config_currency; ?>)</td>
				  <td><?php echo $column_payment_date; ?></td>
                </tr>
              </thead>
              <tbody>
				<?php if ($histories) { ?>
				<?php foreach ($histories as $payment_history) { ?>
				<tbody id="history_<?php echo $payment_history['payment_id']; ?>">
				<tr>
				  <td class="text-left"><?php echo $payment_history['name']; ?></td>
				  <td class="text-left"><?php foreach ($payment_history['details'] AS $orders) { ?>
						[<?php echo $orders['order_id']; ?> - <?php echo $orders['product_name']; ?>]
						<?php } ?></td>
				  <td class="text-left"><?php echo $payment_history['payment_type']; ?></td>
				  <td class="text-right">-<?php echo $payment_history['amount']; ?></td>
				  <td class="text-left"><?php echo $payment_history['date']; ?></td>
				</tr>
				</tbody>
				<?php } ?>
				<?php } else { ?>
				<tr>
				  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			  </tbody>
            </table>
          </div>
		</div>
	    </div>
	    </form>
	  </div>
	</div>
  </div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=report/vdi_transaction&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_paid_status = $('select[name=\'filter_paid_status\']').val();

	if (filter_paid_status) {
		url += '&filter_paid_status=' + encodeURIComponent(filter_paid_status);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();

	if (filter_order_status_id) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}

	location = url;
});
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.js"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link href="view/javascript/jquery/ui/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<?php echo $footer; ?>