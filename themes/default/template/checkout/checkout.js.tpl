//Payment_data JS
$(document).ready(function (e) {
    if ($('input[name=payment_method]:radio:checked').val() == 'bank_transfer') {
        $('#payer_data').show();
    } else {
        $('#payer_data').hide();
    }
    $('input[name=payment_method]').click(function () {
        if ($(this).val() == 'bank_transfer') {
            $('#payer_data').show();
        } else {
            $('#payer_data').hide();
            clear_payer_data();
            $('#address_2').val('');
        }
    });
    if ($('input[name=customer_group_id]:radio:checked').val() == '2') {
        $('#legal_person_div').show();
    } else {
        $('#legal_person_div').hide();
    }
    $('input[name=customer_group_id]').click(function () {
        if ($(this).val() == '2') {
            $('#legal_person_div').show();
        }
        if ($(this).val() == '1') {
            $('#legal_person_div').hide();
            clear_payer_data();
        }
    });

    function clear_payer_data() {
        $('#company_name').val('');
        $('#reg_num').val('');
        $('#vat_num').val('');
        $('#bank_name').val('');
        $('#bank_code').val('');
        $('#bank_account').val('');

    }

    // Shows the form label, in place of the placeholder, when a value has been entered into a field
    $('.field').each(function () {
        var parent = $(this),
            field = parent.find('input, select');
        // Focus: Show label
        field.focus(function () {
            parent.addClass('show-label');
            parent.addClass('show-border');
        });
        // Blur: Hide label if no value was entered (go back to placeholder)
        field.blur(function () {
            if (field.val() === '') {
                parent.removeClass('show-label');
            }
            parent.removeClass('show-border');
        });
        // Handles change without focus/blur action (i.e. form auto-fill)
        field.change(function () {
            if (field.val() !== '') {
                parent.addClass('show-label');
            } else {
                parent.removeClass('show-label');
            }

        });
    });
    // Add class no-selection class to select elements
    $('select').change(function () {
        var field = $(this);
        if (field.val() === '') {
            field.addClass('no-selection');
        } else {
            field.removeClass('no-selection');
        }
    });

});

var zone_id = <?php echo json_encode($zone_id); ?>;
//Shipping Loading.
$("[name='country_id']").change(function () {
    var selected_country = $("[name='country_id'] :selected").val();
    $.ajax({
        url: 'index.php?route=checkout/shipping_method/getZonesByCountryId',
        type: 'post',
        data: {country_id: selected_country},
        dataType: 'json',
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function (data) {
            $('.warning').remove();
            $('#shipping_method_zone').empty();

            var zone_html = '<option> -- Choose -- </option>';
            $.each(data, function (key, value) {
                if (zone_id == value.zone_id) {
                    zone_html += '<option selected="selected" value="' + value.zone_id + '">' + value.name + '</option>';
                } else {
                    zone_html += '<option value="' + value.zone_id + '">' + value.name + '</option>';
                }
            });
            $('#shipping_method_zone').html(zone_html);
            $('.zone').show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
//Shipping Loading.
$("[name='zone_id']").change(function () {
    var selected_country = $("[name='country_id'] :selected").val();
    var selected_zone = $("[name='zone_id'] :selected").val();
    var country_zone_group = $("[name='country_zone_group']").val();

    $.ajax({
        url: 'index.php?route=checkout/shipping_method',
        type: 'post',
        data: {country_id: selected_country, zone_id: selected_zone},
        dataType: 'html',
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function (data) {
            $('.warning').remove();
            $('#shipping-method').html(data);

            var shipping = {};
            var cart_total = '<?php echo $cart_total_value . '\n'; ?>'
            $('input[name=shipping_method]').click(function () {
                if ($(this).val() == 'country_zone.country_zone') {
                    $('input[name=country_zone_group]').val($(this).data('group'));
                    shipping = $('input[name="' + $(this).val() + '-' + $(this).data('group') + '"]').val();
                } else {
                    shipping = $('input[name="' + $(this).val() + '"]').val();

                }

                var order_shipping = shipping * 1;
                var order_total = cart_total * 1 + order_shipping * 1
                $('#order_shipping').html(order_shipping.toFixed(2) + ' €')
                $('#order_total').html(order_total.toFixed(2) + ' €')
                if ($(this).data('show-address')) {
                    $('#shipping_address').show()
                    $('#validate_address').val(1)
                } else {
                    $('#validate_address').val(0)
                    $('#shipping_address').hide();
                }
            });
            $('.selected-shipping-method').trigger("click");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$("[name='country_id']").trigger("change");
$("[name='zone_id']").trigger("change");

$('#tab-' + $('input[name=payment_method]:radio:checked').val()).show();
$('input[name=payment_method]').click(function () {
    $('#tabs > li').each(function () {
        $(this).hide();
    });
    $('#tab-' + $(this).val()).show();

});
var shipping = {}
$('#button-payment-method').on('click', function () {
    $('.wait').show();
});