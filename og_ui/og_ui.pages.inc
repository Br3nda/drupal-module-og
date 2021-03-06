<?php
// $Id: og_ui.pages.inc,v 1.1 2010/01/03 15:24:12 amitaibu Exp $

/**
 * @file
 * Page callbacks for Organic groups module.
 */

/**
 * Manage user subscription to a group.
 *
 * @param $node
 *   The group node object.
 * @param $uid
 *   Optional; The user ID of the subscribing user. If empty the current user
 *   will be used.
 */
function og_ui_subscribe($obj_type, $oid, $uid = NULL) {
  if ($group = og_get_group($obj_type, $oid)) {
    if (empty($uid)) {
      global $user;
      $account = $user;

      if (!$account->uid) {
        $dest = drupal_get_destination();
        if (variable_get('user_register', 1)) {
          drupal_set_message(t('In order to join this group, you must <a href="!login">login</a>. After you have successfully done so, you will need to request membership again.', array('!login' => url("user/login", array('query' => $dest)))));
        }
        else {
          drupal_set_message(t('In order to join this group, you must <a href="!login">login</a> or <a href="!register">register</a> a new account. After you have successfully done so, you will need to request membership again.', array('!register' => url("user/register", array('query' => $dest)), '!login' => url("user/login", array('query' => $dest)))));
        }
        drupal_goto('user');
      }
    }
    else {
      $account = user_load($uid);
    }

    // Check user isn't already subscribed.
    if (in_array($group->gid, og_get_object_groups('user', $account))) {
      drupal_set_message(t('@user is already a member the group @group.', array('@user' => $account->name, '@group' => $node->title[LANGUAGE_NONE][0]['safe'])));
      // TODO: get url.
      // drupal_goto('node/' . $node->nid);
    }
    else {
      // Show the user a subscription confirmation.
      return drupal_get_form('og_ui_confirm_subscribe', $group, $account);
    }
  }
  // Not a valid group node.
  drupal_not_found();

}

/**
 * Confirm subscribe form.
 */
function og_ui_confirm_subscribe($form, &$form_state, $group, $account) {
  $form['group'] = array('#type' => 'value', '#value' => $group);
  $form['account'] = array('#type' => 'value', '#value' => $account);
  if (!og_user_access($group->gid, 'subscribe without approval')) {
    $form['request'] = array(
      '#type' => 'textarea',
      '#title' => t('Additional details'),
      '#description' => t('Add any detail which will help an administrator decide whether to approve or deny your membership request.')
    );
  }
  $label = check_plain(og_entity_get_label($group->obj_type, $group->oid));
  return confirm_form($form, t('Are you sure you want to join the group %title?', array('%title' => $label)), "$group->obj_type/$group->oid", ' ', t('Join'), t('Cancel'));
}

/**
 * Submit handler; Confirm OG membership.
 */
function og_ui_confirm_subscribe_submit($form, &$form_state) {
  $request = !empty($form_state['values']['request']) ? $form_state['values']['request'] : '';
  // The group object.
  $group = $form_state['values']['group'];

  $groups = array(
    'gid' => $group->gid,
    // Get the state of the group, and decide the user's state accordingly.
    'state' => og_user_access($group->gid, 'subscribe without approval') ? OG_STATE_ACTIVE : OG_STATE_PENDING,
  );

  og_subscribe_user(array($groups), NULL, FALSE, $request);

  $form_state['redirect'] = "$group->obj_type/$group->oid";
}


/**
 * Confirm OG unsubscription form.
 *
 * The unsubscribing user is always the acting user - like this we make sure
 * no malicious user will unsubscribe another user. Administrators can reject or
 * ban another group member from the "people" page.
 */
function og_ui_unsubscribe($obj_type, $oid) {
  if ($group = og_get_group($obj_type, $oid)) {
    global $user;

    $label = check_plain(og_entity_get_label($group->obj_type, $group->oid));
    // Check the user isn't the manager of the group.
    $object= current(entity_load($group->obj_type, array($group->oid)));
    if (!empty($object->uid) && $object->uid != $user->uid) {
      // Show the user a subscription confirmation.
      return drupal_get_form('og_ui_confirm_unsubscribe', $group, $user);
    }
    else {
      drupal_set_message(t('As the manager of %group, you can not leave the group.', array('%group' => $label)));
      drupal_goto("$group->obj_type/$group->oid");
    }
  }
  // Not a valid group.
  drupal_not_found();
}

/**
 * Confirm unsubscribe form.
 */
function og_ui_confirm_unsubscribe($form, &$form_state, $group, $account) {
   $form['group'] = array('#type' => 'value', '#value' => $group);
   $form['account'] = array('#type' => 'value', '#value' => $account);

   $label = check_plain(og_entity_get_label($group->obj_type, $group->oid));
   return confirm_form($form, t('Are you sure you want to unsubscribe from the group %title?', array('%title' => $label)), "$group->obj_type/$group->oid", ' ', t('Remove'), t('Cancel'));
}

/**
 * Submit handler; Confirm OG unsubscription.
 */
function og_ui_confirm_unsubscribe_submit($form, &$form_state) {
  $group = $form_state['values']['group'];

  og_unsubscribe_user(array($group->gid), $form_state['values']['account']);

  // Determine where to go next - Group if accessible, or else site front page.
  $form_state['redirect'] = "$group->obj_type/$group->oid";
}