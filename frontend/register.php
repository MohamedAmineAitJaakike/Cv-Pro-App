<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Inscription Candidat</h3>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>
                <form action="../backend/register_action.php" method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                    </div>
                     <div class="mb-3">
                        <label for="confirm_mot_de_passe" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="confirm_mot_de_passe" name="confirm_mot_de_passe" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>