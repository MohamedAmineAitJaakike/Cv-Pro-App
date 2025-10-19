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
            margin: 12px;
            size: A4;
        }
        
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 9pt; 
            color: #333;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        
        .cv-header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 15px;
        }
        
        .profile-container {
            margin-bottom: 6px;
        }
        
        .profile-pic {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #2c3e50;
            margin-bottom: 6px;
        }
        
        .cv-name {
            font-size: 18pt;
            font-weight: bold;
            margin: 5px 0 3px 0;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .cv-subtitle {
            font-size: 10pt;
            margin: 2px 0 5px 0;
            color: #7f8c8d;
            font-weight: normal;
        }
        
        .contact-line {
            font-size: 8pt;
            margin: 1px 0;
            color: #34495e;
        }
        
       
        .section {
            margin-bottom: 12px;
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 6px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #bdc3c7;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            page-break-after: avoid;
        }
        
       
        .cv-item {
            margin-bottom: 8px;
            padding: 0;
            border-left: 2px solid #ecf0f1;
            padding-left: 8px;
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        .item-header {
            margin-bottom: 3px;
        }
        
        .item-title {
            font-size: 10pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }
        
        .item-date {
            font-size: 8pt;
            color: #7f8c8d;
            font-style: italic;
            float: right;
            margin-top: 1px;
        }
        
        .item-subtitle {
            font-size: 9pt;
            color: #34495e;
            font-weight: 600;
            margin: 1px 0 3px 0;
        }
        
        .item-description {
            font-size: 8pt;
            color: #5d6d7e;
            line-height: 1.2;
            margin: 0;
            text-align: justify;
        }
        
       
        .skills-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .skills-column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .skills-column:last-child {
            padding-right: 0;
            padding-left: 10px;
        }
        
        .skills-subtitle {
            font-size: 10pt;
            font-weight: bold;
            color: #34495e;
            margin: 0 0 5px 0;
            padding-bottom: 2px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .skill-item {
            margin-bottom: 3px;
            font-size: 8pt;
            line-height: 1.1;
        }
        
        .skill-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .skill-level {
            color: #7f8c8d;
            font-style: italic;
        }
        
       
        .interests-list {
            font-size: 8pt;
            line-height: 1.3;
            color: #34495e;
        }
        
        .interest-item {
            display: inline;
        }
        
        .interest-item:after {
            content: " • ";
            color: #bdc3c7;
        }
        
        .interest-item:last-child:after {
            content: "";
        }
        
       
        .page-fill {
            page-break-inside: auto;
        }
        
       
        .breakable {
            page-break-inside: auto;
            orphans: 1;
            widows: 1;
        }
        
        
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .text-center {
            text-align: center;
        }
        
        * {
            margin-top: 0;
        }
        
        p {
            margin-bottom: 3px;
        }
        
       
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

  
    <div class="cv-header">
         <div class="profile-container">
            <?php
         
            $nom_dossier_projet = 'Cv_Pro';
            $imageUrl = '';
           
            if (!empty($user['image_profil'])) {
                
                $imageUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $nom_dossier_projet . '/' . $user['image_profil'];
            }

            if ($imageUrl): 
            ?>
                <img src="<?php echo $imageUrl; ?>" class="profile-pic" alt="Photo de profil">
            <?php endif; ?>
        </div>
        
        <h1 class="cv-name"><?php echo htmlspecialchars($user['nom']); ?></h1>
        <p class="cv-subtitle"><?php echo htmlspecialchars($user['filiere']); ?> • <?php echo htmlspecialchars($user['annee_scolaire']); ?></p>
        
        <div class="contact-line"><?php echo htmlspecialchars($user['email']); ?></div>
        <?php if (!empty($user['age'])): ?>
        <div class="contact-line"><?php echo htmlspecialchars($user['age']); ?> ans</div>
        <?php endif; ?>
    </div>

   
    <?php if (isset($cvData['experiences']) && count($cvData['experiences']) > 0): ?>
    <div class="section breakable">
        <h2 class="section-title">Expériences Professionnelles</h2>
        <?php foreach($cvData['experiences'] as $item): ?>
        <div class="cv-item breakable">
            <div class="item-header clearfix">
                <div class="item-title"><?php echo htmlspecialchars($item['titre_poste']); ?></div>
                <div class="item-date"><?php echo htmlspecialchars($item['date_debut']); ?> - <?php echo htmlspecialchars($item['date_fin']); ?></div>
            </div>
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
            <div class="item-header clearfix">
                <div class="item-title"><?php echo htmlspecialchars($item['titre_stage']); ?></div>
                <div class="item-date"><?php echo htmlspecialchars($item['date_debut']); ?> - <?php echo htmlspecialchars($item['date_fin']); ?></div>
            </div>
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
            <div class="item-header clearfix">
                <div class="item-title"><?php echo htmlspecialchars($item['diplome']); ?></div>
                <div class="item-date"><?php echo htmlspecialchars($item['annee_obtention']); ?></div>
            </div>
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
            <div class="item-header clearfix">
                <div class="item-title"><?php echo htmlspecialchars($item['nom_certification']); ?></div>
                <div class="item-date"><?php echo htmlspecialchars($item['annee_obtention']); ?></div>
            </div>
            <div class="item-subtitle"><?php echo htmlspecialchars($item['organisation']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

  
    <?php if ((isset($cvData['competences']) && count($cvData['competences']) > 0) || (isset($cvData['langues']) && count($cvData['langues']) > 0)): ?>
    <div class="section breakable">
        <h2 class="section-title">Compétences & Langues</h2>
        <div class="skills-grid">
            <div class="skills-column">
                <?php if (isset($cvData['competences']) && count($cvData['competences']) > 0): ?>
                    <div class="skills-subtitle">Compétences</div>
                    <?php foreach($cvData['competences'] as $comp): ?>
                    <div class="skill-item">
                        <span class="skill-name"><?php echo htmlspecialchars($comp['nom_competence']); ?></span>
                        <span class="skill-level"> - <?php echo htmlspecialchars($comp['niveau']); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="skills-column">
                <?php if (isset($cvData['langues']) && count($cvData['langues']) > 0): ?>
                    <div class="skills-subtitle">Langues</div>
                    <?php foreach($cvData['langues'] as $lang): ?>
                    <div class="skill-item">
                        <span class="skill-name"><?php echo htmlspecialchars($lang['nom_langue']); ?></span>
                        <span class="skill-level"> - <?php echo htmlspecialchars($lang['niveau']); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    
    <?php if (isset($cvData['centres_interet']) && count($cvData['centres_interet']) > 0): ?>
    <div class="section breakable">
        <h2 class="section-title">Centres d'Intérêt</h2>
        <div class="interests-list">
            <?php foreach($cvData['centres_interet'] as $item): ?>
                <span class="interest-item"><?php echo htmlspecialchars($item['nom_interet']); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>