<?php
// $Id: og_context_plugin_argument_default_group_context.inc,v 1.1.2.1 2010/07/22 03:57:31 amitaibu Exp $
/**
 * @file
 * Contains the group context argument default plugin.
 */

/**
 * The group context argument default handler.
 */
class og_context_plugin_argument_default_group_context extends views_plugin_argument_default {

  /**
   * Return the group context argument.
   */
  function get_argument() {
    $return = FALSE;
    if ($group = og_context()) {
      $return = $group->gid;
    }
    return $return;
  }
}
