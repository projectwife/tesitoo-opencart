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
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-contract-history-list">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="right"><?php echo $column_order_id; ?></td>
			  <td class="left"><?php echo $column_product_name; ?></td>
			  <td class="left"><?php echo $column_date_added; ?></td>
			  <td class="left"><?php echo $column_transaction_status; ?></td>
			  <td class="right"><?php echo $column_unit_price; ?><?php echo ' (' . $config_currency . ')'; ?></td>
		      <td class="right"><?php echo $column_quantity; ?></td>
			  <td class="right"><?php echo $column_total; ?><?php echo ' (' . $config_currency . ')'; ?></td>
			  <td class="right"><?php echo $column_commission; ?><?php echo ' (' . $config_currency . ')'; ?></td>
			  <td class="right"><?php echo $column_amount; ?><?php echo ' (' . $config_currency . ')'; ?></td>
			  <td class="left"><?php echo $column_paid_status; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($histories) { ?>
				<?php foreach ($histories as $signup_history) { ?>
				  <tbody id="history_<?php echo $signup_history['signup_id']; ?>">
					<tr>
					  <td class="left"><?php echo $signup_history['signup_id']; ?></td>
					  <td class="left"><input type="hidden" name="user_id<?php echo $signup_history['signup_id']; ?>" value="<?php echo $signup_history['user_id']; ?>" /><?php echo $signup_history['username']; ?></td>
					  <td class="left"><?php echo $signup_history['vendor_name']; ?></td>
					  <td class="left"><?php echo $signup_history['signup_plan']; ?></td>
					  <td class="left"><?php echo $signup_history['signup_fee']; ?></td>
					  <td class="left"><?php echo $signup_history['status']; ?></td>
					  <td class="left"><?php echo $signup_history['date_start']; ?></td>
					  <td class="left"><?php echo $signup_history['date_end']; ?></td>					
					  <td class="left"><?php if ($signup_history['paid_status']) { echo $text_completed; } else { echo $text_pending; } ?></td>
					</tr>
				  </tbody>
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
<?php if ($this_user_getVP) { ?>
<div id="dialog-form">
  <form id="paynow">
  <fieldset>
  <div id="users-contain" class="ui-widget">
  <table id="users" class="ui-widget ui-widget-content">
    <thead class="ui-widget-header">
      <tr>
        <th width="250px" style="color:#fff">Plan</th>
		<th width="80px" style="color:#fff">Price</th>
		<th width="120px" style="color:#fff">Period</th>        
        <th width="100px" style="color:#fff">Next Due Date</th>
      </tr>
    </thead>
    <tbody>
      <tr>
		<input type="hidden" name="old_date" id="old_date" value="<?php echo $old_date; ?>"/>
		<input type="hidden" name="commission_type" id="commission_type" value="<?php echo $commission_type; ?>"/>
        <th><input type="hidden" name="renew_plan" id="renew_plan" value="<?php echo $renew_plan; ?>"/><?php echo $renew_plan; ?></th>
		<th style="font-weight:normal"><input type="hidden" name="renew_rate" id="renew_rate" value="<?php echo $renew_rate; ?>"/><label id="lnew_rate"><?php echo $this->currency->format($renew_rate, $this->config->get('config_currency')); ?></label></th>
        <th><select name="renew_period" id="renew_period">
		<?php if ($my_type) { ?>
		<?php
			$i=$my_duration; 
			do { 
				echo '<option value="' . $i . '"> ' . $i . ' ' . $month_year . '</option>';
				$i=$i+$my_duration;
			}
			while ($i<=6*$my_duration)			
		?>
		<?php } else { ?>
		<?php
			$i=1; 
			do { 
				echo '<option value="' . $i . '"> ' . $i . ' ' . $month_year . '</option>';
				$i++;
			}
			while ($i<=6)
		?>
		<?php } ?>
		</select></th>
        <th style="font-weight:normal"><input type="hidden" id="due_date" name="due_date" value="<?php echo $due_date; ?>"/><label id="ldue_date"><?php echo $due_date; ?></label></th>
      </tr>
    </tbody>
  </table>
  </div>   
  </fieldset>
  <br/>
  <div class="buttons" style="position:absolute;right:25px"><a name="button_paynow" id="button_paynow" class="button" style="color:white"><span>Pay Now</span></a> <a name="button_cancel" id="button_cancel" class="button" style="color:white"><span>Cancel</span></a></div> 
  </form>
</div>

<script type="text/javascript">
	$(function() { 
		$("#dialog-form").dialog({
		autoOpen: false,
		height: 200,
		width: 550,
		title: 'Renew Contract - <?php echo $renew_plan; ?>',
		modal: true   
		});
	 
		$("#pay-fee")
		.click(function() {
			$("#dialog-form").dialog("open");
		});
	});
</script>

<script type="text/javascript"><!--	
	$('select[name=\'renew_period\']').bind('change', function() {
		$.ajax({
			url: 'index.php?route=sale/contract_history/ajaxrate&token=<?php echo $token; ?>&renewcycle=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'renew_period\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
			},
			
			complete: function() {
				$('.wait').remove();
			},
			
			success: function(json) {
				if (json) {
					$("#renew_rate").val(json['renew_rate']);
					$("#lnew_rate").replaceWith('<label id="lnew_rate">' + json['renew_lrate'] + '</label>');
					$("#due_date").val(json['renew_due_date']);
					$("#ldue_date").replaceWith('<label id="ldue_date">' + json['renew_due_date'] + '</label>');
				}
			},
			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}			
		});				
	});
//--></script>

<script type="text/javascript">
	$('#button_paynow').click(function() {
		$.ajax({
			url: 'index.php?route=sale/contract_history/paynow&token=<?php echo $token; ?>&renew_plan=' + $("#renew_plan").val() + '&renew_rate=' + $("#renew_rate").val() + '&renew_period=' + $("#renew_period").val() + '&next_due_date=' + $("#due_date").val() + '&commission_type=' + $("#commission_type").val() + '&old_date=' + $("#old_date").val(),
			dataType: 'json',
			beforeSend: function() {
				$('#button_paynow').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
			},
			
			complete: function() {
				$('.wait').remove();
			},
			
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}
				
				if (json['success']) {
					window.open(json['success'], '_blank');
				}
			}
		});				
	});
	
	$('#button_cancel').click(function() {
		$("#dialog-form").dialog("close");	
	});
</script>
<?php } ?>
<?php echo $footer; ?>