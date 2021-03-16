<?php
$_['version']                   = '3.0.6(20)';
$_['heading_title']             = 'EasyStripe PRO v' . $_['version'];

// Text
$_['text_extension']            = 'Extension';
$_['text_success']              = 'Success: You have modified Stripe Payments details!';
$_['text_stripepro']            = '<a href="https://www.stripe.com/" target="_blank"><img src="view/image/payment/stripe-logo.png" alt="Stripe" title="Stripe" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_test']                 = 'Test';
$_['text_live']                 = 'Live';
$_['text_authorization']        = 'Authorization';
$_['text_checkout_page']        = 'Stripe Checkout Page';
$_['text_form']                 = 'Payment Methods';
$_['text_charge']               = 'Capture';
$_['text_authorize']            = 'Authorize only';
$_['text_card_template1']       = 'Template 1 (card fields on one line)';
$_['text_card_template2']       = 'Template 2 (card fields on two lines)';
$_['text_storedcard']           = 'Stored Card';


// Entry
$_['entry_status']                    = 'Status';
$_['entry_geo_zone_id']               = 'Geo Zone';
$_['entry_order_status_id']           = 'Order Status';
$_['entry_total']                     = 'Minimum Total';
$_['entry_test_public']               = 'Public Key (Test)';
$_['entry_test_private']              = 'Secret Key (Test)';
$_['entry_live_public']               = 'Public Key (Live)';
$_['entry_live_private']              = 'Secret Key (Live)';
$_['entry_sort_order']                = 'Sort Order';
$_['entry_checkout_type']             = 'Checkout Type';
$_['entry_transaction_mode']          = 'Transaction Mode';
$_['entry_button_text']               = 'Button Title';
$_['entry_button_css_classes']        = 'Button CSS Classes';
$_['entry_button_styles']             = 'Button CSS';
$_['entry_custom_css']                = 'Custom CSS';
$_['entry_charge_mode']               = 'Charge Mode';
$_['entry_title']                     = 'Payment Title';
$_['entry_transaction_description']   = 'Custom Transaction Description';
$_['entry_use_stored_cards']          = 'Allow Customers to Use Stored Cards';
$_['entry_card_template']             = 'Credit Card Template';
$_['entry_default_payment_method']    = 'Default Payment Method';


$_['entry_paymenttype_button']        = 'Payment Button';
$_['entry_paymenttype_bancontact']    = 'Bancontact';
$_['entry_paymenttype_creditcards']   = 'Debit & Credit cards';
$_['entry_paymenttype_giropay']       = 'Giropay';
$_['entry_paymenttype_ideal']         = 'iDEAL';
$_['entry_paymenttype_alipay']        = 'Alipay';
$_['entry_alipay_currency']           = 'Alipay Currency';
$_['entry_paymenttype_wechat']        = 'WeChat Pay';
$_['entry_wechat_currency']           = 'WeChat Pay Currency';
$_['entry_paymenttype_eps']           = 'EPS';
$_['entry_paymenttype_masterpass']    = 'Masterpass';
$_['entry_paymenttype_visacheckout']  = 'Visa Checkout';
$_['entry_paymenttype_p24']           = 'P24 (Przelewy24)';

$_['entry_paymenttype_multibanco']    = 'Multibanco';
$_['entry_multibanco_order_status_pending']    = 'Multibanco Pending Status';
$_['entry_paymenttype_klarna']        = 'Klarna';
$_['entry_paymenttype_fpx']           = 'FPX';
$_['entry_paymenttype_sofort']        = 'SOFORT';


// Help right
$_['helpright_paymenttype_creditcards']   = 'Visa, Mastercard, American Express, Discover, Diners Club, JCB, ... / <a target="_blank" href="https://stripe.com/en-fr/payments/payment-methods-guide#cards">Learn more</a>';
$_['helpright_paymenttype_button']        = 'Payment Button depends on the browser and platform. Possible buttons: <a target="_blank" href="https://stripe.com/apple-pay">Apple Pay</a>, <a target="_blank" href="https://stripe.com/docs/google-pay">Google Pay / Android Pay</a>, <a target="_blank" href="https://stripe.com/docs/microsoft-pay">Microsoft Pay</a>, <b>Chrome saved cards.</b> <br/><br/> Supporting Apple Pay requires <a target="_blank" href="https://stripe.com/docs/stripe-js/elements/payment-request-button#verifying-your-domain-with-apple-pay">additional steps</a>, but compatible devices automatically support browser-saved cards, Google Pay, and Microsoft Pay.';
$_['helpright_paymenttype_alipay']        = 'Before you can use Alipay, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_wechat']        = 'Before you can use WeChat, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_bancontact']    = 'Belgium<br/>Before you can use Bancontact, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_giropay']       = 'Germany<br/>Before you can use Giropay, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_ideal']         = 'The Netherlands<br/>Before you can use iDEAL, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_eps']           = 'Austria<br/> Before you can use EPS, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_p24']           = 'Poland<br/>Before you can use Przelewy24, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_multibanco']    = 'Portugal<br/>Before you can use Multibanco, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_klarna']        = 'Before you can use Klarna, you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>.';
$_['helpright_paymenttype_fpx']           = 'Malaysia<br/>Before you can use FPX (Financial Process Exchange), you must activate it on <a target="_blank" href="https://dashboard.stripe.com/account/payments/settings">Dashboard</a>. Your store should support MYR currency.';


// Help
$_['help_total']                = 'Payment is not active if the order amount less than this value. Can be empty (payment is always active)';
$_['help_sort_order']           = 'Position of the payment method in the payment methods list (0,1,2,10 etc). Can be empty';
$_['help_title']                = 'Name of the payment type in the payment methods list. Leave it empty to use the default value.';
$_['help_button_text']          = 'Confirmation button custom title. Leave it empty to use the default value.';
$_['help_button_css_classes']   = 'Custom CSS classes for the payment button';
$_['help_button_styles']        = 'Custom CSS for the payment button';
$_['help_custom_css']           = 'Custom CSS for the Payment Form';
$_['help_charge_mode']          = 'Capture payment: fully charge when orders are placed. Authorize: you will need to manually confirm each payment from Stripe Dashboard';
$_['help_checkout_type']        = 'Stripe Checkout Page redirects customer to a Stripe-hosted checkout page. Payment Methods are integrated into the OpenCart checkout page.';
$_['help_transaction_description']   = 'Custom text shown on Stripe Dashboard.<br/>Available variables: <b>{fullname}, {order_id}, {email}, {total}, {currency}</b>.<br/>Leave it empty for a default description: <b>Order #ID</b>".';
$_['help_default_payment_method']    = 'Payment method tab selected by default. The corresponding payment method must be enabled';
$_['help_multibanco_order_status_pending']    = 'Newly created order status (e.g. awaiting customer\'s payment)';

// Error
$_['error_permission']          = 'Warning: You do not have permission to modify payment: EasyStripe PRO Online Payments!';
$_['error_test_public']         = 'Public Key Required!';
$_['error_test_private']        = 'Secret Key Required!';
$_['error_live_public']         = 'Public Key Required!';
$_['error_live_private']        = 'Secret Key Required!';
$_['error_license']             = 'License is not valid!';
$_['error_activate_license']    = 'You must activate the license!';
$_['error_license_expired']     = 'Licence expired!';
