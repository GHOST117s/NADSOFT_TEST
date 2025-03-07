<?php

require_once 'database.php';

class Members
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }
    // get all members
    public function getAllMembers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Members");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // get parent 
    public function getMembersByParent($parentId = null) {
        $query = "SELECT * FROM Members WHERE ParentId " . ($parentId === null ? "IS NULL" : "= :parent_id") . " ORDER BY Id";
        $stmt = $this->conn->prepare($query);
        
        if ($parentId !== null) {
            $stmt->bindParam(':parent_id', $parentId);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // add new member
    public function addMember($name, $parentId) {
        try {
            $query = "INSERT INTO Members (Name, ParentId, CreatedDate) 
                     VALUES (:name, :parent_id, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $parentId = ($parentId === '') ? null : $parentId; // Convert empty to NULL
            $stmt->bindParam(':parent_id', $parentId, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'success' => true,
                'id' => $this->conn->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    

    // member tree 
    public function displayMembersTree($parentId = null) {
        $members = $this->getMembersByParent($parentId);
    
        if (empty($members)) {
            return '';
        }
    
        $html = '<ul>';
        foreach ($members as $member) {
            $html .= '<li>' . $member['Name'];
            
            // Fetch children recursively
            $children = $this->displayMembersTree($member['Id']);
            if (!empty($children)) {
                $html .= $children;
            }
    
            $html .= '</li>';
        }
        $html .= '</ul>';
    
        return $html;
    }

    // get options
    public function getParentOptions() {
        $members = $this->getAllMembers();
        $html = '';
        
        foreach ($members as $member) {
            $html .= '<option value="' . $member['Id'] . '">' . $member['Name'] . '</option>';
        }
        
        return $html;
    }


    

}
