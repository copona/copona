var error = true;

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

// Register
$(document).delegate('#button-register', 'click', function () {

  var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select').serialize();
  data += '&_shipping_method=' + jQuery('.checkout_form input[name=\'shipping_method\']:checked').prop('title') + '&_payment_method=' + jQuery('.checkout_form input[name=\'payment_method\']:checked').prop('title');

  $.ajax({
    url: '?route=checkout/checkout/validate',
    type: 'post',
    data: data,
    dataType: 'json',
    beforeSend: function () {
      $('#button-register').button('loading');

    },
    complete: function () {
      $('#button-register').button('reset');
    },
    success: function (json) {
      $('.alert, .text-danger').remove();




      if (json['redirect']) {
        location = json['redirect'];
      } else if (json['error']) {
        error = true;

        if (json['error']['warning']) {
          $('#terms-id').prepend('<div class="alert alert-danger">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }

        for (i in json['error']) {
          $('[name="' + i + '"]').after('<div class="text-danger">' + json['error'][i] + '</div>');
        }
      } else {
        error = false;
        jQuery('[name=\'payment_method\']:checked').click();
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


$('select[name=\'country_id\'], select[name=\'zone_id\'], select[name=\'shipping_country_id\'], select[name=\'shipping_zone_id\'], input[type=\'radio\'][name=\'payment_address\'], input[type=\'radio\'][name=\'shipping_address\']').on('change', function () {
  if (this.name == 'contry_id') jQuery("select[name=\'zone_id\']").val("");
  if (this.name == 'shipping_country_id') jQuery("select[name=\'shipping_zone_id\']").val("");

  jQuery(".shipping-method").load('?route=checkout/checkout/shipping_method', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked,input[name=\'shipping_method\']:first, .checkout_form textarea, .checkout_form select'), function () {
    if (jQuery("input[name=\'shipping_method\']:first").length) {
      jQuery("input[name=\'shipping_method\']:first").attr('checked', 'checked').prop('checked', true).click();
    } else {
      jQuery("#cart_table").load('?route=checkout/checkout/cart', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select'));
    }
  });

  jQuery(".payment-method").load('?route=checkout/checkout/payment_method', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked,input[name=\'shipping_method\']:first, .checkout_form textarea, .checkout_form select'), function () {
    jQuery("[name=\'payment_method\']").attr("checked",'checked').prop('checked', true);
  });
});


$(document).delegate('input[name=\'shipping_method\']', 'click', function () {
  jQuery("#cart_table").load('?route=checkout/checkout/cart', $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select'));
});

$('body').delegate('[name=\'payment_method\']', 'click', function () {

  var data = $('.checkout_form input[type=\'text\'], .checkout_form input[type=\'date\'], .checkout_form input[type=\'datetime-local\'], .checkout_form input[type=\'time\'], .checkout_form input[type=\'password\'], .checkout_form input[type=\'hidden\'], .checkout_form input[type=\'checkbox\']:checked, .checkout_form input[type=\'radio\']:checked, .checkout_form textarea, .checkout_form select').serialize();
  data += '&_shipping_method=' + jQuery('.checkout_form input[name=\'shipping_method\']:checked').prop('title') + '&_payment_method=' + jQuery('.checkout_form input[name=\'payment_method\']:checked').prop('title');


  if (!error) {

    $.ajax({
      url: '?route=checkout/checkout/confirm',
      type: 'post',
      data: data,
      success: function (html) {

        jQuery(".payment").html(html);
        jQuery("#button-confirm").show();

      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });

  }

});

$('select[name=\'country_id\']').trigger('change');
jQuery(document).ready(function () {
  jQuery('input:radio[name=\'payment_method\']:first').attr('checked', true).prop('checked', true);
});



