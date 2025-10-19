<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$cvData = [];


function restructurePostData($post, $section, $fields) {
    $restructured = [];
    if (isset($post[$section]) && is_array($post[$section])) {
        $firstField = reset($fields);
        if (isset($post[$section][$firstField]) && is_array($post[$section][$firstField])) {
            foreach ($post[$section][$firstField] as $key => $value) {
                $item = [];
                foreach ($fields as $field) {
                    $item[$field] = $post[$section][$field][$key] ?? '';
                }
                $restructured[] = $item;
            }
        }
    }
    return $restructured;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    
    $cvData['user'] = $_POST['user'] ?? [];
    
    
    $cvData['experiences'] = restructurePostData($_POST, 'experiences', ['titre_poste', 'entreprise', 'date_debut', 'date_fin', 'description']);
    $cvData['stages'] = restructurePostData($_POST, 'stages', ['titre_stage', 'entreprise', 'date_debut', 'date_fin', 'description']);
    $cvData['formations'] = restructurePostData($_POST, 'formations', ['diplome', 'etablissement', 'annee_obtention']);
    $cvData['certifications'] = restructurePostData($_POST, 'certifications', ['nom_certification', 'organisation', 'annee_obtention']);
    $cvData['competences'] = restructurePostData($_POST, 'competences', ['nom_competence', 'niveau']);
    $cvData['langues'] = restructurePostData($_POST, 'langues', ['nom_langue', 'niveau']);
    $cvData['centres_interet'] = restructurePostData($_POST, 'centres_interet', ['nom_interet']);

} else {
    
    require_once '../config/db_connect.php';
    
    function getAllCvData($conn, $userId) {
        $data = [];
        $data['user'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM utilisateurs WHERE id = $userId"));
        $tables = ['experiences', 'formations', 'competences', 'langues', 'certifications', 'stages', 'centres_interet'];
        foreach ($tables as $table) {
            $result = mysqli_query($conn, "SELECT * FROM $table WHERE id_utilisateur = $userId");
            $data[$table] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return $data;
    }
    $cvData = getAllCvData($conn, $userId);
    mysqli_close($conn);
}

$user = $cvData['user'];
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0"><i class="fas fa-file-alt me-2"></i>Mon CV - <?php echo htmlspecialchars($user['nom'] ?? 'Nouveau CV'); ?></h2>
                </div>
                <div class="card-body p-4">
                    <form action="../backend/cv_save_action.php" method="POST" enctype="multipart/form-data">
                        
                        <fieldset class="mb-5">
                            <legend class="border-bottom pb-2 mb-4"><i class="fas fa-user me-2"></i>Informations Personnelles</legend>
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="nom" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="nom" name="user[nom]" value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="user[email]" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="filiere" class="form-label">Filière</label>
                                    <select class="form-select" id="filiere" name="user[filiere]" required>
                                        <option value="" disabled <?php echo empty($user['filiere']) ? 'selected' : ''; ?>>-- Choisissez une filière --</option>
                                        <?php
                                        $filieres = ['Génie Informatique', 'Data et Intelligence Artificielle', 'Génie Civil', 'GSTR', 'Supply Chain Management', 'Génie Mécatronique'];
                                        foreach ($filieres as $filiere_option) {
                                            $selected = (isset($user['filiere']) && $user['filiere'] == $filiere_option) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($filiere_option) . '" ' . $selected . '>' . htmlspecialchars($filiere_option) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="annee_scolaire" class="form-label">Année scolaire</label>
                                    <select class="form-select" id="annee_scolaire" name="user[annee_scolaire]">
                                        <option value="1ere" <?php echo (isset($user['annee_scolaire']) && $user['annee_scolaire'] == '1ere') ? 'selected' : ''; ?>>1ère année</option>
                                        <option value="2eme" <?php echo (isset($user['annee_scolaire']) && $user['annee_scolaire'] == '2eme') ? 'selected' : ''; ?>>2ème année</option>
                                        <option value="3eme" <?php echo (isset($user['annee_scolaire']) && $user['annee_scolaire'] == '3eme') ? 'selected' : ''; ?>>3ème année</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="age" class="form-label">Âge</label>
                                    <input type="number" class="form-control" id="age" name="user[age]" value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="image_profil" class="form-label">Photo de profil</label>
                                    <input class="form-control" type="file" id="image_profil" name="image_profil">
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mb-5">
                            <legend class="border-bottom pb-2 mb-4"><i class="fas fa-briefcase me-2"></i>Expériences Professionnelles</legend>
                            <div id="experiences-container">
                                <?php if (!empty($cvData['experiences'])) foreach ($cvData['experiences'] as $item): ?>
                                <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Titre du poste</label>
                                            <input type="text" class="form-control" name="experiences[titre_poste][]" placeholder="Titre du poste" value="<?php echo htmlspecialchars($item['titre_poste']); ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Entreprise</label>
                                            <input type="text" class="form-control" name="experiences[entreprise][]" placeholder="Entreprise" value="<?php echo htmlspecialchars($item['entreprise']); ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Date de début</label>
                                            <input type="date" class="form-control" name="experiences[date_debut][]" value="<?php echo htmlspecialchars($item['date_debut']); ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Date de fin</label>
                                            <input type="date" class="form-control" name="experiences[date_fin][]" value="<?php echo htmlspecialchars($item['date_fin']); ?>">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small text-muted">Description</label>
                                            <textarea class="form-control" rows="3" name="experiences[description][]" placeholder="Description de vos missions et réalisations..."><?php echo htmlspecialchars($item['description']); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-add" data-target="experiences-container">
                                <i class="fas fa-plus me-2"></i>Ajouter une expérience
                            </button>
                        </fieldset>

                        <fieldset class="mb-5">
                            <legend class="border-bottom pb-2 mb-4"><i class="fas fa-graduation-cap me-2"></i>Stages</legend>
                            <div id="stages-container">
                                <?php if (!empty($cvData['stages'])) foreach ($cvData['stages'] as $item): ?>
                                <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Titre du stage</label>
                                            <input type="text" class="form-control" name="stages[titre_stage][]" placeholder="Titre du stage" value="<?php echo htmlspecialchars($item['titre_stage']); ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Entreprise</label>
                                            <input type="text" class="form-control" name="stages[entreprise][]" placeholder="Entreprise" value="<?php echo htmlspecialchars($item['entreprise']); ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Date de début</label>
                                            <input type="date" class="form-control" name="stages[date_debut][]" value="<?php echo htmlspecialchars($item['date_debut']); ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Date de fin</label>
                                            <input type="date" class="form-control" name="stages[date_fin][]" value="<?php echo htmlspecialchars($item['date_fin']); ?>">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small text-muted">Description</label>
                                            <textarea class="form-control" rows="3" name="stages[description][]" placeholder="Description de vos missions et réalisations..."><?php echo htmlspecialchars($item['description']); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-add" data-target="stages-container">
                                <i class="fas fa-plus me-2"></i>Ajouter un stage
                            </button>
                        </fieldset>

                        <fieldset class="mb-5">
                            <legend class="border-bottom pb-2 mb-4"><i class="fas fa-university me-2"></i>Formations</legend>
                            <div id="formations-container">
                                <?php if (!empty($cvData['formations'])) foreach ($cvData['formations'] as $item): ?>
                                <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Diplôme</label>
                                            <input type="text" class="form-control" name="formations[diplome][]" placeholder="Diplôme" value="<?php echo htmlspecialchars($item['diplome']); ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Établissement</label>
                                            <input type="text" class="form-control" name="formations[etablissement][]" placeholder="Établissement" value="<?php echo htmlspecialchars($item['etablissement']); ?>">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small text-muted">Année d'obtention</label>
                                            <input type="number" class="form-control" name="formations[annee_obtention][]" placeholder="Année" value="<?php echo htmlspecialchars($item['annee_obtention']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-add" data-target="formations-container">
                                <i class="fas fa-plus me-2"></i>Ajouter une formation
                            </button>
                        </fieldset>
                        
                        <fieldset class="mb-5">
                            <legend class="border-bottom pb-2 mb-4"><i class="fas fa-certificate me-2"></i>Certifications</legend>
                            <div id="certifications-container">
                                <?php if (!empty($cvData['certifications'])) foreach ($cvData['certifications'] as $item): ?>
                                <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Nom de la certification</label>
                                            <input type="text" class="form-control" name="certifications[nom_certification][]" placeholder="Nom de la certification" value="<?php echo htmlspecialchars($item['nom_certification']); ?>">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label small text-muted">Organisation</label>
                                            <input type="text" class="form-control" name="certifications[organisation][]" placeholder="Organisation" value="<?php echo htmlspecialchars($item['organisation']); ?>">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label small text-muted">Année d'obtention</label>
                                            <input type="number" class="form-control" name="certifications[annee_obtention][]" placeholder="Année" value="<?php echo htmlspecialchars($item['annee_obtention']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-add" data-target="certifications-container">
                                <i class="fas fa-plus me-2"></i>Ajouter une certification
                            </button>
                        </fieldset>

                        <div class="row">
                            <div class="col-lg-6">
                                <fieldset class="mb-5">
                                    <legend class="border-bottom pb-2 mb-4"><i class="fas fa-cogs me-2"></i>Compétences</legend>
                                    <div id="competences-container">
                                        <?php if (!empty($cvData['competences'])) foreach ($cvData['competences'] as $item): ?>
                                        <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
                                            <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label small text-muted">Compétence</label>
                                                    <input type="text" class="form-control" name="competences[nom_competence][]" placeholder="Compétence (ex: PHP)" value="<?php echo htmlspecialchars($item['nom_competence']); ?>">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small text-muted">Niveau</label>
                                                    <select class="form-select" name="competences[niveau][]">
                                                        <option value="Débutant" <?php echo ($item['niveau'] == 'Débutant') ? 'selected' : ''; ?>>Débutant</option>
                                                        <option value="Intermédiaire" <?php echo ($item['niveau'] == 'Intermédiaire') ? 'selected' : ''; ?>>Intermédiaire</option>
                                                        <option value="Avancé" <?php echo ($item['niveau'] == 'Avancé') ? 'selected' : ''; ?>>Avancé</option>
                                                        <option value="Expert" <?php echo ($item['niveau'] == 'Expert') ? 'selected' : ''; ?>>Expert</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary btn-add" data-target="competences-container">
                                        <i class="fas fa-plus me-2"></i>Ajouter une compétence
                                    </button>
                                </fieldset>
                            </div>

                            <div class="col-lg-6">
                                <fieldset class="mb-5">
                                    <legend class="border-bottom pb-2 mb-4"><i class="fas fa-language me-2"></i>Langues</legend>
                                    <div id="langues-container">
                                        <?php if (!empty($cvData['langues'])) foreach ($cvData['langues'] as $item): ?>
                                        <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
                                            <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label small text-muted">Langue</label>
                                                    <input type="text" class="form-control" name="langues[nom_langue][]" placeholder="Langue (ex: Anglais)" value="<?php echo htmlspecialchars($item['nom_langue']); ?>">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small text-muted">Niveau</label>
                                                    <input type="text" class="form-control" name="langues[niveau][]" placeholder="Niveau (ex: B2, Courant)" value="<?php echo htmlspecialchars($item['niveau']); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary btn-add" data-target="langues-container">
                                        <i class="fas fa-plus me-2"></i>Ajouter une langue
                                    </button>
                                </fieldset>
                            </div>
                        </div>

                        <fieldset class="mb-5">
                            <legend class="border-bottom pb-2 mb-4"><i class="fas fa-heart me-2"></i>Centres d'intérêt</legend>
                            <div id="centres_interet-container">
                                <?php if (!empty($cvData['centres_interet'])) foreach ($cvData['centres_interet'] as $item): ?>
                                <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="form-label small text-muted">Centre d'intérêt</label>
                                            <input type="text" class="form-control" name="centres_interet[nom_interet][]" placeholder="Intérêt (ex: Lecture, Sport)" value="<?php echo htmlspecialchars($item['nom_interet']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-add" data-target="centres_interet-container">
                                <i class="fas fa-plus me-2"></i>Ajouter un centre d'intérêt
                            </button>
                        </fieldset>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-arrow-right me-2"></i>Étape suivante : Récapitulatif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="experiences-container-template">
    <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
        <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Titre du poste</label>
                <input type="text" class="form-control" name="experiences[titre_poste][]" placeholder="Titre du poste">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Entreprise</label>
                <input type="text" class="form-control" name="experiences[entreprise][]" placeholder="Entreprise">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Date de début</label>
                <input type="date" class="form-control" name="experiences[date_debut][]">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Date de fin</label>
                <input type="date" class="form-control" name="experiences[date_fin][]">
            </div>
            <div class="col-12">
                <label class="form-label small text-muted">Description</label>
                <textarea class="form-control" rows="3" name="experiences[description][]" placeholder="Description de vos missions et réalisations..."></textarea>
            </div>
        </div>
    </div>
</template>

<template id="stages-container-template">
    <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
        <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Titre du stage</label>
                <input type="text" class="form-control" name="stages[titre_stage][]" placeholder="Titre du stage">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Entreprise</label>
                <input type="text" class="form-control" name="stages[entreprise][]" placeholder="Entreprise">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Date de début</label>
                <input type="date" class="form-control" name="stages[date_debut][]">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Date de fin</label>
                <input type="date" class="form-control" name="stages[date_fin][]">
            </div>
            <div class="col-12">
                <label class="form-label small text-muted">Description</label>
                <textarea class="form-control" rows="3" name="stages[description][]" placeholder="Description de vos missions et réalisations..."></textarea>
            </div>
        </div>
    </div>
</template>

<template id="formations-container-template">
    <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
        <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Diplôme</label>
                <input type="text" class="form-control" name="formations[diplome][]" placeholder="Diplôme">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Établissement</label>
                <input type="text" class="form-control" name="formations[etablissement][]" placeholder="Établissement">
            </div>
            <div class="col-lg-6">
                <label class="form-label small text-muted">Année d'obtention</label>
                <input type="number" class="form-control" name="formations[annee_obtention][]" placeholder="Année">
            </div>
        </div>
    </div>
</template>

<template id="certifications-container-template">
    <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
        <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Nom de la certification</label>
                <input type="text" class="form-control" name="certifications[nom_certification][]" placeholder="Nom de la certification">
            </div>
            <div class="col-lg-6 mb-3">
                <label class="form-label small text-muted">Organisation</label>
                <input type="text" class="form-control" name="certifications[organisation][]" placeholder="Organisation">
            </div>
            <div class="col-lg-6">
                <label class="form-label small text-muted">Année d'obtention</label>
                <input type="number" class="form-control" name="certifications[annee_obtention][]" placeholder="Année">
            </div>
        </div>
    </div>
</template>

<template id="competences-container-template">
    <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
        <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label small text-muted">Compétence</label>
                <input type="text" class="form-control" name="competences[nom_competence][]" placeholder="Compétence (ex: PHP)">
            </div>
            <div class="col-12">
                <label class="form-label small text-muted">Niveau</label>
                <select class="form-select" name="competences[niveau][]">
                    <option value="Débutant">Débutant</option>
                    <option value="Intermédiaire">Intermédiaire</option>
                    <option value="Avancé">Avancé</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
        </div>
    </div>
</template>

<template id="langues-container-template">
    <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
        <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label small text-muted">Langue</label>
                <input type="text" class="form-control" name="langues[nom_langue][]" placeholder="Langue (ex: Anglais)">
            </div>
            <div class="col-12">
                <label class="form-label small text-muted">Niveau</label>
                <input type="text" class="form-control" name="langues[niveau][]" placeholder="Niveau (ex: B2, Courant)">
            </div>
        </div>
    </div>
</template>

<template id="centres_interet-container-template">
    <div class="dynamic-item border border-secondary rounded p-3 mb-3 position-relative">
        <button type="button" class="btn btn-sm btn-danger btn-delete-item position-absolute top-0 end-0 m-2">×</button>
        <div class="row">
            <div class="col-12">
                <label class="form-label small text-muted">Centre d'intérêt</label>
                <input type="text" class="form-control" name="centres_interet[nom_interet][]" placeholder="Intérêt (ex: Lecture, Sport)">
            </div>
        </div>
    </div>
</template>

<?php include 'includes/footer.php'; ?>