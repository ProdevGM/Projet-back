<?php 
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

    if(!isset($_GET['salle']) || !isset($_GET['produit'])){
        header('location:index.php');
    }else{

        // Enregistrement du commentaire
        if(isset($_POST['note']) && isset($_POST['commentaire']) && isset($_SESSION['membre'])) {

            $note = trim($_POST['note']);
            $commentaire = trim($_POST['commentaire']);
            $id_membre = $_SESSION['membre']['id_membre'];
            $id_salle = $_GET['salle'];
            echo $note;
            $enregistrement_commentaire = $pdo->prepare("INSERT INTO avis (id_avis, id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (NULL, $id_membre, $id_salle, :commentaire, :note, NOW())");
            $enregistrement_commentaire->bindParam(":commentaire", $commentaire, PDO::PARAM_STR);
            $enregistrement_commentaire->bindParam(":note", $note, PDO::PARAM_STR);
            $enregistrement_commentaire->execute();
        }  

        $id_produit = trim($_GET['produit']);

        // Récupération des caractéristique de la salle
        $pdo_infos_salle = $pdo->prepare("SELECT *, ROUND(AVG(a.note),1) AS moyenne_avis, s.id_salle AS salle FROM produit p
        INNER JOIN salle s ON p.id_salle = s.id_salle
        LEFT JOIN avis a ON a.id_salle = s.id_salle
        WHERE p.id_produit = $id_produit");
        $pdo_infos_salle->bindParam(':id_salle', $_GET['salle'], PDO::PARAM_STR);
        $pdo_infos_salle->execute();

        if($pdo_infos_salle->rowCount() < 1){
            header('location:index.php');
        }
        $infos_salle = $pdo_infos_salle->fetch(PDO::FETCH_ASSOC);


        // Récupération des avis
        $pdo_commentaire = $pdo->prepare("SELECT a.commentaire, a.note, a.date_enregistrement, a.id_membre ,m.pseudo FROM avis a
        LEFT JOIN membre m ON a.id_membre = m.id_membre
        WHERE id_salle = :id_salle");
        $pdo_commentaire->bindParam(":id_salle", $_GET['salle'], PDO::PARAM_STR);
        $pdo_commentaire->execute();


        // Réservation
        if(isset($_GET['action']) && $_GET['action'] == 'reserver'){

            // Contrôle si commande déjà existante pour ce produit
            $pdo_controle = $pdo->prepare("SELECT * FROM commande WHERE id_produit = :id_produit");
            $pdo_controle->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
            $pdo_controle->execute();
            if($pdo_controle->rowCount() > 0){
                header('location:index.php');
            }else{
                $pdo_reservation = $pdo->prepare("INSERT INTO commande (id_commande, id_membre, id_produit, date_enregistrement) VALUES (NULL, :id_membre, :id_produit, CURDATE())");
                $pdo_reservation->bindParam(":id_membre", $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
                $pdo_reservation->bindParam(":id_produit", $_GET['produit'] , PDO::PARAM_STR);
                $pdo_reservation->execute();
                
                // Mettre le produit en réserver
                $pdo_reserver = $pdo->prepare("UPDATE produit SET etat = 'reservation' WHERE id_produit = :id_produit");
                $pdo_reserver->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
                $pdo_reserver->execute();
                header('location:index.php?reservation=ok');
            }
        }
    }

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

	<div class="starter-template">
		<h1><i class="fas fa-puzzle-piece" style="color: #28a745;"></i> <?php echo $infos_salle['titre'] ?> <i class="fas fa-puzzle-piece" style="color: #28a745;"></i></h1>
		<p class="lead"><?php echo $msg; ?></p>
	</div>

	<div class="row align-items-center reservation">
        <div class="col-12 col-sm-6">
            <ul class="list-group">
                <li class="list-group-item"><img src="<?php echo URL.'img/'.$infos_salle['photo'] ?>" alt="image profil" class="img-thumbnail w-100 mt-4"></li>
                <li class="list-group-item"><b>Pays</b> : <?php echo ucfirst($infos_salle['pays']); ?></li>
                <li class="list-group-item"><b>Ville</b> : <?php echo ucfirst($infos_salle['ville']); ?></li>
                <li class="list-group-item"><b>Code postal</b> : <?php echo $infos_salle['cp']; ?></li>
                <li class="list-group-item"><b>Adresse</b> : <?php echo $infos_salle['adresse']; ?></li>
                <?php
                    if(empty($infos_salle['moyenne_avis'])){
                        echo '<li class="list-group-item"><b>Note : </b>Absence de note</li>';
                    }else{ ?>
                        <li class="list-group-item"><b>Avis</b> : <?php echo ($infos_salle['moyenne_avis']); ?> /5</li>
                <?php }

                if(isset($_SESSION['membre'])){?>
                    <li class="list-group-item">
                        <div class="form-group">
                            <form method="post">
                                <label for="commentaire" class="text-center w-100"><b>Laissez-nous un commentaire</b></label>
                                <textarea name="commentaire" id="commentaire" rows="3" class="form-control"></textarea>
                                <label for="note" class="mt-2 text-center w-100"><b>Note</b></label>
                                <select name="note" id="note" class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                                <div class="form-group mt-4">
                                    <input type="submit" class="form-control btn btn-outline-success">
                                </div>
                            </form>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="col-12 col-sm-6">
            <ul class="list-group">
                <li class="list-group-item embed-responsive embed-responsive-16by9"><iframe src="https://www.google.com/maps?q=//<?php echo $infos_salle['adresse'].' '.$infos_salle['ville'].' '.$infos_salle['cp']  ?>&output=embed" frameborder="0" style="border:0;" class="embed-responsive-item"></iframe></li>
                <li class="list-group-item"><b>Description</b> : <?php echo htmlentities($infos_salle['description'], ENT_QUOTES); ?></li> <!-- Sécuritation de l'affichage avec htmlentities -->
                <li class="list-group-item"><b>Capacité</b> :
                <?php
                    if($infos_salle['capacite'] == '5'){
                        echo 'Entre 1 et 5 personnes';
                    }elseif($infos_salle['capacite'] == '10'){
                        echo 'Entre 5 et 10 personnes';
                    }elseif($infos_salle['capacite'] == '20'){
                        echo 'Entre 10 et 20 personnes';
                    }elseif($infos_salle['capacite'] == '30'){
                        echo 'Entre 20 et 30 personnes';
                    }elseif($infos_salle['capacite'] == '50'){
                        echo 'Entre 30 et 50 personnes';
                    }elseif($infos_salle['capacite'] == '100'){
                        echo 'Entre 50 et 100 personnes';
                }?>
                </li>
                <li class="list-group-item"><b>Catégorie</b> : <?php echo ucfirst($infos_salle['categorie']); ?></li>
                <li class="list-group-item"><b>Du</b> <?php echo strftime("%d-%m-%Y", strtotime($infos_salle['date_arrivee'])); ?> <b>au</b> <?php echo strftime("%d-%m-%Y", strtotime($infos_salle['date_depart'])); ?> </li>
            </ul>

            <div class="form-group mt-4">
                <?php if(isset($_SESSION['membre'])){
                    echo '<a href="fiche_salle.php?salle='.$infos_salle['salle'].'&produit='.$id_produit.'&action=reserver" class="form-control btn btn-outline-success")> Reservation </a>';
                }else{?>
                    <a href="connexion.php" class="form-control btn btn-outline-success")> Pour réserver, connectez-vous </a>
                <?php } ?>
            </div>
        </div>

        <div class="col-12 mt-5">
            <div class=" row justify-content-around">
                <?php while($commentaire = $pdo_commentaire->fetch(PDO::FETCH_ASSOC)){
                    echo '<div class="col-11 col-sm-5 commentaire mb-3">
                    <p>Note <b>: ' . $commentaire['note'].'/5</b> de <b>';
                    if($commentaire['id_membre'] == ''){
                        echo 'Inconnu !';
                    }else{
                        echo $commentaire['pseudo'] . '</b> le <b>'. date_format(date_create($commentaire['date_enregistrement']), 'd-m-Y H:i:s').'</b></p>';
                    }
                    echo '<hr>
                    <p>"' . htmlentities($commentaire['commentaire'], ENT_QUOTES) . '"</p>
                    </div>';
                }?>
            </div>
        </div>
    </div>
    
<?php 
include 'inc/footer.inc.php';