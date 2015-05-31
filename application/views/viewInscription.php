		<?php include('header.php'); ?>
		<div class="contenu forms">
			<section>
				<div id="form_inscription">
					<form method="post" action="<?php echo site_url(); ?>inscription/createMembre" id="formInsc" autocomplete="off">
						<fieldset>
							<legend>Identifiants</legend>
							<label for="pseudo">Pseudo* :</label>  <input name="pseudo" type="text" maxlength="50" id="pseudo" placeholder="Johndu34" required class="inputInscription" value="<?php if(isset($_POST['pseudo'])) { echo htmlentities($_POST['pseudo']); } ?>">
							<?php if(isset($message)) echo '<span style="font-weight: bold;">' . $message . '</span>'; ?><br>
							<label for="mdp">Mot de passe* :</label><input type="password" maxlength="50" name="mdp" id="mdp" placeholder="aZERTY" required class="inputInscription"><br>
							<label for="mdp">Retapez le mot de passe* :</label><input type="password" maxlength="50" name="mdp2" id="mdp2" placeholder="aZERTY" required><br>
						</fieldset>
						<fieldset>
							<legend>Contact</legend>
							<label for="email">Votre adresse mail* :</label><input type="email" maxlength="50" name="email" id="email" placeholder="johndu34@yopmail.com" required value="<?php if(isset($_POST['email'])) { echo htmlentities($_POST['email']); } ?>"><br>
						</fieldset>
						<fieldset>
							<legend>Informations supplémentaires</legend>
							<label for="nom">Nom* :</label><input type="text" maxlength="50" name="nom" id="nom" placeholder="Dupond" required class="inputInscription" value="<?php if(isset($_POST['nom'])) { echo htmlentities($_POST['nom']); } ?>"><br>
							<label for="prenom">Prénom* :</label><input type="text" maxlength="50" name="prenom" id="prenom" placeholder="John" required class="inputInscription" value="<?php if(isset($_POST['prenom'])) { echo htmlentities($_POST['prenom']); } ?>"><br>
							<label for="adresse">Adresse* :</label><input type="text" maxlength="50" name="adresse" id="adresse" placeholder="45 Rue des Mimosas" required class="inputInscription" value="<?php if(isset($_POST['adresse'])) { echo htmlentities($_POST['adresse']); } ?>"><br>
							<label for="codep">Code Postal* :</label><input type="text" maxlength="5" name="codep" id="codep" placeholder="34000" required class="inputInscription" value="<?php if(isset($_POST['codep'])) { echo htmlentities($_POST['codep']); } ?>"><br>
							<label for="ville">Ville* :</label><input type="text" maxlength="50" name="ville" id="ville" placeholder="Montpellier" required value="<?php if(isset($_POST['ville'])) { echo htmlentities($_POST['ville']); } ?>"><br>
						</fieldset>
						<p>Les champs suivis d'un * sont obligatoires !</p>
						<input type="submit" name="submitInscription" value="S'inscrire">
					</form>
				</div>
			</section>
		</div>
		<?php include("footer.html"); ?>

		<script type="text/javascript">
			var regEmail = new RegExp('^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$','i');
			$(document).ready(function () {
				$('form#formInsc').on('submit', function(e) {
					var isValid = true;
					$(':input').each(function() {
						if ($(this).val() === '')
						{
							isValid = false;
						}
					});
					if(!isValid)
					{
						alert("Tous les champs sont obligatoires !");
						return false;
					}
					else if(/^[a-zA-Z0-9]*$/.test($('#pseudo').val()) == false)
					{
						alert("Le pseudo contient des caractères spéciaux !");
						return false;
					}
					else if($('#pseudo').val().length < 3)
					{
						alert("Le pseudo doit faire au minimum 3 caractères !");
						return false;
					}
					else if($('#mdp').val().length < 5)
					{
						alert("Le premier mot de passe doit faire au minimum 5 caractères !");
						return false;
					}
					else if($('#mdp2').val().length < 5)
					{
						alert("Le second mot de passe doit faire au minimum 5 caractères !");
						return false;
					}
					else if(/^[a-zA-Z0-9]*$/.test($('#mdp').val()) == false)
					{
						alert("Le premier mot de passe contient des caractères spéciaux !");
						return false;
					}
					else if(/^[a-zA-Z0-9]*$/.test($('#mdp2').val()) == false)
					{
						alert("Le second mot de passe contient des caractères spéciaux !");
						return false;
					}
					else if($('#mdp').val() != $('#mdp2').val())
					{
						alert("Les mots de passe sont différents !");
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
					else if(isNaN($('#codep').val()) || $('#codep').val().length != 5)
					{
						alert("Le code postal est incorrect !");
						return false;
					}

					return isValid;
				});
			});
		</script>
	</body>
</html>
