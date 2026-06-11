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


// BS3→BS5 tab bridge: BS5 Tab._getChildren() requires .nav-link (or role="tab").
// Rather than touching every template, add the class at runtime and migrate
// the active state from <li> to <a>, then add .show to the visible pane.
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (el) {
    el.classList.add('nav-link');
    var li = el.closest('li');
    if (li && li.classList.contains('active')) {
      el.classList.add('active');
      li.classList.remove('active');
    }
  });
  document.querySelectorAll('.tab-pane.active').forEach(function (pane) {
    pane.classList.add('show');
  });
});

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
    $('#menu li.active').has('ul').children('ul').addClass('collapse show');
    $('#menu li').not('.active').has('ul').children('ul').addClass('collapse');
  } else {
    $('#button-menu i').replaceWith('<i class="fa fa-indent fa-lg"></i>');

    $('#menu li li.active').has('ul').children('ul').addClass('collapse show');
    $('#menu li li').not('.active').has('ul').children('ul').addClass('collapse');
    $('#menu > li > ul').removeClass('show collapse');
  }

  // open submenu left (copona)
  $('#menu li.active').has('ul').children('ul').addClass('collapse show');

  // Menu button
  $('#button-menu').on('click', function () {
    // Checks if the left column is active or not.

    if (getCookie('mfold') == 'active') {
      setCookie('mfold', '');
      $('#button-menu i').replaceWith('<i class="fa fa-indent fa-lg"></i>');
      $('#column-left').removeClass('active');
      $('#menu > li > ul').removeClass('show collapse');
      $('#menu > li > ul').removeAttr('style');
    } else {
      setCookie('mfold', 'active');
      $('#button-menu i').replaceWith('<i class="fa fa-dedent fa-lg"></i>');
      $('#column-left').addClass('active');
      // Add the slide down to open menu items
      $('#menu li.open').has('ul').children('ul').addClass('collapse show');
      $('#menu li').not('.open').has('ul').children('ul').addClass('collapse');
    }
  });

  // Menu
  $('#menu').find('li').has('ul').children('a').on('click', function () {
    function collapseToggle(els) {
      els.each(function () { bootstrap.Collapse.getOrCreateInstance(this, {toggle: false}).toggle(); });
    }
    function collapseHide(els) {
      els.each(function () { var c = bootstrap.Collapse.getInstance(this); if (c) c.hide(); });
    }
    if ($('#column-left').hasClass('active')) {
      $(this).parent('li').toggleClass('open');
      collapseToggle($(this).parent('li').children('ul'));
      $(this).parent('li').siblings().removeClass('open');
      collapseHide($(this).parent('li').siblings().children('ul.show'));
    } else if (!$(this).parent().parent().is('#menu')) {
      $(this).parent('li').toggleClass('open');
      collapseToggle($(this).parent('li').children('ul'));
      $(this).parent('li').siblings().removeClass('open');
      collapseHide($(this).parent('li').siblings().children('ul.show'));
    }
  });

  // Tooltip remove fixed
  $(document).on('click', '[data-bs-toggle=\'tooltip\']', function (e) {
    $('body > .tooltip').remove();
  });

  // Tabs to anchor links

  if ($.trim(window.location.hash))
    $('.nav.nav-tabs a[href$="' + $.trim(window.location.hash) + '"]').trigger('click');

  $(".card-body > form > .nav-tabs").on("click", 'a', function (event, ui) {
    window.location.hash = $(this).attr('href');
  });


  // Image Manager
  $(document).on('click', 'a[data-bs-toggle=\'image\']', function (e) {

    var $element = $(this);
    var existingPopover = bootstrap.Popover.getInstance($element[0]);

    e.preventDefault();

    // destroy all image popovers
    document.querySelectorAll('a[data-bs-toggle="image"]').forEach(function (el) {
      var p = bootstrap.Popover.getInstance(el);
      if (p) p.dispose();
    });

    // remove flickering (do not re-add popover when clicking for removal)
    if (existingPopover) {
      return;
    }

    var popover = new bootstrap.Popover($element[0], {
      html: true,
      placement: 'right',
      trigger: 'manual',
      content: function () {
        return '<button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
      }
    });

    popover.show();

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
          $('body').append('<div id="modal-image" class="modal" tabindex="-1">' + html + '</div>');
          new bootstrap.Modal(document.getElementById('modal-image')).show();
        }
      });

      popover.dispose();
    });

    $('#button-clear').on('click', function () {
      $element.find('img').attr('src', $element.find('img').attr('data-placeholder'));
      $element.parent().find('input').val('');
      popover.dispose();
    });
  });

  // tooltips on hover
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
    new bootstrap.Tooltip(el, {container: 'body', html: true});
  });

  // Makes tooltips work on ajax generated content
  $(document).ajaxStop(function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
      if (!bootstrap.Tooltip.getInstance(el)) {
        new bootstrap.Tooltip(el, {container: 'body', html: true});
      }
    });
  });

  // Tab actions
  var langTab = document.querySelector('#language a:first-child');
  if (langTab) new bootstrap.Tab(langTab).show();
  var optionTab = document.querySelector('#option a:first-child');
  if (optionTab) new bootstrap.Tab(optionTab).show();

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

      // Use a plain object to avoid writing to read-only DOM element properties (e.g. prefix)
      var self = {
        timer: null,
        items: [],
        source: option.source || null,
        select: option.select || null,
        prefix: option.prefix || ''
      };

      $this.attr('autocomplete', 'off');

      // Focus
      $this.on('focus', function () {
        self.request();
      });

      // Blur
      $this.on('blur', function () {
        setTimeout(function () {}, 200);
      });

      // Keydown
      $this.on('keydown', function (event) {
        switch (event.keyCode) {
          case 27: // escape
            self.hide();
            break;
          default:
            self.request();
            break;
        }
      });

      // Click
      self.click = function (event) {
        event.stopPropagation();
        event.preventDefault();

        var value = $(event.target).parent().attr('data-value');

        if (value && self.items[value]) {
          self.select(self.items[value]);
          $(event.target).parent().remove();
        }
      };

      // Show
      self.show = function () {
        var pos = $this.position();

        $dropdown.css({
          top: pos.top + $this.outerHeight(),
          left: pos.left
        });

        $dropdown.show();
      };

      // Hide
      self.hide = function () {
        $dropdown.hide();
      };

      // Request
      self.request = function () {
        clearTimeout(self.timer);

        self.timer = setTimeout(function () {
          self.source($this.val(), function (json) { self.response(json); });
        }, 200);
      };

      // Response
      self.response = function (json) {
        var html = '';
        var category = {};
        var name;
        var i = 0, j = 0;

        if (json.length) {
          for (i = 0; i < json.length; i++) {
            self.items[json[i]['value']] = json[i];

            if (!json[i]['category']) {
              var color = '';
              if ($('#' + self.prefix + json[i]['value']).length > 0) {
                color = '#26f326';
              }

              html += '<li data-value="' + json[i]['value'] + '"><a href="#" style="color: ' + color + ';">' + json[i]['label'] + '</a></li>';
            } else {
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
          self.show();
        } else {
          self.hide();
        }

        $dropdown.html(html);
      };

      $dropdown.on('click', '> li > a', function (e) { self.click(e); });
      $this.after($dropdown);
    });
  };
})(window.jQuery);
