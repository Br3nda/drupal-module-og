<?php
// $Id: og-update-20060211.php,v 1.1.2.1 2006/02/11 23:40:28 weitzman Exp $

include_once "includes/bootstrap.inc";
include_once 'includes/common.inc';

// backport of changes in HEAD that landed on 2005-10-19
db_queryd("ALTER TABLE `og_uid` ADD `og_role` int(1) NOT NULL default '0'");
db_queryd("ALTER TABLE `og_uid` ADD `is_active` int(1) default '0'");
db_queryd("ALTER TABLE `og_uid` ADD `is_admin` int(1) default '0'");

// migrate subscriptions to og_uid table
$result = db_query("SELECT * FROM {node_access} WHERE realm = 'og_uid'");
while ($object = db_fetch_object($result)) {
  $sql = "REPLACE INTO {og_uid} (nid, uid, og_role, is_active, is_admin) VALUES (%d, %d, %d, %d, %d)";
  db_queryd($sql, $object->nid, $object->gid, ($object->grant_view + $object->grant_update), $object->grant_view, $object->grant_update);
}
$sql = "DELETE FROM {node_access} WHERE realm = 'og_uid'";
db_queryd($sql);

// feb 2006
$sql = "SELECT DISTINCT(n.nid) FROM {node} n INNER JOIN {node_access} na ON n.nid = na.nid WHERE type != 'og' AND na.realm = 'og_group'";
$result = db_queryd($sql);
while ($row = db_fetch_object($result)) {
  $sql = "UPDATE {node_access} SET grant_view=1, grant_update=1, grant_delete=1 WHERE realm = 'og_group' AND nid = %d AND gid != 0";
  db_queryd($sql, $row->nid);
}

$sql = "SELECT nid FROM {node} WHERE type = 'og'";
$result = db_queryd($sql);
while ($row = db_fetch_object($result)) {
  $sql = "REPLACE INTO {node_access} (nid, gid, realm, grant_view, grant_update, grant_delete) VALUES (%d, %d, 'og_group', 1, 1, 0)";
  db_queryd($sql, $row->nid, $row->nid);
}

?>