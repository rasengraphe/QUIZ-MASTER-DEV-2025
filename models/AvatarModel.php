<?php
require_once 'core/Model.php';

class AvatarModel extends Model {
    
    /**
     * Récupère tous les avatars disponibles
     * 
     * @return array Liste des avatars avec chemins complets
     */
    public function getAllAvatars() {
        try {
            $stmt = $this->db->query("SELECT * FROM quiz_avatar ORDER BY Id_Avatar");
            $avatars = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Ajouter le chemin d'accès aux images pour chaque avatar avec le bon nom de fichier
            foreach ($avatars as &$avatar) {
                $avatar['path'] = 'public/img/avatars/avatar' . $avatar['Id_Avatar'] . '.png';
            }
            
            return $avatars;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des avatars: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère un avatar par son ID
     * 
     * @param int $avatarId ID de l'avatar
     * @return array|false Données de l'avatar ou false si non trouvé
     */
    public function getAvatarById($avatarId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM quiz_avatar WHERE Id_Avatar = ?");
            $stmt->execute([$avatarId]);
            $avatar = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($avatar) {
                $avatar['path'] = 'public/img/avatars/avatar' . $avatar['Id_Avatar'] . '.png';
            }
            
            return $avatar;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'avatar: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ajoute un nouvel avatar
     * 
     * @param string $path Chemin de l'image de l'avatar
     * @return int|false ID de l'avatar créé ou false en cas d'échec
     */
    public function addAvatar($path) {
        $sql = "INSERT INTO quiz_avatar (path) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([$path]);
        return $success ? $this->db->lastInsertId() : false;
    }
}
?>