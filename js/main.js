(function ($) {
    Drupal.behaviors.Gaea = {
        attach: function (context, settings) {
            $('.nav-tabs').stickyTabs();
        }
    };
})(jQuery);
