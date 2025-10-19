<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['user_id'])) {
    exit('Accès non autorisé. Veuillez vous connecter.');
}


$userId = $_GET['user_id'] ?? null;
$template = $_GET['template'] ?? null;

if (!$userId || !$template) {
    die('Informations manquantes pour générer le CV.');
}


$allowed_templates = ['simple_1', 'pro_1', 'advanced_1'];

if (!in_array($template, $allowed_templates)) {
    die('Le modèle demandé n\'est pas valide.');
}


$templatePath = __DIR__ . "/../templates/{$template}.php";

if (!file_exists($templatePath)) {
    die("Erreur : Le fichier du modèle '{$template}.php' est introuvable dans le dossier /templates/.");
}


$options = new Options();

$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'DejaVu Sans'); 

$dompdf = new Dompdf($options);




ob_start();

include $templatePath;
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');


$dompdf->render();


$userName = $_SESSION['user_nom'] ?? 'utilisateur';
$filename = "CV_" . preg_replace('/[^a-z0-d9]/i', '_', $userName) . ".pdf";


$dompdf->stream($filename, ["Attachment" => true]);
exit();
?>