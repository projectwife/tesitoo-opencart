



<link rel="stylesheet" type="text/css" href="<?php echo $b; ?>catalog/view/theme/default/stylesheet/stripepro/common.css">
<script type="text/javascript" src="<?php echo $b; ?>catalog/view/javascript/stripepro/dbi.js" defer></script>
<?php if ($card_template == 'template1') { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $b; ?>catalog/view/theme/default/stylesheet/stripepro/template1.css">
<?php } elseif($card_template == 'template2') { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $b; ?>catalog/view/theme/default/stylesheet/stripepro/template2.css">
<?php } ?>


<?php if ($transaction_mode == 0) { ?>
<div class="alert alert-warning alert-testmode">
    Test mode enabled. You can use a <a target="_blank" href="https://stripe.com/docs/testing#cards">Stripe test card</a>
    <br/>
    For example, <i>4242 4242 4242 4242</i> or <i>4000 0000 0000 3220</i> to test 3D Secure 2 authentication.
    <br/><br/>

    <?php if ($mode == 'demo') { ?>
    For demo purpose, credit card input template is chosen <b>randomly</b>. Refresh the page to see different templates.
    <br/><br/>
    <?php } ?>

    Depending on your browser and device, Apple Pay, Google Pay or Microsoft Pay button can appear.
</div>
<?php } ?>



