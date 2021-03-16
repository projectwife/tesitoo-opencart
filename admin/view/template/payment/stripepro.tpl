<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">

            <?php if ($mode == 'demo') { ?>
            <div class="pull-right">
                <button disabled data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <?php } else { ?>
            <div class="pull-right">
                <button type="submit" form="form-payment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <?php } ?>

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
        <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payment" class="form-horizontal">
            <ul class="nav nav-tabs">
                <li class="nav-item active">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#tabGeneral" role="tab" aria-controls="home" aria-selected="true">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="stripe-tab" data-toggle="tab" href="#tabStripe" role="tab" aria-controls="profile" aria-selected="false">Stripe Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="paymentMethods-tab" data-toggle="tab" href="#tabPaymentMethods" role="tab" aria-controls="contact" aria-selected="false">Payment Methods</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="template-tab" data-toggle="tab" href="#tabTemplate" role="tab" aria-controls="contact" aria-selected="false">Template</a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- @@@@@ GENERAL TAB @@@@@ -->
                <div class="tab-pane fade active in" id="tabGeneral" role="tabpanel" aria-labelledby="general-tab">
                    <?php echo $html_tab1; ?>

                    <!-- License key -->
                    <?php if ($mode != 'demo') { ?>
                    <div class="form-group license-form-group">
                        <label class="col-sm-3 control-label" for="input-license_key">License Key</label>

                        <div class="col-sm-6">
                            <input type="hidden" name="<?php echo $storePrefix; ?>license_is_activated"
                                   value="0" id="input-license_is_activated"/>

                            <?php if (strlen($license_key) == 0) { ?>
                            <input type="text" name="<?php echo $storePrefix; ?>license_key" value="<?php echo $license_key; ?>"
                                   placeholder="License Key" id="input-license_key" class="form-control"/>
                            <?php } else { ?>
                            <input type="hidden" name="<?php echo $storePrefix; ?>license_key" value="<?php echo $license_key; ?>"
                                   id="input-license_key"/>
                            <div class="license-key-text">
                                <?php echo $license_key; ?>
                                <span class="text-danger" id="licenseExpired">Expired. Please renew your license (link is below)</span>
                                <span class="text-success" id="licenseActive">Active</span>
                                <span class="text-danger" id="licenseInvalid">Invalid. Please purchase a license (link is below)</span>
                            </div>
                            <?php } ?>

                            <?php if ($error_license) { ?>
                            <div class="text-danger"><?php echo $error_license; ?></div>
                            <?php } ?>
                            <div id="licenseError"></div>
                        </div>
                        <div class="col-sm-3">
                            <?php if (strlen($license_key) == 0) { ?>
                            <button class="btn btn-primary" id="btnActivateLicense">Activate</button>
                            <?php } else { ?>
                            <button class="btn btn-danger" id="btnRemoveLicense">Remove key</button>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group license-form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">To get or renew the license, click <a href="https://digital-bird.com/charge/license-stripepro" target="_blank">here</a></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-4">For any questions: <a href="mailto:hello@digital-bird.com?subject=EasyStripe Pro v.<?php echo $version; ?>">hello@digital-bird.com</a></div>
                    </div>
                </div>


                <!-- @@@@@ STRIPE TAB @@@@@ -->
                <div class="tab-pane fade" id="tabStripe" role="tabpanel" aria-labelledby="stripe-tab">
                    <?php echo $html_tab2; ?>
                </div>


                <!-- @@@@@ PAYMENT METHODS TAB @@@@@ -->
                <div class="tab-pane fade" id="tabPaymentMethods" role="tabpanel" aria-labelledby="paymentMethods-tab">
                    <?php echo $html_tab3; ?>
                </div>


                <!-- @@@@@ TEMPLATE TAB @@@@@ -->
                <div class="tab-pane fade" id="tabTemplate" role="tabpanel" aria-labelledby="template-tab">
                    <?php echo $html_tab4; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?php echo $footer; ?>

<script type="text/javascript">
    $('#btnActivateLicense').click(function(event) {
        event.preventDefault();

        var request = $.ajax({
            type: "POST",
            url: 'https://license.stripe-opencart.com/activate',
            // dataType: 'jsonp',
            data: {
                'key': $('#input-license_key').val(),
                'extension': 'oc_stripe_pro',
                'v': '<?php echo $version; ?>',
                'b': '<?php echo $b; ?>'
            }
        });
        request.done(function (response, textStatus, jqXHR){
            if(response.success) {
                $('#input-license_is_activated').val(1);
                $('#form-payment').submit();
            } else {
                $('#licenseError').text(response.msg);
                $('#licenseError').addClass('text-danger');
            }
        });
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(jqXHR);
            // console.error(textStatus);
            // console.error(errorThrown);
            $('#licenseError').text('Error. Status ' + jqXHR.status);
            $('#licenseError').addClass('text-danger');
        });
    });

    $('#btnRemoveLicense').click(function(event) {
        event.preventDefault();
        $('#input-license_key').val('');

        $('#form-payment').submit();
    });


    var request = $.ajax({
        type: "GET",
        url: 'https://license.stripe-opencart.com/verify?key=' + '<?php echo $license_key; ?>' + '&extension=oc_stripe_pro&v=<?php echo $version; ?>&b=<?php echo $b; ?>'
    });
    request.done(function (response, textStatus, jqXHR){
        if(response.success) {
            if(response.expiration_date)
                $('#licenseActive').text('Active (expires ' + response.expiration_date + ')');
            else
                $('#licenseActive').text('Active');
            $('#licenseActive').show();
        } else if (response.type === 'expired') {
            $('#licenseExpired').show();
        } else {
            $('#licenseInvalid').show();
        }
    });
</script>


<style>
    #licenseExpired, #licenseActive, #licenseInvalid {
        display: none;
    }
    .license-key-text {
        padding-top: 9px;
    }
    .license-key-text span {
        margin-left: 20px;
    }
    .getNewLicenseLink {
        margin-left: 10px;
    }

    /* ------------------------------------------ */
    #container {
        background-color: white;
    }
    .help-text {
        color: #666;
        font-size: 11px;
        padding-top: 2px;
        font-weight: lighter;
    }
    /* ------------------------------------------ */
</style>

<script type="text/javascript">
    console.log('mode', '<?php echo $mode; ?>');
</script>

<?php if ($mode == 'demo') { ?>
<script type="text/javascript">
    $('#test_public').replaceWith( "<input type=\"text\" name=\"<?php echo $storePrefix; ?>test_public\" value=\"pk_test_**\" placeholder=\"Public Key (Test)\" id=\"test_public\" class=\"form-control\">" );
    $('#test_private').replaceWith( "<input type=\"text\" name=\"<?php echo $storePrefix; ?>test_private\" value=\"sk_test_**\" placeholder=\"Secret Key (Test)\" id=\"test_private\" class=\"form-control\">" );
    $('#live_public').replaceWith( "<input type=\"text\" name=\"<?php echo $storePrefix; ?>live_public\" value=\"pk_live_**\" placeholder=\"Public Key (Live)\" id=\"live_public\" class=\"form-control\">" );
    $('#live_private').replaceWith( "<input type=\"text\" name=\"<?php echo $storePrefix; ?>live_private\" value=\"sk_live_**\" placeholder=\"Secret Key (Live)\" id=\"live_private\" class=\"form-control\">" );
</script>
<?php } ?>