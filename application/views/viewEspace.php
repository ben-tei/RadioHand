		<?php include('header.php'); ?>
		<div class="contenu forms">
			<section>
				<div id="form_inscription">
					<form method="post" action="<?php echo site_url(); ?>monespace/updateEspace" id="formEspace" autocomplete="off">
						<input type="hidden" name="pseudoMembre" value="<?php echo $membre->pseudoMembre;?>">
						<fieldset>
							<legend>Contact</legend>
							<label for="email">Votre adresse mail* :</label><input type="email" name="email" id="email" required value="<?php if(isset($membre)) { echo htmlentities($membre->emailMembre);}?>"><br>
						</fieldset>
						<fieldset>
							<legend>Informations supplémentaires</legend>
							<label for="nom">Nom* :</label><input type="text" name="nom" id="nom" required class="inputEspace" value="<?php if(isset($membre)) { echo htmlentities($membre->nomMembre); } ?>"><br>
							<label for="prenom">Prénom* :</label><input type="text" name="prenom" id="prenom" required class="inputEspace" value="<?php if(isset($membre)) { echo htmlentities($membre->prenomMembre); } ?>"><br>
							<label for="adresse">Adresse* :</label><input type="text" name="adresse" id="adresse" required class="inputEspace" value="<?php if(isset($membre)) { echo htmlentities($membre->adresseMembre); } ?>"><br>
							<label for="codep">Code Postal* :</label><input type="text" name="codep" id="codep" maxlength="5" required class="inputEspace" value="<?php if(isset($membre)) { echo htmlentities($membre->codepMembre); } ?>"><br>
							<label for="ville">Ville* :</label><input type="text" name="ville" id="ville" required value="<?php if(isset($membre)) { echo htmlentities($membre->villeMembre); } ?>"><br>
						</fieldset>
						<p>Les champs suivis d'un * sont obligatoires !</p>
						<input type="submit" name="submitUpdate" value="Modifier">
					</form>
				</div>
			</section>
		</div>
		<?php include("footer.html"); ?>

		<script type="text/javascript">
			var regEmail = new RegExp('^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$','i');
			$(document).ready(function () {
				$('form#formEspace').on('submit', function(e) { /* traitement du formulaire en AJAX */
					if(isNaN($('#codep').val()) || $('#codep').val().length != 5)
					{
						alert("Le code postal est incorrect !");
						return false;
					}
					else if(!regEmail.test($('#email').val()))
					{
						alert("Adresse email invalide !");
						return false;
					}
					else if(/\d/.test($('#nom').val()))
					{
						alert("Votre nom contient des chiffres !");
						return false;
					}
					else if(/\d/.test($('#prenom').val()))
					{
						alert("Votre prénom contient des chiffres !");
						return false;
					}
				});
			});
		</script>
	</body>
</html>
