// v4.4.3
function initStripe(publicKey) {
    if (window.Stripe) {
        stripe = Stripe(publicKey);
    } else {
        setTimeout(function() { initStripe(publicKey) }, 50);
    }
}


function processNewCard(urlBase) {
    function triggerBrowserValidation() {
        // The only way to trigger HTML5 form validation UI is to fake a user submit event.
        const submit = document.createElement('input');
        submit.type = 'submit';
        submit.style.display = 'none';
        form.appendChild(submit);
        submit.click();
        submit.remove();
    }

    // Trigger HTML5 validation UI on the form if any of the inputs fail validation.
    let plainInputsValid = true;
    Array.prototype.forEach.call(form.querySelectorAll('input'), function(
        input
    ) {
        if (input.checkValidity && !input.checkValidity()) {
            plainInputsValid = false;
            return;
        }
    });
    if (!plainInputsValid) {
        triggerBrowserValidation();
        return;
    }

    // Show a loading screen...
    $('.btn-easystripe').hide();
    $('.save-card-checkbox-wrapper').hide();
    $('.payment-form-wrapper').addClass('submitting');

    const clientSecret = $('#clientSecret').val();
    stripe.handleCardPayment(
        clientSecret, elements[0], {
            payment_method_data: {
                billing_details: billingInfo
            }
        }
    ).then(function (result) {
        if (typeof triggerLoadingOff == 'function') triggerLoadingOff(); // Journal fix

        if (result.error) {
            console.log('---------->>');
            console.log(result);
            console.log('<<----------');
            // Inform the user if there was an error
            $('.error .message')[0].textContent = result.error.message;
            $('.payment-form-wrapper .error').addClass('visible');
            $('.payment-form-wrapper').removeClass('submitting');
            if (_bird.module !== 'journal2' && _bird.module !== 'journal3') {
                $('.btn-easystripe').show();
            }
            $('.save-card-checkbox-wrapper').show();
        } else {
            // The payment has succeeded. Display a success message.
            let paymentMethod = null;
            if($("#saveCardCheckbox").is(':checked')) {
                paymentMethod = result.paymentIntent.payment_method;
            }
            finishPayment(null, paymentMethod, urlBase);
        }
    });
}