<?php if ($checkout_type == 'payment_form') { ?>

<div class="payment-types-tabs">
    <ul class="nav nav-tabs" role="tablist">
        <!-- Stored cards -->
        <?php if ($use_stored_cards == '1' && count($storedcards) > 0 ) { ?>
        <li class="nav-item custom-tab custom-tab-cards <?php echo $storedcard_tab; ?>">
            <a class="nav-link" id="storedcard-tab" data-value="storedcard" data-toggle="tab" href="#storedcard" role="tab" aria-controls="storedcard" aria-selected="true">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/cards2.png">
                <?php echo $text_storedcard; ?>
            </a>
        </li>
        <?php } ?>

        <!-- New card -->
        <?php if ($paymenttype_creditcards || $paymenttype_button) { ?>
        <li class="nav-item custom-tab custom-tab-cards <?php echo $creditcards_tab; ?>">
            <a class="nav-link" id="newcard-tab" data-value="newcard" data-toggle="tab" href="#newcard" role="tab" aria-controls="newcard" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/cards2.png">
                <?php echo $text_newcard; ?>
            </a>
        </li>
        <?php } ?>

        <!-- iDEAL -->
        <?php if ($paymenttype_ideal) { ?>
        <li class="nav-item custom-tab <?php echo $ideal_tab; ?>">
            <a class="nav-link" id="ideal-tab" data-value="ideal" data-toggle="tab" href="#ideal" role="tab" aria-controls="ideal" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/ideal.png">
            </a>
        </li>
        <?php } ?>

        <!-- Giropay -->
        <?php if ($paymenttype_giropay) { ?>
        <li class="nav-item custom-tab <?php echo $giropay_tab; ?>">
            <a class="nav-link" id="giropay-tab" data-value="giropay" data-toggle="tab" href="#giropay" role="tab" aria-controls="giropay" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/giropay.png">
            </a>
        </li>
        <?php } ?>

        <!-- Bancontact -->
        <?php if ($paymenttype_bancontact) { ?>
        <li class="nav-item custom-tab <?php echo $bancontact_tab; ?>">
            <a class="nav-link" id="bancontact-tab" data-value="bancontact" data-toggle="tab" href="#bancontact" role="tab" aria-controls="bancontact" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/bancontact.png">
            </a>
        </li>
        <?php } ?>

        <!-- EPS -->
        <?php if ($paymenttype_eps) { ?>
        <li class="nav-item custom-tab <?php echo $eps_tab; ?>">
            <a class="nav-link" id="eps-tab" data-value="eps" data-toggle="tab" href="#eps" role="tab" aria-controls="eps" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/eps.png">
            </a>
        </li>
        <?php } ?>

        <!-- P24 -->
        <?php if ($paymenttype_p24) { ?>
        <li class="nav-item custom-tab <?php echo $p24_tab; ?>">
            <a class="nav-link" id="p24-tab" data-value="p24" data-toggle="tab" href="#p24" role="tab" aria-controls="p24" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/p24.png">
            </a>
        </li>
        <?php } ?>

        <!-- Multibanco -->
        <?php if ($paymenttype_multibanco) { ?>
        <li class="nav-item custom-tab <?php echo $multibanco_tab; ?>">
            <a class="nav-link" id="multibanco-tab" data-value="multibanco" data-toggle="tab" href="#multibanco" role="tab" aria-controls="multibanco" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/multibanco.png">
            </a>
        </li>
        <?php } ?>

        <!-- Klarna -->
        <?php if ($paymenttype_klarna) { ?>
        <li class="nav-item custom-tab <?php echo $klarna_tab; ?>">
            <a class="nav-link" id="klarna-tab" data-value="klarna" data-toggle="tab" href="#klarna" role="tab" aria-controls="klarna" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/klarna.png">
            </a>
        </li>
        <?php } ?>

        <!-- WeChat -->
        <?php if ($paymenttype_wechat) { ?>
        <li class="nav-item custom-tab <?php echo $wechat_tab; ?>">
            <a class="nav-link" id="wechat-tab" data-value="wechat" data-toggle="tab" href="#wechat" role="tab" aria-controls="wechat" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/wechat.png">
            </a>
        </li>
        <?php } ?>

        <!-- Alipay -->
        <?php if ($paymenttype_alipay) { ?>
        <li class="nav-item custom-tab <?php echo $alipay_tab; ?>">
            <a class="nav-link" id="alipay-tab" data-value="alipay" data-toggle="tab" href="#alipay" role="tab" aria-controls="alipay" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/alipay.png">
            </a>
        </li>
        <?php } ?>

        <!-- FPX -->
        <?php if ($paymenttype_fpx) { ?>
        <li class="nav-item custom-tab <?php echo $fpx_tab; ?>">
            <a class="nav-link" id="fpx-tab" data-value="fpx" data-toggle="tab" href="#fpx" role="tab" aria-controls="fpx" aria-selected="false">
                <img src="<?php echo $b; ?>catalog/view/theme/default/image/logos/fpx.png">
            </a>
        </li>
        <?php } ?>
    </ul>


    <div class="tab-content" id="stripeproTabContent">
        <!-- Stored cards -->
        <?php if ($use_stored_cards == '1' && count($storedcards) > 0 ) { ?>
        <div class="tab-pane <?php echo $storedcard_tab; ?>" id="storedcard" role="tabpanel" aria-labelledby="home-tab">
            <select id="selectStoredcards" class="form-control">
                <?php foreach ($storedcards as $card) { ?>
                <option value="<?php echo $card['id']; ?>"><?php echo $card['brand']; ?> ****<?php echo $card['last4']; ?> (<?php echo $card['exp_month']; ?>/<?php echo $card['exp_year']; ?>)</option>
                <?php } ?>
            </select>
        </div>
        <?php } ?>

        <!-- New card -->
        <?php if ($paymenttype_creditcards or $paymenttype_button) { ?>
        <div class="tab-pane <?php echo $creditcards_tab; ?>" id="newcard" role="tabpanel" aria-labelledby="profile-tab">
            <div class="payment-form-wrapper">
                <form id="payment-form">
                    <?php if ($paymenttype_button) { ?>
                    <div id="payment-request-button"></div>
                    <?php } ?>

                    <?php if ($paymenttype_creditcards) { ?>
                    <?php if ($card_template == 'template1') { ?>
                    <div class="cell example example1" id="example-1">
                        <fieldset>
                            <legend class="card-only" data-tid="elements_examples.form.pay_with_card"><?php echo $text_enter_card_detail; ?></legend>
                            <legend class="payment-request-available" data-tid="elements_examples.form.enter_card_manually"><?php echo $text_or_enter_card_detail; ?></legend>
                            <div class="container">
                                <div id="example1-card"></div>
                            </div>
                        </fieldset>

                        <div class="error" role="alert"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                                <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                            </svg>
                            <span class="message"></span>
                        </div>
                    </div>
                    <?php } elseif($card_template == 'template2') { ?>
                    <div class="cell example example2" id="example-2">
                        <div class="form">
                            <div class="row">
                                <div class="field">
                                    <div id="example2-card-number" class="input empty"></div>
                                    <label for="example2-card-number" data-tid="elements_examples.form.card_number_label"><?php echo $text_card_number; ?></label>
                                    <div class="baseline"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field half-width">
                                    <div id="example2-card-expiry" class="input empty"></div>
                                    <label for="example2-card-expiry" data-tid="elements_examples.form.card_expiry_label"><?php echo $text_expiration; ?></label>
                                    <div class="baseline"></div>
                                </div>
                                <div class="field half-width">
                                    <div id="example2-card-cvc" class="input empty"></div>
                                    <label for="example2-card-cvc" data-tid="elements_examples.form.card_cvc_label"><?php echo $text_cvc; ?></label>
                                    <div class="baseline"></div>
                                </div>
                            </div>

                            <div class="error" role="alert"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                                    <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                                </svg>
                                <span class="message"></span>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($use_stored_cards == '1') { ?>
                    <div class="form-check save-card-checkbox-wrapper">
                        <input type="checkbox" class="form-check-input" id="saveCardCheckbox">
                        <label class="form-check-label" for="saveCardCheckbox"><?php echo $text_save_card; ?></label>
                    </div>
                    <?php } ?>


                    <?php } ?>
                </form>

                <?php if ($paymenttype_creditcards) { ?>
                <div class="success-stripe">
                    <div class="icon">
                        <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                        </svg>
                    </div>
                </div>
                <?php } ?>
            </div>
            <input id="clientSecret" type="hidden" value="<?php echo $intent['client_secret']; ?>">
            <input id="paymentIntent" type="hidden" value="<?php echo $intent['id']; ?>">
        </div>
        <?php } ?>

        <!-- iDEAL -->
        <?php if ($paymenttype_ideal) { ?>
        <div class="tab-pane <?php echo $ideal_tab; ?>" id="ideal" role="tabpanel" aria-labelledby="ideal-tab">
        </div>
        <?php } ?>

        <!-- Giropay -->
        <?php if ($paymenttype_giropay) { ?>
        <div class="tab-pane <?php echo $giropay_tab; ?>" id="giropay" role="tabpanel" aria-labelledby="giropay-tab">
        </div>
        <?php } ?>

        <!-- Bancontact -->
        <?php if ($paymenttype_bancontact) { ?>
        <div class="tab-pane <?php echo $bancontact_tab; ?>" id="bancontact" role="tabpanel" aria-labelledby="bancontact-tab">
        </div>
        <?php } ?>

        <!-- EPS -->
        <?php if ($paymenttype_eps) { ?>
        <div class="tab-pane <?php echo $eps_tab; ?>" id="eps" role="tabpanel" aria-labelledby="eps-tab">
        </div>
        <?php } ?>

        <!-- P24 -->
        <?php if ($paymenttype_p24) { ?>
        <div class="tab-pane <?php echo $p24_tab; ?>" id="p24" role="tabpanel" aria-labelledby="p24-tab">
        </div>
        <?php } ?>

        <!-- Multibanco -->
        <?php if ($paymenttype_multibanco) { ?>
        <div class="tab-pane <?php echo $multibanco_tab; ?>" id="multibanco" role="tabpanel" aria-labelledby="multibanco-tab">
        </div>
        <?php } ?>

        <!-- Klarna -->
        <?php if ($paymenttype_klarna) { ?>
        <div class="tab-pane <?php echo $klarna_tab; ?>" id="klarna" role="tabpanel" aria-labelledby="klarna-tab">
        </div>
        <?php } ?>

        <!-- WeChat -->
        <?php if ($paymenttype_wechat) { ?>
        <div class="tab-pane <?php echo $wechat_tab; ?>" id="wechat" role="tabpanel" aria-labelledby="wechat-tab">
            <div id="wechat-qrcode"></div>
            <div id="msg-wechat"><?php echo $msg_wechat; ?> <?php echo $amount_formatted_wechat; ?></div>
        </div>
        <?php } ?>

        <!-- Alipay -->
        <?php if ($paymenttype_alipay) { ?>
        <div class="tab-pane <?php echo $alipay_tab; ?>" id="alipay" role="tabpanel" aria-labelledby="alipay-tab">
        </div>
        <?php } ?>

        <!-- FPX -->
        <?php if ($paymenttype_fpx) { ?>
        <div class="tab-pane <?php echo $fpx_tab; ?>" id="fpx" role="tabpanel" aria-labelledby="fpx-tab">
            <div id="fpx-bank-element" class="fpx-wrapper"></div>
            <div id="fpx-error-message" style="display: none" role="alert">Please select a bank</div>
        </div>
        <?php } ?>

    </div>
</div>

<!-- Common Confirm Button -->
<div class="button-confirm-wrapper">
    <button type="button" id="button-confirm" class="btn-easystripe <?php echo $button_css_classes; ?>" style="<?php echo $button_styles; ?>"><?php echo $text_submit_payment; ?> <?php echo $amount_formatted; ?></button>
</div>

<!-- STRIPE CHECKOUT PAGE -->
<?php } else { ?>
<div class="payment-form-wrapper type-checkout">
    <form id="payment-form">
        <button type="button" id="button-confirm" class="button-checkout-page"><?php echo $text_submit_payment; ?> <?php echo $amount_formatted; ?></button>
    </form>
</div>
<?php } ?>


<?php if ($paymenttype_wechat) { ?>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js" integrity="sha384-3zSEDfvllQohrq0PHL1fOXJuC/jSOO34H46t6UQfobFOmxE5BpjjaIJY5F2/bMnU" crossorigin="anonymous"></script>
<?php } ?>

<style>
    <?php echo $custom_css; ?>
</style>

<script type="text/javascript">
    if (!window.Stripe) {
        $.getScript("https://js.stripe.com/v3/");
    }
</script>

<script type="text/javascript" src="<?php echo $b; ?>catalog/view/javascript/stripepro/index.js" ></script>


<!--Global vars-->
<script type="text/javascript">
    var stripe = null;
    var form = null;
    var elements = null;
    var _bird = null;
    var fpxBank = null;
    var billingInfo = JSON.parse('<?php print_r(json_encode($billing), false);?>');
    var wechatCurrency = '<?php echo $wechat_currency; ?>';
    var wechatAmount = '<?php echo $wechat_amount; ?>';
    var alipayCurrency = '<?php echo $alipay_currency; ?>';
    var alipayAmount = '<?php echo $alipay_amount; ?>';
    var amountEur = '<?php echo $amount_eur; ?>';
    var d19 = '<?php echo $d19; ?>';
    var orderId = '<?php echo $order_id; ?>';
    var klarnaAmount = '<?php echo $amount_klarna; ?>';
    var klarnaCurrency = '<?php echo $klarna_cur; ?>';
    var firstName = "<?php echo $first_name; ?>";
    var lastName = "<?php echo $last_name; ?>";
    var amount = '<?php echo $amount; ?>';
    var currency = '<?php echo $currency; ?>';
    var urlBase = '<?php echo $url_base; ?>';
    var b = '<?php echo $b; ?>';
    var successUrl = '<?php echo $success_url; ?>';
</script>


<script type="text/javascript">
    $('.btn-easystripe').click(function (e) {
        e.preventDefault();
        let method =  $('.nav-tabs li.active .nav-link').attr("data-value");
        if(method === undefined) {
            method =  $('.nav-tabs li .nav-link.active').attr("data-value");
        }

        const btn = $(this);
        if(method === 'storedcard') {
            btn.button('loading');
            btn.attr("disabled", true);
            btn.addClass('disabled');
            processStoredCard(urlBase);
        } else if(method === 'newcard') {
            processNewCard(urlBase);
        } else if(method === 'klarna') {
            btn.button('loading');
            btn.attr("disabled", true);
            btn.addClass('disabled');
            processSourcePaymentKlarna(klarnaAmount, klarnaCurrency, billingInfo, firstName, lastName, orderId, d19);
        } else if(method === 'wechat') {
            btn.button('loading');
            btn.attr("disabled", true);
            btn.addClass('disabled');
            processSourcePayment(method, wechatCurrency.toLowerCase(), wechatAmount, d19, orderId, billingInfo, urlBase);
        } else if(method === 'alipay') {
            btn.button('loading');
            btn.attr("disabled", true);
            btn.addClass('disabled');
            processSourcePayment('alipay', alipayCurrency.toLowerCase(), alipayAmount,  d19, orderId, billingInfo, urlBase);
        } else if(method === 'fpx') {
            if(fpxBank._empty) {
                $("#fpx-error-message").show();
                return;
            } else {
                $("#fpx-error-message").hide();
            }
            btn.button('loading');
            btn.attr("disabled", true);
            btn.addClass('disabled');
            processFPX(b, urlBase, fpxBank);
        } else if(method === 'multibanco') {
            btn.button('loading');
            btn.attr("disabled", true);
            btn.addClass('disabled');
            processSourcePaymentMultibanco(amountEur, orderId, billingInfo, successUrl, urlBase);
        } else {
            // bancontact, giropay, ideal, eps, p24
            btn.button('loading');
            btn.attr("disabled", true);
            btn.addClass('disabled');
            processSourcePayment(method, 'eur', amountEur, d19,  orderId, billingInfo, urlBase);
        }
    });
</script>


<!--Init Stripe-->
<script type="text/javascript">
    console.log('<?php echo $version; ?>');
    // if ($('.quick-checkout-payment').length) {
    //     $('.btn-easystripe').hide();
    //     $('.button-checkout-page').hide();
    // }

    function initStripeSafe() {
        console.log('===initStripeSafe');
        if (typeof initStripe !== "undefined") {
            initStripe('<?php echo $public_key; ?>');
        } else {
            setTimeout(initStripeSafe, 50);
        }
    }
    initStripeSafe();

    // Checkout Fixes
    $( document ).ready(function() {
        _bird = new window.BirdCheckout();
        console.log('module:', _bird.module);
        if(_bird.module === 'journal2') {
            const tabContent = $('#stripeproTabContent');
            const btnConfirm = $('.btn-easystripe');
            if(tabContent.length > 0) {
                tabContent[0].style.display = 'block';
            }
            if(btnConfirm.length > 0) {
                btnConfirm[0].style.display = 'none';
            }
        }
    });
    // end Fixes
</script>


<?php if ($checkout_type == 'payment_form' && $paymenttype_fpx) { ?>
<script type="text/javascript">
    function initFPXSafe() {
        stripe != null ? fpxBank = initFPX()
            : setTimeout(function() { initFPXSafe() }, 50);
    }
    initFPXSafe();
</script>
<?php } ?>

<!--Init elements, payment request button OR checkout page-->
<?php if ($checkout_type == 'payment_form') { ?>
<!--Init Elements-->
<?php if ($paymenttype_creditcards && $card_template == 'template1') { ?>
<script type="text/javascript" src="<?php echo $b; ?>catalog/view/javascript/stripepro/template1.js" ></script>
<?php } elseif ($paymenttype_creditcards and $card_template == 'template2') { ?>
<script type="text/javascript" src="<?php echo $b; ?>catalog/view/javascript/stripepro/template2.js" ></script>
<?php } ?>

<!--Init Payment Button-->
<?php if ($paymenttype_button) { ?>
<script type="text/javascript">
    function initPaymentRequestButtonSafe() {
        stripe != null ? initPaymentRequestButton(amount, currency, urlBase)
            : setTimeout(function() { initPaymentRequestButtonSafe() }, 50);
    }
    initPaymentRequestButtonSafe();
</script>
<?php } ?>

<?php } else { ?>
<script type="text/javascript">
    function initTypeCheckoutPageSafe() {
        stripe != null ? initTypeCheckoutPage(urlBase)
            : setTimeout(function() { initTypeCheckoutPageSafe() }, 50);
    }
    initTypeCheckoutPageSafe();
</script>
<?php } ?>