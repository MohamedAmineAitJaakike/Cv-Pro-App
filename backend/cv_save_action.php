<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../frontend/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$userData = $_POST['user'];

$imagePathForDB = null;
if (isset($_FILES['image_profil']) && $_FILES['image_profil']['error'] == 0) {
    $targetDir = "../uploads/";

    $imageName = $userId . '_' . basename($_FILES["image_profil"]["name"]);
    $targetFile = $targetDir . $imageName;
    
   
    $imagePathForDB = "uploads/" . $imageName;

    
    if (move_uploaded_file($_FILES["image_profil"]["tmp_name"], $targetFile)) {
       
    } else {
        $imagePathForDB = null; 
    }
}


if ($imagePathForDB) {
    $sqlUserUpdate = "UPDATE utilisateurs SET nom=?, email=?, filiere=?, annee_scolaire=?, age=?, image_profil=? WHERE id=?";
    $stmtUser = mysqli_prepare($conn, $sqlUserUpdate);
    mysqli_stmt_bind_param($stmtUser, "ssssisi", $userData['nom'], $userData['email'], $userData['filiere'], $userData['annee_scolaire'], $userData['age'], $imagePathForDB, $userId);
} else {
   
    $sqlUserUpdate = "UPDATE utilisateurs SET nom=?, email=?, filiere=?, annee_scolaire=?, age=? WHERE id=?";
    $stmtUser = mysqli_prepare($conn, $sqlUserUpdate);
    mysqli_stmt_bind_param($stmtUser, "ssssii", $userData['nom'], $userData['email'], $userData['filiere'], $userData['annee_scolaire'], $userData['age'], $userId);
}
mysqli_stmt_execute($stmtUser);


$tablesToDelete = ['experiences', 'formations', 'competences', 'langues', 'certifications', 'stages', 'centres_interet'];
foreach ($tablesToDelete as $table) {
    $sqlDelete = "DELETE FROM $table WHERE id_utilisateur = ?";
    $stmtDelete = mysqli_prepare($conn, $sqlDelete);
    mysqli_stmt_bind_param($stmtDelete, "i", $userId);
    mysqli_stmt_execute($stmtDelete);
}


function insertData($conn, $userId, $tableName, $data, $columns) {
    if (empty($data) || empty(reset($data)[0])) return;
    $placeholders = implode(',', array_fill(0, count($columns), '?'));
    $sql = "INSERT INTO $tableName (id_utilisateur, " . implode(',', $columns) . ") VALUES (?," . $placeholders . ")";
    $stmt = mysqli_prepare($conn, $sql);
    $types = 'i' . str_repeat('s', count($columns));

    foreach ($data[reset($columns)] as $key => $value) {
        $params = [$userId];
        foreach ($columns as $column) {
            $params[] = $data[$column][$key];
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
    }
}


insertData($conn, $userId, 'experiences', $_POST['experiences'] ?? [], ['titre_poste', 'entreprise', 'date_debut', 'date_fin', 'description']);
insertData($conn, $userId, 'formations', $_POST['formations'] ?? [], ['diplome', 'etablissement', 'annee_obtention']);
insertData($conn, $userId, 'competences', $_POST['competences'] ?? [], ['nom_competence', 'niveau']);
insertData($conn, $userId, 'langues', $_POST['langues'] ?? [], ['nom_langue', 'niveau']);
insertData($conn, $userId, 'stages', $_POST['stages'] ?? [], ['titre_stage', 'entreprise', 'date_debut', 'date_fin', 'description']);
insertData($conn, $userId, 'certifications', $_POST['certifications'] ?? [], ['nom_certification', 'organisation', 'annee_obtention']);
insertData($conn, $userId, 'centres_interet', $_POST['centres_interet'] ?? [], ['nom_interet']);



$fileContent = "================ CV de " . $userData['nom'] . " ================\n\n";
$fileContent .= "[INFORMATIONS PERSONNELLES]\n";
$fileContent .= "Nom: " . $userData['nom'] . "\n";
$fileContent .= "Email: " . $userData['email'] . "\n";
$fileContent .= "Filière: " . $userData['filiere'] . "\n";
$fileContent .= "Année: " . $userData['annee_scolaire'] . "\n";
$fileContent .= "Âge: " . $userData['age'] . "\n\n";

function appendSectionToTextFile($postData, $title, $fields) {
    if (empty($postData) || empty(reset($postData)[0])) return "";
    $content = "[$title]\n";
    foreach ($postData[reset($fields)] as $key => $value) {
        $line = "- ";
        $fieldParts = [];
        foreach($fields as $label => $fieldName) {
            if(isset($postData[$fieldName][$key]) && !empty($postData[$fieldName][$key])) {
                $fieldParts[] = $label . ": " . $postData[$fieldName][$key];
            }
        }
        $line .= implode(' | ', $fieldParts);
        $content .= $line . "\n";
    }
    return $content . "\n";
}

$fileContent .= appendSectionToTextFile($_POST['experiences'] ?? [], 'EXPÉRIENCES', ['Poste' => 'titre_poste', 'Entreprise' => 'entreprise']);
$fileContent .= appendSectionToTextFile($_POST['formations'] ?? [], 'FORMATIONS', ['Diplôme' => 'diplome', 'École' => 'etablissement']);
$fileContent .= appendSectionToTextFile($_POST['competences'] ?? [], 'COMPÉTENCES', ['Compétence' => 'nom_competence', 'Niveau' => 'niveau']);
$fileContent .= appendSectionToTextFile($_POST['langues'] ?? [], 'LANGUES', ['Langue' => 'nom_langue', 'Niveau' => 'niveau']);
$fileContent .= appendSectionToTextFile($_POST['stages'] ?? [], 'STAGES', ['Titre' => 'titre_stage', 'Entreprise' => 'entreprise']);
$fileContent .= appendSectionToTextFile($_POST['certifications'] ?? [], 'CERTIFICATIONS', ['Certification' => 'nom_certification']);
$fileContent .= appendSectionToTextFile($_POST['centres_interet'] ?? [], 'CENTRES D\'INTÉRÊT', ['Intérêt' => 'nom_interet']);


$filePath = '../storage/cv_user_' . $userId . '.txt';
file_put_contents($filePath, $fileContent);

mysqli_close($conn);


header("Location: ../frontend/recap.php?status=saved");
exit();
?>