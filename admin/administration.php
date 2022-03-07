<?php 
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

// Contrôle d'accès
if(!user_is_admin()) {
	header('location:' . URL . 'connexion.php');
	exit(); 
}





// ***********
// ***********
// SUPPRESSION
// ***********
// ***********

if(isset($_GET['action']) && $_GET['action'] == 'supprimer'){
    if(isset($_GET['gestion']) && $_GET['gestion'] == 'salles' && !empty($_GET['id_salle'])){
        // Suppression impossible de la salle si commande en cours
        $verif_id_produit = $_GET['id_salle'];
        $verif_commande = $pdo->query("SELECT * FROM commande c 
        INNER JOIN produit p ON c.id_produit = p.id_produit
        WHERE p.id_salle = $verif_id_produit");
        if($verif_commande->rowCount() == 0){
            $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
            $suppression->bindParam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
            $suppression->execute();
        }else{
            $msg .= '<div class="alert alert-danger mt-3">Commande existante pour cette salle. Suppression impossible</div>';
        }
    }elseif(isset($_GET['gestion']) && $_GET['gestion'] == 'produits' && !empty($_GET['id_produit'])){
        // Suppression impossible du produit si commande en cours
        $verif_id_produit = $_GET['id_produit'];
        $verif_commande = $pdo->query("SELECT * FROM commande WHERE id_produit = $verif_id_produit");
        if($verif_commande->rowCount() == 0){
            $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
            $suppression->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
            $suppression->execute();
        }else{
            $msg .= '<div class="alert alert-danger mt-3">Commande existante pour ce prodtuit. Suppression impossible</div>';
        }
    }elseif(isset($_GET['gestion']) && $_GET['gestion'] == 'membres' && !empty($_GET['id_membre'])){
        // Suppression impossible du membre si commande en cours
        $verif_id_membre = $_GET['id_membre'];
        $verif_commande = $pdo->query("SELECT * FROM commande WHERE id_membre = $verif_id_membre");
        affiche($verif_commande->rowCount());
        if($verif_commande->rowCount() == 0){
            $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
            $suppression->bindParam(":id_membre", $_GET['id_membre'], PDO::PARAM_STR);
            $suppression->execute();
        }else{
            $msg .= '<div class="alert alert-danger mt-3">Commande existante pour ce membre. Suppression impossible</div>';
        }
    }elseif(isset($_GET['gestion']) && $_GET['gestion'] == 'avis' && !empty($_GET['id_avis'])){
        $suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
        $suppression->bindParam(":id_avis", $_GET['id_avis'], PDO::PARAM_STR);
        $suppression->execute();
    }elseif(isset($_GET['gestion']) && isset($_GET['id_commande']) && isset($_GET['id_produit']) &&  $_GET['gestion'] == 'commandes' && !empty($_GET['id_commande']) && !empty($_GET['id_produit'])){
        $idp = $_GET['id_produit'];
        $maj = $pdo->query("UPDATE produit SET etat = 'libre' WHERE id_produit = $idp");
        $suppression = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
        $suppression->bindParam(":id_commande", $_GET['id_commande'], PDO::PARAM_STR);
        $suppression->execute();
    }
}


// Gestion salles
$id_salle = '';
$titre = '';
$description = '';
$capacite = '';
$categorie = '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';
$nom_photo = '';

// Gestion produits
$id_produit = '';
$arrivee = '';
$depart = '';
$prix = '';

// Gestion membres
$id_membre = '';
$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email = '';
$civilite = '';
$statut = '';




//*******************
//*******************
// GESTION DES SALLES
//*******************
//*******************

if(
	isset($_POST['id_salle']) &&
	isset($_POST['titre']) &&
	isset($_POST['description']) &&
	isset($_POST['capacite']) &&
	isset($_POST['categorie']) &&
	isset($_POST['pays']) &&
	isset($_POST['ville']) &&
	isset($_POST['adresse']) &&
	isset($_POST['cp'])) {

    $id_salle = trim($_POST['id_salle']);
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $capacite = trim($_POST['capacite']);
    $categorie = trim($_POST['categorie']);
    $pays = trim($_POST['pays']);
    $ville = trim($_POST['ville']);
    $adresse = trim($_POST['adresse']);
    $cp = trim($_POST['cp']);


    // Contrôle titre
    if(empty($titre) || iconv_strlen($cp) > 20){
        $msg .= '<div class="alert alert-danger mt-3">Le titre est obligatoire et doit faire moins de 20 caractères</div>';
    }
    // Controle sur le titre car unique en BDD
    $verif_titre = $pdo->prepare("SELECT * FROM salle WHERE titre = :titre");
    $verif_titre->bindParam(':titre', $titre, PDO::PARAM_STR);
    $verif_titre->execute();


    // Contrôle code postal
    if(empty($cp) || !is_numeric($cp) || iconv_strlen($cp) > 10){
        $msg .= '<div class="alert alert-danger mt-3">Le code postal est obligatoire, doit être numérique et inférieur à 10 caractères</div>';
    }
    

    // Contrôle adresse
    if(empty($adresse)){
        $msg .= '<div class="alert alert-danger mt-3">L\'adresse est obligatoire</div>';
    }


    // Contrôle description
    if(empty($cp) || iconv_strlen($cp) > 1000){
        $msg .= '<div class="alert alert-danger mt-3">Description obligatoire et limitée à 1000 caractères</div>';
    }


    // Récupération en cas de modification
    if(isset($_POST['photo_actuelle'])){
        $nom_photo = $_POST['photo_actuelle'];
    }


    // Vérification de la disponibilite du titre
    if($verif_titre->rowCount() > 0 && empty($id_salle)) {
        $msg .= '<div class="alert alert-danger mt-3">Titre indisponible car déjà attribué</div>';
    } else {
        // Vérification de la validité du format image
        if(!empty($_FILES['photo']['name'])) {
            // Vérification du format de l'image et comparaison aux formats accptés : jpg, jpeg, png, gif
            $extension = strrchr($_FILES['photo']['name'], '.');
            $extension = strtolower(substr($extension, 1));
            $tab_extension_valide = array('png', 'gif', 'jpg', 'jpeg');
            $verif_extension = in_array($extension, $tab_extension_valide);
            
            if($verif_extension) {
                $nom_photo = $titre . '-' .  $_FILES['photo']['name'];
                // Chemin où va être enregistré l'image
                $photo_dossier = SERVER_ROOT. '/img/' . $nom_photo;				
                copy($_FILES['photo']['tmp_name'], $photo_dossier);
            }else{
                $msg .= '<div class="alert alert-danger mt-3">Attention, le format de la photo est invalide, extensions autorisées : jpg, jpeg, png, gif.</div>';
            }
        }
    }
    

    // Enregistrement sur la base de donnée si absence d'erreur
    if(empty($msg)) {
        if(!empty($id_salle)){ // Update de la salle 
            $enregistrement_salle = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, capacite = :capacite, categorie = :categorie, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp WHERE id_salle = :id_salle");
            $enregistrement_salle->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);
            
        }else{ // Création de la salle
            $enregistrement_salle = $pdo->prepare("INSERT INTO salle (titre, description, photo, capacite, categorie, pays, ville, adresse, cp) VALUES (:titre, :description, :photo, :capacite, :categorie, :pays, :ville, :adresse, :cp)");
        }

        $enregistrement_salle->bindParam(":titre", $titre, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":description", $description, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":photo", $nom_photo, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":capacite", $capacite, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":categorie", $categorie, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":pays", $pays, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":ville", $ville, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":adresse", $adresse, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":cp", $cp, PDO::PARAM_STR);
        $enregistrement_salle->execute();
        header('location:' . URL . 'admin/administration.php?gestion=salles');
    }
}


