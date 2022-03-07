<?php
/* error_reporting(E_ALL);
ini_set("display_errors", 1);  */
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


// Selection par filtre unique
$requete = "SELECT s.id_salle, s.titre, s.description, s.photo, s.ville, p.id_produit ,p.prix, p.date_arrivee, p.date_depart, p.etat , ROUND(AVG(a.note),1) AS moyenne_note FROM produit p 
INNER JOIN salle s ON s.id_salle = p.id_salle
LEFT JOIN avis a ON s.id_salle = a.id_salle
WHERE p.etat = 'libre'
AND p.date_arrivee >= CURDATE()"; // Uniquement les produits libres et supérieur à aujourd'hui
if(isset($_GET['categorie'])){
	if($_GET['categorie'] == 'reunion'){
		$pdo_liste_filtre = $pdo->query("$requete AND categorie = 'reunion' GROUP BY p.id_produit");
	}elseif($_GET['categorie'] == 'bureau'){
		$pdo_liste_filtre = $pdo->query("$requete AND categorie = 'bureau' GROUP BY p.id_produit");
	}elseif($_GET['categorie'] == 'formation'){
		$pdo_liste_filtre = $pdo->query("$requete AND categorie = 'formation' GROUP BY p.id_produit");
	}
}elseif(isset($_GET['ville'])){
	if($_GET['ville'] == 'paris'){
		$pdo_liste_filtre = $pdo->query("$requete AND ville = 'paris' GROUP BY p.id_produit");
	}elseif($_GET['ville'] == 'lyon'){
		$pdo_liste_filtre = $pdo->query("$requete AND ville = 'lyon' GROUP BY p.id_produit");
	}elseif($_GET['ville'] == 'marseille'){
		$pdo_liste_filtre = $pdo->query("$requete AND ville = 'marseille' GROUP BY p.id_produit");
	}
}elseif(isset($_GET['capacite'])){
	if($_GET['capacite'] == '5'){
		$pdo_liste_filtre = $pdo->query("$requete AND capacite = '5' GROUP BY p.id_produit");
	}elseif($_GET['capacite'] == '10'){
		$pdo_liste_filtre = $pdo->query("$requete AND capacite = '10' GROUP BY p.id_produit");
	}elseif($_GET['capacite'] == '20'){
		$pdo_liste_filtre = $pdo->query("$requete AND capacite = '20' GROUP BY p.id_produit");
	}elseif($_GET['capacite'] == '30'){
		$pdo_liste_filtre = $pdo->query("$requete AND capacite = '30' GROUP BY p.id_produit");
	}elseif($_GET['capacite'] == '50'){
		$pdo_liste_filtre = $pdo->query("$requete AND capacite = '50' GROUP BY p.id_produit");
	}elseif($_GET['capacite'] == '100'){
		$pdo_liste_filtre = $pdo->query("$requete AND capacite = '100' GROUP BY p.id_produit");
	}
}elseif(isset($_GET['periode'])){
	if($_GET['periode'] == 'mai'){
		$pdo_liste_filtre = $pdo->query("$requete AND (p.date_arrivee BETWEEN '2020-05-01' AND '2020-05-31' OR p.date_depart BETWEEN '2020-05-01' AND '2020-05-31') GROUP BY p.id_produit");
	}elseif($_GET['periode'] == 'juin'){
		$pdo_liste_filtre = $pdo->query("$requete AND (p.date_arrivee BETWEEN '2020-06-01' AND '2020-06-30' OR p.date_depart BETWEEN '2020-06-01' AND '2020-06-30') GROUP BY p.id_produit");
	}elseif($_GET['periode'] == 'juillet'){
		$pdo_liste_filtre = $pdo->query("$requete AND (p.date_arrivee BETWEEN '2020-07-01' AND '2020-07-31' OR p.date_depart BETWEEN '2020-07-01' AND '2020-07-31') GROUP BY p.id_produit");
	}elseif($_GET['periode'] == 'aout'){
		$pdo_liste_filtre = $pdo->query("$requete AND (p.date_arrivee BETWEEN '2020-08-01' AND '2020-08-31' OR p.date_depart BETWEEN '2020-08-01' AND '2020-08-31') GROUP BY p.id_produit");
	}
}else{
	$pdo_liste_filtre = $pdo->query("$requete GROUP BY p.id_produit ORDER BY p.prix");
}


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<div class="starter-template">
    <h1> Switch </h1>
    <p class="lead"><?php echo $msg; ?></p>
