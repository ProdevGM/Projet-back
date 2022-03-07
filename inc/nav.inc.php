<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
		<a class="navbar-brand" href="<?php echo URL; ?>index.php"><h2>Switch</h2></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav mr-auto">
<!-- 				<li class="nav-item">
					<a class="nav-link" href="">Qui sommes-nous</a>
				</li> -->
				<li class="nav-item">
					<a class="nav-link" href="<?php echo URL.'contact.php?'; ?>p">Contact</a>
				</li>
				<?php if(user_is_admin()) { ?>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo URL.'admin/administration.php?gestion=statistiques'; ?>">Administration</a>
				</li>
				<?php } ?>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMembre" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Espace membre
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
						<?php if(!user_is_connect()) { ?>

<!-- 						<button type="button" data-toggle="modal" data-target="#modalInscription" data-whatever="@getbootstrap" class="btn">Inscription</button>
						<button type="button" data-toggle="modal" data-target="#modalConnexion" data-whatever="@getbootstrap" class="btn">Connexion</button> -->

						<a class="dropdown-item" href="<?php echo URL; ?>inscription.php">Inscription</a>
						<a class="dropdown-item" href="<?php echo URL; ?>connexion.php">Connexion</a>

						<?php } else { ?>
						<a class="dropdown-item" href="<?php echo URL; ?>profil.php">Profil</a>
						<a class="dropdown-item" href="<?php echo URL; ?>connexion.php?action=deconnexion">Déconnexion</a>
						<?php } ?>
					</div>
				</li>
			</ul>
		</div>
    </div>
</nav>

<main role="main" class="container">