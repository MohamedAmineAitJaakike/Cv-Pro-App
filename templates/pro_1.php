<?php
if (!isset($userId)) { die('Accès non autorisé'); }
require_once __DIR__ . '/../config/db_connect.php';


function getCvDataForTemplate($conn, $userId) {
    $data = [];
    $stmt = mysqli_prepare($conn, "SELECT * FROM utilisateurs WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data['user'] = mysqli_fetch_assoc($result);
    
    $tables = [
        'experiences' => 'ORDER BY date_debut DESC',
        'stages' => 'ORDER BY date_debut DESC',
        'formations' => 'ORDER BY annee_obtention DESC',
        'certifications' => 'ORDER BY annee_obtention DESC',
        'competences' => '',
        'langues' => '',
        'centres_interet' => ''
    ];

    foreach ($tables as $table => $orderBy) {
        $query = "SELECT * FROM $table WHERE id_utilisateur = ? $orderBy";
        $stmt_table = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt_table, "i", $userId);
        mysqli_stmt_execute($stmt_table);
        $result_table = mysqli_stmt_get_result($stmt_table);
        $data[$table] = mysqli_fetch_all($result_table, MYSQLI_ASSOC);
    }
    
    return $data;
}

$cvData = getCvDataForTemplate($conn, $userId);
$user = $cvData['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CV - <?php echo htmlspecialchars($user['nom']); ?></title>
    <style>
        @page { 
            margin: 10px;
            size: A4;
        }
        
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 9pt; 
            color: #2c3e50;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        
        h1, h2, h3, p, div, td { 
            word-wrap: break-word; 
            margin: 0;
            padding: 0;
        }
        
        
        .cv-header {
            background-color: #34495e;
            color: white;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
            page-break-after: avoid;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .profile-pic {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .cv-name {
            font-size: 20pt;
            font-weight: bold;
            margin: 0 0 4px 0;
            color: white;
        }
        
        .cv-subtitle {
            font-size: 10pt;
            margin: 0 0 6px 0;
            color: #bdc3c7;
            font-weight: normal;
        }
        
        .contact-info {
            font-size: 8pt;
            margin: 1px 0;
            color: #ecf0f1;
        }
        
        
        .section {
            margin-bottom: 10px;
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #34495e;
            margin: 8px 0 6px 0;
            padding: 4px 0 4px 8px;
            border-left: 3px solid #3498db;
            background-color: #ecf0f1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            page-break-after: avoid;
        }
        
      
        .cv-item {
            margin-bottom: 8px;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 3px;
            border-left: 2px solid #3498db;
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .item-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }
        
        .item-title {
            font-size: 10pt;
            font-weight: bold;
            color: #2c3e50;
            width: 65%;
        }
        
        .item-date {
            text-align: right;
            font-size: 8pt;
            color: #7f8c8d;
            font-style: italic;
            width: 35%;
        }
        
        .item-subtitle {
            font-size: 9pt;
            color: #34495e;
            font-weight: 600;
            margin: 0 0 4px 0;
        }
        
        .item-description {
            font-size: 8pt;
            color: #5d6d7e;
            line-height: 1.2;
            margin: 0;
            text-align: justify;
        }
        
        
        .skills-section {
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .skills-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8f9fa;
            border-radius: 3px;
            padding: 8px;
        }
        
        .skills-column {
            width: 50%;
            vertical-align: top;
            padding: 0 6px;
        }
        
        .skills-title {
            font-size: 10pt;
            font-weight: bold;
            color: #34495e;
            margin: 0 0 6px 0;
        }
        
        .skill-item {
            margin-bottom: 4px;
            padding: 3px 6px;
            background-color: white;
            border-radius: 2px;
            border-left: 2px solid #3498db;
            font-size: 8pt;
        }
        
        .skill-name {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .skill-level {
            color: #7f8c8d;
            font-style: italic;
        }
        
        
        .interests-section {
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .interests-container {
            background-color: #f8f9fa;
            border-radius: 3px;
            padding: 8px;
        }
        
        .interest-item {
            display: inline-block;
            margin: 1px 3px 1px 0;
            padding: 3px 8px;
            background-color: white;
            border-radius: 10px;
            font-size: 8pt;
            color: #34495e;
            border: 1px solid #bdc3c7;
        }
        
       
        .section:last-child {
            margin-bottom: 0;
        }
        
       
        .breakable {
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
      
        * {
            margin-top: 0;
        }
        
        p {
            margin-bottom: 2px;
        }
        
        
        .text-center {
            text-align: center;
        }
        
     
        @media print {
            .section {
                page-break-inside: auto;
            }
            
            .cv-item {
                page-break-inside: auto;
            }
        }
        
        
        .space-optimized {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

    <div class="cv-header">
        <table class="header-table">
            <tr>
                <?php
                
                $nom_dossier_projet = 'Cv_Pro';
                $imageUrl = '';
                
                if (!empty($user['image_profil'])) {
                    
                    $imageUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $nom_dossier_projet . '/' . $user['image_profil'];
                }

                if ($imageUrl): 
                ?>
                <td style="width: 85px; text-align: center; vertical-align: middle;">
                    <img src="<?php echo $imageUrl; ?>" class="profile-pic" alt="Photo de profil">
                </td>
                <?php endif; ?>
                
                <td style="vertical-align: middle; padding-left: 12px;">
                    <h1 class="cv-name"><?php echo htmlspecialchars($user['nom']); ?></h1>
                    <p class="cv-subtitle"><?php echo htmlspecialchars($user['filiere']); ?> • <?php echo htmlspecialchars($user['annee_scolaire']); ?></p>
                    <div class="contact-info">✉ <?php echo htmlspecialchars($user['email']); ?></div>
                    <?php if (!empty($user['age'])): ?>
                    <div class="contact-info">🎂 <?php echo htmlspecialchars($user['age']); ?> ans</div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <?php if (isset($cvData['experiences']) && count($cvData['experiences']) > 0): ?>
    <div class="section breakable">
        <h2 class="section-title">Expériences Professionnelles</h2>
        <?php foreach($cvData['experiences'] as $item): ?>
        <div class="cv-item breakable">
            <table class="item-header-table">
                <tr>
                    <td class="item-title"><?php echo htmlspecialchars($item['titre_poste']); ?></td>
                    <td class="item-date"><?php echo htmlspecialchars($item['date_debut']); ?> - <?php echo htmlspecialchars($item['date_fin']); ?></td>
                </tr>
            </table>
            <div class="item-subtitle"><?php echo htmlspecialchars($item['entreprise']); ?></div>
            <?php if (!empty($item['description'])): ?>
            <p class="item-description"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (isset($cvData['stages']) && count($cvData['stages']) > 0): ?>
    <div class="section breakable">
        <h2 class="section-title">Stages</h2>
        <?php foreach($cvData['stages'] as $item): ?>
        <div class="cv-item breakable">
            <table class="item-header-table">
                <tr>
                    <td class="item-title"><?php echo htmlspecialchars($item['titre_stage']); ?></td>
                    <td class="item-date"><?php echo htmlspecialchars($item['date_debut']); ?> - <?php echo htmlspecialchars($item['date_fin']); ?></td>
                </tr>
            </table>
            <div class="item-subtitle"><?php echo htmlspecialchars($item['entreprise']); ?></div>
            <?php if (!empty($item['description'])): ?>
            <p class="item-description"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (isset($cvData['formations']) && count($cvData['formations']) > 0): ?>
    <div class="section breakable">
        <h2 class="section-title">Formations</h2>
        <?php foreach($cvData['formations'] as $item): ?>
        <div class="cv-item breakable">
            <table class="item-header-table">
                <tr>
                    <td class="item-title"><?php echo htmlspecialchars($item['diplome']); ?></td>
                    <td class="item-date"><?php echo htmlspecialchars($item['annee_obtention']); ?></td>
                </tr>
            </table>
            <div class="item-subtitle"><?php echo htmlspecialchars($item['etablissement']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (isset($cvData['certifications']) && count($cvData['certifications']) > 0): ?>
    <div class="section breakable">
        <h2 class="section-title">Certifications</h2>
        <?php foreach($cvData['certifications'] as $item): ?>
        <div class="cv-item breakable">
            <table class="item-header-table">
                <tr>
                    <td class="item-title"><?php echo htmlspecialchars($item['nom_certification']); ?></td>
                    <td class="item-date"><?php echo htmlspecialchars($item['annee_obtention']); ?></td>
                </tr>
            </table>
            <div class="item-subtitle"><?php echo htmlspecialchars($item['organisation']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ((isset($cvData['competences']) && count($cvData['competences']) > 0) || (isset($cvData['langues']) && count($cvData['langues']) > 0)): ?>
    <div class="section skills-section breakable">
        <h2 class="section-title">Compétences & Langues</h2>
        <table class="skills-table">
            <tr>
                <td class="skills-column">
                    <?php if (isset($cvData['competences']) && count($cvData['competences']) > 0): ?>
                        <div class="skills-title">Compétences</div>
                        <?php foreach($cvData['competences'] as $comp): ?>
                        <div class="skill-item">
                            <span class="skill-name"><?php echo htmlspecialchars($comp['nom_competence']); ?></span>
                            <span class="skill-level"> - <?php echo htmlspecialchars($comp['niveau']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td class="skills-column">
                    <?php if (isset($cvData['langues']) && count($cvData['langues']) > 0): ?>
                        <div class="skills-title">Langues</div>
                        <?php foreach($cvData['langues'] as $lang): ?>
                        <div class="skill-item">
                            <span class="skill-name"><?php echo htmlspecialchars($lang['nom_langue']); ?></span>
                            <span class="skill-level"> - <?php echo htmlspecialchars($lang['niveau']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
    <?php endif; ?>
    
    <?php if (isset($cvData['centres_interet']) && count($cvData['centres_interet']) > 0): ?>
    <div class="section interests-section breakable">
        <h2 class="section-title">Centres d'Intérêt</h2>
        <div class="interests-container text-center">
            <?php foreach($cvData['centres_interet'] as $item): ?>
                <span class="interest-item"><?php echo htmlspecialchars($item['nom_interet']); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>