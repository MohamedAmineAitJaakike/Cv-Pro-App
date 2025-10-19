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
    <title>CV Advanced - <?php echo htmlspecialchars($user['nom']); ?></title>
    <style>
        @page { 
            margin: 8px;
            size: A4;
        }
        
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 8pt; 
            color: #2c3e50;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .cv-container {
            background: white;
            margin: 0;
            min-height: 100vh;
            position: relative;
        }
       
        .cv-header {
            background-color: #667eea;
            color: white;
            padding: 15px;
            position: relative;
            border-bottom: 3px solid #764ba2;
        }
        
        .header-decoration {
            position: absolute;
            top: 5px;
            right: 15px;
            width: 60px;
            height: 60px;
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 50%;
        }
        
        .header-decoration::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 15px;
            width: 25px;
            height: 25px;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50%;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
            position: relative;
            z-index: 2;
        }
        
        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.4);
        }
        
        .cv-name {
            font-size: 22pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .cv-subtitle {
            font-size: 11pt;
            margin: 0 0 8px 0;
            color: #e8eaf6;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .contact-info {
            font-size: 8pt;
            margin: 2px 0;
            color: #f3e5f5;
            background-color: rgba(255,255,255,0.1);
            padding: 2px 6px;
            border-radius: 8px;
            display: inline-block;
            margin-right: 3px;
        }
        
        
        .content-wrapper {
            padding: 12px;
        }
        
        .section {
            margin-bottom: 12px;
            page-break-inside: auto;
            position: relative;
            orphans: 1;
            widows: 1;
        }
        
        .section-title {
            font-size: 13pt;
            font-weight: bold;
            color: #667eea;
            margin: 0 0 8px 0;
            padding: 6px 0 6px 12px;
            background-color: #e8eaf6;
            border-left: 4px solid #667eea;
            border-right: 4px solid #764ba2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            page-break-after: avoid;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 12px;
            width: 30px;
            height: 2px;
            background-color: #764ba2;
        }
        
      
        .cv-item {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #fafafa;
            border-radius: 5px;
            border-left: 3px solid #667eea;
            border-right: 1px solid #e0e0e0;
            border-top: 1px solid #e0e0e0;
            border-bottom: 2px solid #e0e0e0;
            position: relative;
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .cv-item::before {
            content: '';
            position: absolute;
            top: 3px;
            right: 3px;
            width: 20px;
            height: 20px;
            background-color: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
        }
        
        .item-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
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
            color: white;
            font-weight: 600;
            width: 35%;
            background-color: #667eea;
            padding: 2px 6px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .item-subtitle {
            font-size: 9pt;
            color: #764ba2;
            font-weight: 600;
            margin: 0 0 5px 0;
            font-style: italic;
        }
        
        .item-description {
            font-size: 8pt;
            color: #5d6d7e;
            line-height: 1.2;
            margin: 0;
            text-align: justify;
        }
        
      
        .skills-section {
            background-color: #fafafa;
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #e8eaf6;
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .skills-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .skills-column {
            width: 50%;
            vertical-align: top;
            padding: 0 8px;
        }
        
        .skills-title {
            font-size: 10pt;
            font-weight: bold;
            color: #667eea;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e8eaf6;
            padding-bottom: 3px;
        }
        
        .skill-item {
            margin-bottom: 6px;
            padding: 5px;
            background-color: white;
            border-radius: 3px;
            border-left: 2px solid #667eea;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .skill-name {
            font-size: 9pt;
            font-weight: bold;
            color: #2c3e50;
            display: block;
            margin-bottom: 2px;
        }
        
        .skill-level-visual {
            background-color: #e0e0e0;
            height: 4px;
            border-radius: 2px;
            overflow: hidden;
            margin: 2px 0;
        }
        
        .skill-bar {
            height: 100%;
            border-radius: 2px;
        }
        
        .skill-bar.debutant { width: 25%; background-color: #ff9800; }
        .skill-bar.intermediaire { width: 50%; background-color: #2196f3; }
        .skill-bar.avance { width: 75%; background-color: #4caf50; }
        .skill-bar.expert { width: 100%; background-color: #f44336; }
        
        .skill-level-text {
            font-size: 7pt;
            color: #7f8c8d;
            font-style: italic;
        }
        
        
        .interests-section {
            background-color: #fafafa;
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #e8eaf6;
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .interests-container {
            text-align: center;
        }
        
        .interest-item {
            display: inline-block;
            margin: 2px;
            padding: 4px 10px;
            background-color: #667eea;
            color: white;
            border-radius: 15px;
            font-size: 8pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #764ba2;
        }
        
      
        @media print {
            .header-decoration {
                display: none;
            }
            
            .cv-item::before {
                display: none;
            }
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
        
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

<div class="cv-container">
    <div class="cv-header">
        <div class="header-decoration"></div>
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
                <td style="width: 90px; text-align: center; vertical-align: middle;">
                    <img src="<?php echo $imageUrl; ?>" class="profile-pic" alt="Photo de profil">
                </td>
                <?php endif; ?>
                
                <td style="vertical-align: middle; padding-left: 15px;">
                    <h1 class="cv-name"><?php echo htmlspecialchars($user['nom']); ?></h1>
                    <p class="cv-subtitle"><?php echo htmlspecialchars($user['filiere']); ?></p>
                    <div style="margin-top: 6px;">
                        <span class="contact-info">Email: <?php echo htmlspecialchars($user['email']); ?></span>
                        <span class="contact-info">Annee: <?php echo htmlspecialchars($user['annee_scolaire']); ?></span>
                        <?php if (!empty($user['age'])): ?>
                        <span class="contact-info">Age: <?php echo htmlspecialchars($user['age']); ?> ans</span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content-wrapper">
        <?php if (isset($cvData['experiences']) && count($cvData['experiences']) > 0): ?>
        <div class="section breakable">
            <h2 class="section-title">EXPERIENCES PROFESSIONNELLES</h2>
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
            <h2 class="section-title">STAGES</h2>
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
            <h2 class="section-title">FORMATIONS</h2>
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
            <h2 class="section-title">CERTIFICATIONS</h2>
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
        <div class="section breakable">
            <h2 class="section-title">COMPETENCES & LANGUES</h2>
            <div class="skills-section">
                <table class="skills-table">
                    <tr>
                        <td class="skills-column">
                            <?php if (isset($cvData['competences']) && count($cvData['competences']) > 0): ?>
                                <div class="skills-title">COMPETENCES</div>
                                <?php foreach($cvData['competences'] as $comp): ?>
                                <div class="skill-item">
                                    <span class="skill-name"><?php echo htmlspecialchars($comp['nom_competence']); ?></span>
                                    <div class="skill-level-visual">
                                        <div class="skill-bar <?php echo strtolower(str_replace(['é', 'è'], ['e', 'e'], $comp['niveau'])); ?>"></div>
                                    </div>
                                    <div class="skill-level-text"><?php echo htmlspecialchars($comp['niveau']); ?></div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td class="skills-column">
                            <?php if (isset($cvData['langues']) && count($cvData['langues']) > 0): ?>
                                <div class="skills-title">LANGUES</div>
                                <?php foreach($cvData['langues'] as $lang): ?>
                                <div class="skill-item">
                                    <span class="skill-name"><?php echo htmlspecialchars($lang['nom_langue']); ?></span>
                                    <div class="skill-level-visual">
                                        <div class="skill-bar intermediaire"></div>
                                    </div>
                                    <div class="skill-level-text"><?php echo htmlspecialchars($lang['niveau']); ?></div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (isset($cvData['centres_interet']) && count($cvData['centres_interet']) > 0): ?>
        <div class="section breakable">
            <h2 class="section-title">CENTRES D'INTERET</h2>
            <div class="interests-section">
                <div class="interests-container">
                    <?php foreach($cvData['centres_interet'] as $item): ?>
                        <span class="interest-item"><?php echo htmlspecialchars($item['nom_interet']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>