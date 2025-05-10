<?php
// quiz_project/models/Model.php
class Model {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Exemple de méthode générique (à utiliser avec prudence et à adapter)
    public function getAll($table) {
        $stmt = $this->db->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($table, $id, $idColumn = 'id') {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $idColumn = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ... Autres méthodes génériques si besoin
}
?>