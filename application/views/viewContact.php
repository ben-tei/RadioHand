		<?php include('header.php'); ?>
		<div class="contenu forms">
			<section>
				<div id="Formulaire">
					<form method="post" action="<?php echo site_url(); ?>contact/sendmail" id="formContact" autocomplete="off">
						<p>
							<label for="identifiant">Votre mail :</label>  <input type="email" name="email" id="email" placeholder="johndu34@yopmail.com" required class="inputContact" value="<?php if(isset($membre)) { echo htmlentities($membre->emailMembre); } else if(isset($_POST['email'])) { echo htmlentities($_POST['email']); } ?>">
							<?php if(isset($message)) echo '<span style="font-weight: bold;">' . $message . '</span>'; ?><br>
							<label for="identifiant">Sujet :</label>  <input type="text" name="sujet" id="sujet" placeholder="Question SAV" required class="inputContact" value="<?php if(isset($_POST['sujet'])) { echo htmlentities($_POST['sujet']); } ?>"><br>
							<label for="identifiant">Message :</label>  <textarea name="message" id="message" placeholder="Bonjour, ..." rows="10" cols="50" ><?php if(isset($_POST['message'])) { echo htmlentities($_POST['message']); } ?></textarea><br><br>
							<input type="submit" name="submitContact" value="Envoyer">
							<input type="reset" name="reset" value="Réinitialiser le formulaire">
						</p>
					</form>
				</div>
			</section>
		</div>
		<?php include("footer.html"); ?>
		
		<script type="text/javascript">
			var regEmail = new RegExp('^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$','i');
			$(document).ready(function () {
				$('form#formContact').on('submit', function(e) { /* traitement du formulaire d'inscription en AJAX */
					if(!regEmail.test($('#email').val()))
					{
						alert("Adresse email invalide !");
						return false;
					}
				});
			});
		</script>
	</body>
</html>
