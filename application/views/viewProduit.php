		<?php include('header.php'); ?>
		<div class="contenu">
			<section>
				<aside id="asideProduit">
					<?php include("viewSearchForm.php"); ?>
				</aside>
				<article id="articleProduit">
					<h1><?php echo $produit->libelleProduit; ?></h1>
					<?php
					if($produit->qteProduit > 0 && isset($membre)) { /* si le produit est en stock et que l'utilisateur est connecté */ ?>
						<form method="post" action="<?php echo site_url(); ?>monpanier/addProduitToLigne"> <!-- l'utilisateur peut alors choisir une quantité -->
							<input type="hidden" name="pseudoMembre" value="<?php echo $membre->pseudoMembre;?>">
							<input type="hidden" name="idProduit" value="<?php echo $produit->idProduit;?>">
							<label for="quantite">Quantité voulue :</label>
							<select name="quantite" id="quantite"> <?php
								$i = 1;
								while ($i <= $produit->qteProduit)
								{
									echo '<option value="' . $i . '">' . $i . '</option>';
									$i++;
								} ?>
							</select>
							<input type="submit" value="Ajouter au panier" name="submitPanier"/>
						</form> <?php
					} ?>
					<p>Prix : <?php echo $produit->prixProduit; ?> €</p>
					<p>Quantité en stock : <?php echo $produit->qteProduit; ?></p>
					<p><span class="desc">Description : <br><?php echo $produit->descriptifProduit; ?></span></p>
					<p>Aperçu : <br><img id="imgProduit" src="<?php echo img_url($produit->nomImage) ?>" width="300" border="0" alt="Image du gant"></p>
				</article>
			</section>
		</div>
		<?php include("footer.html"); ?>

		<script type="text/javascript">
			var availableTagsProduits = new Array(); /* autocomplete sur le formulaire de recherche */ <?php
			if(isset($allProduits) && $allProduits != null)
			{ /* source : la liste des produits */
				$listeProduits = array();
				foreach($allProduits as $p):
					$listeProduits[] = $p->libelleProduit;
					$listeProduits[] = $p->descriptifProduit;
				endforeach; 
				$listeProduits = json_encode($listeProduits); ?>
				availableTagsProduits= <?=$listeProduits?>; <?php
			} ?>

			$(function() {
				$('#recherche').autocomplete({
					source: availableTagsProduits
				});
			});
		</script>
	</body>
</html>