</div>

<div class="row">
	<div class="col-lg-3">
		<h3 class="my-4">Catégorie</h3>
		<div class="list-group">
			<a href="?categorie=reunion" class="list-group-item">Réunion</a>
			<a href="?categorie=bureau" class="list-group-item">Bureau</a>
			<a href="?categorie=formation" class="list-group-item">Formation</a>
		</div>
		<h3 class="my-4">Ville</h3>
		<div class="list-group">
			<a href="?ville=paris" class="list-group-item">Paris</a>
			<a href="?ville=lyon" class="list-group-item">Lyon</a>
			<a href="?ville=marseille" class="list-group-item">Marseille</a>
		</div>
		<h3 class="my-4">Capacité</h3>
		<div class="list-group">
			<a href="?capacite=5" class="list-group-item">Entre 1 et 5</a>
			<a href="?capacite=10" class="list-group-item">Entre 5 et 10</a>
			<a href="?capacite=20" class="list-group-item">Entre 10 et 20</a>
			<a href="?capacite=30" class="list-group-item">Entre 20 et 30</a>
			<a href="?capacite=50" class="list-group-item">Entre 30 et 50</a>
			<a href="?capacite=100" class="list-group-item">Entre 50 et 100</a>
		</div>
		<h3 class="my-4">Période</h3>
		<div class="list-group">
			<a href="?periode=mai" class="list-group-item">Mai</a>
			<a href="?periode=juin" class="list-group-item">Juin</a>
			<a href="?periode=juillet" class="list-group-item">Juillet</a>
			<a href="?periode=aout" class="list-group-item">Août</a>
		</div>
		<div class="form-group mt-4">
			<a href="<?php echo URL.'index.php'; ?>" class="form-control btn btn-outline-success"> Réinitialiser </a>
        </div>	
	</div>

	<div class="col-lg-9 index">
		<div class="row">
		<?php
			while($liste_filtre = $pdo_liste_filtre->fetch(PDO::FETCH_ASSOC)){
				echo '<div class="col-lg-4 col-md-6 mb-4">
					<div class="card h-100">
						<a href="fiche_salle.php?salle='.$liste_filtre['id_salle'].'&produit='.$liste_filtre['id_produit'].'"><img class="card-img-top" src="'.URL.'/img/'.$liste_filtre['photo'].'" alt="'.$liste_filtre['titre'].'"></a>
						<div class="card-body">
							<h5 class="card-title"><a href="fiche_salle.php?salle='.$liste_filtre['id_salle'].'&produit='.$liste_filtre['id_produit'].'">'.$liste_filtre['titre'].' - '.ucfirst($liste_filtre['ville']).'</a></h5>
							<p class="card-text">'.htmlentities(substr($liste_filtre['description'], 0, 50), ENT_QUOTES).'...</p>
							<h5>'.$liste_filtre['prix'].'€</h5>
							<p>Du <b>'.strftime("%d-%m-%Y", strtotime($liste_filtre['date_arrivee'])).'</b><br>Au <b>'.strftime("%d-%m-%Y", strtotime($liste_filtre['date_depart'])).'</b></p>
						</div>
						<div class="card-footer">
							<p>';
							if(!empty($liste_filtre['moyenne_note'])){
								echo $liste_filtre['moyenne_note'].'/5';
							}else{
								echo 'Absence de note';
							}
							echo '</p>
						</div>
					</div>
				</div>';
			}
		?>
		</div>
	</div>
</div>


<?php 
include 'inc/footer.inc.php';

if(isset($_GET['reservation']) && $_GET['reservation'] == 'ok'){
	echo "<script>alert(\"Votre réservation a été prise en compte\")</script>";
}
/* 
if(isset($_GET['contact']) && $_GET['contact'] == 'ok'){
	echo "<script>alert(\"Votre message a été envoyé\")</script>";
} */