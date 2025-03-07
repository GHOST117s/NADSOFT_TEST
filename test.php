<?php 

require_once 'database.php';
require_once 'Members.php';


$member = new Members();

// Test getAllMembers method
echo "<h2>All Members:</h2>";
$allMembers = $member->getAllMembers();
echo '<pre>';
print_r($allMembers);
echo '</pre>';

// Test getMembersByParent method with no parentId (should return all members)
echo "<h2>All Members (getMembersByParent with null):</h2>";
$allMembersByParent = $member->getMembersByParent(null);
echo '<pre>';
print_r($allMembersByParent);
echo '</pre>';

// Test getMembersByParent method with a specific parentId
echo "<h2>Members by Parent (ParentId = 0):</h2>";
$membersByParent = $member->getMembersByParent(0);
echo '<pre>';
print_r($membersByParent);
echo '</pre>';

// Test addMember method
echo "<h2>Adding a New Member:</h2>";
$addResult = $member->addMember("Test Member", 0);
echo '<pre>';
print_r($addResult);
echo '</pre>';

// Test displayMembersTree method
echo "<h2>Members Tree:</h2>";
$membersTree = $member->displayMembersTree();
echo $membersTree;

// Test getParentOptions method
echo "<h2>Parent Options:</h2>";
$parentOptions = $member->getParentOptions();
echo "<select>";
echo $parentOptions;
echo "</select>";

?>