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
			  <td><span data-toggle="tooltip" title="<?php echo $help_gross_revenue . $income['revenue_shipping']; ?>"><i class="fa fa-shopping-cart"></i></span>&nbsp;<font color="#FDD017">[ </font><b><?php echo $text_gross_incomes; ?> :</b> <?php echo $income['gross_amount']; ?><font color="#FDD017"> ]</font></td>
			  <td><span data-toggle="tooltip" title="<?php echo $help_store_commission; ?>"><i class="fa fa-line-chart"></i></span>&nbsp;<font color="#4AA02C">[ </font><b><?php echo $text_commission; ?> :</b> <?php echo $income['commission']; ?><font color="#4AA02C"> ]</font></td>
			  <td><span data-toggle="tooltip" title="<?php echo $help_vendor_balance; ?>"><i class="fa fa-money"></i></span>&nbsp;<font color="#FF0000">[ </font><b><?php echo $text_vendor_earning; ?> :</b> <?php echo $income['vendor_amount']; ?></td>
			  <td><span data-toggle="tooltip" title="<?php echo $help_total_shipping; ?>"><i class="fa fa-truck"></i></span><font color="#FDD017">&nbsp;[ </font><b><?php echo $text_shipping; ?> :</b> <?php echo $income['shipping_charged']; ?><font color="#FDD017"> ] </font></td>
			  <td><span data-toggle="tooltip" title="<?php echo $help_total_coupon; ?>"><i class="fa fa-tags"></i></span><font color="#FDD017">&nbsp;[ </font><b><?php echo $text_coupon; ?> :</b> <?php echo '-' . $income['coupon_amount']; ?><font color="#FDD017"> ] </font></td>
			  <td><span data-toggle="tooltip" title="<?php echo $help_amount_to_vendor; ?>"><i class="fa fa-paypal"></i></span>&nbsp;<font color="#FDD017">&nbsp;[ </font> <b><?php echo $text_amount_pay_vendor; ?> :</b> <?php echo $income['amount_pay_vendor']; ?><font color="#FF0000"> ]</font></td>
			  <?php if ($filter_paid_status != 1 && $filter_vendor_group !=0 && !empty($orders)) { ?>
			  <td><div><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&no_shipping=1&business=<?php echo $income['paypal_email']; ?>&item_name=<?php echo $income['company']; ?>&amount=<?php echo $income['paypal_amount']; ?>&currency_code=<?php echo $config_currency; ?>" target="_blank"><span data-toggle="tooltip" title="<?php echo $help_pay_now; ?>" class="btn btn-mini btn-primary"><i class="fa fa-paypal"></i></span></a></div></td>
			  <td><div><a name="submit_add_payment" id="submit_add_payment" class="btn btn-mini btn-primary"><span><?php echo $button_addPayment; ?></span></a></div></td>
			  <input type="hidden" name="paypal_standard" id="paypal_standard" value="<?php echo $addPayment . '&payment_option=paypal_standard'; ?>" />
			  <input type="hidden" name="pay_cheque" id="pay_cheque" value="<?php echo $addPayment . '&payment_option=pay_cheque&chequeno='; ?>" />
			  <input type="hidden" name="other_payment_method" id="other_payment_method" value="<?php echo $addPayment . '&payment_option=other_payment_method&opm='; ?>" />
			  <?php } ?>
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
                  <option value="0"><?php echo $text_all_vendors; ?></option>
				  <?php foreach ($vendors_name as $vendors_name) { ?>
				  <?php if ($vendors_name['vendor_id'] == $filter_vendor_group) { ?>
				  <option value="<?php echo $vendors_name['vendor_id']; ?>" selected="selected"><?php echo $vendors_name['name']; ?></option>
				  <?php } else { ?>
				  <option value="<?php echo $vendors_name['vendor_id']; ?>"><?php echo $vendors_name['name']; ?></option>
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
					<div><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&no_shipping=1&business=<?php echo $order['paypal_email']; ?>&item_name=<?php echo $order['pp_product_name']; ?>&amount=<?php echo $order['paypal_amount']; ?>&invoice=<?php echo $order['order_id']; ?>&currency_code=<?php echo $config_currency; ?>" target="_blank"><span data-toggle="tooltip" title="<?php echo $help_pay_now; ?>"><i class="fa fa-paypal"></i></span></a> <span style="cursor:pointer;" onclick="$('#form').attr('action', '<?php echo 'index.php?route=report/mvd_vendor_transaction/addPaymentRecord&token=' . $token . '&oid=' . $order['order_id'] . '&pid=' . $order['product_id'] . '&opid=' . $order['order_product_id']; ?>'); $('#form').submit();">&nbsp;<i class="fa fa-plus-circle"></i></span></div>
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
				  <td></td>
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
				  <td class="text-left"><span style="cursor:pointer;" onclick="removeHistory('<?php echo $payment_history['payment_id']; ?>');">&nbsp;<i class="fa fa-minus-square"></i></span></td>
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
				  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
	var url = 'index.php?route=report/mvd_vendor_transaction&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_vendor_group = $('select[name=\'filter_vendor_group\']').val();
	
	if (filter_vendor_group != '*') {
		url += '&filter_vendor_group=' + encodeURIComponent(filter_vendor_group);
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

<script type="text/javascript"><!--
$('#submit_add_payment').click(function() {
    var $dialog = $('<div></div>')
    .load('view/template/report/choose_payment_type.html')
    .dialog({
        autoOpen: false,
        title: 'Select Payment Methods',
        buttons: {
            "Add Payment": function() {
				var pay_type = $("input[name=RadioGroup]:checked").val();
				var paypal_standard = $("input[name=paypal_standard]").val();
				var pay_cheque = $("input[name=pay_cheque]").val() + $("input[name=cheque_no]").val();
				var other_payment_method = $("input[name=other_payment_method]").val() + $("input[name=other_payment]").val();
				
				switch(pay_type) {
					case 'paypal_direct':
					$('#form').attr('action', paypal_standard); $('#form').submit();
					break;
					
					case 'cheque':
					$('#form').attr('action', pay_cheque); $('#form').submit();
					break;
					
					case 'other':
					$('#form').attr('action', other_payment_method); $('#form').submit();
					break;
				}
	
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }
    });
    $dialog.dialog('open');
});
//--></script>

<script type="text/javascript"><!--
function removeHistory(id) {
	$.ajax({
		url: 'index.php?route=report/mvd_vendor_transaction/removeHistory&token=<?php echo $token; ?>&payment_id=' + id,
		dataType: 'json',
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#history').before('<i class="fa fa-circle-o-notch fa-spin"></i> <label><?php echo $text_wait; ?></label>');
		},
		
		complete: function() {
			$('.fa-spin').remove();
			$('label').remove();
		},
		
		success: function(data) {
			$('#history_' + id).remove();
			$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i>' + data.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		}
			
	});
}
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui.js"></script>
<link href="view/javascript/jquery/ui/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<?php echo $footer; ?>