/**
 * jQuery shim: maps legacy bootstrap-datetimepicker API to Flatpickr.
 * Handles the three call patterns used across admin templates:
 *   .datetimepicker({ pickTime: false })   → date picker only
 *   .datetimepicker({ pickDate: false })   → time picker only
 *   .datetimepicker({ pickDate, pickTime })→ datetime picker
 *   .datetimepicker()                      → date picker (default)
 */
(function ($) {
    if (typeof flatpickr === 'undefined') return;

    $.fn.datetimepicker = function (opts) {
        opts = opts || {};
        return this.each(function () {
            var config = { allowInput: true, dateFormat: 'Y-m-d' };

            if (opts.pickDate === false) {
                // time only
                config.noCalendar = true;
                config.enableTime = true;
                config.time_24hr = true;
                config.dateFormat = 'H:i';
            } else if (opts.pickTime !== false && (opts.pickTime === true || opts.pickDate === true)) {
                // datetime
                config.enableTime = true;
                config.time_24hr = true;
                config.dateFormat = 'Y-m-d H:i';
            }
            // else: date only (default)

            flatpickr(this, config);
        });
    };
}(jQuery));
