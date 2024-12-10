<?php
session_start();
include 'connexionBD.php'; 

if (!isset($_SESSION['id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $nom = $_POST['nom'];
    $categorie = $_POST['categorie'];
    $couleur = $_POST['couleur'];
    $image = $_FILES['image'];

    if ($image['error'] == UPLOAD_ERR_OK) {
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_path = 'uploads/' . $image_name;

        if (move_uploaded_file($image_tmp_name, $image_path)) {
            $query = "INSERT INTO vetements (nom, categorie, couleur, image, utilisateur_id) 
                      VALUES (:nom, :categorie, :couleur, :image, :utilisateur_id)";

            $stmt = $connexion->prepare($query);
            
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':categorie', $categorie);
            $stmt->bindParam(':couleur', $couleur);
            $stmt->bindParam(':image', $image_name);
            $stmt->bindParam(':utilisateur_id', $user_id); 

            if ($stmt->execute()) {
                $message = "Vêtement ajouté avec succès !";
                header("Location: garde_robe.php");
                exit();
            } else {
                $message = "Erreur lors de l'ajout du vêtement.";
            }
        } else {
            $message = "Erreur lors du téléchargement de l'image.";
        }
    } else {
        $message = "Veuillez sélectionner une image valide.";
    }
}

if (isset($_POST['delete'])) {
    $vetement_id = $_POST['vetement_id'];
    $query = "SELECT image FROM vetements WHERE id = :vetement_id";
    $stmt = $connexion->prepare($query);
    $stmt->bindParam(':vetement_id', $vetement_id);
    $stmt->execute();
    $vetement = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vetement) {
        $image_path = 'uploads/' . $vetement['image'];
        if (file_exists($image_path)) {
            unlink($image_path); 
        }

        $query = "DELETE FROM vetements WHERE id = :vetement_id";
        $stmt = $connexion->prepare($query);
        $stmt->bindParam(':vetement_id', $vetement_id);

        if ($stmt->execute()) {
            header("Location: garde_robe.php");
            exit();
        } else {
            $message = "Erreur lors de la suppression du vêtement.";
        }
    }
}

