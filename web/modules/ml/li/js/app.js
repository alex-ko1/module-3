(function ($, Drupal) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      $("input[data-drupal-selector='edit-phone-0-value']").inputmask("+380(99)-999-99-99");
    }
  };
})(jQuery, Drupal);
