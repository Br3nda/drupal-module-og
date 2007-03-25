// $Id: og.js,v 1.3 2007/03/25 16:15:29 weitzman Exp $

Drupal.ogAttach = function() {
/*  Disable the public checkbox if no groups are selected in in Audience*/
  $('.og-audience').click(function() {
    // Audience can be select or checkboxes
    if ($('input.og-audience.form-checkbox').size()) {
      var cnt = $('input.og-audience:checked').size();  
    }
    else {
      var cnt = $('select.og-audience option:selected').size();      
    }
    
      
    if (cnt > 0) {
      $('#edit-og-public').removeAttr("disabled");
    }
    else {
      $('#edit-og-public').attr("disabled", "disabled");
    }
  })
  $('.og-audience').click();
}

if (Drupal.jsEnabled) {
  $(document).ready(Drupal.ogAttach);
}