$vetements = [];
$query = "SELECT * FROM vetements WHERE utilisateur_id = :utilisateur_id AND image IS NOT NULL";
$stmt = $connexion->prepare($query);
$stmt->bindParam(':utilisateur_id', $user_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $vetements = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$query_categories = "SELECT DISTINCT categorie FROM vetements WHERE utilisateur_id = :utilisateur_id";
$stmt_categories = $connexion->prepare($query_categories);
$stmt_categories->bindParam(':utilisateur_id', $user_id);
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

$query_couleurs = "SELECT DISTINCT couleur FROM vetements WHERE utilisateur_id = :utilisateur_id";
$stmt_couleurs = $connexion->prepare($query_couleurs);
$stmt_couleurs->bindParam(':utilisateur_id', $user_id);
$stmt_couleurs->execute();
$couleurs = $stmt_couleurs->fetchAll(PDO::FETCH_ASSOC);

$categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
$couleur = isset($_POST['couleur']) ? $_POST['couleur'] : '';

$query = "SELECT * FROM vetements WHERE utilisateur_id = :utilisateur_id AND image IS NOT NULL";

if ($categorie != '') {
    $query .= " AND categorie = :categorie";
}
if ($couleur != '') {
    $query .= " AND couleur = :couleur";
}

$stmt = $connexion->prepare($query);

$stmt->bindParam(':utilisateur_id', $user_id);

if ($categorie != '') {
    $stmt->bindParam(':categorie', $categorie);
}
if ($couleur != '') {
    $stmt->bindParam(':couleur', $couleur);
}

$stmt->execute();

$vetements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ma Garde-Robe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/b71dea2871.js" crossorigin="anonymous"></script>
  <link rel="icon" type="image/x-icon" href="/img/logo_stylish.ico">
  <link rel="stylesheet" href="../css/style_garde_robe.css">
</head>
<body>

<section id="garde-robe">
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="../img/logo_stylish.png" alt="logo" class="img-fluid" style="width: 32%;">
      </a>
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active text-white" href="#">
              <i class="fas fa-users"></i> SOCIAL
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#">
              <i class="fa-solid fa-qrcode"></i> SCAN
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#">
              <i class="fa-solid fa-shirt"></i> GARDE ROBE
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#">
              <i class="fas fa-info-circle"></i> À PROPOS
            </a>
          </li>
        </ul>
      </div>

      <div class="d-flex align-items-center">
        <div class="user-icon d-flex justify-content-center align-items-center">
          <i class="fas fa-user"></i>
        </div>
        <a href="logout.php" class="text-white ms-3">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </div>
    </div>
  </nav>
</section>

<main class="container py-5">
    <h2 class="text-center mb-4">MA GARDE ROBE</h2>
    
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div class="input-group w-50">
        <span class="input-group-text bg-light border-end-0" id="basic-addon1">
          <i class="fas fa-search"></i>
        </span>
        <input type="text" class="form-control border-start-0" placeholder="Rechercher un produit..." id="searchInput">
      </div>
      
      <div class="d-flex gap-2">
        <button class="btn btn-outline-light ms-2" id="addButton" data-bs-toggle="modal" data-bs-target="#addProductModal">
          <i class="fa-solid fa-plus"></i> Ajouter un vêtement
        </button>
        <button class="btn" id="sortButton">
          <i class="fa-solid fa-arrow-up-wide-short"></i> Trier
        </button>
      </div>
    </div>

<div class="row g-4" id="productList">
    <?php if ($vetements): ?>
        <?php foreach ($vetements as $vetement): ?>
            <div class="col-md-4 col-lg-3">
                <div class="card product-card shadow-sm position-relative">
                    <img src="uploads/<?php echo htmlspecialchars($vetement['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($vetement['nom']); ?>">
                    
                    <form action="garde_robe.php" method="POST" class="position-absolute top-0 end-0 m-2">
                        <input type="hidden" name="vetement_id" value="<?php echo $vetement['id']; ?>">
                        <button type="submit" class="btn btn-danger" name="delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>

                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($vetement['nom']); ?></h5>
                        <p class="card-text fw-bold">Catégorie: <?php echo htmlspecialchars($vetement['categorie']); ?></p>
                        <p class="card-text fw-bold">Couleur: <?php echo htmlspecialchars($vetement['couleur']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun vêtement ajouté pour le moment.</p>
    <?php endif; ?>
</div>

<div class="modal fade" id="sortModal" tabindex="-1" aria-labelledby="sortModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sortModalLabel">Trier les vêtements</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="filterForm" method="POST" action="garde_robe.php">
            <div class="mb-3">
                <label for="categorieFilter" class="form-label">Catégorie</label>
                <select class="form-select" name="categorie" id="categorieFilter">
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo htmlspecialchars($categorie['categorie']); ?>" 
                        <?php echo ($categorie == $categorie) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categorie['categorie']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="couleurFilter" class="form-label">Couleur</label>
                <select class="form-select" name="couleur" id="couleurFilter">
                    <option value="">Sélectionner une couleur</option>
                    <?php foreach ($couleurs as $couleur): ?>
                        <option value="<?php echo htmlspecialchars($couleur['couleur']); ?>"
                        <?php echo ($couleur == $couleur) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($couleur['couleur']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Appliquer le filtre</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    document.getElementById('sortButton').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('sortModal')).show();
    });
</script>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel">Ajouter un vêtement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="garde_robe.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du vêtement</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <input type="text" class="form-control" id="categorie" name="categorie" required>
            </div>
            <div class="mb-3">
                <label for="couleur" class="form-label">Couleur</label>
                <input type="text" class="form-control" id="couleur" name="couleur" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
