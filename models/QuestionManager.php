<?php
// filepath: c:\wamp64\www\QUIZ-MASTER-DEV-2025\models\QuestionManager.php

class QuestionManager {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Récupère toutes les questions (simplifié)
    public function getAllQuestions() {
                $sql = "SELECT * FROM quiz_question ORDER BY Id_question ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupère une question par son ID
    public function getQuestionById($questionId) {
        $stmt = $this->db->prepare("SELECT * FROM quiz_question WHERE Id_question = ?");
        $stmt->execute([$questionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Récupère toutes les réponses associées à une question
    public function getAnswersForQuestion($questionId) {
        $stmt = $this->db->prepare("SELECT * FROM quiz_question_answer WHERE Id_question = ? ORDER BY Id_question_answer ASC");
        $stmt->execute([$questionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

// Version simplifiée qui ne tient plus compte des catégories et difficultés
    public function updateQuestion($data) {
        $sql = "UPDATE quiz_question 
                SET text = ?, 
                                        picture = ? 
                WHERE Id_question = ?";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['text'],
                        $data['picture'],
            $data['Id_question']
        ]);
        return $result;
    }

    // Version simplifiée pour ajouter une question
    public function addQuestion($data) {
        // On définit des valeurs par défaut pour les champs obligatoires
        $adminId = $data['Id_admin_editor'] ?? 1;
        
        $sql = "INSERT INTO quiz_question (text, picture, Id_admin_editor, Id_question_category, Id_question_difficulte) 
                VALUES (?, ?, ?, 1, 1)"; // Valeurs par défaut pour catégorie et difficulté

        $stmt = $this->db->prepare($sql);
$stmt->execute([
            $data['text'],
            $data['picture'] ?? null,
            $adminId
        ]);
        
        return $this->db->lastInsertId();
    }

    // Ajoute une réponse pour une question
    public function addQuestionAnswer($data) {
        $stmt = $this->db->prepare("INSERT INTO quiz_question_answer (text, correct, Id_question) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['text'],
            $data['correct'],
            $data['Id_question']
        ]);
    }

    // Récupère les détails d'une question (utile pour l'affichage)
    public function getQuestionDetails($questionId) {
        $stmt = $this->db->prepare("SELECT * FROM quiz_question WHERE Id_question = ?");
        $stmt->execute([$questionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Méthode qui sera utilisée uniquement pour récupérer les questions, sans se préoccuper des catégories
    public function getQuestionsByIds($questionIds) {
        if (empty($questionIds)) {
            return [];
        }
        
        $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
        $sql = "SELECT * FROM quiz_question WHERE Id_question IN ($placeholders) ORDER BY Id_question ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($questionIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}