// ******************************************
// RECUPERATION DES DONNEES POUR MODIFICATION
// ******************************************

if(isset($_GET['gestion']) && $_GET['gestion'] == 'salles' &&  isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_salle'])) {
	
	$infos_salle = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
	$infos_salle->bindparam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
	$infos_salle->execute();
	
	if($infos_salle->rowCount() > 0) {
		$salle_actuelle = $infos_salle->fetch(PDO::FETCH_ASSOC);
		$id_salle = $salle_actuelle['id_salle']; 
		$titre = $salle_actuelle['titre'];
		$description = $salle_actuelle['description'];
		$photo_actuelle = $salle_actuelle['photo'];
		$pays = $salle_actuelle['pays'];
		$ville = $salle_actuelle['ville'];
		$adresse = $salle_actuelle['adresse'];
		$cp = $salle_actuelle['cp'];
		$capacite = $salle_actuelle['capacite'];
		$categorie = $salle_actuelle['categorie'];
	}
}




//*********************
//*********************
// GESTION DES PRODUITS
//*********************
//*********************

// Récupération des salles pour le "select" du formulaire
$donnees_salles = $pdo->query("SELECT * FROM salle");

if(
	isset($_POST['id_produit']) &&
	isset($_POST['arrivee']) &&
	isset($_POST['depart']) &&
	isset($_POST['salle']) &&
	isset($_POST['prix'])) {

    $id_produit = trim($_POST['id_produit']);
    $arrivee = trim($_POST['arrivee']);
    $depart = trim($_POST['depart']);
    $prix = trim($_POST['prix']);
    $salle = trim($_POST['salle']);


    // Contrôle prix
    if(empty($prix) || !is_numeric($prix) || iconv_strlen($prix) > 10){
        $msg .= '<div class="alert alert-danger mt-3">Le prix est obligatoire, doit être numérique et inférieur à 10 chiffres</div>';
    }


    // Contrôle date
    if(empty($arrivee) || empty($depart)){
        $msg .= '<div class="alert alert-danger mt-3">Les dates d\'arrivée et de départ sont obligatoires</div>';
    }

    if($arrivee < date('Y-m-d h:i:s')){
        $msg .= '<div class="alert alert-danger mt-3">La date d\'arrivée ne peut pas être antérieur à la date d\'aujourd\'hui</div>';
    }

    if($depart <= $arrivee){
        $msg .= '<div class="alert alert-danger mt-3">La date de départ ne peut pas être antérieur à la date d\'arrivée</div>';
    }
    

    // Vérification disponibilitée date 
    $pdo_verif_disponibilite_date = $pdo->prepare("SELECT id_produit FROM produit
    WHERE id_salle = :salle
    AND id_produit != :id_produit
    AND ((:arrivee >= date_arrivee AND :depart <= date_depart)
    OR (:arrivee <= date_arrivee AND :depart >= date_depart)
    OR (:arrivee <= date_arrivee AND :depart BETWEEN date_arrivee AND date_depart)
    OR (:depart >= date_depart AND :arrivee BETWEEN date_arrivee AND date_depart))");
    $pdo_verif_disponibilite_date->bindParam(':salle', $salle, PDO::PARAM_STR);
    $pdo_verif_disponibilite_date->bindParam(':arrivee', $arrivee, PDO::PARAM_STR);
    $pdo_verif_disponibilite_date->bindParam(':depart', $depart, PDO::PARAM_STR);
    $pdo_verif_disponibilite_date->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
    $pdo_verif_disponibilite_date->execute();

    if($pdo_verif_disponibilite_date->rowCount() > 0){
        $msg .= '<div class="alert alert-danger mt-3">Produit existant sur cette période</div>';
    }


    // Enregistrement sur la base de donnée si absence d'erreur
    if(empty($msg)) {
        if(!empty($id_produit)){ // Update du produit
            $enregistrement_produit = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee = :arrivee, date_depart = :depart, prix = :prix WHERE id_produit = :id_produit");
            $enregistrement_produit->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
            
        }else{ // Création du produit
            $enregistrement_produit = $pdo->prepare("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_salle, :arrivee, :depart, :prix, 'libre')");
        }

        $enregistrement_produit->bindParam(":id_salle", $salle, PDO::PARAM_STR);
        $enregistrement_produit->bindParam(":arrivee", $arrivee, PDO::PARAM_STR);
        $enregistrement_produit->bindParam(":depart", $depart, PDO::PARAM_STR);
        $enregistrement_produit->bindParam(":prix", $prix, PDO::PARAM_STR);
        $enregistrement_produit->execute();
        header('location:' . URL . 'admin/administration.php?gestion=produits');
    }        

}


// ******************************************
// RECUPERATION DES DONNEES POUR MODIFICATION
// ******************************************

if(isset($_GET['gestion']) && $_GET['gestion'] == 'produits' &&  isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_produit'])) {
	
	$infos_produit = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
	$infos_produit->bindparam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
	$infos_produit->execute();
    
	if($infos_produit->rowCount() > 0) {
		$produit_actuelle = $infos_produit->fetch(PDO::FETCH_ASSOC);
		$id_produit = $produit_actuelle['id_produit']; 
		$salle = $produit_actuelle['id_salle'];
		$arrivee = strftime("%Y-%m-%d", strtotime($produit_actuelle['date_arrivee']));
		$depart = strftime("%Y-%m-%d", strtotime($produit_actuelle['date_depart']));
		$prix = $produit_actuelle['prix'];

        //Récupération information de la salle sélectionnée pour affichage dans le champ 'select'
        $donnees_salle = $pdo->query("SELECT * FROM salle WHERE id_salle = $salle");
        $ligne_salle = $donnees_salle->fetch(PDO::FETCH_ASSOC);
	}
}




//********************
//********************
// GESTION DES MEMBRES
//********************
//********************

if(
	isset($_POST['id_membre']) &&
	isset($_POST['pseudo']) &&
	isset($_POST['mdp']) &&
	isset($_POST['nom']) &&
	isset($_POST['prenom']) &&
	isset($_POST['email']) &&
	isset($_POST['civilite']) &&
	isset($_POST['statut'])) {

    $id_membre = trim($_POST['id_membre']);
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $prenom = ucfirst(trim($_POST['prenom']));
    $nom = ucfirst(trim($_POST['nom']));
    $email = trim($_POST['email']);
    $civilite = trim($_POST['civilite']);
    $statut = trim($_POST['statut']);


    // Condition pour le champ pseudo : a-z A-Z 0-9 -._ 
    $verif_pseudo = preg_match('#^(?=.*[a-zA-Z0-9._-]).{4,14}+$#', $pseudo);
    // Condition pour le champ mdp : Au moins une minuscule, une majuscule, un chiffre, un caractère spécial et longueur entre 10 et 20 caractère
    $verif_mdp = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{10,20}$#', $mdp);
    // Condition prenom et nom : Max 25 caractères, limitation caractère spéciaux et une majuscule
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


    // Enregistrement sur la base de donnée
    if(empty($msg)) {
        // Vérification de la disponibilité du pseudo
        $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $verif_pseudo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $verif_pseudo->execute();

        if($verif_pseudo->rowCount() > 0 && empty($id_membre)){
            $msg .= '<div class="alert alert-danger mt-3">Pseudo indisponible !</div>';	
        }else{    
            if(!empty($id_membre)){ // Update du membre
                // cryptage du mdp
                $mdp = password_hash($mdp, PASSWORD_DEFAULT);
                $enregistrement_membre = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");
                $enregistrement_membre->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);

            }else{ // Création d'un membre
                // cryptage du mdp
                $mdp = password_hash($mdp, PASSWORD_DEFAULT);
                $enregistrement_membre = $pdo->prepare("INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, CURDATE())");
                echo'test';
            }
            $enregistrement_membre->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(':nom', $nom, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(':email', $email, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(':civilite', $civilite, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(':statut', $statut, PDO::PARAM_STR);
            $enregistrement_membre->execute();
            header('location:' . URL . 'admin/administration.php?gestion=membres');
        }
    }
}


// ******************************************
// RECUPERATION DES DONNEES POUR MODIFICATION
// ******************************************

if(isset($_GET['gestion']) && $_GET['gestion'] == 'membres' &&  isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_membre'])) {
	
	$infos_membre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
	$infos_membre->bindparam(":id_membre", $_GET['id_membre'], PDO::PARAM_STR);
	$infos_membre->execute();
	
	if($infos_membre->rowCount() > 0) {
		$membre_actuel = $infos_membre->fetch(PDO::FETCH_ASSOC);
		$id_membre = $membre_actuel['id_membre']; 
		$pseudo = $membre_actuel['pseudo'];
		$mdp = $membre_actuel['mdp'];
		$nom = $membre_actuel['nom'];
		$prenom = $membre_actuel['prenom'];
		$email = $membre_actuel['email'];
		$civilite = $membre_actuel['civilite'];
        $statut = $membre_actuel['statut'];
	}
}




//*****************
//*****************
// GESTION DES AVIS
//*****************
//*****************

$pdo_liste_avis = $pdo->query("SELECT a.id_avis, a.id_membre, a.id_salle, m.pseudo, s.titre, a.commentaire, a.note, a.date_enregistrement FROM membre m, salle s, avis a
WHERE a.id_salle = s.id_salle
AND a.id_membre = m.id_membre
ORDER BY a.date_enregistrement DESC");




//**********************
//**********************
// GESTION DES COMMANDES
//**********************
//**********************

$pdo_liste_commande = $pdo->query("SELECT c.id_commande, c.date_enregistrement, c.id_membre, m.pseudo, s.id_salle ,s.titre, p.prix, p.date_arrivee, p.date_depart, p.id_produit  FROM membre m, produit p, commande c, salle s
WHERE c.id_membre = m.id_membre
AND c.id_produit = p.id_produit
AND p.id_salle = s.id_salle
ORDER BY c.date_enregistrement DESC");




//*************************
//*************************
// GESTION DES STATISTIQUES
//*************************
//*************************

/* Top 5 des salles les mieux notees */
$pdo_top_salle_note = $pdo->query("SELECT a.id_salle, s.titre, ROUND(AVG(a.note),1) AS moyenne_note FROM salle s
INNER JOIN avis a ON s.id_salle = a.id_salle 
GROUP BY s.id_salle
ORDER BY moyenne_note DESC
LIMIT 0, 5");

/* Top 5 des salles les plus commandées */
$pdo_top_salle_commande = $pdo->query("SELECT COUNT(*) AS nombre, p.id_salle, titre FROM commande c
INNER JOIN produit p ON p.id_produit = c.id_produit
INNER JOIN salle s ON s.id_salle = p.id_salle
GROUP BY p.id_salle
ORDER BY nombre DESC
LIMIT 0, 5");

/* Top 5 des membres qui achètent le plus */
$pod_top_membre_quantite = $pdo->query("SELECT COUNT(*) AS nombre, m.id_membre, m.pseudo FROM commande c
INNER JOIN membre m ON c.id_membre = m.id_membre
GROUP BY m.id_membre
ORDER BY nombre DESC
LIMIT 0, 5");

/* Top 5 des membres qui achètent le plus */
$pod_top_membre_prix = $pdo->query("SELECT m.id_membre, m.pseudo, p.prix FROM commande c
INNER JOIN membre m ON c.id_membre = m.id_membre
INNER JOIN produit p ON c.id_produit = p.id_produit
ORDER BY p.prix DESC
LIMIT 0, 5");


include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<!-- Affichage du titre en fonction de la fenêtre de gestion sélectionnée -->
<div class="starter-template">
            <h1><?php
            if(isset($_GET['gestion']) && ($_GET['gestion'] == 'salles')){
                echo '<i class="fas fa-puzzle-piece" style="color: #28a745;"></i> Gestion des salles <i class="fas fa-puzzle-piece" style="color: #28a745;"></i>';
            }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'produits')){
                echo '<i class="fab fa-product-hunt" style="color: #28a745;"></i> Gestion des produits <i class="fab fa-product-hunt" style="color: #28a745;"></i>';
            }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'membres')){
                echo '<i class="fas fa-users-cog" style="color: #28a745;"></i> Gestion des membres <i class="fas fa-users-cog" style="color: #28a745;"></i>';
            }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'avis')){
                echo '<i class="far fa-comments" style="color: #28a745;"></i> Gestion des avis <i class="far fa-comments" style="color: #28a745;"></i>';
            }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'commandes')){
                echo '<i class="fas fa-shopping-basket" style="color: #28a745;"></i> Gestion des commandes <i class="fas fa-shopping-basket" style="color: #28a745;"></i>';
            }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'statistiques')){
                echo '<i class="fas fa-signal" style="color: #28a745;"></i> Statistiques <i class="fas fa-signal" style="color: #28a745;"></i>';
            }
            ?></h1>
            <p class="lead"><?php echo $msg; ?></p>