function processStoredCard(urlBase) {
    let pm = $('#selectStoredcards').val();
    let clientSecret = $('#clientSecret').val();
    let pi = $('#paymentIntent').val();

    $.ajax({
        url: urlBase + '/intentPost',
        type: 'post',
        dataType: 'json',
        data: {
            'paymentIntent': pi
        },
        success: function(json) {
            if(!json['intent']) {
                console.log(json);
                alert('Error');
                return;
            }

            stripe.handleCardPayment(
                clientSecret, { payment_method: pm }
            ).then(function(result) {
                if (result.error) {
                    console.log(result);
                    alert('Error');
                } else {
                    finishPayment(null, null, urlBase);
                }
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            alert('Error');
        }
    });
}

function finishPayment(callback, paymentMethod, urlBase) {
    $.ajax({
        url: urlBase + '/send',
        type: 'post',
        dataType: 'json',
        data: {
            'paymentMethod': paymentMethod ? paymentMethod : ''
        },
        complete: function() {
        },
        success: function(json) {
            if (json['success']) {
                location = json['success'];
            }
            if(callback)    { callback('success'); }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            if(callback)    { callback('error'); }
        }
    });
}

function processSourcePaymentMultibanco(amount, orderId, billingInfo, successUrl, urlBase) {
    $.ajax({
        url: urlBase + '/multibancoPendingOrder',
        type: 'post',
        dataType: 'json',
        data: {'post': 1},
        complete: function() {
        },
        success: function(json) {
            // console.log('=======json', json);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log('=======error');
            console.log(xhr);
            console.log(xhr.responseText);
        }
    });

    stripe.createSource({
        type: 'multibanco',
        amount: parseInt(amount),
        currency: 'eur',
        owner: {
            name: billingInfo['name'],
            email: billingInfo['email'],
        },
        metadata: {
            order_id: orderId,
        },
        redirect: {
            return_url: successUrl,
        },
    }).then(function(result) {
        console.log('==> createSource res', result);
        if(result.error) {
            console.log(result);
            alert('Error');
        } else {
            location = result.source['redirect']['url']
        }
    });
}

function processSourcePayment(type, currency, amount, d19, orderId, billingInfo, urlBase) {
    stripe.createSource({
        type: type,
        amount: parseInt(amount),
        currency: currency,
        owner: {
            name: billingInfo['name'],
            email: billingInfo['email'],
        },
        metadata: {
            order_id: orderId,
        },
        redirect: {
            return_url: d19
        },
    }).then(function(result) {
        console.log('==> createSource res', result);
        if(result.error) {
            alert(result.error.message);
        }
        const sourceId = result.source.id;
        const mode = result.source.livemode ? 'live' : 'test';

        if(result.error) {
            console.log(result);
            alert('Error');
        } else if(result.source['type'] === 'wechat') {
            // Display the QR code.
            const qrCode = new QRCode('wechat-qrcode', {
                text: result.source['wechat']['qr_code_url'],
                width: 128,
                height: 128,
                colorDark: '#424770',
                colorLight: '#f8fbfd',
                correctLevel: QRCode.CorrectLevel.H,
            });

            $('#msg-wechat').show();
            // Check the source status
            var isCheckSourceStatus = true;
            setInterval(function() {
                if(!isCheckSourceStatus) {
                    return;
                }

                // console.log('checking...', sourceId);
                $.ajax({
                    url: urlBase + `/checkStripeSource&sourceId=${sourceId}&mode=${mode}`,
                    type: 'get',
                    complete: function() {
                    },
                    success: function(json) {
                        const obj = JSON.parse(json);
                        console.log('returned', obj.status);
                        if(obj.status === 'consumed') {
                            isCheckSourceStatus = false
                            // Call server, create order and rredirect
                            finishPayment(null, null, urlBase);
                        } else if(obj.status === 'failed') {
                            // Redirect to failure page
                            isCheckSourceStatus = false
                            location = "/index.php?route=checkout/failure";
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log('error...');
                        console.log(xhr, ajaxOptions, thrownError);
                        console.log(xhr.responseText);
                        if(callback)    { callback('error'); }
                    }
                });
            }, 5000);

        } else {
            location = result.source['redirect']['url']
        }
    });
}

function initFPX() {
    const elements = stripe.elements();
    const style = {
        base: {
            padding: '10px 12px',
            color: '#32325d',
            fontSize: '16px',
        },
    };
    const fpxBank = elements.create('fpxBank', {
        style: style,
        accountHolderType: 'individual',
        });
    fpxBank.mount('#fpx-bank-element');
    return fpxBank;
}

function processFPX(b, urlBase, fpxBank) {
    $.ajax({
        url: urlBase + '/createFPXintent',
        type: 'post',
        dataType: 'json',
        // data: {'': ''},
        complete: function() {
        },
        success: function(json) {
            const clientSecret = json['client_secret'];
            stripe.confirmFpxPayment(clientSecret, {
                payment_method: {
                    fpx: fpxBank,
                },
                return_url: b + urlBase + '/finishFPX',
            }).then((result) => {
                console.log('== result:', result);
                if (result.error) {}
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log('FPX error');
            console.log(xhr.responseText);
        }
    });
}

function processSourcePaymentKlarna(klarnaAmount, klarnaCurrency, billingInfo, firstName, lastName, orderId, d19) {
    stripe.createSource({
        flow: 'redirect',
        type: 'klarna',
        amount: parseInt(klarnaAmount),
        currency: klarnaCurrency,
        klarna: {
            product: 'payment',
            purchase_country: billingInfo['address']['country'],
            first_name: firstName,
            last_name: lastName
        },
        source_order: {
            items: [{
                type: 'sku',
                description: 'Order total',
                quantity: 1,
                currency: klarnaCurrency,
                amount: parseInt(klarnaAmount)
            }]
        },
        owner: {
            email: billingInfo['email'],
            address: {
                line1: billingInfo['address']['line1'],
                line2: billingInfo['address']['line2'],
                city: billingInfo['address']['city'],
                state: billingInfo['address']['state'],
                postal_code: billingInfo['address']['postal_code'],
                country: billingInfo['address']['country']
            }
        },
        metadata: {
            order_id: orderId,
        },
        redirect: {
            return_url: d19
        }
    }).then(function(result) {
        console.log(result);
        if(result.error) {
            console.log(result);
            alert('Error');
        } else {
            // TODO Klarna doesnt have pay_now_redirect_url field
            // location = result.source['klarna']['pay_now_redirect_url']
            location = result.source['redirect']['url']
        }
    });
}

function initTypeCheckoutPage(urlBase) {
    console.log('initTypeCheckoutPage');
    $('.button-checkout-page').click(function(event) {
        $.ajax({
            url: urlBase + '/sessionPost',
            type: 'post',
            dataType: 'json',
            data: {},
            success: function(json) {
                console.log(json);
                if(json['error']) {
                    console.log(json);
                    alert('Error sessionPost');
                    return;
                }
                stripe.redirectToCheckout({
                    sessionId: json['session_id']
                }).then(function (result) {
                    console.log('error::', result);
                    alert(result.error.message);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
                alert('Error sessionPost');
            }
        });
    });
}

function registerElements(elts, exampleName) {
    const formClass = '.' + exampleName;

    elements = elts;
    form = document.querySelector(formClass);
    const error = form.querySelector('.error');
    const errorMessage = error.querySelector('.message');


    // Listen for errors from each Element, and show error messages in the UI.
    let savedErrors = {};
    elements.forEach(function(element, idx) {
        element.on('change', function(event) {
            if (event.error) {
                error.classList.add('visible');
                savedErrors[idx] = event.error.message;
                errorMessage.innerText = event.error.message;
            } else {
                savedErrors[idx] = null;

                // Loop over the saved errors and find the first one, if any.
                const nextError = Object.keys(savedErrors)
                    .sort()
                    .reduce(function(maybeFoundError, key) {
                        return maybeFoundError || savedErrors[key];
                    }, null);

                if (nextError) {
                    // Now that they've fixed the current error, show another one.
                    errorMessage.innerText = nextError;
                } else {
                    // The user fixed the last error; no more errors.
                    error.classList.remove('visible');
                }
            }
        });
    });
}

function initPaymentRequestButton(amount, currency, urlBase) {
    var elements = stripe.elements();

    // Payment request button
    if ($('.quick-checkout-payment').length === 0) {
        var paymentRequest = stripe.paymentRequest({
            country: 'US',
            requestPayerName: true,
            requestPayerEmail: true,
            currency: currency.toLowerCase(),
            total: {
                label: 'Total',
                amount: parseInt(amount),
            }
        });
        paymentRequest.canMakePayment().then(function (result) {
            console.log('canMakePayment', result);
            if (result) {
                $(".card-only").hide();
                $(".payment-request-available").show();
                var prButton = elements.create('paymentRequestButton', {
                    paymentRequest: paymentRequest,
                });
                prButton.mount('#payment-request-button');
            } else {    // apple pay is not available
                document.getElementById('payment-request-button').style.display = 'none';
            }
        });

        paymentRequest.on('paymentmethod', function (ev) {
            var clientSecret = $('#clientSecret').val();
            stripe.confirmPaymentIntent(clientSecret, {
                payment_method: ev.paymentMethod.id,
            }).then(function (confirmResult) {
                console.log(confirmResult);
                if (confirmResult.error) {
                    ev.complete('fail');
                    $('.payment-form-wrapper').removeClass('submitting');
                } else {
                    ev.complete('success');
                    var paymentMethod = null;
                    if($("#saveCardCheckbox").is(':checked')) {
                        paymentMethod = confirmResult.paymentIntent.payment_method;
                    }
                    finishPayment(null, paymentMethod, urlBase);
                }
            });
        });
    }
}

