# Quiz Master Dev 2025

Une plateforme interactive de quiz en ligne développée en PHP, permettant aux utilisateurs de tester leurs connaissances en développement web et à d'autres sujets.

## Caractéristiques

- Architecture MVC personnalisée
- Interface responsive adaptée à tous les appareils
- Gestion complète des quiz, questions et utilisateurs
- Différents niveaux de difficulté
- Système de scores et statistiques
- Partage des résultats sur les réseaux sociaux

## Prérequis

- PHP 8.0 ou supérieur
- MySQL 8.0 ou supérieur
- Serveur web (Apache recommandé)
- Navigateur web moderne

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/votre-nom/QUIZ-MASTER-DEV-2025.git
   ```

2. **Configurer la base de données**
   - Créer une base de données MySQL
   - Importer le fichier SQL situé dans `database/quiz_master.sql`
   - Modifier les informations de connexion dans `config/database.php`

3. **Configurer le serveur web**
   - Pointer le document root vers le dossier du projet
   - Assurer que le module de réécriture d'URL est activé (si applicable)

4. **Permissions des dossiers**
   ```bash
   chmod 755 uploads/ -R
   ```

5. **Accéder à l'application**
   - Ouvrir un navigateur et accéder à l'URL du projet

## Utilisation

### Compte administrateur par défaut
- Email: admin@example.com
- Mot de passe: Admin123!

### Interface joueur
- S'inscrire/se connecter
- Sélectionner et participer à un quiz
- Consulter ses résultats et statistiques
- Personnaliser son profil

### Interface administrateur
- Gérer les utilisateurs
- Créer/modifier/supprimer des quiz et questions
- Gérer les catégories et niveaux de difficulté
- Consulter les statistiques de la plateforme

## Documentation

La documentation complète est disponible dans le dossier `documentation/` :

- [Guide utilisateur](documentation/USER_GUIDE.md)
- [Documentation technique](documentation/TECHNICAL.md)
- [Dossier de projet](documentation/dossier_projet.md)
- [Guide de déploiement](documentation/deploiement.md)

## Licence

Ce projet est distribué sous licence MIT.

## Contact

Pour toute question ou suggestion, n'hésitez pas à me contacter à [votre-email@example.com].
