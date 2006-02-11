<?php
// $Id: update-og.php,v 1.4 2006/02/11 23:46:12 weitzman Exp $
include_once "includes/bootstrap.inc";
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

db_query("ALTER TABLE `og_uid` ADD `og_role` int(1) NOT NULL default '0'");
db_query("ALTER TABLE `og_uid` ADD `is_active` int(1) default '0'");
db_query("ALTER TABLE `og_uid` ADD `is_admin` int(1) default '0'");

$result = db_query("SELECT * FROM {node_access} WHERE realm = 'og_uid'");
while ($object = db_fetch_object($result)) {
  $sql = "REPLACE INTO {og_uid} (nid, uid, og_role, is_admin, is_active) VALUES (%d, %d, %d, %d, %d)";
  db_query($sql, $object->nid, $object->gid, ($object->grant_view + $object->grant_update), $object->grant_update, $object->grant_view);
}

?>
