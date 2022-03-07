<?php 
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

// si l'utilisateur est connecté, on le renvoie sur la page d'accueil
if(user_is_connect() && !isset($_GET['action'])){
	header('location:index.php');
}


// Déclaration des vériables
$pseudo = '';
$mdp = '';
$prenom = '';
$nom = '';
$email = '';
$civilite = '';

// on controle l'existence des champs du formulaire	
if(
	isset($_POST['pseudo']) && 
	isset($_POST['mdp']) && 
	isset($_POST['prenom']) && 
	isset($_POST['nom']) && 
	isset($_POST['email']) && 
	isset($_POST['civilite'])) {
		
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $prenom = ucfirst(trim($_POST['prenom']));
    $nom = ucfirst(trim($_POST['nom']));
    $email = trim($_POST['email']);
    $civilite = trim($_POST['civilite']);
    
    // Condition pour le champ pseudo : a-z A-Z 0-9 -._ et entre 4 et 14 caractères
    $verif_pseudo = preg_match('#^(?=.*[a-zA-Z0-9._-]).{4,14}+$#', $pseudo);
    // Condition pour le champ mdp : Au moins une minuscule, une majuscule, un chiffre, un caractère spécial et longueur entre 10 et 20 caractère
    $verif_mdp = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{10,20}$#', $mdp);
    // Condition prenom et nom : Max 20 caractères, limitation caractère spéciaux et une majuscule
    $verif_nom = preg_match('#^([A-Z][\p{L}-]).{0,20}+$#', $nom);
    $verif_prenom = preg_match('#^([A-Z][\p{L}-]).{0,20}+$#', $prenom);


    // Vérification validité pseudo
    if(!$verif_pseudo || empty($pseudo)) {
        $msg .= '<div class="alert alert-danger mt-3">Pseudo invalide caractères (entre 4 et 14) autorisés : a-z et de 0-9</div>';			
    }
    
    // Vérification validité mdp
    if(!$verif_mdp || empty($mdp)){
        $msg .= '<div class="alert alert-danger mt-3">Mot de passe invalide (au moins une majuscule, une minuscule, un caractère spécial, un chiffre et doit contenir entre 10 et 20 caractères </div>';
    }
    
    // Vérification validité nom
    if(!$verif_nom || empty($nom)){
        $msg .= '<div class="alert alert-danger mt-3">Nom invalide (maximum de 20 caractères et caractère spéciaux limités)</div>';	
    }

    // Vérification validité prénom
    if(!$verif_prenom || empty($prenom)){
        $msg .= '<div class="alert alert-danger mt-3">Prénom invalide (maximum de 20 caractères et caractère spéciaux limités)</div>';	
    }
    
    // Vérification validité email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
        $msg .= '<div class="alert alert-danger mt-3">Adresse mail invalide</div>';
    }


    // Si pas d'erreur
    if(empty($msg)){

        // Vérification de la disponibilité du pseudo si création et non modification
        if(empty($_SESSION['membre']['id_membre'])){
            $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
            $verif_pseudo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
            $verif_pseudo->execute();
            
            if($verif_pseudo->rowCount() > 0) {
                $msg .= '<div class="alert alert-danger mt-3">Pseudo indisponible !</div>';
            }
        }

        if(empty($msg)) {

            // Modification du profil
            if(!empty($_SESSION['membre']['id_membre'])){
                $enregistrement = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite WHERE id_membre = :id_membre");
                $enregistrement->bindParam(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);

            // Création du profil
            }else{
                $enregistrement = $pdo->prepare("INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :civilite, 2, CURDATE())");
            }

            // cryptage du mdp
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);

            $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
            $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
            $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
            $enregistrement->execute();
            header('location:profil.php');
        }				
    }		
}


// ******************************************
// RECUPERATION DES DONNEES POUR MODIFICATION
// ******************************************

if(isset($_GET['action']) && $_GET['action'] == 'modification' && !empty($_SESSION['membre']['id_membre'])){
	
	$pdo_infos_profil = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
	$pdo_infos_profil->bindparam(":id_membre", $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
	$pdo_infos_profil->execute();
	
	if($pdo_infos_profil->rowCount() > 0) {
        $infos_profil = $pdo_infos_profil->fetch(PDO::FETCH_ASSOC);
        
        $pseudo = $infos_profil['pseudo'];;
        $mdp = $infos_profil['mdp'];;
        $prenom = $infos_profil['prenom'];;
        $nom = $infos_profil['nom'];;
        $email = $infos_profil['email'];;
        $civilite = $infos_profil['civilite'];;
	}
}


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

	<div class="starter-template">
		<h1><i class="fas fa-id-card" style="color: #28a745;"></i> Inscription <i class="fas fa-id-card" style="color: #28a745;"></i></h1>
		<p class="lead"><?php echo $msg; ?></p>
	</div>

	<div class="row">
		<div class="col-12">
			<form method="post" action="">
				<div class="row justify-content-center">
					<div class="col-9 col-sm-6">
                        <div class="form-group mt-5">
                            <label for="pseudo">Pseudo</label>
                            <input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" class="form-control">
                        </div>
                        <div class="form-group mt-5">
                            <label for="mdp">Mot de passe</label>
                            <input type="password" name="mdp" id="mdp" value="" class="form-control">
                        </div>
                        <div class="form-group mt-5">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="form-control">
                        </div>	
					</div>
					<div class="col-9 col-sm-6">
                        <div class="form-group mt-5">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" value="<?php echo $nom; ?>" class="form-control">
                        </div>
                        <div class="form-group mt-5">
                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" class="form-control">
                        </div>				
                        <div class="form-group mt-5">
                            <label for="civilite">Civilité</label>
                            <select name="civilite" id="civilite" class="form-control">
                                <option value="m">Homme</option>
                                <option value="f" <?php if($civilite == 'f') { echo 'selected'; } ?> >Femme</option>
                            </select>	
                        </div>						
					</div>
                    <div class="form-group col-4 mt-5">
                        <button type="submit" name="inscription" id="inscription" class="form-control btn btn-outline-success">
                            <?php if(empty($_SESSION['membre']['id_membre'])){ ?>
                            Inscription
                            <?php }else{ ?>
                            Modification
                            <?php } ?>
                        </button>
                    </div>	
				</div>
			</form>			
		</div>
    </div>

    
<?php 
include 'inc/footer.inc.php';