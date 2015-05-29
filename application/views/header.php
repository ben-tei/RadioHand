<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="<?php echo css_url('style'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo css_url('jquery-ui'); ?>" >
		<link rel="icon" href="<?php echo img_url('favicon.png'); ?>">
		<title>RadioHand - La musique à portée de main</title>
	</head>
	<body>
		<noscript>
			<span style="background-color: red; display: block; text-align: center; font-weight: bold; font-size: 16px; padding: 1px;">Votre navigateur ne supporte pas JavaScript !</span>
		</noscript>
		<header>
			<div class="contenu">
				<?php $path = '';
				$img = '<img src="' . img_url('polytech.png') . '" alt="Logo du site" id="logo" >';
				echo anchor($path, $img); /* affiche le logo du site. Le logo est cliquable et redirige vers l'accueil */

				if(!isset($membre)) /* si l'utilisateur n'est pas connecté, on affiche le formulaire de connexion */
				{ ?>
					<form method="post" action="<?php echo site_url(); ?>monespace/connexion" id="formId" autocomplete="off">
						<div id="Identification">
							<p>
								<input type="text" name="identifiant" id="identifiant" placeholder="Identifiant">
								<input type="password" name="password" id="password" placeholder="Mot de passe">
								<input type="submit" value="Connexion" name="submitHeader" id="submitHead">
								<br><input type="hidden" name="rememberMe" value="non"><input type="checkbox" id="check" name="rememberMe" value="oui">
								<span id="remMe">
									Se souvenir de moi
								</span>
							</p>
						</div>
					</form>
					<?php
				}
				else /* si l'utilisateur est connecté */
				{ ?>
					<div id="Identifie">
						<ul id="menu">
							<li>
								<span id="cursor">
									<?php echo "Bienvenue, ".$membre->pseudoMembre."<img src=\"" . img_url('triangle.png') . "\" alt=\"Triangle\" id=\"triangle\" width=\"15\" style=\"margin-left:3px;\" />"; ?>
								</span>
								<ul class="sousMenu">
									<li>
										<a class="noborderbottom" href="<?php echo site_url(); ?>monespace">Mes Paramètres</a>
									</li>
									<li>
										<a class="noborderbottom" href="<?php echo site_url(); ?>mescommandes">Mes Commandes</a>
									</li>
									<li>
										<a href="<?php echo site_url(); ?>monespace/deconnexion">Déconnexion</a>
									</li>
								</ul>
							</li>
						</ul>
					</div> <?php
				} ?>
				<nav>
					<ul>
						<li>
							<?php echo anchor('welcome', 'Accueil', array('class' => 'itemMenu')); ?>
						</li> <?php
						if(!isset($membre)) /* si l'utilisateur est connecté, on cache l'onglet Inscription */
						{ ?>
							<li>
								<?php echo anchor('inscription', 'Inscription', array('class' => 'itemMenu')); ?>
							</li> <?php
						} ?>
						<li>
							<?php echo anchor('catalogue', 'Catalogue', array('class' => 'itemMenu')); ?>
						</li>
						<li>
							<?php echo anchor('contact', 'Contact', array('class' => 'itemMenu')); ?>
						</li>
					</ul> <?php
					if(!isset($membre))
					{
						$id = "quatreOnglets";
					}
					else
					{
						$id = "troisOnglets";
					} ?>
					<div id="<?php echo $id; ?>"> <?php
						$path = 'monpanier';
						$img = '<img src="' . img_url('panier.png') . '" alt="Panier du site" id="imagePanier" >';
						echo anchor($path, $img) . anchor('monpanier', 'Panier', array('class' => 'basket'));
						/* affiche l'image du panier. L'image est cliquable et redirige vers le panier */ ?>
					</div>
				</nav>
			</div>
		</header>
