<?php 
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

// Si l'utilisateur n'est connecté, on le renvoie sur la page connexion
if(!user_is_connect()) {
	header('location:connexion.php');
}




//***********
//***********
// HISTORIQUE
//***********
//***********

$id_membre = $_SESSION['membre']['id_membre'];

$pdo_liste_historique = $pdo->query("SELECT c.id_commande, c.date_enregistrement, c.id_membre, m.pseudo, s.id_salle ,s.titre, p.prix, p.date_arrivee, p.date_depart, p.id_produit FROM membre m, produit p, commande c, salle s
WHERE c.id_membre = m.id_membre
AND c.id_produit = p.id_produit
AND p.id_salle = s.id_salle
AND c.id_membre = $id_membre
ORDER BY c.id_commande DESC");

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>


<div class="starter-template">
    <h1><i class="fas fa-user" style="color: #28a745;"></i> Profil <i class="fas fa-user" style="color: #28a745;"></i></h1>
    <p class="lead"><?php echo $msg; ?></p>
</div>

<div class="row justify-content-center profil">
    <div class="col-12 col-sm-6">
        <ul class="list-group">
            <li class="list-group-item active fond">Bonjour <b><?php echo ucfirst($_SESSION['membre']['pseudo']); ?></b></li>
            <li class="list-group-item">Pseudo : <b><?php echo ucfirst($_SESSION['membre']['pseudo']); ?></b></li>
            <li class="list-group-item">Nom : <b><?php echo strtoupper($_SESSION['membre']['nom']); ?></b></li>
            <li class="list-group-item">Prénom : <b><?php echo ucfirst($_SESSION['membre']['prenom']); ?></b></li>
        </ul>
    </div>
    <div class="col-12 col-sm-6">
        <ul class="list-group">
            <li class="list-group-item">Email : <b><?php echo $_SESSION['membre']['email']; ?></b></li>
            <li class="list-group-item">Sexe : <b>
                <?php 
                    if($_SESSION['membre']['civilite'] == 'm') {
                        echo 'Homme';
                    } else {
                        echo 'Femme';
                    }
                ?></b>
            </li>
            <li class="list-group-item">Statut : <b>
            <?php 
                if($_SESSION['membre']['statut'] == 1) {
                    echo 'Membre';
                } elseif($_SESSION['membre']['statut'] == 2) {
                    echo 'Administrateur';
                }
            ?>
            </b></li>
            <li class="list-group-item">Date d'inscription : <b><?php echo strftime("%d-%m-%Y", strtotime($_SESSION['membre']['date_enregistrement'])); ?></b></li>
        </ul>
    </div>
    <div class="col-6 col-sm-3 mt-5">
        <div class="form-group">
            <a href="inscription.php?action=modification" class="form-control btn btn-outline-success">Modification du profil</a>
        </div>	       
    </div>

    <!-- Affichage de l'historique --> 
    <div class="col-12 mt-5 text-center">
        <?php
            echo '<div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Id commande</th>
                        <th>Id produit/salle</th>
                        <th>Prix</th>
                        <th>Date de la commande</th>
                    </tr>';
                    
                    while($liste_historique = $pdo_liste_historique->fetch(PDO::FETCH_ASSOC)) {

                        echo '<tr>
                            <td>' . $liste_historique['id_commande'] . '</td>
                            <td><b>' . $liste_historique['id_produit'].'/'. $liste_historique['id_salle'] .' - '.$liste_historique['titre'] . '</b><br>'.strftime("%d-%m-%Y", strtotime($liste_historique['date_arrivee'])).' au '.strftime("%d-%m-%Y", strtotime($liste_historique['date_depart'])).'</td>
                            <td>' . $liste_historique['prix'] . '€</td>
                            <td>' . strftime("%d-%m-%Y", strtotime($liste_historique['date_enregistrement'])) . '</td>
                            <td><a href="fiche_salle.php?salle=' . $liste_historique['id_salle'] . '&produit='. $liste_historique['id_produit'] .'" class="btn btn-success"><i class="far fa-comment-alt"></i></a></td>
                        </tr>';
                    }                        
                echo '</table>';
            echo '</div>';  
        ?>
    </div>
</div>

<?php 
include 'inc/footer.inc.php';