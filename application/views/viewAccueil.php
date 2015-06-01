		<?php include('header.php'); ?>
		<div class="contenu">
			<section>
				<aside>
					<h1>À propos de nous</h1>
					<p>RHCorp (RadioHand Corporation), entreprise française, implantée depuis 10 ans dans plusieurs pays.</p>
					<h3>Notre philosophie ?</h3>
					<p>Vouer nos aptitudes humaines et nos atouts technologiques à la conception de produits et de services de qualité pour favoriser un monde meilleur.</p>
				</aside>
				<article>
					<?php
					if(isset($message))
					{
						echo '<span style="font-weight: bold;">' . $message . '</span><br>';
					} ?>
					<h1>Bienvenue sur RadioHand !</h1>
					<p>Entreprise révolutionnaire et à la pointe de la technologie, RadioHand Corporation propose à ses clients des technologies innovantes, ainsi que des applications pour Smartphone.</p>
				</article>
			</section>
		</div>
		<?php include("footer.html"); ?>
	</body>
</html>
