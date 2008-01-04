// $Id: og.js,v 1.1.2.8 2008/01/04 02:12:58 weitzman Exp $

Drupal.ogAttach = function() {
  /* Node authoring form for group content -Disable the public checkbox if no groups are selected in in Audience */
  $('.og-audience').click(function() {
    // Audience can be select or checkboxes
    var cnt;
    if ( $('.og-audience .form-checkbox').size() > 0) {
      cnt = $('input.og-audience:checked').size();  
    }
    else {
      cnt = $('.og-audience option:selected').size();      
    }
    if (cnt > 0) {
      $('#edit-og-public').removeAttr("disabled");
    }
    else {
      $('#edit-og-public').attr("disabled", "disabled");
    }
  });
  
  if ( $('.og-audience .form-checkbox').size() > 0 ) {
    if ( $('input.og-audience:checked').size() < 1) {
        $('#edit-og-public').attr("disabled", "disabled");
    }    
  }
  else {
    if ( $('.og-audience option:selected').size() < 1) {
        $('#edit-og-public').attr("disabled", "disabled");
    }        
  }

  /* Node authoring form for group homepages - Don't allow "private group" and "Open subscription" at the same time 
   * This is just for improved UI. You may change it if you need this combination.
   */
  $("#edit-og-private").click(function() { 
    if ($("#edit-og-private:checked").val()) {
      $("input[@Name='og_selective']:nth(0)").removeAttr('checked').attr('disabled','disabled');
    }
    else {
      $("input[@Name='og_selective']:nth(0)").removeAttr('disabled');
    }
  });
  
  $("input[@Name='og_selective']").click(function() {
      // if Open is selected
      if ($("input[@Name='og_selective']:checked").val() == 0) {
        $("#edit-og-private").removeAttr("checked").attr('disabled','disabled');
      }
      else {
        $("#edit-og-private").removeAttr("disabled");
      }
  });
  
  if ($("#edit-og-private:checked").val()) {
      $("input[@Name='og_selective']:nth(0)").removeAttr('checked').attr('disabled','disabled');
  }
  
  
  /* Node authoring form for group homepages - Don't allow "private group" and "list in groups directory" at the same time 
   * This is just for improved UI. You may change it if you need this combination.
   */
  $("#edit-og-private").click(function() { 
    if ($("#edit-og-private:checked").val()) {
      $("#edit-og-directory").removeAttr("checked").attr('disabled','disabled');
    }
    else {
      $("#edit-og-directory").removeAttr('disabled');
    }
  });
  
  $("#edit-og-directory").click(function() {
    if ($("#edit-og-directory:checked").val()) {
      $("#edit-og-private").attr('disabled','disabled');
    }
    else {
      $("#edit-og-private").removeAttr('disabled');
    }
  });
  if ($("#edit-og-directory:checked").val() && !$("#edit-og-private:checked").val()) {
      $("#edit-og-private").attr('disabled','disabled');
  }
  if ($("#edit-og-private:checked").val() && !$("#edit-og-directory:checked").val()) {
      $("#edit-og-directory").attr('disabled','disabled');
  } 

  /* admin og settings form, "Group details - Private Groups"
   * Disable "always public" if Node authoring visibility set to "Visible only within the targeted groups"
   * Disable "always private" if Node authoring visibility set to "Visible within the targeted groups and on other pages"
   */
  $("input[@Name='og_visibility']").click(function() {
    if ($("input[@Name='og_visibility']:checked").val() == 0) {
        $("input[@name='og_private_groups']:nth(0)").attr('disabled','disabled');
        $("input[@name='og_private_groups']:nth(1)").removeAttr('disabled');
      }
      else if ($("input[@Name='og_visibility']:checked").val() == 1) {
        $("input[@name='og_private_groups']:nth(0)").removeAttr('disabled');
        $("input[@name='og_private_groups']:nth(1)").attr('disabled','disabled');
      } 
      else {
        $("input[@name='og_private_groups']:nth(0)").removeAttr('disabled');
        $("input[@name='og_private_groups']:nth(1)").removeAttr('disabled');
      }
    }
  );
  
  if ($("input[@Name='og_visibility']:checked").val() == 0) {
      $("input[@name='og_private_groups']:nth(0)").attr('disabled','disabled');
      $("input[@name='og_private_groups']:nth(1)").removeAttr('disabled');
  }
  else if ($("input[@Name='og_visibility']:checked").val() == 1) {
      $("input[@name='og_private_groups']:nth(0)").removeAttr('disabled');
      $("input[@name='og_private_groups']:nth(1)").attr('disabled','disabled');     
  }
      
  /* admin og settings form, "Node Authoring Form - Visibilty of Posts"
   * Disable "Visible within the targeted groups and on other pages" if private groups set to "always private"
   * Disable "Visible only within the targeted groups" if private groups set to "always public"
   */
  $("input[@Name='og_private_groups']").click(function() {
      if ( $("input[@Name='og_private_groups']:checked").val() == 1 ) {
        $("input[@name='og_visibility']:nth(0)").removeAttr('disabled');
        $("input[@name='og_visibility']:nth(1)").attr('disabled','disabled');
      }
      else if ( $("input[@Name='og_private_groups']:checked").val() == 0 ) {
        $("input[@name='og_visibility']:nth(0)").attr('disabled','disabled');
        $("input[@name='og_visibility']:nth(1)").removeAttr('disabled');  
      }
      else { 
        $("input[@name='og_visibility']:nth(0)").removeAttr('disabled');  
        $("input[@name='og_visibility']:nth(1)").removeAttr('disabled');  
      }
    }
  );
  
  if ( $("input[@Name='og_private_groups']:checked").val() == 1 ) {
    $("input[@name='og_visibility']:nth(0)").removeAttr('disabled');
    $("input[@name='og_visibility']:nth(1)").attr('disabled','disabled');
  }
  else if ( $("input[@Name='og_private_groups']:checked").val() == 0 ) {
      $("input[@name='og_visibility']:nth(0)").attr('disabled','disabled');
      $("input[@name='og_visibility']:nth(1)").removeAttr('disabled');  
  }
};
if (Drupal.jsEnabled) {
  $(document).ready(Drupal.ogAttach);
}
