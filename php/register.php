<?php
session_start();
include 'connexionBD.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $pseudo = $_POST['pseudo'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    try {
        $stmt = $connexion->prepare("INSERT INTO utilisateur (nom, email, pseudo, mdp) VALUES (:name, :email, :pseudo, :mdp)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':mdp', $mdp);

        if ($stmt->execute()) {
            header("Location: login.php?register_success=1");
            exit;
        } else {
            $error = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
        }
    } catch (PDOException $e) {
        $error = "Erreur interne : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b71dea2871.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="/img/logo_stylish.ico">
    <link rel="stylesheet" href="../css/style_register.css">
    <title>Stylish - Créer un compte</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    <img src="../img/logo_stylish.png" alt="logo" class="img-fluid mt-5">
                    <h1>Créer un compte</h1> <br>
                </div>
                <form action="register.php" method="POST">
                    <div class="mb-3 input-container">
                        <input name="name" type="text" id="name" class="form-control" placeholder="Nom complet" required>
                        <i class="fa-solid fa-user icon"></i>
                    </div>
                    <div class="mb-3 input-container">
                        <input name="email" type="email" id="email" class="form-control" placeholder="Adresse Email" required>
                        <i class="fa-solid fa-envelope icon"></i>
                    </div>
                    <div class="mb-3 input-container">
                        <input name="pseudo" type="text" id="pseudo" class="form-control" placeholder="Nom d'utilisateur" required>
                        <i class="fa-solid fa-user-tag icon"></i>
                    </div>
                    <div class="mb-3 input-container">
                        <input name="mdp" type="password" id="mdp" class="form-control" placeholder="Mot de passe" required>
                        <i class="fa-solid fa-lock icon"></i>
                    </div>
                    <?php if (isset($error)): ?>
                        <p class="text-danger text-center"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <div class="d-grid">
                        <input type="submit" value="Créer un compte" class="btn btn-primary">
                    </div>
                    <div class="text-center mt-3">
                        <p>Vous avez déjà un compte ? <a href="login.php" class="text-decoration-none">Se connecter</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer mt-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-12 col-md-4">
                    <h5>À propos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none">Notre histoire</a></li>
                        <li><a href="#" class="text-decoration-none">Nos valeurs</a></li>
                        <li><a href="#" class="text-decoration-none">Carrières</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><a href="mailto:contact@stylish.com" class="text-decoration-none">contact@stylish.com</a></li>
                        <li><a href="tel:+123456789" class="text-decoration-none">+33 6 62 95 67 62</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4">
                    <h5>Suivez-nous</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.facebook.com" class="text-decoration-none" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a></li>
                        <li><a href="https://www.instagram.com" class="text-decoration-none" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="https://www.twitter.com" class="text-decoration-none" target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p>&copy; 2024 Stylish - Tous droits réservés</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
