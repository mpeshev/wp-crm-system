jQuery(document).ready(function($) {
  $('#wpcrm-updater').submit(function() {
    $('#wpcrm-update-submit').attr('disabled', true);

    data = {
      action: 'wpcrm_update_contacts',
      wpcrm_nonce: wpcrm_vars.wpcrm_nonce
    };

    $.post(ajaxurl, data, function (response) {
      alert(response);
      $('#wpcrm-update-submit').attr('disabled', false);
      $('#wpcrm_update_nag').hide();
      $('#wpcrm_update_status').show();
    });

    return false;
  });
});
