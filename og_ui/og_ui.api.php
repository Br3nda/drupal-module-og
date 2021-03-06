<?php
// $Id: og_ui.api.php,v 1.1 2010/01/03 15:24:12 amitaibu Exp $

/**
 * @file
 * Hooks provided by the Organic groups module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Add a menu item that should appear in the group admin page.
 */
function hook_og_ui_get_group_admin() {
  $items = array();
  $items['add_people'] = array(
    'title' => t('Add people'),
    'description' => t('Add group members.'),
    // The final URL will be "og/$obj_type/$oid/admin/people/add-user".
    'href' => 'admin/people/add-user',
  );

  return $items;
}

/**
 * Alter existing group admin menu items.
 *
 * @param $data
 *   The menu items passed by reference.
 */
function hook_og_ui_get_group_admin_alter(&$data) {
  // Hijack the add people to use a custom implementation.
  $items['add_people']['href'] = 'admin/people/custom-add-user';
}

/**
 * @} End of "addtogroup hooks".
 */