<?php
session_start();
include 'connexionBD.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    try {
        $stmt = $connexion->prepare("SELECT email, mdp, nom, pseudo, id FROM utilisateur WHERE email = :email AND mdp = :mdp");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdp', $mdp);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['id'] = $row['id'];
            $_SESSION["email"] = $row['email'];
            $_SESSION["nom"] = $row['nom'];
            $_SESSION["pseudo"] = $row['pseudo'];

            echo "<pre>";
            print_r($_SESSION);
            echo "</pre>";

            header("Location: garde_robe.php");
            exit();
        } else {
            $error = "Les identifiants sont incorrects. Veuillez réessayer.";
        }
    } catch (PDOException $e) {
        $error = "Erreur interne. Veuillez réessayer plus tard.";
        error_log("Erreur PDO : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b71dea2871.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="/img/logo_stylish.ico">
    <link rel="stylesheet" href="../css/style_login.css">
    <title>Stylish - Connexion</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    <img src="../img/logo_stylish.png" alt="logo" class="img-fluid mt-5">
                    <h1>SE CONNECTER</h1> <br>
                </div>
                <form action="login.php" method="POST">
                    <div class="mb-3 input-container">
                        <input name="email" type="text" id="email" class="form-control" placeholder="Email" required>
                        <i class="fa-solid fa-user icon"></i>
                    </div>
                    <div class="mb-3 input-container">
                        <input name="mdp" type="password" id="mdp" class="form-control" placeholder="Mot de passe" required>
                        <i class="fa-solid fa-lock icon"></i>
                    </div>
                    <?php if (isset($error)): ?>
                        <p class="text-danger text-center"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <div class="text-center">
                        <input type="submit" value="Connexion" id="Connexion" class="btn btn-primary">
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="#" class="text-decoration-none">Mot de passe oublié ?</a>
                        <a href="register.php" class="text-decoration-none">Créer un compte</a>
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
