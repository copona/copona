(function ($) {
    if (typeof flatpickr === 'undefined') return;

    $.fn.datetimepicker = function (opts) {
        opts = opts || {};
        return this.each(function () {
            var config = { allowInput: true, dateFormat: 'Y-m-d' };

            if (opts.pickDate === false) {
                config.noCalendar = true;
                config.enableTime = true;
                config.time_24hr = true;
                config.dateFormat = 'H:i';
            } else if (opts.pickTime !== false && (opts.pickTime === true || opts.pickDate === true)) {
                config.enableTime = true;
                config.time_24hr = true;
                config.dateFormat = 'Y-m-d H:i';
            }

            flatpickr(this, config);
        });
    };
}(jQuery));
