<?php
// $Id: update-og.php,v 1.1.4.3 2006/01/20 01:22:20 weitzman Exp $

include_once "includes/bootstrap.inc";
include_once 'includes/common.inc';

// backport of changes in HEAD that landed on 2005-10-19
db_query("ALTER TABLE `og_uid` ADD `og_role` int(1) NOT NULL default '0'");
db_query("ALTER TABLE `og_uid` ADD `is_active` int(1) default '0'");
db_query("ALTER TABLE `og_uid` ADD `is_admin` int(1) default '0'");

$result = db_query("SELECT * FROM {node_access} WHERE realm = 'og_uid' AND grant_view = 1");
while ($object = db_fetch_object($result)) {
  $sql = "REPLACE INTO {og_uid} (nid, uid, og_role) VALUES (%d, %d, %d)";
  db_query($sql, $gid, $uid, ($object->grant_view + $object->grant_update));
}

// this came from updates to 4.6 branch on 1.16.06
$sql = "SELECT nid FROM {node} WHERE type = 'og'";
$result = db_query($sql);
while ($row = db_fetch_object($result)) {
  $sql = "REPLACE INTO {node_access} (nid, gid, realm, grant_view, grant_update, grant_delete) VALUES (%d, %d, 'og_group', 1, 1, 1)";
  db_query($sql, $row->nid, $row->nid);
}

$sql = "DELETE FROM {node_access} WHERE realm = 'og_uid'";
db_query($sql);


?>
