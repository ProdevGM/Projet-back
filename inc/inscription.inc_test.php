<?php

$pseudo = '';
$mdp = '';
$prenom = '';
$nom = '';
$email = '';
$sexe = '';
$ville = '';
$cp = '';
$adresse = '';

// on controle l'existence des champs du formulaire	
if(
	isset($_POST['pseudo']) && 
	isset($_POST['mdp']) && 
	isset($_POST['prenom']) && 
	isset($_POST['nom']) && 
	isset($_POST['email']) && 
	isset($_POST['sexe'])) {

    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $sexe = trim($_POST['sexe']);

    // S'il n'y pas eu d'erreur au préalable, on doit vérifier si le pseudo existe déjà dans la BDD
    if(empty($msg)) {
        // si la variable $msg est vide, alors il n'y a pas eu d'erreur dans nos controles.
        
        // on vérifie si le pseudo est disponible.
        $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $verif_pseudo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $verif_pseudo->execute();
        
        if($verif_pseudo->rowCount() > 0) {
            // si le nombre de ligne est supérieur à zéro, alors le pseudo est déjà utilisé.
            $msg .= '<div class="alert alert-danger mt-3">Pseudo indisponible !</div>';	
        } else {
            // insert into
            // cryptage du mot de passe pour l'insertion en BDD
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);
            
            // On déclenche l'insertion
            $enregistrement = $pdo->prepare("INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, sexe, statut) VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :sexe, 2)");
            $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
            $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
            $enregistrement->bindParam(':sexe', $sexe, PDO::PARAM_STR);
            $enregistrement->execute();
        }			
        
    }
}

?>

<div class="modal fade" id="modalInscription" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="starter-template">
                <h3>S'inscrire</h3>
                <p class="lead"><?php echo $msg; ?></p>
            </div>
            <div class="modal-body">
                <form class="row justify-content-center" method="post">
                    <div class="form-group col-9">
                        <input class="form-control mt-2" type="text" name="pseudo" placeholder="Votre pseudo" value="<?php echo $pseudo; ?>">
                    </div>
                    <div class="form-group col-9">
                        <input class="form-control mt-2" type="text" name="mdp" placeholder="Votre mot de passe">
                    </div>
                    <div class="form-group col-9">
                        <input class="form-control mt-2" type="text" name="nom" id="nom" placeholder="Votre nom" value="<?php echo $nom; ?>">
                    </div>
                    <div class="form-group col-9">
                        <input class="form-control mt-2" type="text" name="prenom" id="nom" placeholder="Votre prénom" value="<?php echo $prenom; ?>">
                    </div>
                    <div class="form-group col-9">
                        <input class="form-control mt-2" type="text" name="email" id="nom" placeholder="Votre email" value="<?php echo $email; ?>">
                    </div>
<!--                     <div class="form-group col-9">
                        <select class="form-control" name="sexe">
                            <option value='m'>Homme</option>
                            <option value='f' <?php if($sexe == 'f') { echo 'selected'; } ?> >Femme</option>
                        </select>
                    </div> -->
                    <button type="submit" class="form-control col-9 btn btn-outline-primary mb-5">Inscription</button>
                </form>
            </div>
        </div>
    </div>
</div>