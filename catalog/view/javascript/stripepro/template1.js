function initTemplate() {
    'use strict';

    var elements = stripe.elements({
        fonts: [
            {
                cssSrc: "https://rsms.me/inter/inter.css"
            }
        ],
        locale: 'auto'
    });

    var card = elements.create("card", {
        hidePostalCode: true,
        style: {
            base: {
                color: "#32325D",
                fontWeight: 500,
                fontFamily: "Inter UI, Open Sans, Segoe UI, sans-serif",
                fontSize: "16px",
                fontSmoothing: "antialiased",

                "::placeholder": {
                    color: "#CFD7DF"
                }
            },
            invalid: {
                color: "#E25950"
            }
        }
    });

    card.mount("#example1-card");

    registerElements([card], "example1");
}


function initTemplateSafe() {
    stripe != null ? initTemplate()
        : setTimeout(function() { initTemplateSafe() }, 50);
}
initTemplateSafe();
