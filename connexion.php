<?php 
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

// Déconnexion
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_destroy();
    header('location:connexion.php');
}


// Si connecté, on le renvoie sur sa page profil
if(user_is_connect()) {
	header('location:profil.php');
}


// Déclaration de la variable
$pseudo = '';


if(isset($_POST['pseudo']) && isset($_POST['mdp'])) {

	$pseudo = trim($_POST['pseudo']);
	$mdp = trim($_POST['mdp']);
	
	// Récupération des données correspondant à ce pseudo
	$verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
	$verif_connexion->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
	$verif_connexion->execute();
	
	if($verif_connexion->rowCount() > 0) {
		// Pseudo existe
		$infos = $verif_connexion->fetch(PDO::FETCH_ASSOC);
		
		// Comparaison du mot de passe
		if(password_verify($mdp, $infos['mdp'])) {
			// Mot de passe correct, stockage des informations dans session			
		      	
            foreach($infos AS $indice => $valeur) {
				if($indice != 'mdp') {
                    $_SESSION['membre'][$indice] = $valeur;
				}				
            }
            header('location:index.php');
		} else {
			$msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';
		}
	} else {
		$msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';	
	}
}


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

	<div class="starter-template">
		<h1><i class="fas fa-wifi" style="color: #28a745;"></i> Connexion <i class="fas fa-wifi" style="color: #28a745;"></i></h1>
		<p class="lead"><?php echo $msg; ?></p>
	</div>

	<div class="row">
		<div class="col-7 col-sm-4 mx-auto">
            <form method="post">
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" class="form-control">
                </div>
                <div class="form-group mt-5">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" autocomplete="off" name="mdp" id="mdp" class="form-control">
                </div>
                <div class="form-group mt-5">
                    <button type="submit" name="connexion" id="connexion" class="form-control btn btn-outline-success"> Connexion </button>
                </div>
            </form>			
		</div>
	</div>

<?php 
include 'inc/footer.inc.php';