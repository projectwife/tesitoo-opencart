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
	<div class="panel panel-default" id="history">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
    <div class="panel-body">
    <form action="" method="post" enctype="multipart/form-data" id="form-payment-history-list">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
			  <td></td>
              <td class="text-left"><?php echo $column_vendor_name; ?></td>
			  <td class="text-left"><?php echo $column_order_product; ?></td>
              <td class="text-left"><?php echo $column_payment_amount; ?> (<?php echo $config_currency; ?>)</td>
              <td class="text-left"><?php echo $column_payment_date; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($histories) { ?>
				<?php foreach ($histories as $payment_history) { ?>
				  <tbody id="history_<?php echo $payment_history['payment_id']; ?>">
					<tr>
					  <td class="text-left"><span style="cursor:pointer" onclick="removeHistory('<?php echo $payment_history['payment_id']; ?>');">&nbsp;<i class="fa fa-minus-square"></i></span></td>
					  <td class="text-left"><?php echo $payment_history['name']; ?></td>
					  <td class="text-left" >
						<?php foreach ($payment_history['details'] as $orders) { ?>
						[<?php echo $orders['order_id']; ?> - <?php echo $orders['product_name']; ?>]
						<?php } ?>
						</td>
					  <td class="text-left">-<?php echo $payment_history['amount']; ?></td>
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
    </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  </div>
  </div>
</div>
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
<?php echo $footer; ?>