<?php
// $Id: update-og.php,v 1.1 2005/10/20 10:20:30 killes Exp $
include_once "includes/bootstrap.inc";
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

db_query("ALTER TABLE `og_uid` ADD `og_role` int(1) NOT NULL default '0'");

$result = db_query("SELECT * FROM {node_access} WHERE realm = 'og_uid' AND grant_view = 1");
while ($object = db_fetch_object($result)) {
  $sql = "REPLACE INTO {og_uid} (nid, uid, og_role) VALUES (%d, %d, %d)";
  db_query($sql, $gid, $uid, ($object->grant_view + $object->grant_update));
}
?>
