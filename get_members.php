<?php

require_once 'database.php';
require_once 'Members.php';

$db = new Database();
$member = new Members($db);

echo $member->displayMembersTree();

?>