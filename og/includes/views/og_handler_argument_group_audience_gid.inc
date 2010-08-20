<?php
// $Id: og_handler_argument_group_audience_gid.inc,v 1.1.2.2 2010/08/20 10:45:54 amitaibu Exp $
/**
 * @file
 * Contains the group argument for field group audience.
 */

/**
 * Field group audience - gid argument handler.
 */
class og_handler_argument_group_audience_gid extends views_handler_argument_numeric {

  /**
   * Override title(); Get the label of the entity type.
   */
  function title() {
    $title = '';
    if ($entity = entity_load($this->options['og_type'], array($this->argument))) {
      $entity = current($entity);

      // FIXME: Implement when http://drupal.org/node/629484
      $title = '@placeholder';
    }

    return $title;
  }

}
