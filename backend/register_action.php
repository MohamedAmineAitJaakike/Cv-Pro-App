<?php
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirm_mot_de_passe = $_POST['confirm_mot_de_passe'];

    if ($mot_de_passe !== $confirm_mot_de_passe) {
        header("Location: ../frontend/register.php?error=Les mots de passe ne correspondent pas.");
        exit();
    }

    $sql_check = "SELECT id FROM utilisateurs WHERE email = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        header("Location: ../frontend/register.php?error=Cet email est déjà utilisé.");
        exit();
    }

    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'candidat')";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "sss", $nom, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt_insert)) {
        header("Location: ../frontend/login.php?success=Inscription réussie. Vous pouvez maintenant vous connecter.");
        exit();
    } else {
        header("Location: ../frontend/register.php?error=Une erreur est survenue.");
        exit();
    }

    mysqli_stmt_close($stmt_check);
    mysqli_stmt_close($stmt_insert);
    mysqli_close($conn);
}
?>