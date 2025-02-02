;
(function ($, window, document) {
    'use strict';
    var paltechwpdevwpc_render = function () {
        paypal.Buttons({
            style: {
                layout: paltechwpdevwpc_param.layout,
                color: paltechwpdevwpc_param.color,
                shape: paltechwpdevwpc_param.shape,
                label: paltechwpdevwpc_param.label
            },
            createOrder: function (data, actions) {
                if (paltechwpdevwpc_param.page === 'product') {
                    $('.cart').block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
                    var get_attributes = function () {
                        var select = $('.variations_form').find('.variations select'),
                                data = {},
                                count = 0,
                                chosen = 0;
                        select.each(function () {
                            var attribute_name = $(this).data('attribute_name') || $(this).attr('name');
                            var value = $(this).val() || '';
                            if (value.length > 0) {
                                chosen++;
                            }
                            count++;
                            data[ attribute_name ] = value;
                        });
                        return {
                            'count': count,
                            'chosenCount': chosen,
                            'data': data
                        };
                    };
                    var data_param = {
                        'qty': $('.quantity .qty').val(),
                        'attributes': $('.variations_form').length ? JSON.stringify(get_attributes().data) : [],
                        'wc-paypal_express-new-payment-method': $("#wc-paypal_express-new-payment-method").is(':checked'),
                        'product_id': $('[name=add-to-cart]').val(),
                        'variation_id': $("input[name=variation_id]").val()
                    };
                    return fetch(paltechwpdevwpc_param.add_to_cart_ajaxurl, {
                        method: 'post',
                        body: JSON.stringify(data_param),
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (res) {
                        return res.json();
                    }).then(function (data) {
                        return data.orderID;
                    });
                } else if (paltechwpdevwpc_param.page === 'cart') {
                    $('.woocommerce').block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
                    return fetch(paltechwpdevwpc_param.set_checkout)
                            .then(function (res) {
                                return res.json();
                            }).then(function (data) {
                        return data.orderID;
                    });
                } else if (paltechwpdevwpc_param.page === 'checkout') {
                    
                    var data = $('#paltechwpdevwpc_paypal_button_' + paltechwpdevwpc_param.page).closest('form')
                            .add($('<input type="hidden" name="from_checkout" /> ')
                                    .attr('value', 'yes')
                                    )
                            .serialize();
                    return fetch(paltechwpdevwpc_param.set_checkout, {
                        method: 'POST',
                        body: data,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (res) {
                        return res.json();
                    }).then(function (data) {
                        return data.orderID;
                    });
                }
            },
            onApprove: function (data, actions) {
                if (paltechwpdevwpc_param.page === 'checkout') {
                    actions.order.capture().then(function (details) {
                        actions.redirect(paltechwpdevwpc_param.display_order_page + '&orderID=' + data.orderID + '&payment_id=' + details.id);
                    });
                } else {
                    actions.redirect(paltechwpdevwpc_param.get_checkout_details + '&orderID=' + data.orderID);
                }
            },
            onError: function (err) {
                window.location.reload();
            },
            onCancel: function (data) {
                window.location.replace(paltechwpdevwpc_param.cancel_url);
            }
        }).render('#paltechwpdevwpc_paypal_button_' + paltechwpdevwpc_param.page);
    };
    if (paltechwpdevwpc_param.page) {
        if (paltechwpdevwpc_param.page !== "checkout") {
            paltechwpdevwpc_render();
        }
        $(document.body).on('updated_cart_totals updated_checkout', paltechwpdevwpc_render.bind(this, false));
    }
    if (paltechwpdevwpc_param.page === "checkout") {
        $('form.checkout').on('click', 'input[name="payment_method"]', function () {
            var ispaltechwpdevwpc = $(this).is('#payment_method_paltechwpdevwpc_paypal_checkout');
            $('#place_order').toggle(!ispaltechwpdevwpc);
            $('#paltechwpdevwpc_paypal_button_checkout').toggle(ispaltechwpdevwpc);
        });
    }
    $('.variations_form').on('hide_variation', function () {
        $('#paltechwpdevwpc_paypal_button_product').hide();
    });
    $('.variations_form').on('show_variation', function () {
        $('#paltechwpdevwpc_paypal_button_product').show();
    });
})(jQuery, window, document);