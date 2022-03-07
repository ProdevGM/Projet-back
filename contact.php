<?php 
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


// Déclaration des variables
$nom = '';
$email = '';
$objet = '';
$message = '';
$destinataire = 'contact@switch.com';

// Si membre, récupération des données utiles
if(isset($_SESSION['membre'])){
    $nom = $_SESSION['membre']['nom'];
    $email = $_SESSION['membre']['email'];
}

// on teste si le formulaire a été soumis
if (
    isset($_POST['nom']) &&
    isset($_POST['email']) &&
    isset($_POST['objet']) &&
    isset($_POST['message'])){
    
        $nom = ucfirst(trim($_POST['nom']));
        $email = trim($_POST['email']);
        $objet = ucfirst(trim($_POST['objet']));
        $message = trim($_POST['message']);


        // Condition objet et nom : Max 20 caractères, limitation caractère spéciaux et une majuscule
        $verif_nom = preg_match('#^([A-Z][\p{L}-]).{0,20}+$#', $nom);
        $verif_objet = preg_match('#^([A-Z][\p{L}-]).{0,20}+$#', $objet);


        // Vérification validité nom
        if(!$verif_nom || empty($nom)){
            $msg .= '<div class="alert alert-danger mt-3">Nom invalide (maximum de 20 caractères et caractère spéciaux limités)</div>';	
        }


        // Vérification validité objet
        if(!$verif_objet || empty($objet)){
            $msg .= '<div class="alert alert-danger mt-3">Objet invalide (maximum de 20 caractères et caractère spéciaux limités)</div>';	
        }


        // Vérification validité email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
            $msg .= '<div class="alert alert-danger mt-3">Adresse mail invalide</div>';
        }


        // Si pas d'erreur
        if(empty($msg)){

            // Envoie du mail

        }
}


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

    <div class="starter-template">
		<h1><i class="far fa-envelope" style="color: #28a745;"></i> Contact <i class="far fa-envelope" style="color: #28a745;"></i></h1>
		<p class="lead"><?php echo $msg; ?></p>
	</div>

    <form method="post" action="contact.php?message=ok" class="row justify-content-center align-items-center">
        <div class="col-10 col-sm-6 form-group">
            <fieldset><legend>Vos coordonnées</legend>
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" class="form-control" value="<?php echo $nom; ?>">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>">
            </fieldset>
        </div>

        <div class="col-10 col-sm-6">
            <fieldset><legend>Votre message :</legend>
                <label for="objet">Objet :</label>
                <input type="text" id="objet" name="objet" class="form-control" value="<?php echo $objet; ?>">
                <label for="message">Message :</label>
                <textarea id="message" name="message" rows="4" class="form-control"><?php echo $message; ?></textarea>
                <button type="submit" name="inscription" id="inscription" class="form-control btn btn-outline-success mt-4"> Envoyer
            </fieldset>
        </div>
    </form>

<?php 
include 'inc/footer.inc.php';

// Notification de message envoyé
if(empty($msg)){
    if(isset($_GET['message']) && $_GET['message'] == 'ok'){
        echo "<script>alert(\"Votre message a été bien été envoyé\")</script>";
    }
}