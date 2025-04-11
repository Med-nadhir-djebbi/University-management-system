<?php
class Section {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM section");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM section WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($id, $designation, $description) {
        $stmt = $this->pdo->prepare("
            INSERT INTO section (id, designation, description) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$id, $designation, $description]);
    }
    
    public function update($id, $designation, $description) {
        $stmt = $this->pdo->prepare("
            UPDATE section 
            SET designation=?, description=? 
            WHERE id=?
        ");
        return $stmt->execute([$designation, $description, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sections WHERE id = :id");
        return $stmt->execute(['id' => $id]); 
    }
    
}
?>