# Guide de déploiement sur OVH

## Prérequis
- Un compte OVH avec un hébergement web
- Un client FTP (FileZilla, WinSCP, etc.)
- Accès à phpMyAdmin ou un autre outil de gestion de base de données

## 1. Préparation du projet

### Configuration pour la production
Modifiez votre fichier de configuration de base de données:

```php
// Modifiez le fichier config/database.php avec les informations OVH
$db_host = "votre_serveur_mysql.sql.db";  // Fourni par OVH
$db_name = "votre_nom_de_base";           // Votre nom de base de données sur OVH
$db_user = "votre_utilisateur";           // Votre nom d'utilisateur BDD sur OVH
$db_pass = "votre_mot_de_passe";          // Votre mot de passe BDD sur OVH
```

### Optimisation pour la production
1. Désactivez l'affichage des erreurs PHP dans `index.php`:
```php
// Commentez ou modifiez ces lignes pour la production
// error_reporting(E_ALL);
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
```

2. Compressez les fichiers CSS et JavaScript si ce n'est pas déjà fait.

## 2. Export de la base de données

1. Ouvrez phpMyAdmin sur votre environnement local (WAMP)
2. Sélectionnez votre base de données
3. Cliquez sur "Exporter"
4. Choisissez "Personnalisée" pour configurer l'export
5. Assurez-vous que l'option "Structure et données" est sélectionnée
6. Cochez "Ajouter DROP TABLE" pour éviter les conflits
7. Cliquez sur "Exécuter" pour télécharger le fichier SQL

## 3. Création de la base de données sur OVH

1. Connectez-vous à votre espace client OVH
2. Accédez à la section "Hébergement" puis "Bases de données"
3. Créez une nouvelle base de données si nécessaire
4. Notez les informations de connexion (serveur, nom d'utilisateur, mot de passe)
5. Accédez à phpMyAdmin via l'interface OVH
6. Importez le fichier SQL précédemment exporté

## 4. Transfert des fichiers via FTP

1. Lancez votre client FTP (FileZilla par exemple)
2. Entrez les informations de connexion fournies par OVH:
   - Hôte: ftp.votre-domaine.com (ou l'adresse FTP fournie par OVH)
   - Nom d'utilisateur: votre identifiant FTP OVH
   - Mot de passe: votre mot de passe FTP OVH
   - Port: 21 (port par défaut)

3. Naviguez jusqu'au dossier www/ ou au répertoire racine de votre hébergement
4. Transférez tous les fichiers et dossiers de votre projet QUIZ-MASTER-DEV-2025
   - Sélectionnez tous les fichiers de c:\wamp64\www\QUIZ-MASTER-DEV-2025\
   - Glissez-déposez dans le dossier approprié sur votre hébergement

## 5. Configuration des droits d'accès

Une fois le transfert terminé, assurez-vous que les permissions des dossiers sont correctement configurées:

1. Dossier "uploads" et ses sous-dossiers: 755 (rwxr-xr-x)
2. Fichiers images/téléchargements: 644 (rw-r--r--)
3. Fichiers PHP, HTML, CSS, JS: 644 (rw-r--r--)

Si votre hébergement OVH propose un outil de gestion des permissions dans l'interface, vous pouvez l'utiliser. Sinon, via FTP:
- Clic droit sur le dossier/fichier → Permissions/Attributs
- Définissez les permissions appropriées

## 6. Configuration du .htaccess (si nécessaire)

Si vous utilisez des URL propres, assurez-vous que votre fichier .htaccess est correctement configuré:

```apache
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?action=$1 [QSA,L]
```

## 7. Test et dépannage

1. Accédez à votre site via votre nom de domaine: http://votre-domaine.com/
2. Vérifiez que toutes les fonctionnalités fonctionnent correctement:
   - Inscription et connexion
   - Création et participation aux quiz
   - Upload d'images
   - Etc.

3. Problèmes courants:
   - Erreur de connexion à la base de données: vérifiez vos paramètres dans config/database.php
   - Erreur 500: vérifiez les logs d'erreur sur votre espace OVH
   - Problèmes de droits: vérifiez les permissions des dossiers et fichiers
   - Chemins incorrects: assurez-vous que tous les chemins sont relatifs et non absolus

## 8. Sécurité post-déploiement

1. Changez les mots de passe par défaut des comptes administrateur
2. Vérifiez que les informations sensibles (comme les mots de passe de BDD) ne sont pas exposées
3. Activez HTTPS si votre hébergement OVH le permet

## 9. Maintenance

Pour les futures mises à jour:

1. Testez d'abord les modifications sur un environnement de développement
2. Faites une sauvegarde complète du site en production avant toute mise à jour
3. Effectuez les mises à jour pendant les périodes de faible trafic
4. Testez à nouveau après la mise à jour

Pour toute assistance supplémentaire, consultez la documentation OVH ou contactez leur support client.
