		<?php include('header.php'); ?>
		<noscript>
			<meta http-equiv="refresh" content="0;url=monpanier/noJS">
		</noscript>
		<div class="contenu">
			<section>
				<article>
					<h1>Votre Panier</h1> <?php
					$i = 0;
					while($i < count($panier)) /* parcourt chacune des lignes du panier et affiche ses infos */
					{
						echo '<div id="div' . $i . '">',
						'<p class = "supprPanier" >',
						'<a href="' . site_url() . 'catalogue/getProduit?idProduit=' . $produits[$i]->idProduit . '"><img class = "imagePanier" src="'. img_url($produits[$i]->nomImage) . '" border="0" width="75" alt="Image du gant"></a>',
						/* affiche l'image du produit. L'image est cliquable et redirige vers le produit en question */
						'<a class="liencatalogue margeEntreLesInfosPanier" href="'. site_url() .'catalogue/getProduit?idProduit=' . $produits[$i]->idProduit . '">' . $produits[$i]->libelleProduit . '</a>',
						/* affiche le libellé du produit. Le libellé est cliquable et redirige vers le produit en question */
						' Quantité : ';
						/* l'utilisateur a la possibilité de diminuer ou augmenter la quantité */ ?>
						<select name="qtePanier" class="margeEntreLesInfosPanier" id="<?php echo $produits[$i]->idProduit . '+' . $produits[$i]->prixProduit . '+' . $i . '+' . $membre->pseudoMembre; ?>">
							<?php $j = 1; /* l'id de chaque select va contenir l'id du produit + le prix du produit + le numéro de la ligne ($i) + le pseudo du membre connecté ; On a ainsi accès aux données voulues beaucoup plus facilement */
							while ($j <= $qteMax[$i])
							{
								if($j == $panier[$i]->qteProduit)
								{
									echo '<option value="' . $j . '" selected>'. $j .'</option>';
								}
								else
								{
									echo '<option value="' . $j . '">'. $j .'</option>';
								}
								$j++;
							} ?>
						</select> <?php
						
						echo '<div id="prix' . $i . '" style="display:inline-block; margin-left:5px;">',
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
				</article> <?php
				if($i != 0) /* s'il existe des lignes panier */
				{ ?>
					<aside id="asideBton">
						<form method="post" action="<?php echo site_url(); ?>mescommandes/createCommande" id="confirmCommande">
							<input type="hidden" name="pseudoMembre" value="<?php echo $membre->pseudoMembre; ?>">
							<input type="submit" name="submitCommande" value="Passer la commande" id="btonCommande">
						</form>
					</aside> <?php
				} ?>
			</section>
		</div>
		<?php include("footer.html"); ?>

		<script>			
			var totalChaqueLigne = new Array(); /* on stocke le total de chaque ligne dans un tableau javascript pour changer dynamiquement le panier */
			<?php if(isset($produits) && $produits != null) {
				$tab = array();
				$i = 0;
				while($i < count($produits))
				{
					$tab[] = $totauxLignes[$i]->totalLigne;
					$i++;
				}
				$tab = json_encode($tab); ?>
				totalChaqueLigne= <?=$tab?>; <?php
			} ?>

			$(document).ready(function () {
				$('form.supprPanier').on('submit', function(e) { /* traitement du formulaire de suppression d'une ligne en AJAX */
					if(!confirm("Êtes-vous sûr ?"))
					{
						return false;
					}

					e.preventDefault();
					var obj = $(this),
					url = obj.attr('action'),
					method = obj.attr('method');

					obj.find('[name]').each(function(index, value) { /* recupere les informations */
						var obj = $(this),
						name = obj.attr('name'),
						value = obj.val();

						data.push({'name': name, 'value': value});
					});

					totalChaqueLigne.splice(data[0].value, 1, 0); /* on remplace le total de la ligne en question par 0 */

					data.push({'name': 'totChaqueLigne', 'value': totalChaqueLigne});

					$.ajax({
						url: url,
						type: method,
						data: data,
						success: function(response) {
							$('#div' + data[0].value).empty(); /* on vide la div de la ligne en question */
							$('#divTotalPanier').empty(); /* on vide la div du total du panier pour le remplacer par le nouveau */
							document.getElementById('divTotalPanier').innerHTML += '<p class = "total" ><br>Total : ' + response + ' €</p>';
							if(response == 0) /* s'il n'existe plus de lignes panier, on cache le boutton de confirmation de la commande */
							{
								document.getElementById("asideBton").style.display = "none";
							}
							data = new Array();
						}
					});
					return false;
				});

				$('article').on('change', 'select[name="qtePanier"]', function() { /* dès changement de la quantité d'une ligne */

					var url = "<?php echo site_url(); ?>monpanier/updateLigne";

					totalChaqueLigne.splice(this.id.split("+")[2], 1, parseInt(this.value) * parseInt(this.id.split("+")[1])); /* on remplace le total de la ligne en question par la nouvelle quantité * le prix du produit */

					data.push({'name': 'qtePanier', 'value': this.value});
					data.push({'name': 'idProduit', 'value': this.id.split("+")[0]});
					data.push({'name': 'prixProduit', 'value': this.id.split("+")[1]});
					data.push({'name': 'divPrix', 'value': this.id.split("+")[2]});
					data.push({'name': 'pseudoMembre', 'value': this.id.split("+")[3]});
					data.push({'name': 'totChaqueLigne', 'value': totalChaqueLigne});

					$.ajax({
						url: url,
						type: "POST",
						data: data,
						success: function(response) {
							if(response == "Plus assez en stock")
							{
								window.location.href = baseUrl + 'monpanier'; /* si plus de stock pendant la transaction, on rafraichit la page */
							}
							else
							{
								$('#prix' + data[3].value).empty(); /* on vide la div du total de la ligne modifiée pour y mettre le nouveau */
								document.getElementById('prix' + data[3].value).innerHTML += 'Sous-total : ' + response[0] + ' €';
								$('#divTotalPanier').empty(); /* on vide la div du total du panier pour y mettre le nouveau */
								document.getElementById('divTotalPanier').innerHTML += '<p class = "total" ><br>Total : ' + response[1] + ' €</p>';
							}
							data = new Array();
						}
					});
					return false;
				});
			});
		</script>
	</body>
</html>