</div>

<!-- Menu de gestion -->
<div class="row administration align-items-center">
	<div class="col-lg-3">
		<div class="list-group text-center">
			<a href="<?php echo URL.'admin/administration.php?gestion=salles' ?>" class="list-group-item pad"><i class="fas fa-puzzle-piece" style="color: #28a745;"></i> Gestion des salles</a>
			<a href="<?php echo URL.'admin/administration.php?gestion=produits' ?>" class="list-group-item pad"><i class="fab fa-product-hunt" style="color: #28a745;"></i>  Gestion des produits</a>
			<a href="<?php echo URL.'admin/administration.php?gestion=membres' ?>" class="list-group-item pad"><i class="fas fa-users-cog" style="color: #28a745;"></i> Gestion des membres</a>
			<a href="<?php echo URL.'admin/administration.php?gestion=avis' ?>" class="list-group-item pad"><i class="far fa-comments" style="color: #28a745;"></i> Gestion des avis</a>
			<a href="<?php echo URL.'admin/administration.php?gestion=commandes' ?>" class="list-group-item pad"><i class="fas fa-shopping-basket" style="color: #28a745;"></i> Gestion des commandes</a>
			<a href="<?php echo URL.'admin/administration.php?gestion=statistiques' ?>" class="list-group-item pad"><i class="fas fa-signal" style="color: #28a745;"></i> Statistiques</a>
		</div>
	</div>

	<div class="col-lg-9 mt-5 mt-md-0">




