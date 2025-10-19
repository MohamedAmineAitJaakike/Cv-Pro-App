<h1 align="center">Cv_Pro - Générateur de CV Dynamiques</h1>

<p align="center">
  Une application web complète permettant de créer, gérer et exporter des CV professionnels à partir de formulaires dynamiques. 
  <br />
  Le projet inclut un dashboard admin, plusieurs modèles de CV, et des fonctionnalités d'export PDF et d'envoi par email.
</p>

<p align="center">
  <img src="demo/cv-pro-demo.gif" alt="Démo animée du générateur de CV" width="85%"/>
</p>

---

## ✨ Fonctionnalités Principales

* **✨ Dashboard Admin :** Interface sécurisée pour gérer les utilisateurs, les CV créés et les modèles de CV.
* **📝 Formulaires Dynamiques :** Saisie fluide des informations (profil, expériences, formations, compétences) avec la possibilité d'ajouter/supprimer des sections.
* **📄 Génération PDF :** Utilise **DomPDF** pour générer des CV professionnels au format PDF à partir des données saisies.
* **🎨 Modèles Multiples :** L'utilisateur peut choisir entre plusieurs modèles (templates simples, avancés, professionnels) avant l'export.
* **📧 Envoi par Email :** Intégration de **PHPMailer** pour permettre aux utilisateurs d'envoyer leur CV généré directement par email.
* **🔐 Gestion Utilisateurs :** Système complet d'inscription et de connexion pour que chaque utilisateur gère ses propres CV.

---

## 🛠️ Technologies Utilisées

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript (AJAX)"/>
  <img src="https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap"/>
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3"/>
  
  <img src="https://img.shields.io/badge/DomPDF-E4422E?style=for-the-badge&logo=php&logoColor=white" alt="DomPDF"/>
  <img src="https://img.shields.io/badge/PHPMailer-0078D4?style=for-the-badge&logo=php&logoColor=white" alt="PHPMailer"/>
</p>

---

## 🚀 Installation et Lancement

1.  **Cloner le dépôt :**
    ```bash
    git clone https://github.com/MohamedAmineAitJaakike/Cv-Pro-App.git
    cd Cv_Pro
    ```

2.  **Installer les dépendances (TRÈS IMPORTANT) :**
    Ce projet utilise des librairies PHP (`DomPDF`, `PHPMailer`) gérées par Composer.
    ```bash
    composer install
    ```

3.  **Base de Données :**
    * Ouvrez phpMyAdmin et créez une base de données nommée `db_cv_pro`.
    * Importez le fichier **`Base de données cv_pro.sql`** (fourni dans ce projet) pour créer toutes les tables nécessaires.

4.  **Configuration :**
    * Localisez le fichier de configuration de la base de données (ex: `config/database.php` ou `.env`).
    * Mettez à jour les identifiants (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`) pour qu'ils correspondent à votre environnement local (XAMPP/WAMP).

5.  **Lancer le Serveur :**
    * Placez le dossier `Cv_Pro` dans votre répertoire `www` (WAMP) ou `htdocs` (XAMPP).
    * Lancez vos services Apache et MySQL.

6.  **Accéder au site :**
    * Ouvrez votre navigateur et allez sur `http://localhost/Cv_Pro/frontend/` (ou l'URL de votre page d'accueil).
    * Accédez au dashboard admin via `http://localhost/Cv_Pro/backend/` (ou l'URL de votre panel admin).

---

## 📂 Structure du Projet

*Basé sur votre capture d'écran `image_c61ac5.png`.*

```
Cv_Pro/
├── assets/                 # CSS, JS, Images, Polices
├── backend/                # Dashboard Admin (PHP, HTML)
├── config/                 # Fichiers de configuration (BDD, etc.)
├── export/                 # (Possiblement) Dossier temporaire pour les PDF générés
├── frontend/               # Espace utilisateur (PHP, HTML)
├── storage/                # Stockage (logs, cache, etc.)
├── templates/              # Modèles de CV (HTML/CSS pour DomPDF)
├── uploads/                # Photos de profil des utilisateurs
├── vendor/                 # Dépendances (DomPDF, PHPMailer) - Géré par Composer
│
├── Base de données cv_pro.sql # Script SQL de la BDD
└── composer.json           # Définition des dépendances
```

---

## 🧑‍💻 Auteur

* **Mohamed Amine AIT JAAKIK**
* `mohamedamine.aitjaakike@etu.uae.ac.ma`
* ENSA Tétouan - Université Abdelmalek Essaâdi
