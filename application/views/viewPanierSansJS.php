		<?php include('header.php'); ?>
		<div class="contenu">
			<section> <?php
				if(count($panier) > 0) /* s'il existe des lignes panier */
				{ ?>
					<aside id="asideBton">
						<form method="post" action="<?php echo site_url(); ?>mescommandes/createCommande" id="confirmCommande">
							<input type="hidden" name="pseudoMembre" value="<?php echo $membre->pseudoMembre; ?>">
							<input type="submit" name="submitCommande" value="Passer la commande" id="btonCommande">
						</form>
					</aside> <?php
				} ?>
				<article id="articlePanierNoJS">
					<h1>Votre Panier</h1> <?php
					$i = 0;
					while($i < count($panier)) /* parcourt chacune des lignes du panier et affiche ses infos */
					{
						echo '<div id="divLigne' . $i . '">',
						'<p class = "supprPanier" >',
						'<a href="' . site_url() . 'catalogue/getProduit?idProduit=' . $produits[$i]->idProduit . '"><img class = "imagePanier" src="'. img_url($produits[$i]->nomImage) . '" border="0" width="75" alt="image"></a>',
						/* affiche l'image du produit. L'image est cliquable et redirige vers le produit en question */
						'<a class="liencatalogue margeEntreLesInfosPanier" href="'. site_url() .'catalogue/getProduit?idProduit=' . $produits[$i]->idProduit . '">' . $produits[$i]->libelleProduit . '</a>',
						/* affiche le libellé du produit. Le libellé est cliquable et redirige vers le produit en question */
						' Quantité : ';
						/* l'utilisateur a la possibilité de diminuer ou augmenter la quantité */ ?>
						<form method="post" action="<?php echo site_url(); ?>monpanier/updateLigne" autocomplete="off" class="ligneNoJS">
							<input type="number" value="<?php echo $panier[$i]->qteProduit; ?>" min="1" max="<?php echo $qteMax[$i]; ?>" name="qtePanier" required>
							<input type="hidden" name="pseudoMembre" value="<?php echo $membre->pseudoMembre;?>">
							<input type="hidden" name="idProduit" value="<?php echo $produits[$i]->idProduit;?>">
							<input type="submit" name="submitPanierNoJS" style="display:none;">
						</form> <?php
						
						echo '<div id="divTotalLigne' . $i . '" style="display:inline-block; margin-left:5px;">',
						'Sous-total : ' . $totauxLignes[$i]->totalLigne . ' €</div>';

						echo '<form method="post" action="' .  site_url()  . 'monpanier/deleteLigne/" class="supprPanier">
						<input type="hidden" name="i" value="' . $i . '">
						<input type="hidden" name="pseudoMembre" value="' . $membre->pseudoMembre . '">
						<input type="hidden" name="idProduit" value="' . $produits[$i]->idProduit . '">
						<input type="submit" name="submitSuppression" value=" " class="submitSuppression">
						</form></div>'; /* formulaire de suppression de la ligne */

						$i++;
					}
					echo '<div id="divTotalPanier">' . '<p class = "total" ><br>Total : ' . $total . ' €</p></div>'; /* affiche le total de la commande */ ?>
				</article>
			</section>
		</div>
		<?php include("footer.html"); ?>
		
	</body>
</html>