<!-- ------------------- -->
<!-- ------------------- -->
<!-- GESTIONS DES SALLES -->
<!-- ------------------- -->
<!-- ------------------- -->

<?php if(isset($_GET['gestion']) && ($_GET['gestion'] == 'salles')){ ?>
	
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="id_salle" value="<?php echo $id_salle ?>">
            <div class="row align-items-center justify-content-center">				
                <div class="col-10 col-sm-6">					
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" name="titre" id="titre" value="<?php echo $titre; ?>" class="form-control">
                    </div>	
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="2" class="form-control"><?php echo $description; ?></textarea>
                    </div>
                    <?php
                        // Récupération de la photo de l'article en cas de modification pour la conserver si l'utilisateur n'en charge pas une nouvelle
                        if(!empty($photo_actuelle)){
                            echo '<div class="form-group text-center">
                            <label>Photo actuelle</label><hr>
                            <img src="'.URL.'img/'.$photo_actuelle.'"class="w-25 img-thumbnail" alt="image de l\'article">
                            <input type="hidden" name="photo_actuelle" value="'.$photo_actuelle.'">
                            </div>';
                        }
                    ?>	
                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="capacite">Capacité</label>
                        <select name="capacite" id="capacite" class="form-control">
                            <option value="5">Entre 1 et 5</option>
                            <option value="10" <?php if($capacite == '10') { echo 'selected'; } ?> >Entre 5 et 10</option>
                            <option value="20" <?php if($capacite == '20') { echo 'selected'; } ?> >Entre 10 et 20</option>
                            <option value="30" <?php if($capacite == '30') { echo 'selected'; } ?> >Entre 20 et 30</option>
                            <option value="50" <?php if($capacite == '50') { echo 'selected'; } ?> >Entre 30 et 50</option>
                            <option value="100" <?php if($capacite == '100') { echo 'selected'; } ?> >Entre 50 et 100</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select name="categorie" id="categorie" class="form-control">
                            <option value="bureau">Bureau</option>
                            <option <?php if($categorie == 'reunion') { echo 'selected'; } ?> value="reunion">Réunion</option>
                            <option <?php if($categorie == 'formation') { echo 'selected'; } ?> value="formation">Formation</option>
                        </select>
                    </div>			
                </div>
                <div class="col-10 col-sm-6">					
                    <div class="form-group">
                        <label for="pays">Pays</label>
                        <select name="pays" id="pays" class="form-control">
                            <option value="france">France</option>
                        </select>
                    </div>	
                    <div class="form-group">
                        <label for="ville">Ville</label>
                        <select name="ville" id="ville" class="form-control">
                            <option value="paris">Paris</option>
                            <option value="lyon" <?php if($ville == 'lyon') { echo 'selected'; } ?> >Lyon</option>
                            <option value="marseille" <?php if($ville == 'marseille') { echo 'selected'; } ?> >Marseille</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="adresse">Adresse</label>
                        <textarea name="adresse" id="adresse" rows="3" class="form-control"><?php echo $adresse; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cp">Code Postal</label>
                        <input type="text" name="cp" id="cp" value="<?php echo $cp; ?>" class="form-control">
                    </div>	
                    <div class="form-group">
                        <button type="submit" name="enregistrement_salle" id="enregistrement" class="form-control btn btn-outline-success"> Enregistrement </button>
                    </div>								
                </div>
            </div>
        </form>
    </div>

    <!-- Affichage des salles --> 
    <div class="col-12 mt-5 text-center">
        <?php
            $liste_salle = $pdo->query("SELECT * FROM salle ORDER BY id_salle DESC");
            echo '<div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Id salle</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Capacité max</th>
                        <th>Catégorie</th>
                        <th>Pays</th>
                        <th>Ville</th>
                        <th>Adresse</th>
                        <th>Code Postal</th>
                        <th>Photo</th>
                    </tr>';
                    
                    while($ligne_salles = $liste_salle->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>
                            <td>' . $ligne_salles['id_salle'] . '</td>
                            <td>' . $ligne_salles['titre'] . '</td>
                            <td>' . substr($ligne_salles['description'], 0, 30) . ' ...</td>
                            <td>' . $ligne_salles['capacite'] . '</td>
                            <td>' . ucfirst($ligne_salles['categorie']) . '</td>
                            <td>' . ucfirst($ligne_salles['pays']) . '</td>
                            <td>' . ucfirst($ligne_salles['ville']) . '</td>
                            <td>' . $ligne_salles['adresse'] . '</td>
                            <td>' . $ligne_salles['cp'] . '</td>
                            <td><img src="' . URL . '/img/' . $ligne_salles['photo'] . '" class="img-thumbnail" width="140"></td>
                            <td><a href="?gestion=salles&action=modifier&id_salle=' . $ligne_salles['id_salle'] . '" class="btn btn-warning"><i class="fas fa-edit"></i></a></td>
                            <td><a href="?gestion=salles&action=supprimer&id_salle=' . $ligne_salles['id_salle'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-trash-alt"></i></a></td>
                        </tr>';
                    }    
                echo '</table>
            </div>';  
        ?>
    </div>




<!-- --------------------- -->
<!-- --------------------- -->
<!-- GESTIONS DES PRODUITS -->
<!-- --------------------- -->
<!-- --------------------- -->

<?php }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'produits')) { ?>

        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="id_produit" value="<?php echo $id_produit ?>">
            <div class="row justify-content-center">				
                <div class="col-10 col-sm-6">					
                    <div class="form-group">
                        <label for="arrivee">Date d'arrivée</label>
                        <input type="date" name="arrivee" id="arrivee" value="<?php echo $arrivee; ?>" class="form-control">
                    </div>	
                    <div class="form-group">
                        <label for="depart">Date de départ</label>
                        <input type="date" name="depart" id="depart" value="<?php echo $depart; ?>" class="form-control">
                    </div>
                </div>	
                <div class="col-10 col-sm-6">					
                    <div class="form-group">
                        <label for="salle">Salle</label>
                        <select name="salle" id="salle" class="form-control">
                            <?php
                                if(!empty($id_produit)){
                                    echo '<option value="'.$ligne_salle['id_salle'].'">'.$ligne_salle['id_salle'].' - '.$ligne_salle['titre'].', '.$ligne_salle['adresse'].', '.$ligne_salle['ville'].'</option>';
                                }
                                while($ligne = $donnees_salles->fetch(PDO::FETCH_ASSOC)){
                                echo '<option value="'.$ligne['id_salle'].'">'.$ligne['id_salle'].' - '.$ligne['titre'].', '.$ligne['adresse'].', '.$ligne['ville'].'</option>';}
                            ?>
                        </select>
                    </div>	
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="text" name="prix" id="prix" value="<?php echo $prix; ?>" class="form-control">
                    </div>								
                </div>
                <div class="form-group col-10 col-sm-6">
                    <button type="submit" name="enregistrement_produit" id="enregistrement2" class="form-control btn btn-outline-success"> Enregistrement </button>
                </div>	
            </div>
        </form>
    </div>

    <!-- Affichage des produits --> 
    <div class="col-12 mt-5  text-center">
        <?php
            $liste_produit = $pdo->query("SELECT *, p.id_produit AS idp FROM produit p
            LEFT JOIN commande c ON p.id_produit = c.id_produit
            LEFT JOIN membre m ON c.id_membre = m.id_membre
            ORDER BY p.id_produit DESC");
            echo '<div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Id produit</th>
                        <th>Date d\'arrivée</th>
                        <th>Date de départ</th>
                        <th>Id salle</th>
                        <th>Prix</th>
                        <th>Etat</th>
                    </tr>';
                    
                    while($produit = $liste_produit->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>
                            <td>' . $produit['idp'] . '</td>
                            <td>' . strftime("%d-%m-%Y", strtotime($produit['date_arrivee'])) . '</td>
                            <td>' . strftime("%d-%m-%Y", strtotime($produit['date_depart'])) . '</td>
                            <td>' . $produit['id_salle'] . '</td>
                            <td>' . $produit['prix'] . '€</td>
                            <td>';
                            if($produit['etat'] == 'libre'){
                                echo ucfirst($produit['etat']);
                            }else{
                                echo ucfirst($produit['etat']) . ' par : <br>' . $produit['id_membre'] . ' - ' . $produit['pseudo'];
                            }
                            echo '</td>
                            <td><a href="?gestion=produits&action=modifier&id_produit=' . $produit['idp'] . '" class="btn btn-warning"><i class="fas fa-edit"></i></a></td>
                            <td><a href="?gestion=produits&action=supprimer&id_produit=' . $produit['idp'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-trash-alt"></i></a></td>                            
                        </tr>';
                    } 
                echo '</table>
            </div>';  
        ?>
    </div>




<!-- -------------------- -->
<!-- -------------------- -->
<!-- GESTIONS DES MEMBRES -->
<!-- -------------------- -->
<!-- -------------------- -->

<?php }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'membres')){ ?>
		
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="id_membre" value="<?php echo $id_membre ?>">
            <div class="row justify-content-center align-items-center">				
                <div class="col-10 col-sm-6">					
                    <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" class="form-control">
                    </div>	
                    <div class="form-group">
                        <label for="mdp">Mot de passe</label>
                        <input type="password" name="mdp" id="mdp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" value="<?php echo $nom; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" class="form-control">
                    </div>
                </div>	
                <div class="col-10 col-sm-6">					
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="civilite">Civilité</label>
                        <select name="civilite" id="civilite" class="form-control">
                            <option value="m">Homme</option>
                            <option value="f" <?php if($civilite == 'f') { echo 'selected'; } ?> >Femme</option>
                        </select>	
                    </div>		
                    <div class="form-group">
                        <label for="statut">Statut</label>
                        <select name="statut" id="statut" class="form-control">
                            <option value="2">Membre</option>
                            <option value="1" <?php if($statut == "1") { echo 'selected'; } ?> >Administrateur</option>
                        </select>	
                    </div>
                    <div class="form-group">
                        <button type="submit" name="enregistrement" id="enregistrement2" class="form-control btn btn-outline-primary"> Enregistrement </button>
                    </div>										
                </div>
            </div>
        </form>
    </div>

    <!-- Affichage des membres --> 
    <div class="col-12 mt-5  text-center">
        <?php
            $liste_membre = $pdo->query("SELECT * FROM membre ORDER BY date_enregistrement DESC");
            echo '<div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Id membre</th>
                        <th>Pseudo</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>email</th>
                        <th>Civilité</th>
                        <th>Statut</th>
                        <th>Date_enregistrement</th>
                    </tr>';
                    
                    while($membre = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>
                            <td>' . $membre['id_membre'] . '</td>
                            <td>' . $membre['pseudo'] . '</td>
                            <td>' . $membre['nom'] . '</td>
                            <td>' . $membre['prenom'] . '</td>
                            <td>' . $membre['email'] . '</td>';
                            if($membre['civilite']=="f"){
                                echo '<td>Femme</td>';
                            }else{
                                echo '<td>Homme</td>';
                            }
                            if($membre['statut']=='1'){
                                echo '<td>Admin</td>';
                            }else{
                                echo '<td>Membre</td>';
                            }
                            echo '<td>' . strftime("%d-%m-%Y", strtotime($membre['date_enregistrement'])) . '</td>
                            <td><a href="?gestion=membres&action=modifier&id_membre=' . $membre['id_membre'] . '" class="btn btn-warning"><i class="fas fa-edit"></i></a></td>
                            <td><a href="?gestion=membres&action=supprimer&id_membre=' . $membre['id_membre'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-trash-alt"></i></a></td>                            
                        </tr>';
                    }                    
                echo '</table>
            </div>';  
        ?>
    </div>




<!-- ----------------- -->
<!-- ----------------- -->
<!-- GESTIONS DES AVIS -->
<!-- ----------------- -->
<!-- ----------------- -->

<?php }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'avis')){ ?>

    </div>

    <!-- Affichage des avis--> 
    <div class="col-12 mt-5  text-center">
        <?php
            echo '<div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Id avis</th>
                        <th>Id membre</th>
                        <th>Id salle</th>
                        <th>Commentaire</th>
                        <th>Note /5</th>
                        <th>Date d\'enregistrement</th>
                    </tr>';
                    
                    while($liste_avis = $pdo_liste_avis->fetch(PDO::FETCH_ASSOC)) {

                        echo '<tr>
                            <td>' . $liste_avis['id_avis'] . '</td>
                            <td>' . $liste_avis['id_membre'] .' - '. $liste_avis['pseudo'] . '</td>
                            <td>' . $liste_avis['id_salle'] .' - '. $liste_avis['titre'] . '</td>
                            <td>' . htmlentities($liste_avis['commentaire'], ENT_QUOTES) . '</td>
                            <td>' . $liste_avis['note'] . '</td>
                            <td>' . date_format(date_create($liste_avis['date_enregistrement']), 'd-m-Y H:i:s') . '</td>
                            <td><a href="?gestion=avis&action=supprimer&id_avis=' . $liste_avis['id_avis'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-trash-alt"></i></a></td>           
                        </tr>';
                    }      
                echo '</table>
            </div>';  
        ?>
    </div>




<!-- --------------------- -->
<!-- --------------------- -->
<!-- GESTION DES COMMANDES -->
<!-- --------------------- -->
<!-- --------------------- -->

<?php }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'commandes')){ ?>

    </div>

    <!-- Affichage des commande --> 
    <div class="col-12 mt-5 text-center">
        <?php
            echo '<div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Id commande</th>
                        <th>Id membre</th>
                        <th>Id produit / salle</th>
                        <th>Prix</th>
                        <th>Date d\'enregistrement</th>
                    </tr>';
                    
                    while($liste_commande = $pdo_liste_commande->fetch(PDO::FETCH_ASSOC)) {

                        echo '<tr>
                            <td>' . $liste_commande['id_commande'] . '</td>
                            <td>' . $liste_commande['id_membre'].' - '.$liste_commande['pseudo'] . '</td>
                            <td>' . $liste_commande['id_produit'].' / '.$liste_commande['id_salle'].' - '.$liste_commande['titre'] . '<br>'.strftime("%d-%m-%Y", strtotime($liste_commande['date_arrivee'])).' au '.strftime("%d-%m-%Y", strtotime($liste_commande['date_depart'])).'</td>
                            <td>' . $liste_commande['prix'] . '€</td>
                            <td>' . strftime("%d-%m-%Y", strtotime($liste_commande['date_enregistrement'])) . '</td>
                            <td><a href="?gestion=commandes&action=supprimer&id_commande=' . $liste_commande['id_commande'] . '&id_produit=' . $liste_commande['id_produit'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-trash-alt"></i></a></td>                       
                        </tr>';
                    }                    
                echo '</table>
            </div>';  
        ?>
    </div>




<!-- ------------------------- -->
<!-- ------------------------- -->
<!-- GESTIONS DES STATISTIQUES -->
<!-- ------------------------- -->
<!-- ------------------------- -->

<?php }elseif(isset($_GET['gestion']) && ($_GET['gestion'] == 'statistiques')){ ?>

	    <div class="row align-items-center justify-content-center text-center">

            <!-- Top 5 salles les mieux notées --> 
            <div class="col-10 col-sm-6 mt-4">
                <h4 class="mb-3 ">Salles les mieux notées</h4>
                <?php
                    echo '<div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID et nom de la salle</th>
                                <th>Note</th>
                            </tr>';
                            
                            while($liste1 = $pdo_top_salle_note->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>
                                    <td>' . $liste1['id_salle'] . ' - ' . $liste1['titre'] . '</td>
                                    <td>' . $liste1['moyenne_note'] . '/5</td>
                                </tr>';
                            }                                
                        echo '</table>
                    </div>';  
                ?>
            </div>

            <!-- Top 5 des salles les plus commandées --> 
            <div class="col-10 col-sm-6 mt-4">
                <h4 class="mb-3 ">Salles les plus commandées</h4>
                <?php
                    echo '<div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID et nom de la salle</th>
                                <th>Nbr de commande</th>
                            </tr>';
                            
                            while($liste2 = $pdo_top_salle_commande->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>
                                    <td>' . $liste2['id_salle'] . ' - ' . $liste2['titre'] . '</td>
                                    <td>' . $liste2['nombre'] . '</td>
                                </tr>';
                            }                                
                        echo '</table>
                    </div>';  
                ?>
            </div>

            <!-- Top 5 des membres qui achètent le plus --> 
            <div class="col-10 col-sm-6 mt-4">
                <h4 class="mb-3 ">Les plus gros acheteurs</h4>
                <?php
                    echo '<div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID et pseudo du membre</th>
                                <th>Nbr de commande</th>
                            </tr>';
                            
                            while($liste3 = $pod_top_membre_quantite->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>
                                    <td>' . $liste3['id_membre'] . ' - ' . $liste3['pseudo'] . '</td>
                                    <td>' . $liste3['nombre'] . '</td>
                                </tr>';
                            }                                
                        echo '</table>
                    </div>';  
                ?>
            </div>

            <!-- Top 5 des membres qui achètent le plus cher --> 
            <div class="col-10 col-sm-6 mt-4">
                <h4 class="mb-3 ">Les plus gros achats</h4>
                <?php
                    echo '<div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID et pseudo du membre</th>
                                <th>Montant</th>
                            </tr>';
                            
                            while($liste4 = $pod_top_membre_prix->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>
                                    <td>' . $liste4['id_membre'] . ' - ' . $liste4['pseudo'] . '</td>
                                    <td>' . $liste4['prix'] . '€</td>
                                </tr>';
                            }                                
                        echo '</table>
                    </div>';  
                ?>
            </div>
        </div>
    </div>

<?php } ?> 

<?php 
include '../inc/footer.inc.php';