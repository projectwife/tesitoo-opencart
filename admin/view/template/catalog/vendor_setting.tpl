<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-vendor-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-vendor-setting" class="form-horizontal">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-email" data-toggle="tab"><?php echo $tab_mail_setting; ?></a></li>
        <li><a href="#tab-catalog" data-toggle="tab"><?php echo $tab_catalog; ?></a></li>
        <li><a href="#tab-sales" data-toggle="tab"><?php echo $tab_sales; ?></a></li>
        <li><a href="#tab-store" data-toggle="tab"><?php echo $tab_store; ?></a></li>
        <li><a href="#tab-notification" data-toggle="tab"><?php echo $tab_notification; ?></a></li>
		<li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
        <li><a href="#tab-signup" data-toggle="tab"><?php echo $tab_signup; ?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-email">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-email-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
			  <select name="mvd_email_status" class="form-control">
				<?php if ($mvd_email_status) { ?>
				  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				  <option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				  <option value="1"><?php echo $text_enabled; ?></option>
				  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
              </select>
            </div>
          </div>
		  <?php foreach ($languages as $language) { ?>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-new-order-message<?php echo $language['language_id']; ?>"><?php echo $entry_new_order_message; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="mvd_new_order_message<?php echo $language['language_id']; ?>" cols="80" rows="10" placeholder="<?php echo $entry_new_order_message; ?>" id="input-new-order-message<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset(${'mvd_new_order_message' . $language['language_id']}) ? ${'mvd_new_order_message' . $language['language_id']} : ''; ?></textarea>
              </div>
              <?php if (${'error_code_new_message' . $language['language_id']}) { ?>
              <div class="text-danger"><?php echo ${'error_code_new_message' . $language['language_id']}; ?></div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
		  <?php foreach ($languages as $language) { ?>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-history-order-message<?php echo $language['language_id']; ?>"><?php echo $entry_history_order_message; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="mvd_history_order_message<?php echo $language['language_id']; ?>" cols="80" rows="10" placeholder="<?php echo $entry_history_order_message; ?>" id="input-history-order-message<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset(${'mvd_history_order_message' . $language['language_id']}) ? ${'mvd_history_order_message' . $language['language_id']} : ''; ?></textarea>
              </div>
              <?php if (${'error_code_history_message' . $language['language_id']}) { ?>
              <div class="text-danger"><?php echo ${'error_code_history_message' . $language['language_id']}; ?></div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-order-id"><span data-toggle="tooltip" title="<?php echo $help_order_id; ?>"><?php echo $entry_order_id; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_order_id) { ?>
                <input type="radio" name="mvd_show_order_id" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_order_id" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_order_id) { ?>
                <input type="radio" name="mvd_show_order_id" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_order_id" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-order-status"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $entry_order_status; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_order_status) { ?>
                <input type="radio" name="mvd_show_order_status" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_order_status" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_order_status) { ?>
                <input type="radio" name="mvd_show_order_status" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_order_status" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-payment-method"><span data-toggle="tooltip" title="<?php echo $help_payment_method; ?>"><?php echo $entry_payment_method; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_payment_method) { ?>
                <input type="radio" name="mvd_show_payment_method" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_payment_method" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_payment_method) { ?>
                <input type="radio" name="mvd_show_payment_method" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_payment_method" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-cust-email"><span data-toggle="tooltip" title="<?php echo $help_cust_email; ?>"><?php echo $entry_cust_email; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_cust_email) { ?>
                <input type="radio" name="mvd_show_cust_email" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_cust_email" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_cust_email) { ?>
                <input type="radio" name="mvd_show_cust_email" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_cust_email" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-cust-telephone"><span data-toggle="tooltip" title="<?php echo $help_cust_telephone; ?>"><?php echo $entry_cust_telephone; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_cust_telephone) { ?>
                <input type="radio" name="mvd_show_cust_telephone" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_cust_telephone" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_cust_telephone) { ?>
                <input type="radio" name="mvd_show_cust_telephone" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_cust_telephone" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-cust-shipping-address"><span data-toggle="tooltip" title="<?php echo $help_shipping_address; ?>"><?php echo $entry_shipping_address; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_cust_shipping_address) { ?>
                <input type="radio" name="mvd_show_cust_shipping_address" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_cust_shipping_address" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_cust_shipping_address) { ?>
                <input type="radio" name="mvd_show_cust_shipping_address" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_cust_shipping_address" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-vendor-address"><span data-toggle="tooltip" title="<?php echo $help_vendor_address; ?>"><?php echo $entry_vendor_address; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_vendor_address) { ?>
                <input type="radio" name="mvd_show_vendor_address" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_vendor_address" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_vendor_address) { ?>
                <input type="radio" name="mvd_show_vendor_address" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_vendor_address" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-vendor-email"><span data-toggle="tooltip" title="<?php echo $help_vendor_email; ?>"><?php echo $entry_vendor_email; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_vendor_email) { ?>
                <input type="radio" name="mvd_show_vendor_email" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_vendor_email" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_vendor_email) { ?>
                <input type="radio" name="mvd_show_vendor_email" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_vendor_email" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-show-vendor-telephone"><span data-toggle="tooltip" title="<?php echo $help_vendor_telephone; ?>"><?php echo $entry_vendor_telephone; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_show_vendor_telephone) { ?>
                <input type="radio" name="mvd_show_vendor_telephone" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_vendor_telephone" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_show_vendor_telephone) { ?>
                <input type="radio" name="mvd_show_vendor_telephone" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_show_vendor_telephone" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>	  
        </div>
        <div class="tab-pane" id="tab-catalog">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-product-approval"><span data-toggle="tooltip" title="<?php echo $help_vendor_product_approval; ?>"><?php echo $entry_vendor_product_approval; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_product_approval) { ?>
                <input type="radio" name="mvd_product_approval" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_product_approval" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_product_approval) { ?>
                <input type="radio" name="mvd_product_approval" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_product_approval" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-product-edit-approval"><span data-toggle="tooltip" title="<?php echo $help_vendor_product_edit_approval; ?>"><?php echo $entry_vendor_product_edit_approval; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_product_edit_approval) { ?>
                <input type="radio" name="mvd_product_edit_approval" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_product_edit_approval" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_product_edit_approval) { ?>
                <input type="radio" name="mvd_product_edit_approval" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_product_edit_approval" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-bulk-products-activation"><span data-toggle="tooltip" title="<?php echo $help_bulk_products_activation; ?>"><?php echo $entry_bulk_products_activation; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_bulk_products_activation) { ?>
                <input type="radio" name="mvd_bulk_products_activation" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_bulk_products_activation" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_bulk_products_activation) { ?>
                <input type="radio" name="mvd_bulk_products_activation" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_bulk_products_activation" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tab"><span data-toggle="tooltip" title="<?php echo $help_vendor_tab; ?>"><?php echo $entry_vendor_tab; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_vendor_tab) { ?>
                <input type="radio" name="mvd_vendor_tab" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_vendor_tab" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_vendor_tab) { ?>
                <input type="radio" name="mvd_vendor_tab" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_vendor_tab" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-design-tab"><span data-toggle="tooltip" title="<?php echo $help_desgin_tab; ?>"><?php echo $entry_desgin_tab; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_desgin_tab) { ?>
                <input type="radio" name="mvd_desgin_tab" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_desgin_tab" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_desgin_tab) { ?>
                <input type="radio" name="mvd_desgin_tab" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_desgin_tab" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-reward-points"><span data-toggle="tooltip" title="<?php echo $help_reward_points; ?>"><?php echo $entry_reward_points; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_reward_points) { ?>
                <input type="radio" name="mvd_reward_points" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_reward_points" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_reward_points) { ?>
                <input type="radio" name="mvd_reward_points" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_reward_points" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-category-menu"><span data-toggle="tooltip" title="<?php echo $help_category_menu; ?>"><?php echo $entry_category_menu; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_category_menu) { ?>
                <input type="radio" name="mvd_category_menu" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_category_menu" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_category_menu) { ?>
                <input type="radio" name="mvd_category_menu" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_category_menu" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-menu-bar"><span data-toggle="tooltip" title="<?php echo $help_menu_bar; ?>"><?php echo $entry_menu_bar; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_menu_bar) { ?>
                <input type="radio" name="mvd_menu_bar" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_menu_bar" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_menu_bar) { ?>
                <input type="radio" name="mvd_menu_bar" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_menu_bar" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
		</div>
        <div class="tab-pane" id="tab-sales">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-invoice-address"><span data-toggle="tooltip" title="<?php echo $help_vendor_invoice_address; ?>"><?php echo $entry_vendor_invoice_address; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_sales_order_invoice_address) { ?>
                <input type="radio" name="mvd_sales_order_invoice_address" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_invoice_address" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_sales_order_invoice_address) { ?>
                <input type="radio" name="mvd_sales_order_invoice_address" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_invoice_address" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label></span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sales-order-detail"><span data-toggle="tooltip" title="<?php echo $help_order_detail; ?>"><?php echo $entry_order_detail; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_sales_order_detail) { ?>
                <input type="radio" name="mvd_sales_order_detail" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_detail" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_sales_order_detail) { ?>
                <input type="radio" name="mvd_sales_order_detail" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_detail" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sales-payment-detail"><span data-toggle="tooltip" title="<?php echo $help_payment_detail; ?>"><?php echo $entry_payment_detail; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_sales_payment_detail) { ?>
                <input type="radio" name="mvd_sales_payment_detail" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_payment_detail" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_sales_payment_detail) { ?>
                <input type="radio" name="mvd_sales_payment_detail" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_payment_detail" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sales-shipping-detail"><span data-toggle="tooltip" title="<?php echo $help_shipping_detail; ?>"><?php echo $entry_shipping_detail; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_sales_shipping_detail) { ?>
                <input type="radio" name="mvd_sales_shipping_detail" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_shipping_detail" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_sales_shipping_detail) { ?>
                <input type="radio" name="mvd_sales_shipping_detail" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_shipping_detail" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sales-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_sales_product) { ?>
                <input type="radio" name="mvd_sales_product" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_product" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_sales_product) { ?>
                <input type="radio" name="mvd_sales_product" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_product" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sales-order-history"><span data-toggle="tooltip" title="<?php echo $help_order_history; ?>"><?php echo $entry_order_history; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_sales_order_history) { ?>
                <input type="radio" name="mvd_sales_order_history" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_history" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_sales_order_history) { ?>
                <input type="radio" name="mvd_sales_order_history" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_history" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sales-order-history-update"><span data-toggle="tooltip" title="<?php echo $help_order_history_update; ?>"><?php echo $entry_order_history_update; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_sales_order_history_update) { ?>
                <input type="radio" name="mvd_sales_order_history_update" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_history_update" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_sales_order_history_update) { ?>
                <input type="radio" name="mvd_sales_order_history_update" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_history_update" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-sales-order-allow-notification"><span data-toggle="tooltip" title="<?php echo $help_allow_notification; ?>"><?php echo $entry_allow_notification; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($mvd_sales_order_allow_notification) { ?>
                <input type="radio" name="mvd_sales_order_allow_notification" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_allow_notification" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$mvd_sales_order_allow_notification) { ?>
                <input type="radio" name="mvd_sales_order_allow_notification" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="mvd_sales_order_allow_notification" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
			  </div>
			</div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-sales-order-status-availability"><span data-toggle="tooltip" title="<?php echo $help_order_status_availability; ?>"><?php echo $entry_order_status_availability; ?></span></label>
              <div class="col-sm-10">
                <div class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <div class="checkbox">
                    <label>
					<?php if ($mvd_sales_order_status_availability) { ?>
						  <?php if (in_array($order_status['order_status_id'], $mvd_sales_order_status_availability)) { ?>
							<input type="checkbox" name="mvd_sales_order_status_availability[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
							<?php echo $order_status['name']; ?>
						  <?php } else { ?>
							<input type="checkbox" name="mvd_sales_order_status_availability[]" value="<?php echo $order_status['order_status_id']; ?>" />
							<?php echo $order_status['name']; ?>
						  <?php } ?>
					  <?php } else { ?>
						<input type="checkbox" name="mvd_sales_order_status_availability[]" value="<?php echo $order_status['order_status_id']; ?>" /><?php echo $order_status['name']; ?>
					<?php } ?>
                    </label>
                  </div>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
              </div>
            </div>
        </div>
        <div class="tab-pane" id="tab-store">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-multi-store-activated"><span data-toggle="tooltip" title="<?php echo $help_multi_store_activated; ?>"><?php echo $entry_multi_store_activated; ?></span></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($mvd_store_activated) { ?>
                  <input type="radio" name="mvd_store_activated" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_store_activated" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$mvd_store_activated) { ?>
                  <input type="radio" name="mvd_store_activated" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_store_activated" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-multi-payment-gateway-status"><span data-toggle="tooltip" title="<?php echo $help_multi_payment_gateway; ?>"><?php echo $entry_multi_payment_gateway; ?></span></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($mvd_payment_gateway_status) { ?>
                  <input type="radio" name="mvd_payment_gateway_status" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_payment_gateway_status" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$mvd_payment_gateway_status) { ?>
                  <input type="radio" name="mvd_payment_gateway_status" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_payment_gateway_status" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label></div>
            </div>
        </div>   
        <div class="tab-pane" id="tab-notification">
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-product-notification"><span data-toggle="tooltip" title="<?php echo $help_product_notification; ?>"><?php echo $entry_product_notification; ?></span></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($mvd_product_notification) { ?>
                  <input type="radio" name="mvd_product_notification" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_product_notification" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$mvd_product_notification) { ?>
                  <input type="radio" name="mvd_product_notification" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_product_notification" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label></div>
            </div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-stock-threshold"><span data-toggle="tooltip" title="<?php echo $help_stock_threshold; ?>"><?php echo $entry_stock_threshold; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="mvd_stock_threshold" value="<?php echo isset($mvd_stock_threshold) ? $mvd_stock_threshold : '5'; ?>" class="form-control" />
              </div>
            </div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-multivendor-order-status"><span data-toggle="tooltip" title="<?php echo $help_multivendor_order_status; ?>"><?php echo $entry_multivendor_order_status; ?></span></label>
              <div class="col-sm-10">
                <select name="mvd_order_status" class="form-control">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $mvd_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
			</div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-vendor-checkout-order-status"><span data-toggle="tooltip" title="<?php echo $help_checkout_order_status; ?>"><?php echo $entry_checkout_order_status; ?></span></label>
              <div class="col-sm-10">
                <div class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <div class="checkbox">
                    <label>
					<?php if ($mvd_checkout_order_status) { ?>
						  <?php if (in_array($order_status['order_status_id'], $mvd_checkout_order_status)) { ?>
							<input type="checkbox" name="mvd_checkout_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
							<?php echo $order_status['name']; ?>
						  <?php } else { ?>
							<input type="checkbox" name="mvd_checkout_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
							<?php echo $order_status['name']; ?>
						  <?php } ?>
					  <?php } else { ?>
						<input type="checkbox" name="mvd_checkout_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" /><?php echo $order_status['name']; ?>
					<?php } ?>
                    </label>
                  </div>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
              </div>
            </div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-vendor-history-order-status"><span data-toggle="tooltip" title="<?php echo $help_history_order_status; ?>"><?php echo $entry_history_order_status; ?></span></label>
              <div class="col-sm-10">
                <div class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <div class="checkbox">
                    <label>
					<?php if ($mvd_history_order_status) { ?>
						  <?php if (in_array($order_status['order_status_id'], $mvd_history_order_status)) { ?>
							<input type="checkbox" name="mvd_history_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
							<?php echo $order_status['name']; ?>
						  <?php } else { ?>
							<input type="checkbox" name="mvd_history_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
							<?php echo $order_status['name']; ?>
						  <?php } ?>
					  <?php } else { ?>
						<input type="checkbox" name="mvd_history_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" /><?php echo $order_status['name']; ?>
					<?php } ?>
                    </label>
                  </div>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
              </div>
            </div>
        </div>
		<div class="tab-pane" id="tab-payment">
		  <fieldset>
            <legend><?php echo $text_paypal; ?></legend>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-sign-up-paypal-email"><span data-toggle="tooltip" title="<?php echo $help_signup_paypal_email; ?>"><?php echo $entry_signup_paypal_email; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="mvd_signup_paypal_email" value="<?php echo $mvd_signup_paypal_email; ?>" class="form-control" />
              </div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label" for="input-signup-paypal-sandbox"><span data-toggle="tooltip" title="<?php echo $help_paypal; ?>"><?php echo $entry_paypal_sandbox; ?></span></label>
                <div class="col-sm-10">
                  <select name="mvd_signup_paypal_sandbox" id="input-signup-paypal-sandbox" class="form-control">
                    <?php if ($mvd_signup_paypal_sandbox) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label" for="input-signup-paypal-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="mvd_paypal_status" id="input-signup-paypal-status" class="form-control">
					<?php if ($mvd_paypal_status) { ?>
					  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					  <option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					  <option value="1"><?php echo $text_enabled; ?></option>
					  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
                </div>
            </div>
	      </fieldset>
		  <fieldset>
            <legend><?php echo $text_bank; ?></legend>
			<?php foreach ($languages as $language) { ?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-bank<?php echo $language['language_id']; ?>"><?php echo $entry_bank_instruction; ?></label>
				<div class="col-sm-10">
				  <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
					<textarea name="mvd_bank_transfer_bank<?php echo $language['language_id']; ?>" cols="80" rows="10" placeholder="<?php echo $entry_bank_instruction; ?>" id="input-bank<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset(${'mvd_bank_transfer_bank' . $language['language_id']}) ? ${'mvd_bank_transfer_bank' . $language['language_id']} : ''; ?></textarea>
				  </div>
				</div>
			</div>
			<?php } ?>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-bank-order-status"><?php echo $entry_bank_order_status; ?></label>
              <div class="col-sm-10">
                <select name="mvd_bank_order_status" class="form-control">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $mvd_bank_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
			</div>
			<div class="form-group">
                <label class="col-sm-2 control-label" for="input-bank-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="mvd_bank_status" id="input-bank-status" class="form-control">
                    <?php if ($mvd_bank_status) { ?>
					  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					  <option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					  <option value="1"><?php echo $text_enabled; ?></option>
					  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
                  </select>
                </div>
            </div>
	      </fieldset>
        </div>
        <div class="tab-pane" id="tab-signup">
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-sign-up"><span data-toggle="tooltip" title="<?php echo $help_sign_up; ?>"><?php echo $entry_sign_up; ?></span></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($mvd_sign_up) { ?>
                  <input type="radio" name="mvd_sign_up" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_sign_up" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$mvd_sign_up) { ?>
                  <input type="radio" name="mvd_sign_up" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_sign_up" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label></div>
            </div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-sign-up-auto-approval"><span data-toggle="tooltip" title="<?php echo $help_vendor_approval; ?>"><?php echo $entry_vendor_approval; ?></span></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($mvd_signup_auto_approval) { ?>
                  <input type="radio" name="mvd_signup_auto_approval" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_signup_auto_approval" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$mvd_signup_auto_approval) { ?>
                  <input type="radio" name="mvd_signup_auto_approval" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_signup_auto_approval" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label></div>
            </div>
          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-sign-up-show-plan"><span data-toggle="tooltip" title="<?php echo $help_show_plan; ?>"><?php echo $entry_show_plan; ?></span></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($mvd_signup_show_plan) { ?>
                  <input type="radio" name="mvd_signup_show_plan" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_signup_show_plan" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$mvd_signup_show_plan) { ?>
                  <input type="radio" name="mvd_signup_show_plan" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="mvd_signup_show_plan" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label></div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label" for="input-bank-status"><?php echo $entry_signup_payment_method; ?></label>
                <div class="col-sm-10">
                  <select name="mvd_signup_default_payment_method" id="input-bank-status" class="form-control">
                    <?php if ($mvd_signup_default_payment_method) { ?>
					  <option value="0"><?php echo $text_bank; ?></option>
					  <option value="1" selected="selected"><?php echo $text_paypal; ?></option>
					<?php } else { ?>
					  <option value="0" selected="selected"><?php echo $text_bank; ?></option>
					  <option value="1"><?php echo $text_paypal; ?></option>
					<?php } ?>
                  </select>
                </div>
            </div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-sign-up-policy"><span data-toggle="tooltip" title="<?php echo $help_policy; ?>"><?php echo $entry_policy; ?></span></label>
              <div class="col-sm-10">
                <select name="mvd_signup_policy" class="form-control">
				  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($informations as $information) { ?>
                  <?php if ($information['information_id'] == $mvd_signup_policy) { ?>
                  <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
			</div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-sign-up-commission"><span data-toggle="tooltip" title="<?php echo $help_commission; ?>"><?php echo $entry_commission; ?></span></label>
              <div class="col-sm-10">
                <select name="mvd_signup_commission" class="form-control">
				  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php foreach ($signup_commissions as $commission) { ?>
					<?php if ($mvd_signup_commission == $commission['commission_id']) { ?>
					<option value="<?php echo $commission['commission_id']; ?>:<?php echo $commission['commission_type']; ?>:<?php echo $commission['product_limit_id']; ?>:<?php echo $commission['duration']; ?>" selected="selected">
					<?php if ($commission['commission_type'] == '0') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '1') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '2') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '3') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '4') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '5') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } ?>
					</option> 
					<?php } else { ?>
					<option value="<?php echo $commission['commission_id']; ?>:<?php echo $commission['commission_type']; ?>:<?php echo $commission['product_limit_id']; ?>:<?php echo $commission['duration']; ?>">
					<?php if ($commission['commission_type'] == '0') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '1') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '2') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '3') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '4') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } elseif ($commission['commission_type'] == '5') { ?>
						<?php echo $commission['commission_text']; ?>
					<?php } ?>
					</option> 
					<?php } ?>
				  <?php } ?>
                </select>
                </div>
			</div>
			<div class="form-group">
              <label class="col-sm-2 control-label" for="input-sign-up-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
              <div class="col-sm-10">
                <div class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($categories as $category) { ?>
                  <div class="checkbox">
                    <label>
					<?php if ($mvd_signup_category) { ?>
						  <?php if (in_array($category['category_id'], $mvd_signup_category)) { ?>
							<input type="checkbox" name="mvd_signup_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
							<?php echo $category['name']; ?>
						  <?php } else { ?>
							<input type="checkbox" name="mvd_signup_category[]" value="<?php echo $category['category_id']; ?>" />
							<?php echo $category['name']; ?>
						  <?php } ?>
					  <?php } else { ?>
						<input type="checkbox" name="mvd_signup_category[]" value="<?php echo $category['category_id']; ?>" /><?php echo $category['name']; ?>
					<?php } ?>
                    </label>
                  </div>
                  <?php } ?>
                </div><a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
              </div>
            </div>
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-sign-up-store"><span data-toggle="tooltip" title="<?php echo $help_store; ?>"><?php echo $entry_store; ?></span></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <div class="checkbox">
				<?php if ($mvd_signup_store) { ?>
                  <label>
                    <?php if (in_array(0, $mvd_signup_store)) { ?>
                    <input type="checkbox" name="mvd_signup_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="mvd_signup_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </label>
				<?php } else { ?>
				   <label>
					<input type="checkbox" name="mvd_signup_store[]" value="0" />
                    <?php echo $text_default; ?>
				   </label>
				<?php } ?>
                </div>
                <?php foreach ($stores as $store) { ?>
                <div class="checkbox">
				<?php if ($mvd_signup_store) { ?>
                  <label>
                    <?php if (in_array($store['store_id'], $mvd_signup_store)) { ?>
                    <input type="checkbox" name="mvd_signup_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="mvd_signup_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </label>
				<?php } else { ?>
				  <label>
					<input type="checkbox" name="mvd_signup_store[]" value="<?php echo $store['store_id']; ?>" />
					<?php echo $store['name']; ?>
				  </label>
				<?php } ?>  
                </div>
                <?php } ?>
              </div><a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
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