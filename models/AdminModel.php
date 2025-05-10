<?php
// quiz_project/models/AdminModel.php
class AdminModel extends Model {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getAdminById($id) {
        $stmt = $this->db->prepare("SELECT * FROM quiz_admin_editor WHERE Id_admin_editor = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllAdmins() {
        $stmt = $this->db->query("SELECT * FROM quiz_admin_editor");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ... Autres méthodes pour la gestion des administrateurs
}
?>