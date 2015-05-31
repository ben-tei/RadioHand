		<?php include('header.php'); ?>
		<div class="contenu">
			<section>
				<article id="articleCommandes">
					<h1>Vos Commandes</h1> <?php
					$j = 0;
					foreach($allCommandes as $commande):  /* parcourt chacune des commandes et affiche ses infos dans un tableau */ ?>
						<table class="tableCommandes">
							<tr>
								<th>
									<span class="thTable">Date : <?php echo date('d-m-Y', strtotime($commande->dateCommande));
									echo '</span><span class="thTable">Total : ', $montant[$j];
									echo '€</span>Numéro de Commande : ', $commande->idCommande; ?>
								</th>
							</tr> <?php
							$i = 0;
							while($i < count($ligne[$j])) /* parcourt chacune des lignes de la commande en question */
							{ ?>
								<tr class="nostyle">
									<td>
										<span class="tdTable"> <?php
										$path = 'catalogue/getProduit?idProduit=' . $ligne[$j][$i]->idProduit;
										$img = '<img src="' . img_url($ligne[$j][$i]->nomImage) . '" alt="Image du gant" border="0" width="150" >';
										echo anchor($path, $img);
										/* affiche l'image du produit. L'image est cliquable et redirige vers le produit en question */
										echo anchor('catalogue/getProduit?idProduit=' . $ligne[$j][$i]->idProduit, $ligne[$j][$i]->libelleProduit, array('class' => 'liencatalogue'));
										/* affiche le libellé du produit. Le libellé est cliquable et redirige vers le produit en question */
										echo '</span><span class="tdTable">Prix : ', $ligne[$j][$i]->prixProduit,
										' €</span>Quantité : ', $ligne[$j][$i]->qteProduit; ?>
									</td>
								</tr> <?php
								$i++;
							} ?>
						</table><br> <?php
						$j++;
					endforeach; ?>
				</article>
			</section>
		</div>
		<?php include("footer.html"); ?>
	</body>
</html>
