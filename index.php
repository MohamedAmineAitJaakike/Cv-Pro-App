<?php 

include 'frontend/includes/header.php'; 
?>

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h1 class="display-4 fw-bold">Bienvenue sur CvPro</h1>
            <p class="lead my-4">
                Votre solution tout-en-un pour créer, gérer et exporter des CV professionnels en quelques clics. Que vous soyez étudiant ou jeune diplômé, notre plateforme est conçue pour vous aider à mettre en valeur vos compétences et vos expériences de manière simple et élégante.
            </p>
            <p>
                Inscrivez-vous gratuitement, remplissez vos informations via nos formulaires dynamiques, choisissez parmi nos modèles modernes et générez un CV parfait au format PDF.
            </p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/Cv_Pro/frontend/formulaire.php" class="btn btn-primary btn-lg px-4 me-md-2">Aller à mon CV</a>
                <?php else: ?>
                    <a href="/Cv_Pro/frontend/register.php" class="btn btn-primary btn-lg px-4 me-md-2">Créez votre CV</a>
                    <a href="/Cv_Pro/frontend/login.php" class="btn btn-outline-secondary btn-lg px-4">Se connecter</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-5 text-center">
            <img src="assets/images/image1.png" class="img-fluid rounded-3 shadow-lg" alt="Illustration de création de CV">
        </div>
    </div>
</div>

<?php 

include 'frontend/includes/footer.php'; 
?>