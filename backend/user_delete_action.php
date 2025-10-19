<?php
session_start();
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'RH' || !isset($_GET['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$userIdToDelete = $_GET['user_id'];


$sql = "DELETE FROM utilisateurs WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userIdToDelete);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../frontend/dashboard_rh.php?success=Utilisateur supprimé avec succès.");
} else {
    header("Location: ../frontend/dashboard_rh.php?error=Erreur lors de la suppression.");
}
exit();
?>