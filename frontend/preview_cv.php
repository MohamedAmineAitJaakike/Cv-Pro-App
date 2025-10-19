<?php
include 'includes/header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['user_id'];
?>

<div class="text-center">
    <h2 class="mb-3">Choisissez un Modèle de CV</h2>
    <p class="text-muted">Cliquez sur un modèle pour le télécharger en format PDF.</p>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success col-md-8 mx-auto"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm card-selectable">
            <div class="card-img-container">
                <img src="/Cv_Pro/assets/images/preview_simple.jpg" class="card-img-top" alt="Aperçu du modèle simple">
            </div>
            <div class="card-body text-center d-flex flex-column">
                <h5 class="card-title">Modèle Simple</h5>
                <p class="card-text">Un design classique et épuré.</p>
                <a href="../export/generate_pdf.php?user_id=<?php echo $userId; ?>&template=simple_1" class="btn btn-primary mt-auto">Télécharger</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm card-selectable">
            <div class="card-img-container">
                <img src="/Cv_Pro/assets/images/preview_pro.jpg" class="card-img-top" alt="Aperçu du modèle professionnel">
            </div>
            <div class="card-body text-center d-flex flex-column">
                <h5 class="card-title">Modèle Professionnel</h5>
                <p class="card-text">Mise en page avec barre latérale colorée.</p>
                <a href="../export/generate_pdf.php?user_id=<?php echo $userId; ?>&template=pro_1" class="btn btn-primary mt-auto">Télécharger</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm card-selectable">
            <div class="card-img-container">
                <img src="/Cv_Pro/assets/images/preview_advanced.jpg" class="card-img-top" alt="Aperçu du modèle avancé">
            </div>
            <div class="card-body text-center d-flex flex-column">
                <h5 class="card-title">Modèle Avancé</h5>
                <p class="card-text">Style créatif avec des barres de compétences.</p>
                <a href="../export/generate_pdf.php?user_id=<?php echo $userId; ?>&template=advanced_1" class="btn btn-primary mt-auto">Télécharger</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>