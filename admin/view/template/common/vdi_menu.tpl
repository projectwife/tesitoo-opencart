<ul id="menu">
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
    <ul>
      <li><a href="<?php echo $vdi_category; ?>"><?php echo $text_category; ?></a></li>
      <li><a href="<?php echo $vdi_product; ?>"><?php echo $text_product; ?></a></li>
      <li><a class="parent"><?php echo $text_attribute; ?></a>
        <ul>
          <li><a href="<?php echo $vdi_attribute; ?>"><?php echo $text_attribute; ?></a></li>
          <li><a href="<?php echo $vdi_attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
        </ul>
      </li>
      <li><a href="<?php echo $vdi_option; ?>"><?php echo $text_option; ?></a></li>
      <li><a href="<?php echo $vdi_download; ?>"><?php echo $text_download; ?></a></li>
      <li><a href="<?php echo $vdi_information; ?>"><?php echo $text_information; ?></a></li>
    </ul>
  </li>
  <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
    <ul>
      <li><a href="<?php echo $vdi_sale_order; ?>"><?php echo $text_order; ?></a></li>
	  <li><a href="<?php echo $vdi_coupon; ?>"><?php echo $text_coupon; ?></a></li>
      <li><a href="<?php echo $vdi_transaction; ?>"><?php echo $text_vendor_transaction; ?></a></li>
    </ul>
  </li>
  <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
    <ul>
        <li><a href="<?php echo $vdi_report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
        <li><a href="<?php echo $vdi_report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
    </ul>
  </li>
  <li id="reports"><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_vendor_personal; ?></span></a>
    <ul>
		<?php if ($expiration_date) { ?>
        <li><a href="<?php echo $vdi_contract_history; ?>"><?php echo $text_contract_history; ?></a></li>
		<?php } ?>
        <li><a href="<?php echo $vdi_update_vendor_profile; ?>"><?php echo $text_vendor_update_profile; ?></a></li>
		<li><a href="<?php echo $vdi_user_password; ?>"><?php echo $text_vendor_update_password; ?></a></li>
    </ul>
  </li>
</ul>
