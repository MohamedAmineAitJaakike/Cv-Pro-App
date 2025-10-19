</div> </main> <footer class="footer-custom text-white pt-5 pb-4">
        <div class="container text-center text-md-start">
            <div class="row">

                <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">
                        <img src="/Cv_Pro/assets/images/logo.png" alt="Logo CvPro" width="30" height="30" class="d-inline-block align-top me-2" style="filter: brightness(0) invert(1);"> CvPro
                    </h6>
                    <p>
                        Votre solution pour créer des CV professionnels simplement. Mettez en valeur vos compétences et décrochez le poste de vos rêves.
                    </p>
                </div>

                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Navigation</h6>
                    <p><a href="/Cv_Pro/index.php" class="text-reset">Accueil</a></p>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'candidat'): ?>
                        <p><a href="/Cv_Pro/frontend/formulaire.php" class="text-reset">Mon CV</a></p>
                        <p><a href="/Cv_Pro/frontend/preview_cv.php" class="text-reset">Prévisualiser</a></p>
                    <?php endif; ?>
                </div>

                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Légal</h6>
                    <p><a href="#!" class="text-reset">Mentions Légales</a></p>
                    <p><a href="#!" class="text-reset">Politique de confidentialité</a></p>
                </div>

                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                    <p><i class="fas fa-envelope me-3"></i><a href="mailto:mohamedamine.aitjaakike@etu.uae.ac.ma" class="email-link">mohamedamine.aitjaakike@etu.uae.ac.ma</a></p>
                    <div class="mt-3">
                        <a href="#!" class="me-4 text-reset"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#!" class="me-4 text-reset"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#!" class="me-4 text-reset"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>

            </div>
        </div>
        <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.1);">
            &copy; <?php echo date("Y"); ?> CvPro. Tous droits réservés.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Cv_Pro/assets/js/script.js"></script>
    </body>
</html>