<?php
// $Id: og.api.php,v 1.1.2.1 2010/01/28 07:48:35 amitaibu Exp $

/**
 * @file
 * Hooks provided by the Organic groups module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Add group permissions.
 */
function hook_og_permission() {
  return array(
    'subscribe' => array(
      'title' => t('Subscribe user to group'),
      'description' => t("Allow user to be a member of a group (approval required)."),
      'roles' => array(OG_ANONYMOUS_ROLE),
    ),
  );
}

/**
 * Set the default permissions to be assigned to members, by thier role.
 */
function hook_og_permission_default() {
  return array(
    OG_ANONYMOUS_ROLE => array('an anonymous permission'),
    OG_AUTHENTICATED_ROLE => array('an authenticated permission'),
    OG_ADMINISTRATOR_ROLE => array(),
  );
}

/**
 * Determine if a user has access to a certain operation within a group context.
 *
 * For content access @see hook_og_node_access().
 *
 * @param $op
 *   The operation name.
 * @param $node
 *   The group or group content node object.
 * @param $acting_user
 *   The user object of the acting user.
 * @param $account
 *   Optional; The account related to the operation.
 * @return
 *   OG_ACCESS_ALLOW  - if operation is allowed;
 *   OG_ACCESS_DENY   - if it should be denied;
 *   OG_ACCESS_IGNORE - if you don't care about this operation.
 */
function hook_og_access($op, $node, $acting_user, $account = NULL) {
  if ($op == 'view') {
    // Show group content only if they are in a certain day, defined in the
    // group's data. This data is fictional, and it's up to an implementing
    // module to implement it.
    if (og_is_group_content_type($node->type)) {
      // Get the first node group this group content belongs to.
      $gids = og_get_object_groups('node', $node);
      $group = node_load($gids[0]);
      if (!empty($group->data['show day'])) {
        $today = date('N');
        if ($group->data['show day'] == $today) {
          return OG_ACCESS_ALLOW;
        }
        else {
          return OG_ACCESS_DENY;
        }
      }
      else {
        // The group doesn't have a day definition, so we don't care about this
        // operation.
        return OG_ACCESS_IGNORE;
      }
    }
  }
}

/**
 * Own implementation of hook_node_access().
 *
 * Having this implementation makes sure that if no organic group module
 * allowed access to the content, then it will be denied.
 */
function hook_og_node_access($node, $op, $account) {
  // Allow user to edit posts if the title is 'My post'.
  // For the example we assume the $node is the full node object. If the title
  // of the node doesn't match, and the user didn't get permission from
  // somewhere else (e.g. user already has a permission to 'Edit group content')
  // Then access will be denied.
  if ($node->title == 'MY post') {
    return OG_ACCESS_ALLOW;
  }
}

/**
 * Alter a group that is being fetched.
 *
 * @param $group
 *   An object with the following keys:
 *   - nid:
 *       The node ID of the group.
 *   - data:
 *       Optional; An array of data related to the association. The data is per
 *       per group, and it's up to an implementing module to act on the data.
 */
function hook_og_get_group_alter($group) {
  // Set the theme according to the user name.
  global $user;
  if ($user->name == 'foo') {
    // An implementing module should act on this data and change the theme
    // accordingly.
    $group->data['theme'] = 'MY_THEME';
  }
}

/**
 * Alter a group that is being saved.
 *
 * @param $group
 *   An object with the following keys:
 *   - nid:
 *       The node ID of the group.
 *   - data:
 *       Optional; An array of data related to the association. The data is per
 *       per group, and it's up to an implementing module to act on the data.
 */
function hook_og_set_group_alter($group) {

}

/**
 * Alter the users which will be notified about a subscription of another user.
 *
 * @param $uids
 *   An array with the users ID, passed by reference.
 * @param $node
 *   The group node, the user has subscribed to.
 * @param $group
 *   The group object.
 * @param $account
 *   The subscribing user object.
 * @param $request
 *   Optional; The request text the subscribing user has entered.
 */
function hook_og_user_request(&$uids, $node, $group, $account, $request) {
  // Add user ID 1 to the list of notified users.
  $uids[] = 1;
}

/**
 * Insert state and data, that will be saved with the group content.
 *
 * @param $alter
 *   Array keyed by "state" and "data" passed by reference.
 *   The data passed by reference.
 * @param $obj_type
 * @param $object
 * @param $field
 * @param $instance
 * @param $langcode
 * @param $items
 */
function hook_og_field_insert(&$alter, $obj_type, $object, $field, $instance, $langcode, $items) {
  // Add timestamp for the subscription.
  // It's up to the implementing module to act on the data.
  $alter['data']['timestamp'] = time();
}


/**
 * Update state and data, that will be saved with the group content.
 *
 * @param $alter
 *   Array keyed by "state" and "data" passed by reference.
 *   The data passed by reference.
 * @param $obj_type
 * @param $object
 * @param $field
 * @param $instance
 * @param $langcode
 * @param $items
 */
function hook_og_field_update (&$alter, $obj_type, $object, $field, $instance, $langcode, $items) {
  // Reject a group content when it's updated.
  // It's up to the implementing module to act on the data.
  $alter['state'] = 'updated, approve urgently';
}

/**
 * Alter the groups an object is associated with.
 *
 * User subscription for example is passed through here, allows modules to
 * change them. Also user unsubscription is using this function. To identify
 * the type of action, we get the $op argument.
 *
 * @param $edit
 *   An array with the groups the object will be associated with, passed by
 *   reference.
 * @param $account
 *   The user being subscribed.
  @param $op
 *   Optional; The operation that is being done (e.g. "subscribe user" or
 *   "unsubscribe content").
 */
function hook_og_set_association_alter(&$gids, $account, $op = '') {
  if ($op == 'subscribe user') {
    //   Subscribe the user to another group.
    $gids[] = 1;
  }
}


function hook_og_users_roles_grant($nid, $uid, $rid) {

}

function hook_og_users_roles_revoke($nid, $uid, $rid) {

}

/**
 * Define the table, ID and label columns of a fieldable entity.
 *
 * This is used so groups can appear in the OG audience field with their
 * sanitized name.
 */
function hook_og_entity_get_info() {
  return array(
    'my_entity' => array(
      'table' => 'foo',
      'id' => 'bar',
      'label' => 'baz',
    ),
  );
}

/**
 * Alter get entity label definitions.
 *
 */
function hook_og_entity_get_info_alter(&$data) {
  if (!empty($data['my_entity'])) {
    $data['my_entity']['table'] = 'new_foo';
  }
}

/**
 * @} End of "addtogroup hooks".
 */