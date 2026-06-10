#!/usr/bin/env python3
"""Bootstrap 3 → 5 mechanical migration for admin templates."""

import os
import re
import glob

# ── TPL replacements (applied in order) ──────────────────────────────────────
TPL_REPLACEMENTS = [
    # Data attributes
    ('data-toggle=',      'data-bs-toggle='),
    ('data-dismiss=',     'data-bs-dismiss='),
    ('data-target=',      'data-bs-target='),
    ('data-parent=',      'data-bs-parent='),

    # Buttons
    ('btn-default',       'btn-secondary'),

    # Panel → Card (specific before generic)
    ('panel panel-default', 'card'),
    ('panel panel-primary', 'card border-primary'),
    ('panel panel-success', 'card border-success'),
    ('panel panel-warning', 'card border-warning'),
    ('panel panel-danger',  'card border-danger'),
    ('panel panel-info',    'card border-info'),
    ('panel-heading',       'card-header'),
    ('panel-body',          'card-body'),
    ('panel-footer',        'card-footer'),
    ('panel-title',         'card-title'),

    # Layout utilities
    ('pull-right',           'float-end'),
    ('pull-left',            'float-start'),
    ('dropdown-menu-right',  'dropdown-menu-end'),
    ('text-right',           'text-end'),
    ('text-left',            'text-start'),

    # Accessibility
    ('sr-only',              'visually-hidden'),

    # Grid
    ('col-xs-',              'col-'),

    # Collapse: BS5 uses .show, not .in
    ('collapse in',          'collapse show'),

    # Navbar (only 1–2 instances each)
    (' navbar-static-top',   ''),
    (' navbar-header',       ''),

    # Dropdown divider
    ('class="divider"',      'class="dropdown-divider"'),

    # Label → Badge (keep class name but add alias for compat — handled in CSS)
    # Not renamed here; handled by bs3-compat.css

    # Hidden responsive utilities (handled in compat CSS, not renamed here)
]

# ── common.js replacements ────────────────────────────────────────────────────
COMMONJS_REPLACEMENTS = [
    # Update tooltip selectors after data-toggle rename
    ("data-toggle='tooltip'",  "data-bs-toggle='tooltip'"),
    ('data-toggle=\'tooltip\'', "data-bs-toggle='tooltip'"),
    ("data-toggle='image'",     "data-bs-toggle='image'"),
    ('data-toggle=\'image\'',   "data-bs-toggle='image'"),

    # Bootstrap 5 renamed destroy → dispose for popovers/tooltips
    ("popover('destroy')",  "popover('dispose')"),
    ("tooltip('destroy')",  "tooltip('dispose')"),

    # Collapse class: BS5 uses .show not .in
    ("'collapse in'",           "'collapse show'"),
    ('"collapse in"',           '"collapse show"'),
    (".children('ul.in')",      ".children('ul.show')"),
    ("removeClass('in collapse')", "removeClass('show collapse')"),
    ("removeClass('in '",       "removeClass('show '"),

    # Panel → Card class selectors
    ("'panel-body'",  "'card-body'"),
    ('"panel-body"',  '"card-body"'),

    # Remove dead datetimepicker calls (3 lines)
    ("  $('.date').datetimepicker({\n    pickTime: false\n  });\n\n  $('.time').datetimepicker({\n    pickDate: false\n  });\n\n  $('.datetime').datetimepicker({\n    pickDate: true,\n    pickTime: true\n  });\n\n",
     ""),

    # Remove stray console.log()
    ("  console.log();\n",  ""),
]

# ── stylesheet.css replacements ───────────────────────────────────────────────
STYLESHEET_REPLACEMENTS = [
    # Update CSS selector for tooltip (we renamed data-toggle in templates)
    ('data-toggle="tooltip"',  'data-bs-toggle="tooltip"'),

    # Collapse: .collapse.in → .collapse.show
    ('.collapse.in',  '.collapse.show'),

    # Panel CSS overrides → Card equivalents
    ('.panel .panel-heading',              '.card .card-header'),
    ('.panel-primary .panel-heading',      '.card.border-primary .card-header'),
    ('.panel-default .panel-heading',      '.card .card-header'),
    ('.panel-primary',                     '.card.border-primary'),
    ('.panel-default',                     '.card'),
    ('.panel {',                           '.card {'),
    ('.panel-heading h3 i',                '.card-header h3 i'),
    ('.panel-heading i',                   '.card-header i'),
    ('.panel-heading h3',                  '.card-header h3'),
    ('.panel-heading {',                   '.card-header {'),

    # Summernote uses panel-heading class
    ('.note-toolbar.panel-heading',        '.note-toolbar.card-header'),

    # Layout
    ('.pull-right',  '.float-end'),

    # Hidden responsive — keep the fix but update to BS5 breakpoints
    ('span.hidden-xs, span.hidden-sm, span.hidden-md, span.hidden-lg',
     'span.d-none, span.d-sm-block, span.d-md-block, span.d-lg-block'),
]


def apply_replacements(content, replacements):
    for old, new in replacements:
        content = content.replace(old, new)
    return content


def process_file(path, replacements, dry_run=False):
    with open(path, 'r', encoding='utf-8', errors='replace') as f:
        original = f.read()

    updated = apply_replacements(original, replacements)

    if updated != original:
        if not dry_run:
            with open(path, 'w', encoding='utf-8') as f:
                f.write(updated)
        return True
    return False


def main():
    base = os.path.dirname(os.path.abspath(__file__))

    # Process all admin templates
    tpl_files = glob.glob(
        os.path.join(base, 'admin/view/template/**/*.tpl'), recursive=True
    )
    tpl_changed = 0
    for path in sorted(tpl_files):
        if process_file(path, TPL_REPLACEMENTS):
            tpl_changed += 1

    print(f'Templates: {tpl_changed}/{len(tpl_files)} files updated')

    # Process common.js
    cjs = os.path.join(base, 'admin/view/javascript/common.js')
    changed = process_file(cjs, COMMONJS_REPLACEMENTS)
    print(f'common.js: {"updated" if changed else "no changes"}')

    # Process stylesheet.css
    css = os.path.join(base, 'admin/view/stylesheet/stylesheet.css')
    changed = process_file(css, STYLESHEET_REPLACEMENTS)
    print(f'stylesheet.css: {"updated" if changed else "no changes"}')


if __name__ == '__main__':
    main()
