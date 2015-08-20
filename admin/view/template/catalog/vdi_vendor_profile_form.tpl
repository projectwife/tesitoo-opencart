<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-vendor" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-check-circle"></i></button>
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
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-vendor" class="form-horizontal">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <li><a href="#tab-address" data-toggle="tab"><?php echo $tab_address; ?></a></li>
        <li><a href="#tab-finance" data-toggle="tab"><?php echo $tab_finance; ?></a></li>
		<?php if ($mvd_store_activated) { ?><li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li><?php } ?>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-general">
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-company_id"><?php echo $entry_company_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="company_id" value="<?php echo $company_id; ?>" placeholder="<?php echo $entry_company_id; ?>" id="input-company_id" class="form-control" />
              </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax_id"><?php echo $entry_tax_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="tax_id" value="<?php echo $tax_id; ?>" placeholder="<?php echo $entry_tax_id; ?>" id="input-tax_id" class="form-control" />
              </div>
          </div>
		  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-10">
            <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
            <?php if ($error_vendor_firstname) { ?>
				<div class="text-danger"><?php echo $error_vendor_firstname; ?></div>
			<?php } ?>
			</div>
          </div>
		  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-10">
            <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
            <?php if ($error_vendor_lastname) { ?>
				<div class="text-danger"><?php echo $error_vendor_lastname; ?></div>
			<?php } ?>
			</div>
          </div>
		  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
            <div class="col-sm-10">
            <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
            <?php if ($error_vendor_telephone) { ?>
				<div class="text-danger"><?php echo $error_vendor_telephone; ?></div>
			<?php } ?>
			</div>
          </div>
		  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><span data-toggle="tooltip" title="<?php echo $help_email; ?>"><?php echo $entry_email; ?></span></label>
            <div class="col-sm-10">
            <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
            <?php if ($error_vendor_email) { ?>
				<div class="text-danger"><?php echo $error_vendor_email; ?></div>
			<?php } ?>
			</div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
            <div class="col-sm-10">
              <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
              </div>
          </div>
		  <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
			  <textarea name="vendor_description" rows="5" placeholder="<?php echo $entry_description; ?>" class="form-control"><?php echo $vendor_description; ?></textarea>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store_url"><?php echo $entry_store_url; ?></label>
            <div class="col-sm-10">
             <input type="text" name="store_url" value="<?php echo $store_url; ?>" placeholder="<?php echo $entry_store_url; ?>" id="input-store_url" class="form-control" />
            </div>
          </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_image; ?>"><?php echo $entry_image; ?></span></label>
			<div class="col-sm-10"><a href="" id="thumb-vendor_image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
			<input type="hidden" name="vendor_image" value="<?php echo $vendor_image; ?>" id="input-vendor_image" />
			</div>
		  </div>
        </div>
        <div class="tab-pane" id="tab-address">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address_1"><?php echo $entry_address_1; ?></label>
            <div class="col-sm-10">
            <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address_1" class="form-control" />
            <?php if ($error_vendor_address_1) { ?>
				<div class="text-danger"><?php echo $error_vendor_address_1; ?></div>
			<?php } ?>
			</div>
          </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="input-address_2"><?php echo $entry_address_2; ?></label>
            <div class="col-sm-10">
            <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address_2" class="form-control" />
			</div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-city"><?php echo $entry_city; ?></label>
            <div class="col-sm-10">
            <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
            <?php if ($error_vendor_city) { ?>
				<div class="text-danger"><?php echo $error_vendor_city; ?></div>
			<?php } ?>
			</div>
          </div>
		  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
            <div class="col-sm-10">
            <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
            <?php if ($error_vendor_postcode) { ?>
				<div class="text-danger"><?php echo $error_vendor_postcode; ?></div>
			<?php } ?>
			</div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
            <div class="col-sm-10">
			  <select name="country_id" id="input-country" class="form-control" onchange="$('select[name=\'zone_id\']').load('index.php?route=catalog/vdi_vendor_profile/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_vendor_country) { ?>
              <div class="text-danger"><?php echo $error_vendor_country; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
            <div class="col-sm-10">
              <select name="zone_id" id="input-zone" class="form-control">
              </select>
              <?php if ($error_vendor_zone) { ?>
              <div class="text-danger"><?php echo $error_vendor_zone; ?></div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-finance">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-paypal_email"><span data-toggle="tooltip" title="<?php echo $help_paypal_email; ?>"><?php echo $entry_paypal_email; ?></span></label>
            <div class="col-sm-10">
            <input type="text" name="paypal_email" value="<?php echo $paypal_email; ?>" placeholder="<?php echo $entry_paypal_email; ?>" id="input-paypal_email" class="form-control" />
            <?php if ($error_vendor_paypal_email) { ?>
				<div class="text-danger"><?php echo $error_vendor_paypal_email; ?></div>
			<?php } ?>
			</div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-iban"><?php echo $entry_iban; ?></label>
            <div class="col-sm-10">
            <input type="text" name="iban" value="<?php echo $iban; ?>" placeholder="<?php echo $entry_iban; ?>" id="input-iban" class="form-control" />
			</div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-bank_name"><?php echo $entry_bank_name; ?></label>
            <div class="col-sm-10">
            <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="<?php echo $entry_bank_name; ?>" id="input-bank_name" class="form-control" />
			</div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-bank_address"><?php echo $entry_bank_addr; ?></label>
            <div class="col-sm-10">
            <input type="text" name="bank_address" value="<?php echo $bank_address; ?>" placeholder="<?php echo $entry_bank_addr; ?>" id="input-bank_address" class="form-control" />
			</div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-swift_bic"><?php echo $entry_swift_bic; ?></label>
            <div class="col-sm-10">
            <input type="text" name="swift_bic" value="<?php echo $swift_bic; ?>" placeholder="<?php echo $entry_swift_bic; ?>" id="input-swift_bic" class="form-control" />
			</div>
          </div>
        </div>
		<?php if ($mvd_store_activated) { ?>
		<div class="tab-pane" id="tab-payment">
          <div class="form-group">
			<label class="col-sm-2 control-label" for="input-accept_paypal"><?php echo $entry_accept_paypal; ?></label>
			<div class="col-sm-10">
			  <select name="accept_paypal" id="input-accept_paypal" class="form-control">
				<?php if ($accept_paypal) { ?>
				<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_enabled; ?></option>
				<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-accept_cheques"><?php echo $entry_accept_cheques; ?></label>
			<div class="col-sm-10">
			  <select name="accept_cheques" id="input-accept_cheques" class="form-control">
				<?php if ($accept_cheques) { ?>
				<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_enabled; ?></option>
				<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-accept_bank_transfer"><?php echo $entry_accept_bank_transfer; ?></label>
			<div class="col-sm-10">
			  <select name="accept_bank_transfer" id="input-accept_bank_transfer" class="form-control">
				<?php if ($accept_bank_transfer) { ?>
				<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_enabled; ?></option>
				<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
        </div>
		<?php } ?>
      </div>
    </form>
	</div>
	</div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=catalog/vdi_vendor_profile/zone&token=<?php echo $token; ?>&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<?php echo $footer; ?>