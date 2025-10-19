<?php
session_start();
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

   
    if (empty($email) || empty($mot_de_passe)) {
        header("Location: ../frontend/login.php?error=Veuillez remplir tous les champs.");
        exit();
    }

    $sql = "SELECT id, nom, mot_de_passe, role FROM utilisateurs WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
       
        if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
           
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            
      
            $_SESSION['user_name'] = $user['nom'];

           
            if ($user['role'] === 'RH') {
                header("Location: ../frontend/dashboard_rh.php");
            } else {
                header("Location: ../frontend/formulaire.php");
            }
            exit();
        }
    }

    
    header("Location: ../frontend/login.php?error=Email ou mot de passe incorrect.");
    exit();
}
?>