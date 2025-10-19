<?php
include 'includes/header.php';
require_once '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'RH') {
    header("Location: login.php");
    exit();
}


$sql = "SELECT id, nom, email, filiere, annee_scolaire FROM utilisateurs WHERE role = 'candidat'";
$params = [];
$types = "";

if (!empty($_GET['search_nom'])) {
    $sql .= " AND nom LIKE ?";
    $params[] = "%" . $_GET['search_nom'] . "%";
    $types .= "s";
}
if (!empty($_GET['search_filiere'])) {
    $sql .= " AND filiere LIKE ?";
    $params[] = "%" . $_GET['search_filiere'] . "%";
    $types .= "s";
}

$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<h2>Dashboard RH - Liste des Candidats</h2>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="dashboard_rh.php">
            <div class="row">
                <div class="col-md-5"><input type="text" name="search_nom" class="form-control" placeholder="Rechercher par nom..." value="<?php echo htmlspecialchars($_GET['search_nom'] ?? ''); ?>"></div>
                <div class="col-md-5"><input type="text" name="search_filiere" class="form-control" placeholder="Filtrer par filière..." value="<?php echo htmlspecialchars($_GET['search_filiere'] ?? ''); ?>"></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Filtrer</button></div>
            </div>
        </form>
    </div>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Filière</th>
            <th>Année</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($candidat = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($candidat['nom']); ?></td>
            <td><?php echo htmlspecialchars($candidat['email']); ?></td>
            <td><?php echo htmlspecialchars($candidat['filiere']); ?></td>
            <td><?php echo htmlspecialchars($candidat['annee_scolaire']); ?></td>
            <td>
                <a href="../export/generate_pdf.php?user_id=<?php echo $candidat['id']; ?>&template=advanced_1" class="btn btn-sm btn-success">Télécharger CV</a>
                <a href="../backend/user_delete_action.php?user_id=<?php echo $candidat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat et toutes ses données ?');">Supprimer</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php 
mysqli_close($conn);
include 'includes/footer.php'; 
?>