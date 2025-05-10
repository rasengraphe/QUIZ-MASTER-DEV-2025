<?php
class CategoryModel extends Model {
    public function getAllCategories() {
        try {
            $stmt = $this->db->query("SELECT * FROM quiz_question_category");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des catégories: " . $e->getMessage());
            return [];
        }
    }
}
?>