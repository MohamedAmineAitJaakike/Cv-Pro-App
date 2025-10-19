<?php
include 'includes/header.php';
require_once '../config/db_connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];


function getFullCvData($conn, $userId) {
    $data = [];
    $data['user'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM utilisateurs WHERE id = $userId"));
    $tables = ['experiences', 'formations', 'competences', 'langues', 'certifications', 'stages', 'centres_interet'];
    foreach ($tables as $table) {
        $result = mysqli_query($conn, "SELECT * FROM $table WHERE id_utilisateur = $userId");
        $data[$table] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $data;
}

$cvData = getFullCvData($conn, $userId);
$user = $cvData['user'];
?>

<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0"><i class="fas fa-eye me-2 text-light"></i>Récapitulatif de votre CV</h3>
            </div>
            <div class="card-body p-4">
                <?php if (isset($_GET['status']) && $_GET['status'] == 'saved'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>Votre CV a été enregistré avec succès !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user me-2 text-warning"></i>Informations Personnelles</h4>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-3 text-center mb-3 mb-lg-0">
                                <?php if (!empty($user['image_profil']) && file_exists(__DIR__ . '/../' . $user['image_profil'])): ?>
                                    <img src="../<?php echo htmlspecialchars($user['image_profil']); ?>" alt="Photo de profil" class="img-fluid rounded-circle shadow" style="max-width: 150px; width: 100%;">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                        <i class="fas fa-user-circle text-muted" style="font-size: 80px;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3"><div class="d-flex align-items-center p-3 border rounded bg-light info-card"><i class="fas fa-user text-primary me-3 fs-4 flex-shrink-0"></i><div class="info-card-content"><small class="text-muted d-block">Nom complet</small><strong class="fs-5 text-overflow-fix"><?php echo htmlspecialchars($user['nom']); ?></strong></div></div></div>
                                    <div class="col-md-6 mb-3"><div class="d-flex align-items-center p-3 border rounded bg-light info-card"><i class="fas fa-envelope text-primary me-3 fs-4 flex-shrink-0"></i><div class="info-card-content"><small class="text-muted d-block">Email</small><strong class="text-overflow-fix long-text"><?php echo htmlspecialchars($user['email']); ?></strong></div></div></div>
                                    <div class="col-md-6 mb-3"><div class="d-flex align-items-center p-3 border rounded bg-light info-card"><i class="fas fa-graduation-cap text-primary me-3 fs-4 flex-shrink-0"></i><div class="info-card-content"><small class="text-muted d-block">Filière</small><strong class="text-overflow-fix"><?php echo htmlspecialchars($user['filiere']); ?></strong></div></div></div>
                                    <div class="col-md-6 mb-3"><div class="d-flex align-items-center p-3 border rounded bg-light info-card"><i class="fas fa-calendar text-primary me-3 fs-4 flex-shrink-0"></i><div class="info-card-content"><small class="text-muted d-block">Année scolaire</small><strong class="text-overflow-fix"><?php echo htmlspecialchars($user['annee_scolaire']); ?></strong></div></div></div>
                                    <div class="col-md-6"><div class="d-flex align-items-center p-3 border rounded bg-light info-card"><i class="fas fa-birthday-cake text-primary me-3 fs-4 flex-shrink-0"></i><div class="info-card-content"><small class="text-muted d-block">Âge</small><strong class="text-overflow-fix"><?php echo htmlspecialchars($user['age']); ?> ans</strong></div></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php 
                function displaySavedSection($title, $data, $fields, $icon = 'fas fa-list') {
                    if (empty($data)) return;
                    echo "<div class='card mb-4'><div class='card-header bg-dark text-white border-bottom'><h4 class='mb-0'><i class='$icon me-2 text-warning'></i>$title</h4></div><div class='card-body'><div class='row'>";
                    foreach ($data as $item) {
                        echo "<div class='col-lg-6 mb-3'><div class='card border h-100'><div class='card-body'>";
                        $details = [];
                        foreach ($fields as $label => $fieldName) {
                            if (!empty($item[$fieldName])) {
                                $details[] = "<div class='mb-2'><small class='text-muted fw-bold'>$label:</small> <span class='text-dark text-overflow-fix'>" . nl2br(htmlspecialchars($item[$fieldName])) . "</span></div>";
                            }
                        }
                        echo implode('', $details);
                        echo "</div></div></div>";
                    }
                    echo "</div></div></div>";
                }
                function displayCompetencesLangages($title, $data, $icon = 'fas fa-cogs') {
                    if (empty($data)) return;
                    echo "<div class='card mb-4'><div class='card-header bg-dark text-white border-bottom'><h4 class='mb-0'><i class='$icon me-2 text-warning'></i>$title</h4></div><div class='card-body'><div class='row'>";
                    foreach ($data as $item) {
                        echo "<div class='col-lg-4 col-md-6 mb-3'><div class='text-center p-3 border rounded bg-light h-100'>";
                        if (isset($item['nom_competence'])) {
                            echo "<strong class='d-block mb-2 text-primary text-overflow-fix'>" . htmlspecialchars($item['nom_competence']) . "</strong>";
                            if (isset($item['niveau'])) {
                                $badgeClass = 'bg-primary';
                                switch(strtolower($item['niveau'])){
                                    case 'débutant': $badgeClass = 'bg-info'; break;
                                    case 'intermédiaire': $badgeClass = 'bg-warning'; break;
                                    case 'avancé': $badgeClass = 'bg-success'; break;
                                    case 'expert': $badgeClass = 'bg-danger'; break;
                                }
                                echo "<span class='badge $badgeClass'>" . htmlspecialchars($item['niveau']) . "</span>";
                            }
                        } elseif (isset($item['nom_langue'])) {
                            echo "<strong class='d-block mb-2 text-primary text-overflow-fix'>" . htmlspecialchars($item['nom_langue']) . "</strong>";
                            if (isset($item['niveau'])) {
                                echo "<span class='badge bg-info'>" . htmlspecialchars($item['niveau']) . "</span>";
                            }
                        }
                        echo "</div></div>";
                    }
                    echo "</div></div></div>";
                }
                function displayCentresInteret($title, $data, $icon = 'fas fa-heart') {
                    if (empty($data)) return;
                    echo "<div class='card mb-4'><div class='card-header bg-dark text-white border-bottom'><h4 class='mb-0'><i class='$icon me-2 text-warning'></i>$title</h4></div><div class='card-body'><div class='d-flex flex-wrap gap-2'>";
                    foreach ($data as $item) {
                        if (isset($item['nom_interet'])) {
                            echo "<span class='badge bg-secondary fs-6 px-3 py-2 text-overflow-fix'>" . htmlspecialchars($item['nom_interet']) . "</span>";
                        }
                    }
                    echo "</div></div></div>";
                }

                
                displaySavedSection('Expériences Professionnelles', $cvData['experiences'] ?? [], ['Poste' => 'titre_poste', 'Entreprise' => 'entreprise', 'Description' => 'description'], 'fas fa-briefcase');
                displaySavedSection('Stages', $cvData['stages'] ?? [], ['Titre' => 'titre_stage', 'Entreprise' => 'entreprise', 'Description' => 'description'], 'fas fa-graduation-cap');
                displaySavedSection('Formations', $cvData['formations'] ?? [], ['Diplôme' => 'diplome', 'Établissement' => 'etablissement', 'Année' => 'annee_obtention'], 'fas fa-university');
                displaySavedSection('Certifications', $cvData['certifications'] ?? [], ['Certification' => 'nom_certification', 'Organisation' => 'organisation', 'Année' => 'annee_obtention'], 'fas fa-certificate');
                displayCompetencesLangages('Compétences', $cvData['competences'] ?? [], 'fas fa-cogs');
                displayCompetencesLangages('Langues', $cvData['langues'] ?? [], 'fas fa-language');
                displayCentresInteret('Centres d\'intérêt', $cvData['centres_interet'] ?? [], 'fas fa-heart');
                ?>
                
                <div class="card bg-light mt-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <a href="formulaire.php" class="btn btn-outline-secondary btn-lg w-100">
                                    <i class="fas fa-edit me-2"></i>Modifier les informations
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="preview_cv.php" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-arrow-right me-2"></i>Suivant : Choisir un modèle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
mysqli_close($conn);
include 'includes/footer.php'; 
?>