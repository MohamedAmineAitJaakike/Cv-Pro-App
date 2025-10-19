<?php
// On démarre la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CvPro - Créateur de CV Professionnel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Cv_Pro/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand d-flex align-items-center" href="/Cv_Pro/index.php">
            <img src="/Cv_Pro/assets/images/logo.png" alt="Logo CvPro" width="35" height="35" class="d-inline-block align-top me-2">
            <span>CvPro</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'candidat'): ?>
                        <li class="nav-item"><a class="nav-link px-3" href="/Cv_Pro/frontend/formulaire.php"><i class="fas fa-file-alt me-2"></i>Mon CV</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="/Cv_Pro/frontend/preview_cv.php"><i class="fas fa-eye me-2"></i>Prévisualiser</a></li>
                    <?php elseif ($_SESSION['user_role'] === 'RH'): ?>
                        <li class="nav-item"><a class="nav-link px-3" href="/Cv_Pro/frontend/dashboard_rh.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard RH</a></li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-info px-3" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur'); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><h6 class="dropdown-header"><i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur'); ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if ($_SESSION['user_role'] === 'candidat'): ?>
                                <li><a class="dropdown-item" href="/Cv_Pro/frontend/formulaire.php"><i class="fas fa-edit me-2"></i>Éditer mon CV</a></li>
                                <li><a class="dropdown-item" href="/Cv_Pro/frontend/preview_cv.php"><i class="fas fa-eye me-2"></i>Voir mon CV</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="/Cv_Pro/backend/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link px-3" href="/Cv_Pro/frontend/login.php"><i class="fas fa-sign-in-alt me-2"></i>Connexion</a></li>
                    <li class="nav-item"><a class="nav-link px-3 btn btn-outline-light ms-2" href="/Cv_Pro/frontend/register.php"><i class="fas fa-user-plus me-2"></i>Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="main-content">
    <div class="container-fluid px-3 px-lg-4">