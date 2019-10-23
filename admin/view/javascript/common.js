// arrangable Plugin for "drag and sort" input lists.
"use strict";
!function (a) {
  "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery)
}(function (a) {
  function f(a) {
    var b = a.clone();
    return b.css({position: "absolute", width: a.width(), height: a.height(), "z-index": 1e5}), b
  }

  function g(a, b, c) {
    for (var k, l, m, n, o, p, q, d = a.offset(), e = a.width(), f = a.height(), g = d.left, h = d.left + e, i = d.top, j = d.top + f, r = 0; r < c.length; r++) if (k = c.eq(r), k[0] !== b[0] && (n = k.offset(), l = n.left + .5 * k.width(), m = n.top + .5 * k.height(), o = l < h && l > g, p = m < j && m > i, q = o && p)) return k[0]
  }

  function h(b, c, d) {
    var e = g(b, c, d);
    if (e !== c[0]) {
      var f = d.index(e), h = d.index(c);
      f < h ? a(e).before(c) : a(e).after(c), i(d, h, f)
    }
  }

  function i(a, b, c) {
    var d = a.splice(b, 1)[0];
    return a.splice(c, 0, d)
  }

  function j() {
    return d += 1, ".drag-arrange-" + d
  }

  var b = "ontouchstart" in document.documentElement, c = 5, d = 0, e = function () {
    return b ? {START: "touchstart", MOVE: "touchmove", END: "touchend"} : {
      START: "mousedown",
      MOVE: "mousemove",
      END: "mouseup"
    }
  }();
  a.fn.arrangeable = function (b) {
    function s(b) {
      if (n) {
        var e = a(i), j = (b.clientX || b.originalEvent.touches[0].clientX) - k,
          q = (b.clientY || b.originalEvent.touches[0].clientY) - l;
        d ? (b.stopPropagation(), g.css({
          left: o + j,
          top: p + q
        }), h(g, e, m)) : (Math.abs(j) > c || Math.abs(q) > c) && (g = f(e), o = i.offsetLeft - parseInt(e.css("margin-left")) - parseInt(e.css("padding-left")), p = i.offsetTop - parseInt(e.css("margin-top")) - parseInt(e.css("padding-top")), g.css({
          left: o,
          top: p
        }), e.parent().append(g), e.css("visibility", "hidden"), d = !0)
      }
    }

    function t(b) {
      d && (b.stopPropagation(), d = !1, g.remove(), i.style.visibility = "visible", a(i).parent().trigger(r, [a(i)])), n = !1
    }

    function u() {
      m.each(function () {
        var c = b.dragSelector, d = a(this);
        c ? d.off(e.START + q, c) : d.off(e.START + q)
      }), a(document).off(e.MOVE + q).off(e.END + q), m.eq(0).data("drag-arrange-destroy", null), m = null, s = null, t = null
    }

    var g, i, k, l, m, o, p, q, d = !1, n = !1;
    if ("string" == typeof b && "destroy" === b) return this.eq(0).data("drag-arrange-destroy") && this.eq(0).data("drag-arrange-destroy")(), this;
    b = a.extend({dragEndEvent: "drag.end.arrangeable"}, b);
    var r = b.dragEndEvent;
    m = this, q = j(), this.each(function () {
      function g(a) {
        a.stopPropagation(), n = !0, k = a.clientX || a.originalEvent.touches[0].clientX, l = a.clientY || a.originalEvent.touches[0].clientY, i = d
      }

      var c = b.dragSelector, d = this, f = a(this);
      c ? f.on(e.START + q, c, g) : f.on(e.START + q, g)
    }), a(document).on(e.MOVE + q, s).on(e.END + q, t), this.eq(0).data("drag-arrange-destroy", u)
  }
});

/*$(document).mouseup(function (e)
 {
 var container = new Array();
 container.push($('#input-product_autocomplete').parent());
 //container.push($('#item_2'));
 $.each(container, function (key, value) {

 if (!$(value).is(e.target) // if the target of the click isn't the container...
 && $(value).has(e.target).length === 0) // ... nor a descendant of the container
 {
 $(value).closest('.dropdown-menu').hide();
 }
 });
 }); */

function saveAndContinue(e) {
  e.preventDefault();
  e.stopPropagation();
  // BUG: if clicked on <i> - will return false.
  var form = $("#" + $(e.currentTarget).attr('form'));

  !form.length ? alert("Can't find form to submit! Please, call Copona!") : '';

  form.append('<input type="hidden" name="save_continue" value="1"  />');
  form.submit();
}

function getURLVar(key) {
  var value = [];
  var query = String(location.href.replace(location.hash, "")).split('?');

  if (query[1]) {
    var part = query[1].split('&');

    for (var i = 0; i < part.length; i++) {
      var data = part[i].split('=');

      if (data[0] && data[1]) {
        value[data[0]] = data[1];
      }
    }

    if (value[key]) {
      return value[key];
    } else {
      return '';
    }
  }
}

