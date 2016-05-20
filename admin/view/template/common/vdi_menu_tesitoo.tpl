<ul id="menu">
  <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <li id="product"><a href="<?php echo $vdi_product; ?>"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_product; ?></span></a></li>
  <li id="sale"><a href="<?php echo $vdi_sale_order; ?>"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_order; ?></span></a></li>
  <li id="personal"><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_vendor_personal; ?></span></a>
    <ul>
		<?php if ($expiration_date) { ?>
        <li><a href="<?php echo $vdi_contract_history; ?>"><?php echo $text_contract_history; ?></a></li>
		<?php } ?>
        <li><a href="<?php echo $vdi_update_vendor_profile; ?>"><?php echo $text_vendor_update_profile; ?></a></li>
		<li><a href="<?php echo $vdi_user_password; ?>"><?php echo $text_vendor_update_password; ?></a></li>
    </ul>
  </li>
</ul>
