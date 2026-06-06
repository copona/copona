<?php echo $header; ?>
<div class="container">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
  <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          <?php echo $error_warning; ?>
      </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
          <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
          <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
          <?php $class = 'col-sm-12'; ?>
      <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="panel-group" id="accordion">
        <div class="panel card">
          <div class="card-header">
            <h4 class="card-title"><?php echo $text_checkout_option; ?></h4>
          </div>
          <div class=" collapse" id="collapse-checkout-option">
            <div class="card-body"></div>
          </div>
        </div>
        <?php if (!$logged && $account != 'guest') { ?>
            <div class="panel card">
              <div class="card-header">
                <h4 class="card-title"><?php echo $text_checkout_account; ?></h4>
              </div>
              <div class=" collapse" id="collapse-payment-address">
                <div class="card-body"></div>
              </div>
            </div>
        <?php } else { ?>
            <div class="panel card">
              <div class="card-header">
                <h4 class="card-title"><?php echo $text_checkout_payment_address; ?></h4>
              </div>
              <div class=" collapse" id="collapse-payment-address">
                <div class="card-body"></div>
              </div>
            </div>
        <?php } ?>
        <?php if ($shipping_required) { ?>
            <div class="panel card">
              <div class="card-header">
                <h4 class="card-title"><?php echo $text_checkout_shipping_address; ?></h4>
              </div>
              <div class=" collapse" id="collapse-shipping-address">
                <div class="card-body"></div>
              </div>
            </div>
            <div class="panel card">
              <div class="card-header">
                <h4 class="card-title"><?php echo $text_checkout_shipping_method; ?></h4>
              </div>
              <div class=" collapse" id="collapse-shipping-method">
                <div class="card-body"></div>
              </div>
            </div>
        <?php } ?>
        <div class="panel card">
          <div class="card-header">
            <h4 class="card-title"><?php echo $text_checkout_payment_method; ?></h4>
          </div>
          <div class=" collapse" id="collapse-payment-method">
            <div class="card-body"></div>
          </div>
        </div>
        <div class="panel card">
          <div class="card-header">
            <h4 class="card-title"><?php echo $text_checkout_confirm; ?></h4>
          </div>
          <div class=" collapse" id="collapse-checkout-confirm">
            <div class="card-body"></div>
          </div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>
$(document).on('change', 'input[name=\'account\']', function () {
        if ($('#collapse-payment-address').parent().find('.card-header .card-title > *').is('a')) {
            if (this.value == 'register') {
                $('#collapse-payment-address').parent().find('.card-header .card-title').html('<a href="#collapse-payment-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_account; ?> <i class="fa fa-caret-down"></i></a>');
            } else {
                $('#collapse-payment-address').parent().find('.card-header .card-title').html('<a href="#collapse-payment-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_address; ?> <i class="fa fa-caret-down"></i></a>');
            }
        } else {
            if (this.value == 'register') {
                $('#collapse-payment-address').parent().find('.card-header .card-title').html('<?php echo $text_checkout_account; ?>');
            } else {
                $('#collapse-payment-address').parent().find('.card-header .card-title').html('<?php echo $text_checkout_payment_address; ?>');
            }
        }
    });

