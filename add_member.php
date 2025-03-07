<?php 

require_once 'database.php';
require_once 'Members.php';

header('Content-Type: application/json');

try{
    $name = $_POST['name'] ?? '';
    $parent = $_POST['parent'] ?? null;

    if(empty($name)) {
        throw new Exception('Name is required');
    }

    $db = new Database();
    $member = new Members($db);
    $result = $member->addMember($name, $parent);


    if($result['success']){
            echo json_encode([
                'success' => true,
                'member' => [
                    'id' => $result['id'],
                    'name' => $name,
                    'parent' => $parent
                ]
            ]);
    }
    else {
        throw new Exception($result['message']);
    }

}
catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    return;
}
?>