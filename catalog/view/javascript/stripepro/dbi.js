// v0.2.0
(function(window) {
    function BirdCheckout() {
        this.detectModule = function detectModule() {
            let name;
            if (window.qc && window.qc.d_quickcheckout_store) {
                name = "qc_d"
            } else if (window.qc && (window.qc.PaymentMethod || window.qc.ShippingMethod)) {
                name = "qc_d_latency"
            } else if (window._QuickCheckout || window._QuickCheckoutData) {
                name = "journal3"
            } else if ($(".journal-checkout").length || window.Journal) {
                name = "journal2"
            } else if (window.validateShippingMethod || window.validatePaymentMethod) {
                name = "qc_msg"
            } else if (window.xcart) {
                name = "best_checkout"
            } else if ($("#onepagecheckout").length) {
                name = "onepagecheckout"
            } else if ($("#input-order-status").length && $("#input-store").length) {
                name = "oc_admin"
            } else if ($(".nicocheckout").length) {
                name = "nicocheckout"
            } else if (window.MPSHOPPINGCART) {
                name = "mpcheckout"
            } else if (window.Simplecheckout) {
                name = "simplecheckout"
            } else if (window.checkoutCustomer) {
                name = "custom"
            } else {
                name = "default_oc"
            }
            return name
        };
        this.module = this.detectModule();
    }

    window.BirdCheckout = BirdCheckout
})(window);