var error = true;

function delay(callback, ms) {
    var timer = 0;
    return function () {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}


var easy_cart = {
    'loading': function (speed) {

        // if (speed == 'fast') {
        //     $('#cart_table').css('opacity', 0)
        // }

        $('#cart_table').addClass('loading');
    },
    'loaded': function () {
        $('#cart_table').removeClass('loading');
    },
    'valid': false,
    validate: function () {

        var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], ' +
            '.checkout_form input[type=\'datetime-local\'], ' +
            '.checkout_form input[type=\'time\'], ' +
            '.checkout_form input[type=\'password\'], ' +
            '.checkout_form input[type=\'hidden\'], ' +
            '.checkout_form input[type=\'checkbox\']:checked, ' +
            '.checkout_form input[type=\'radio\']:checked, ' +
            '.checkout_form input[type=\'custom_field1\'], ' +
            '.checkout_form textarea, .checkout_form select').serialize();
        data += '&_shipping_method=' + jQuery('.checkout_form input[name=\'shipping_method\']:checked').prop('title') + '&_payment_method=' + jQuery('.checkout_form input[name=\'payment_method\']:checked').prop('title');

        easy_cart.valid = false;


        var validateProgress = $.ajax({
            url: '?route=checkout/checkout/validate',
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function () {

                if (validateProgress != null) {
                    validateProgress.abort();
                }

                $('#button-checkout-loading').toggle();
                $('#button-confirm').hide();
                $('#button-checkout').hide();

            },
            complete: function () {
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                $('#button-checkout-loading').toggle();
                $('#button-confirm').show();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {

                    $('#button-checkout').show();

                    error = true;
                    if (json['error']['warning']) {
                        $('.confirm_button').prepend('<div class="alert alert-danger">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }

                    json['error'][0]

                    var first_warning = '';
                    for (i in json['error']) {
                        if (!first_warning) first_warning = i;
                        $('[name="' + i + '"]').after('<div class="text-danger">' + json['error'][i] + '</div>');
                    }

                    var top_offset = 0;
                    if (first_warning) {
                        var page = $("html, body");
                        page.on("scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove", function () {
                            page.stop();
                        });

                        $([document.documentElement, document.body]).stop(); // To stop animation, if checkout pressed multiple times.
                        console.log(first_warning)

                        if ($('[name=' + first_warning + ']').first().length) {


                            top_offset = $('[name=' + first_warning + ']').first().offset().top - 120;
                            $([document.documentElement, document.body]).animate({
                                scrollTop: top_offset
                            }, 1000);
                        }
                    }

                } else {
                    easy_cart.valid = true;
                    easy_cart.confirm();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    },
    'confirm': function () {
        var data =
            $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], ' +
                '.checkout_form input[type=\'datetime-local\'], ' +
                '.checkout_form input[type=\'time\'], ' +
                '.checkout_form input[type=\'password\'], ' +
                '.checkout_form input[type=\'hidden\'], ' +
                '.checkout_form input[type=\'checkbox\']:checked, ' +
                '.checkout_form input[type=\'radio\']:checked, ' +
                '.checkout_form textarea, .checkout_form select').serialize();
        data += '&_shipping_method=' + jQuery('.checkout_form input[name=\'shipping_method\']:checked').prop('title') +
            '&_payment_method=' + jQuery('.checkout_form input[name=\'payment_method\']:checked').prop('title');


        if (easy_cart.valid) {

            $('#button-checkout-loading').toggle();
            $('#button-confirm').hide();

            $.ajax({
                url: '?route=checkout/checkout/confirm',
                type: 'post',
                data: data,
                success: function (html) {

                    jQuery(".payment").html(html);

                    $('#button-checkout').hide();
                    $('#button-checkout-loading').hide();

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });

        } else {
            // resetojam maksājumus logu
            // console.log('resetting payments');
            // jQuery(".payment").html('');
            // $('#button-checkout').show();
        }

    },
    remove: function (cart_id) {
        $('#cart_id_' + cart_id).val(0);

        this.edit_only();

        delay(function () {
            $('#cart_id_' + cart_id).closest('tr').remove();
        }, 100);

        // alert();
    },
    edit_only: function () {

        $('.alert').remove();
        easy_cart.loading('fast');

        var data = $('input[name^=quantity]').serialize();
        // data += '&_shipping_method=' + jQuery('.checkout_form input[name=\'shipping_method\']:checked').prop('title') + '&_payment_method=' + jQuery('.checkout_form input[name=\'payment_method\']:checked').prop('title');



        var edit_onlyProgress = $.ajax({
            url: '?route=checkout/checkout/edit_only',
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function () {
                $('#cart > button').button('loading');

                if (edit_onlyProgress != null) {
                    edit_onlyProgress.abort();
                }

            },
            success: function (json) {
                // Need to set timeout otherwise it wont update the total
                // setTimeout(function () {
                //     $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                // }, 100);
                $('.cart .cart-link').load('?route=common/cart/render');

                jQuery("#shipping_method_block").load('?route=checkout/checkout/shipping_method', function () {
                    shippingMethod.update();
                });


            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    validateCoupon: function () {

        // $(document).on('click', '#button-coupon', function () {
        $.ajax({
            url: 'index.php?route=checkout/checkout/validateCoupon',
            type: 'post',
            data: $('#coupon-content :input'),
            dataType: 'json',
            cache: false,
            beforeSend: function () {
                $('#button-coupon').prop('disabled', true);
                $('#button-coupon').after('<i class=" fa fa-spinner fa-spin "></i>');
            },
            complete: function () {
                $('#button-coupon').prop('disabled', false);
                $('#coupon-content .fa-spinner').remove();
            },
            success: function (json) {
                $('.alert').remove();
                $('html, body').animate({scrollTop: 0}, 'slow');
                if (json['success']) {
                    // location.reload();
                    shippingMethod.update();
                    $('#success-messages').prepend('<div class=" alert alert-success " style=" display:none;"><i class=" fa fa-check-circle "></i> ' + json['success'] + '</div>');
                    $('.alert-success').fadeIn('slow');
                } else if (json['error']) {
                    $('#warning-messages').prepend('<div class=" alert alert-danger " style=" display: none;"><i class=" fa fa-exclamation-circle "></i> ' + json['error']['warning'] + '</div>');

                    $('.alert-danger').fadeIn('slow');
                }
            },
        });
        // })

    }
}

var shippingMethodProgress = null;
var shippingMethod = {
    'update': function (method) {
        easy_cart.loading();
        shippingMethodProgress = $.ajax({
            url: '?route=checkout/checkout/shipping_method_set&shipping_method=' + method,
            type: 'POST',
            data: 'shipping_method=' + method,
            dataType: 'json',

            beforeSend: function () {
                if (shippingMethodProgress != null) {
                    shippingMethodProgress.abort();
                }
            },
            success: function (json) {
                // console.log('Shipping method set: '.method)
                // console.log(json.success);
                // delay(function () {
                //
                // }, 500);
                // Kāpēc te bija Delay?
                cartLoad(); /* load updated cart . */

                // jQuery(".payment-method").load('?route=checkout/checkout/payment_method');
                // alert(  ) ;
                // Jāatjauno arī maksājumu metodes, tikai - vispirsm saglabājam to, kas ieķeksēts.
                // paymentMethod.update(jQuery('.checkout_form input[vme=\'payment_method\']:checked').val());
                jQuery('.checkout_form input[name=\'payment_method\']:checked').trigger('change');


            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText)
            }
        });
    },
    'remove': function () {

    }
};

var paymentMethodProgress = null;
var paymentMethod = {
    'update': function (method) {
        easy_cart.loading();
        paymentMethodProgress = $.ajax({
            url: '?route=checkout/checkout/payment_method_set&payment_method=' + method,
            type: 'POST',
            data: 'payment_method=' + method,
            dataType: 'json',
            beforeSend: function () {
                if (paymentMethodProgress != null) {
                    paymentMethodProgress.abort();
                }
            },
            success: function (json) {
                // console.log('Payment method set: ' + method);
                // mums jāielādē maksājumu metodes, jo tās "mēdz" būt atkarīgas no piegādes veida!
                jQuery(".payment-method").load('?route=checkout/checkout/payment_method');
                jQuery(".payment").html('');
                cartLoad();
                // delay(function(){ cartLoad(); /* load updated cart . */ }, 400);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText)
            }
        });
    },
    'remove': function () {

    }
};


var cartLoad = function () {
    $('.alert').remove();

    jQuery("#cart_table").load('?route=checkout/checkout/cart', function () {
        easy_cart.loaded();

        if ($('#cart_table form').length > 0) {
            $('#button-checkout').show();
            $('#button-continue').hide();
        } else {
            $('#button-checkout').hide();
            $('#button-continue').show();
        }


    })
};


// Login
$(document).delegate('#button-login', 'click', function () {
    $.ajax({
        url: '?route=checkout/checkout/login_validate',
        type: 'post',
        data: $('.login-form :input'),
        dataType: 'json',
        beforeSend: function () {
            $('#button-login').button('loading');
        },
        complete: function () {
            $('#button-login').button('reset');
        },
        success: function (json) {
            $('.alert, .text-danger').remove();

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                $('.login-form .message').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('select[name=\'country_id\']').on('change', function () {
    $.ajax({
        url: '?route=checkout/checkout/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function () {
            $('select[name=\'country_id\']').after(' <i class="fa fa-spinner fa-spin"></i>');
        },
        complete: function () {
            $('.fa-spinner').remove();
        },
        success: function (json) {
            if (json['postcode_required'] == '1') {
                $('input[name=\'postcode\']').parent().parent().addClass('required');
            } else {
                $('input[name=\'postcode\']').parent().parent().removeClass('required');
            }

            jQuery("#shipping_method_block").load('?route=checkout/checkout/shipping_method');

            html = '<option value=""> -- select -- </option>';

            if (json['zone'] && json['zone'] != '') {
                for (i = 0; i < json['zone'].length; i++) {
                    html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                    if (json['zone'][i]['zone_id'] == 'zone_id') { //TODO '<php echo $zone_id; >'
                        html += ' selected="selected"';
                    }

                    html += '>' + json['zone'][i]['name'] + '</option>';
                }
            } else {
                html += '<option value="0" selected="selected">-- none --</option>';
            }

            $('select[name=\'zone_id\']').html(html).val("");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});


$('select[name=\'shipping_country_id\']').on('change', function () {
    $.ajax({
        url: '?route=checkout/checkout/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function () {
            $('select[name=\'country_id\']').after(' <i class="fa fa-spinner fa-spin"></i>');
        },
        complete: function () {
            $('.fa-spinner').remove();
        },
        success: function (json) {
            if (json['postcode_required'] == '1') {
                $('input[name=\'postcode\']').parent().parent().addClass('required');
            } else {
                $('input[name=\'postcode\']').parent().parent().removeClass('required');
            }

            html = '<option value=""> -- select -- </option>'; //TODO: <php echo $text_select; >

            if (json['zone'] && json['zone'] != '') {
                for (i = 0; i < json['zone'].length; i++) {
                    html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                    if (json['zone'][i]['zone_id'] == '') { //todo: '<php echo $zone_id; >'
                        html += ' selected="selected"';
                    }

                    html += '>' + json['zone'][i]['name'] + '</option>';
                }
            } else {
                html += '<option value="0" selected="selected">-- none --</option>'; //todo: <php echo $text_none; >
            }

            $('select[name=\'shipping_zone_id\']').html(html).val("");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});


$('select#omniva').on('change', function () {
    shippingMethod.update(this.value);
});


$('select[name=\'country_id\'], ' +
    'select[name=\'zone_id\'], ' +
    'select[name=\'shipping_country_id\'], ' +
    'select[name=\'shipping_zone_id\'], ' +
    'input[type=\'radio\'][name=\'payment_address\'], ' +
    'input[type=\'radio\'][name=\'shipping_address\']').on('change', function () {

    if (this.name == 'contry_id') jQuery("select[name=\'zone_id\']").val("");
    if (this.name == 'shipping_country_id') jQuery("select[name=\'shipping_zone_id\']").val("");

    jQuery(".shipping-method").load('?route=checkout/checkout/shipping_method',
        $('.checkout_form input[type=\'text\'], ' +
            '.checkout_form input[type=\'date\'], ' +
            '.checkout_form input[type=\'datetime-local\'], ' +
            '.checkout_form input[type=\'time\'], ' +
            '.checkout_form input[type=\'password\'], ' +
            '.checkout_form input[type=\'hidden\'], ' +
            '.checkout_form input[type=\'checkbox\']:checked, ' +
            '.checkout_form input[type=\'radio\']:checked, ' +
            'input[name=\'shipping_method\']:first, ' +
            '.checkout_form textarea, ' +
            '.checkout_form select'), function () {


            if (jQuery("input[name=\'shipping_method\']:first").length) {
                jQuery("input[name=\'shipping_method\']:first").attr('checked', 'checked').prop('checked', true).click();
            } else {
                jQuery("#cart_table").load('?route=checkout/checkout/cart', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select'));
            }
        });


    paymentMethod.update();
    console.log('Updating payment method ... ');
    // alert();
    // jQuery(".payment-method").load('?route=checkout/checkout/payment_method',
    //     function () {
    // jQuery("[name=\'payment_method\']").attr("checked", 'checked').prop('checked', true);

    // });
});


$('#shipping_method_block').on('change', 'input[type=radio][name=shipping_method],select.selected_sub_quote', function () {
    shippingMethod.update(this.value);
});

$('#payment_method_block').on('change', 'input[type=radio][name=payment_method]', function () {
    paymentMethod.update(this.value);
});


// $('body').delegate('[name=\'payment_method\']', 'change', function () {
$('.checkout_form').on('click', '#button-checkout', function () {

    // validate, AND confirm.
    easy_cart.validate();

});


/* Plus, Minus, Update - Edit */
jQuery(document).ready(function () {
    {
        // debugger;
        // increase number of product
        var minimum = 1;

        function minus(el) {
            var currentval = parseInt($(el).val());
            $(el).val(currentval - 1);
            if ($(el).val() <= 0 || $(el).val() < minimum) {
                alert("Minimum Quantity: " + minimum);
                $(el).val(minimum);
            }
        }


        // decrease of product
        function plus(el) {
            var currentval = parseInt($(el).val());
            $(el).val(currentval + 1);
        }


        $('.checkout_form').on('blur', '.box-input-qty input[name^=quantity]', function () {
            easy_cart.edit_only();
        });

        $(document).on('click', '#button-coupon', function (e) {
            e.preventDefault();
            easy_cart.validateCoupon();
        });


        $('.checkout_form').on('click', '.btn-minus input', function () {
            minus(document.getElementById($(this).data('for')));
            // delay(function () {
            //
            // }, 500);
            // Kāpēc te bija delay?
            easy_cart.edit_only();
        });

        $('.checkout_form').on('click', '.btn-plus input', function () {
            plus(document.getElementById($(this).data('for')));
            // delay(function () {
            //     easy_cart.edit_only();
            // }, 500);
            // Kāpēc te bija delay?
            easy_cart.edit_only();
        });


        $('#cart_table').on('click', '.btn-remove', function () {
            easy_cart.remove($(this).data('cart-id'));
        })


    }
});