<?php if (!$logged) { ?>
        $(document).ready(function () {
            $.ajax({
                url: 'index.php?route=checkout/login',
                dataType: 'html',
                success: function (html) {
                    $('#collapse-checkout-option .card-body').html(html);

                    $('#collapse-checkout-option').parent().find('.card-header .card-title').html('<a href="#collapse-checkout-option" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_option; ?> <i class="fa fa-caret-down"></i></a>');

                    $('a[href=\'#collapse-checkout-option\']').trigger('click');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
<?php } else { ?>
        $(document).ready(function () {
            $.ajax({
                url: 'index.php?route=checkout/payment_address',
                dataType: 'html',
                success: function (html) {
                    $('#collapse-payment-address .card-body').html(html);

                    $('#collapse-payment-address').parent().find('.card-header .card-title').html('<a href="#collapse-payment-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_address; ?> <i class="fa fa-caret-down"></i></a>');

                    $('a[href=\'#collapse-payment-address\']').trigger('click');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
<?php } ?>

// Checkout
    $(document).delegate('#button-account', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/' + $('input[name=\'account\']:checked').val(),
            dataType: 'html',
            beforeSend: function () {
                $('#button-account').button('loading');
            },
            complete: function () {
                $('#button-account').button('reset');
            },
            success: function (html) {
                $('.alert, .text-danger').remove();

                $('#collapse-payment-address .card-body').html(html);

                if ($('input[name=\'account\']:checked').val() == 'register') {
                    $('#collapse-payment-address').parent().find('.card-header .card-title').html('<a href="#collapse-payment-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_account; ?> <i class="fa fa-caret-down"></i></a>');
                } else {
                    $('#collapse-payment-address').parent().find('.card-header .card-title').html('<a href="#collapse-payment-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_address; ?> <i class="fa fa-caret-down"></i></a>');
                }

                $('a[href=\'#collapse-payment-address\']').trigger('click');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

// Login
    $(document).delegate('#button-login', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/login/save',
            type: 'post',
            data: $('#collapse-checkout-option :input'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-login').button('loading');
            },
            complete: function () {
                $('#button-login').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('is-invalid');

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#collapse-checkout-option .card-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        json['error']['warning'] + '</div>');

                    // Highlight any found errors
                    $('input[name=\'email\']').parent().addClass('is-invalid');
                    $('input[name=\'password\']').parent().addClass('is-invalid');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

// Register
    $(document).delegate('#button-register', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/register/save',
            type: 'post',
            data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address textarea, #collapse-payment-address select'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-register').button('loading');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('is-invalid');

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#button-register').button('reset');

                    if (json['error']['warning']) {
                        $('#collapse-payment-address .card-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-payment-' + i.replace('_', '-'));

                        if ($(element).parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('is-invalid');
                } else {
<?php if ($shipping_required) { ?>
                        var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').prop('value');

                        if (shipping_address) {
                            $.ajax({
                                url: 'index.php?route=checkout/shipping_method',
                                dataType: 'html',
                                success: function (html) {
                                    // Add the shipping address
                                    $.ajax({
                                        url: 'index.php?route=checkout/shipping_address',
                                        dataType: 'html',
                                        success: function (html) {
                                            $('#collapse-shipping-address .card-body').html(html);

                                            $('#collapse-shipping-address').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_address; ?> <i class="fa fa-caret-down"></i></a>');
                                        },
                                        error: function (xhr, ajaxOptions, thrownError) {
                                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                        }
                                    });

                                    $('#collapse-shipping-method .card-body').html(html);

                                    $('#collapse-shipping-method').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-method" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_method; ?> <i class="fa fa-caret-down"></i></a>');

                                    $('a[href=\'#collapse-shipping-method\']').trigger('click');

                                    $('#collapse-shipping-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_shipping_method; ?>');
                                    $('#collapse-payment-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_payment_method; ?>');
                                    $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        } else {
                            $.ajax({
                                url: 'index.php?route=checkout/shipping_address',
                                dataType: 'html',
                                success: function (html) {
                                    $('#collapse-shipping-address .card-body').html(html);

                                    $('#collapse-shipping-address').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_address; ?> <i class="fa fa-caret-down"></i></a>');

                                    $('a[href=\'#collapse-shipping-address\']').trigger('click');

                                    $('#collapse-shipping-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_shipping_method; ?>');
                                    $('#collapse-payment-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_payment_method; ?>');
                                    $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        }
<?php } else { ?>
                        $.ajax({
                            url: 'index.php?route=checkout/payment_method',
                            dataType: 'html',
                            success: function (html) {
                                $('#collapse-payment-method .card-body').html(html);

                                $('#collapse-payment-method').parent().find('.card-header .card-title').html('<a href="#collapse-payment-method" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');

                                $('a[href=\'#collapse-payment-method\']').trigger('click');

                                $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
<?php } ?>

                    $.ajax({
                        url: 'index.php?route=checkout/payment_address',
                        dataType: 'html',
                        complete: function () {
                            $('#button-register').button('reset');
                        },
                        success: function (html) {
                            $('#collapse-payment-address .card-body').html(html);

                            $('#collapse-payment-address').parent().find('.card-header .card-title').html('<a href="#collapse-payment-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_address; ?> <i class="fa fa-caret-down"></i></a>');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

// Payment Address
    $(document).delegate('#button-payment-address', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/payment_address/save',
            type: 'post',
            data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address textarea, #collapse-payment-address select'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-payment-address').button('loading');
            },
            complete: function () {
                $('#button-payment-address').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#collapse-payment-address .card-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-payment-' + i.replace('_', '-'));

                        if ($(element).parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().parent().addClass('is-invalid');
                } else {
<?php if ($shipping_required) { ?>
                        $.ajax({
                            url: 'index.php?route=checkout/shipping_address',
                            dataType: 'html',
                            success: function (html) {
                                $('#collapse-shipping-address .card-body').html(html);

                                $('#collapse-shipping-address').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_address; ?> <i class="fa fa-caret-down"></i></a>');

                                $('a[href=\'#collapse-shipping-address\']').trigger('click');

                                $('#collapse-shipping-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_shipping_method; ?>');
                                $('#collapse-payment-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_payment_method; ?>');
                                $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
<?php } else { ?>
                        $.ajax({
                            url: 'index.php?route=checkout/payment_method',
                            dataType: 'html',
                            success: function (html) {
                                $('#collapse-payment-method .card-body').html(html);

                                $('#collapse-payment-method').parent().find('.card-header .card-title').html('<a href="#collapse-payment-method" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');

                                $('a[href=\'#collapse-payment-method\']').trigger('click');

                                $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
<?php } ?>

                    $.ajax({
                        url: 'index.php?route=checkout/payment_address',
                        dataType: 'html',
                        success: function (html) {
                            $('#collapse-payment-address .card-body').html(html);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

// Shipping Address
    $(document).delegate('#button-shipping-address', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/shipping_address/save',
            type: 'post',
            data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-shipping-address').button('loading');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#button-shipping-address').button('reset');

                    if (json['error']['warning']) {
                        $('#collapse-shipping-address .card-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-shipping-' + i.replace('_', '-'));

                        if ($(element).parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().parent().addClass('is-invalid');
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/shipping_method',
                        dataType: 'html',
                        complete: function () {
                            $('#button-shipping-address').button('reset');
                        },
                        success: function (html) {
                            $('#collapse-shipping-method .card-body').html(html);

                            $('#collapse-shipping-method').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-method" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_method; ?> <i class="fa fa-caret-down"></i></a>');

                            $('a[href=\'#collapse-shipping-method\']').trigger('click');

                            $('#collapse-payment-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_payment_method; ?>');
                            $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');

                            $.ajax({
                                url: 'index.php?route=checkout/shipping_address',
                                dataType: 'html',
                                success: function (html) {
                                    $('#collapse-shipping-address .card-body').html(html);
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });

                    $.ajax({
                        url: 'index.php?route=checkout/payment_address',
                        dataType: 'html',
                        success: function (html) {
                            $('#collapse-payment-address .card-body').html(html);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

// Guest
    $(document).delegate('#button-guest', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/guest/save',
            type: 'post',
            data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address textarea, #collapse-payment-address select'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-guest').button('loading');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#button-guest').button('reset');

                    if (json['error']['warning']) {
                        $('#collapse-payment-address .card-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-payment-' + i.replace('_', '-'));

                        if ($(element).parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('is-invalid');
                } else {
<?php if ($shipping_required) { ?>
                        var shipping_address = $('#collapse-payment-address input[name=\'shipping_address\']:checked').prop('value');

                        if (shipping_address) {
                            $.ajax({
                                url: 'index.php?route=checkout/shipping_method',
                                dataType: 'html',
                                complete: function () {
                                    $('#button-guest').button('reset');
                                },
                                success: function (html) {
                                    // Add the shipping address
                                    $.ajax({
                                        url: 'index.php?route=checkout/guest_shipping',
                                        dataType: 'html',
                                        success: function (html) {
                                            $('#collapse-shipping-address .card-body').html(html);

                                            $('#collapse-shipping-address').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_address; ?> <i class="fa fa-caret-down"></i></a>');
                                        },
                                        error: function (xhr, ajaxOptions, thrownError) {
                                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                        }
                                    });

                                    $('#collapse-shipping-method .card-body').html(html);

                                    $('#collapse-shipping-method').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-method" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_method; ?> <i class="fa fa-caret-down"></i></a>');

                                    $('a[href=\'#collapse-shipping-method\']').trigger('click');

                                    $('#collapse-payment-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_payment_method; ?>');
                                    $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        } else {
                            $.ajax({
                                url: 'index.php?route=checkout/guest_shipping',
                                dataType: 'html',
                                complete: function () {
                                    $('#button-guest').button('reset');
                                },
                                success: function (html) {
                                    $('#collapse-shipping-address .card-body').html(html);

                                    $('#collapse-shipping-address').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-address" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_address; ?> <i class="fa fa-caret-down"></i></a>');

                                    $('a[href=\'#collapse-shipping-address\']').trigger('click');

                                    $('#collapse-shipping-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_shipping_method; ?>');
                                    $('#collapse-payment-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_payment_method; ?>');
                                    $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        }
<?php } else { ?>
                        $.ajax({
                            url: 'index.php?route=checkout/payment_method',
                            dataType: 'html',
                            complete: function () {
                                $('#button-guest').button('reset');
                            },
                            success: function (html) {
                                $('#collapse-payment-method .card-body').html(html);

                                $('#collapse-payment-method').parent().find('.card-header .card-title').html('<a href="#collapse-payment-method" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');

                                $('a[href=\'#collapse-payment-method\']').trigger('click');

                                $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
<?php } ?>
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

// Guest Shipping
    $(document).delegate('#button-guest-shipping', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/guest_shipping/save',
            type: 'post',
            data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-guest-shipping').button('loading');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#button-guest-shipping').button('reset');

                    if (json['error']['warning']) {
                        $('#collapse-shipping-address .card-body').prepend('<div class="alert alert-danger">' + json['error']['warning'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }

                    for (i in json['error']) {
                        var element = $('#input-shipping-' + i.replace('_', '-'));

                        if ($(element).parent().hasClass('input-group')) {
                            $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                        } else {
                            $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                        }
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('is-invalid');
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/shipping_method',
                        dataType: 'html',
                        complete: function () {
                            $('#button-guest-shipping').button('reset');
                        },
                        success: function (html) {
                            $('#collapse-shipping-method .card-body').html(html);

                            $('#collapse-shipping-method').parent().find('.card-header .card-title').html('<a href="#collapse-shipping-method" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_method; ?> <i class="fa fa-caret-down"></i>');

                            $('a[href=\'#collapse-shipping-method\']').trigger('click');

                            $('#collapse-payment-method').parent().find('.card-header .card-title').html('<?php echo $text_checkout_payment_method; ?>');
                            $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $(document).delegate('#button-shipping-method', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/shipping_method/save',
            type: 'post',
            data: $('#collapse-shipping-method input[type=\'radio\']:checked, #collapse-shipping-method textarea'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-shipping-method').button('loading');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#button-shipping-method').button('reset');

                    if (json['error']['warning']) {
                        $('#collapse-shipping-method .card-body').prepend('<div class="alert alert-danger">' + json['error']['warning'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/payment_method',
                        dataType: 'html',
                        complete: function () {
                            $('#button-shipping-method').button('reset');
                        },
                        success: function (html) {
                            $('#collapse-payment-method .card-body').html(html);

                            $('#collapse-payment-method').parent().find('.card-header .card-title').html('<a href="#collapse-payment-method" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');

                            $('a[href=\'#collapse-payment-method\']').trigger('click');

                            $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<?php echo $text_checkout_confirm; ?>');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $(document).delegate('#button-payment-method', 'click', function () {
        $.ajax({
            url: 'index.php?route=checkout/payment_method/save',
            type: 'post',
            data: $('#collapse-payment-method input[type=\'radio\']:checked, #collapse-payment-method input[type=\'checkbox\']:checked, #collapse-payment-method textarea'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-payment-method').button('loading');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    $('#button-payment-method').button('reset');

                    if (json['error']['warning']) {
                        $('#collapse-payment-method .card-body').prepend('<div class="alert alert-danger">' + json['error']['warning'] + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/confirm',
                        dataType: 'html',
                        complete: function () {
                            $('#button-payment-method').button('reset');
                        },
                        success: function (html) {
                            $('#collapse-checkout-confirm .card-body').html(html);

                            $('#collapse-checkout-confirm').parent().find('.card-header .card-title').html('<a href="#collapse-checkout-confirm" data-bs-toggle="collapse" data-bs-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_confirm; ?> <i class="fa fa-caret-down"></i></a>');

                            $('a[href=\'#collapse-checkout-confirm\']').trigger('click');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
</script>
<?php echo $footer; ?>
