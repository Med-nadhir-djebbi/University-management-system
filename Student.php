<?php
class Student {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT e.*, s.designation as section_name 
            FROM etudiant e
            JOIN section s ON e.section_id = s.id
        ");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, s.designation as section_name 
            FROM etudiant e
            JOIN section s ON e.section_id = s.id
            WHERE e.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($id, $name, $birthday, $section_id) {
        $stmt = $this->pdo->prepare("
            INSERT INTO etudiant (id, Name, birthday, section_id) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$id, $name, $birthday, $section_id]);
    }
    
    public function update($id, $name, $birthday, $section_id) {
        $stmt = $this->pdo->prepare("
            UPDATE etudiant 
            SET Name=?, birthday=?, section_id=? 
            WHERE id=?
        ");
        return $stmt->execute([$name, $birthday, $section_id, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM etudiant WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>