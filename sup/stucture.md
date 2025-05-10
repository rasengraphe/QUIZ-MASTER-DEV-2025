quiz_project/
├── index.php # Point d'entrée de l'application (Front Controller)
├── config/ # Fichiers de configuration
│ └── database.php # Connexion à la base de données
├── controllers/ # Logique de l'application
│ ├── AdminController.php
│ ├── QuizController.php
│ ├── UserController.php
│ └── HomeController.php
├── models/ # Interaction avec la base de données
│ ├── AdminModel.php
│ ├── QuizModel.php
│ ├── UserModel.php
│ └── QuestionModel.php
├── views/ # Présentation des données
│ ├── admin/
│ │ ├── dashboard.php
│ │ ├── question_list.php
│ │ ├── question_form.php
│ │ └── user_list.php
│ ├── quiz/
│ │ ├── take.php
│ │ └── results.php
│ ├── user/
│ │ ├── login.php
│ │ ├── register.php
│ │ └── profile.php
│ └── home.php
├── core/ # Classes et fonctions principales
│ ├── Controller.php # Classe de base des contrôleurs
│ └── Database.php # Classe de gestion de la base de données (si vous n'utilisez pas directement PDO dans les modèles)
├── public/ # Ressources publiques (CSS, JS, images)
│ ├── css/
│ │ └── style.scss # Fichier SCSS principal
│ │ └── style.css # Fichier CSS compilé (généré par SCSS)
│ ├── js/
│ │ └── script.js # JavaScript principal
│ ├── img/
│ │ └── avatars/ # Dossier pour les avatars
│ │ ├── avatar1.png
│ │ ├── avatar2.png
│ │ └── ...
│ └── uploads/ # Dossier pour les images téléchargées (questions, etc.)
└── .htaccess # (Optionnel) Pour la réécriture d'URL
