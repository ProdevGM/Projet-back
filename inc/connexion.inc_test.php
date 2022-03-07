<?php

// déconnexion
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
	session_destroy(); // on détruit la session pour provoquer la déconnexion.
}

// si l'utilisateur est connecté, on le renvoie sur le page d'accueil
/* if(user_is_connect()) {
	header('location:index.php');
} */

$pseudo = '';
// est ce que le formulaire a été validé
if(isset($_POST['pseudo']) && isset($_POST['mdp'])) {
	$pseudo = trim($_POST['pseudo']);
	$mdp = trim($_POST['mdp']);
	
	// on récupère les informations en bdd de l'utilisateur sur la base du pseudo (unique en bdd)
	$verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
	$verif_connexion->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
	$verif_connexion->execute();
	
	if($verif_connexion->rowCount() > 0) {
		// s'il y a une ligne dans $verif_connexion alors le pseudo est bon
		$infos = $verif_connexion->fetch(PDO::FETCH_ASSOC);
		
		// on compare le mot de passe qui a été crypté avec password_hash() via la fonction prédéfinie pasword_verify()
		if(password_verify($mdp, $infos['mdp'])) {
			// le pseudo et le mot de passe sont corrects, on enregistre les informations du membre dans la session 
			
			$_SESSION['membre'] = array();
			
			// avec un foreach()
			
			foreach($infos AS $indice => $valeur) {
				if($indice != 'mdp') {
					$_SESSION['membre'][$indice] = $valeur;
				}				
			}
			
			// maintenant que l'utilisateur est connecté, on le redirige vers profil.php
/* 			header('location:profil.php'); */
			// header('location:...) doit être exécuté AVANT le moindre affichage dans la page sinon => bug
			
			
		} else {
			$msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';	
		}
		
	} else {
		$msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';	
	}
	
}

?>


<div class="modal fade" id="modalConnexion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="starter-template">
                <h3>Se connecter</h3>
                <p class="lead"><?php echo $msg; ?></p>
            </div>
            <div class="modal-body">
                <form class="row justify-content-center" method="post">
                    <div class="form-group col-9">
                        <input class="form-control mt-2" type="text" name="pseudo" placeholder="Votre pseudo">
                    </div>
                    <div class="form-group col-9">
                        <input class="form-control mt-2" type="text" name="mdp" placeholder="Votre mot de passe">
                    </div>
                    <button type="submit" class="form-control col-9 btn btn-outline-primary mb-5">Inscription</button>
                </form>
            </div>
        </div>
    </div>
</div>