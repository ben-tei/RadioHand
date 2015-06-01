		<?php include('header.php'); ?>
		<div class="contenu">
			<section>
				<aside>
					<?php include("viewSearchForm.php"); ?>
				</aside>
				<article> <?php
					foreach($allProduits as $produit): /* parcourt chacun des produits et affiche ses infos */
						$path = 'catalogue/getProduit?idProduit=' . $produit->idProduit;
						$img = '<img src="' . img_url($produit->nomImage) . '" alt="Image du gant" border="0" width="150" >';
						echo '<p class="margeEntreLesGants">' . anchor($path, $img);
						echo '<span class="libAndPrix">';
						/* affiche l'image du produit. L'image est cliquable et redirige vers le produit en question */
						echo anchor('catalogue/getProduit?idProduit=' . $produit->idProduit, $produit->libelleProduit, array('class' => 'liencatalogue'));
						/* affiche le libellé du produit. Le libellé est cliquable et redirige vers le produit en question */
						echo ' ' . $produit->prixProduit . " €</span></p>";
					endforeach; ?>
					<span id="pagination"><?php echo $pages; ?></span> <!-- pagination -->
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
