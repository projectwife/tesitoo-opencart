<?php
// Heading
$_['heading_title']      = 'Add/Remove Product Email Notification';

// Text
$_['text_to']    		= 'Dear %s,';
$_['text_subject_add']  = 'New product %s submitted from %s';
$_['text_subject_edit'] = '%s edited the product %s';
$_['text_subject_approve'] = 'Your product has been approved: %s';
$_['text_subject_expire'] = 'Your account will expire in %s';

$_['text_message_add']  = 'The following product has been submitted for your approval:<br/>
Product: <b>%s</b><br/>
Vendor: <b>%s</b><br/>';
$_['text_message_edit'] = 'This is to inform you that product <b>%s</b> has been edited. Please review again!';
$_['text_message_approve'] = 'This is to inform you that product <b>%s</b> has been approved. It is now listed publicly at <a href="%s" target="_blank">%s<a/>.';
$_['text_message_expire'] = 'This is to inform you that your account will expire in <b>%s</b>.<br/> Please renew the contract as soon as possible to avoid account closed.<br/><br/>Kindly login to your account, go to Personal Detail -> Contract History -> Renew Contract to renew the contract.';

$_['text_thanks']    	= 'Thank You,';
$_['text_system']    	= '<span class="help">This is a system generated message. Please do not reply.</span>';


$_['text_subject_confirm_add'] = 'New product %s submission received.';
$_['text_subject_confirm_edit'] = 'Product %s edit received.';

$_['text_message_confirm_add'] = 'Thank you for submitting the new product <b>%s</b> to the store for review.<br/> You will receive a confirmation email shortly.';
$_['text_message_confirm_edit'] = 'Thank you for editing the product <b>%s</b>.<br/> It will be reviewed again shortly.';



$_['date_format_email'] = 'g:i A, F j, Y';


$_['text_subject_shipped'] = "Order shipped";
$_['text_subject_complete'] = "Order complete";
$_['text_subject_cancelled'] = "Order cancelled";
$_['text_subject_other_status'] = "Order status updated to: %s";
$_['text_subject_order_product_status_update']   = "Status update for order %d";

$_['text_message_shipped'] = "Shipping notice for order <b>%s</b><br/>\nProduct <b>%s</b> has been shipped at: <b>%s</b>, and shall be arriving within 5 working days.";
$_['text_message_complete'] = "Thank you for your order of product: <b>%s</b>. Your order is now complete. We hope you were satisfied with the transaction. If any problem occurred, please get in touch to let us know.";
$_['text_message_cancelled'] = "The vendor has cancelled the following product at: <b>%s</b><br/>\nProduct: <b>%s</b><br/>\nQuantity: <b>%s</b><br/>\nTotal: <b>%s</b><br/>\nNo further action is required.";
$_['text_message_other_status'] = "The status of your order has been updated. Product <b>%s</b> is now in status: <b>%s</b>.";

$_['text_message_admin_shipped'] = "Shipping notice for order <b>%s</b><br/>\nProduct <b>%s</b> has been shipped at: <b>%s</b> by vendor <b>%s</b>, and shall be arriving within 5 working days.";
$_['text_message_admin_complete'] = "Order <b>%d</b> - product: <b>%s</b> has been marked as complete by vendor <b>%s</b>.";
$_['text_message_admin_cancelled'] = "The vendor <b>%s</b> has cancelled the following product at: <b>%s</b><br/>\nProduct: <b>%s</b><br/>\nQuantity: <b>%s</b><br/>\nTotal: <b>%s</b><br/>\n";
$_['text_message_admin_other_status'] = "The vendor <b>%s</b> has updated the status of product: <b>%s</b> in order: <b>%d</b>. The status is now: <b>%s</b>.\n";

$_['text_message_order_product_status_update'] = "The status of the product: <b>%s</b> in your order %d has been changed to: %s.<br><br>";


$_['text_subject_password_reset_requested'] = "Password reset request";

$_['text_message_password_reset_requested'] = "A request has been received to reset the password of this account. Your new password is:<br>\n%s<br>\n"

?>