// Cookie functions
// Credit to: http://stackoverflow.com/questions/14573223/set-cookie-and-get-cookie-with-javascript
function setCookie(name, value, days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    var expires = "; expires=" + date.toGMTString();
  } else
    var expires = "";
  document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ')
      c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0)
      return c.substring(nameEQ.length, c.length);
  }
  return null;
}

function deleteCookie(name) {
  setCookie(name, "", -1);
}


$(document).ready(function () {
  //Form Submit for IE Browser
  $('button[type=\'submit\']').on('click', function () {
    $("form[id*='form-']").submit();
  });

  // Highlight any found errors
  $('.text-danger').each(function () {
    var element = $(this).parent().parent();

    if (element.hasClass('form-group')) {
      element.addClass('has-error');
    }
  });

  // Set last page opened on the menu
  $('#menu a[href]').on('click', function () {
    sessionStorage.setItem('menu', $(this).attr('href'));
  });

  if (!sessionStorage.getItem('menu')) {
    $('#menu #dashboard').addClass('active');
  } else {
    // Sets active and open to selected page in the left column menu.
    $('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li').addClass('active open');
  }


  if (getCookie('mfold') == 'active') {
    // Slide Down Menu
    $('#menu li.active').has('ul').children('ul').addClass('collapse in');
    $('#menu li').not('.active').has('ul').children('ul').addClass('collapse');
  } else {
    $('#button-menu i').replaceWith('<i class="fa fa-indent fa-lg"></i>');

    $('#menu li li.active').has('ul').children('ul').addClass('collapse in');
    $('#menu li li').not('.active').has('ul').children('ul').addClass('collapse');
    $('#menu > li > ul').removeClass('in collapse');
  }

  // open submenu left (copona)
  $('#menu li.active').has('ul').children('ul').addClass('collapse in');

  // Menu button
  $('#button-menu').on('click', function () {
    // Checks if the left column is active or not.

    if (getCookie('mfold') == 'active') {
      setCookie('mfold', '');
      $('#button-menu i').replaceWith('<i class="fa fa-indent fa-lg"></i>');
      $('#column-left').removeClass('active');
      $('#menu > li > ul').removeClass('in collapse');
      $('#menu > li > ul').removeAttr('style');
    } else {
      setCookie('mfold', 'active');
      $('#button-menu i').replaceWith('<i class="fa fa-dedent fa-lg"></i>');
      $('#column-left').addClass('active');
      // Add the slide down to open menu items
      $('#menu li.open').has('ul').children('ul').addClass('collapse in');
      $('#menu li').not('.open').has('ul').children('ul').addClass('collapse');
    }
  });

  // Menu
  $('#menu').find('li').has('ul').children('a').on('click', function () {
    if ($('#column-left').hasClass('active')) {
      $(this).parent('li').toggleClass('open').children('ul').collapse('toggle');
      $(this).parent('li').siblings().removeClass('open').children('ul.in').collapse('hide');
    } else if (!$(this).parent().parent().is('#menu')) {
      $(this).parent('li').toggleClass('open').children('ul').collapse('toggle');
      $(this).parent('li').siblings().removeClass('open').children('ul.in').collapse('hide');
    }
  });

  // Tooltip remove fixed
  $(document).on('click', '[data-toggle=\'tooltip\']', function (e) {
    $('body > .tooltip').remove();
  });

  // Tabs to anchor links

  if ($.trim(window.location.hash))
    $('.nav.nav-tabs a[href$="' + $.trim(window.location.hash) + '"]').trigger('click');

  $(".panel-body > form > .nav-tabs").on("click", 'a', function (event, ui) {
    window.location.hash = $(this).attr('href');
  });


  // Image Manager
  $(document).on('click', 'a[data-toggle=\'image\']', function (e) {

    var $element = $(this);
    var $popover = $element.data('bs.popover'); // element has bs popover?

    e.preventDefault();

    // destroy all image popovers
    $('a[data-toggle="image"]').popover('destroy');

    // remove flickering (do not re-add popover when clicking for removal)
    if ($popover) {
      return;
    }

    $element.popover({
      html: true,
      placement: 'right',
      trigger: 'manual',
      content: function () {
        return '<button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
      }
    });

    $element.popover('show');

    $('#button-image').on('click', function () {
      var $button = $(this);
      var $icon = $button.find('> i');

      $('#modal-image').remove();
      $.ajax({
        url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&target=' + $element.parent().find('input').attr('id') + '&thumb=' + $element.attr('id'),
        dataType: 'html',
        beforeSend: function () {
          $button.prop('disabled', true);
          if ($icon.length) {
            $icon.attr('class', 'fa fa-circle-o-notch fa-spin');
          }
        },
        complete: function () {
          $button.prop('disabled', false);
          if ($icon.length) {
            $icon.attr('class', 'fa fa-pencil');
          }
        },
        success: function (html) {
          $('body').append('<div id="modal-image" class="modal">' + html + '</div>');
          $('#modal-image').modal('show');
        }
      });

      $element.popover('destroy');
    });

    $('#button-clear').on('click', function () {

      $element.find('img').attr('src', $element.find('img').attr('data-placeholder'));

      $element.parent().find('input').val('');

      $element.popover('destroy');
    });
  });

  // tooltips on hover
  $('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

  // Makes tooltips work on ajax generated content
  $(document).ajaxStop(function () {
    $('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
  });

  // https://github.com/opencart/opencart/issues/2595
  $.event.special.remove = {
    remove: function (o) {
      if (o.handler) {
        o.handler.apply(this, arguments);
      }
    }
  }

  $('[data-toggle=\'tooltip\']').on('remove', function () {
    $(this).tooltip('destroy');
  });

  //TODO:  moved from product_form,
  // DateTime calendar
  $('.date').datetimepicker({
    pickTime: false
  });

  $('.time').datetimepicker({
    pickDate: false
  });

  $('.datetime').datetimepicker({
    pickDate: true,
    pickTime: true
  });

  // Tab actions
  $('#language a:first').tab('show');
  console.log();
  $('#option a:first').tab('show');

});

document.addEventListener('click', function () {
  $('.dropdown-menu').hide();
});

// Warn, if user exits EDIT form without saveing
// TODO: implement AreYouSure
var formSubmitting = false;
window.somethingChanged = false;

$(document).ready(function () {

  // pārbauda vai OC forma ir submitēta
  $('form').on('submit', function (e) {
    formSubmitting = true;
  });

  $('form').on('keyup', 'input', function (e) {
    // product_form.js.tpl has 'keyup' function for prices input change.
    window.somethingChanged = true;
  });

  //arangable.
  $('.well.well-sm>div').arrangeable();

});

window.onload = function () {
  window.addEventListener("beforeunload", function (e) {
    var confirmationMessage = 'It looks like you have been editing something. ';
    confirmationMessage += 'If you leave before saving, your changes will be lost.';

    if (formSubmitting == true) {
      return undefined;
    } else if (window.somethingChanged == false) {
      return undefined;
    }


    (e || window.event).returnValue = confirmationMessage;
    return confirmationMessage;
  });
};

// Autocomplete */
(function ($) {
  $.fn.autocomplete = function (option) {
    return this.each(function () {
      var $this = $(this);
      var $dropdown = $('<ul class="dropdown-menu" />');

      this.timer = null;
      this.items = [];

      $.extend(this, option);

      $this.attr('autocomplete', 'off');

      // Focus
      $this.on('focus', function () {
        this.request();
      });

      // Blur
      $this.on('blur', function () {
        setTimeout(function (object) {
          //object.hide();
        }, 200, this);
      });

      // Keydown
      $this.on('keydown', function (event) {
        switch (event.keyCode) {
          case 27: // escape
            this.hide();
            break;
          default:
            this.request();
            break;
        }
      });

      // Click
      this.click = function (event) {
        event.stopPropagation();
        event.preventDefault();

        var value = $(event.target).parent().attr('data-value');

        if (value && this.items[value]) {
          this.select(this.items[value]);
          $(event.target).parent().remove();
        }
      }

      // Show
      this.show = function () {
        var pos = $this.position();

        $dropdown.css({
          top: pos.top + $this.outerHeight(),
          left: pos.left
        });

        $dropdown.show();
      }

      // Hide
      this.hide = function () {
        $dropdown.hide();
      }

      // Request
      this.request = function () {
        clearTimeout(this.timer);

        this.timer = setTimeout(function (object) {
          object.source($(object).val(), $.proxy(object.response, object));
        }, 200, this);
      }

      // Response
      this.response = function (json) {
        var html = '';
        var category = {};
        var name;
        var i = 0, j = 0;

        if (json.length) {
          for (i = 0; i < json.length; i++) {
            // update element items
            this.items[json[i]['value']] = json[i];

            if (!json[i]['category']) {
              // ungrouped items

              var color = '';
              if ($('#' + option.prefix + json[i]['value']).length > 0) {
                color = '#26f326';
              }

              html += '<li data-value="' + json[i]['value'] + '"><a href="#" style="color: ' + color + ';">' + json[i]['label'] + '</a></li>';
            } else {
              // grouped items
              name = json[i]['category'];
              if (!category[name]) {
                category[name] = [];
              }

              category[name].push(json[i]);
            }
          }

          for (name in category) {
            html += '<li class="dropdown-header">' + name + '</li>';

            for (j = 0; j < category[name].length; j++) {
              html += '<li data-value="' + category[name][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[name][j]['label'] + '</a></li>';
            }
          }
        }

        if (html) {
          this.show();
        } else {
          this.hide();
        }

        $dropdown.html(html);
      }

      $dropdown.on('click', '> li > a', $.proxy(this.click, this));
      $this.after($dropdown);
    });
  }
})(window.jQuery);
