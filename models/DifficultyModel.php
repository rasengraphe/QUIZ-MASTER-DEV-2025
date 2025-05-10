<?php
class DifficultyModel extends Model {
    public function getAllDifficulties() {
        try {
            $stmt = $this->db->query("SELECT * FROM quiz_question_difficulte");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des niveaux de difficulté: " . $e->getMessage());
            return [];
        }
    }
}
?>