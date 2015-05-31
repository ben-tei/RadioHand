		<?php include('header.php'); ?>
		<div class="contenu">
			<section>
				<aside id="asideAccueil">
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
					<p>Entreprise révolutionnaire et à la pointe de la technologie, RadioHand Corporation propose à ses clients des technologies innovantes, ainsi que des applications pour Smartphone.<br><br></p>
					<p>Dans ces gants d'un nouveau genre, des détecteurs sensibles permettent de changer de musique ou bien de recevoir un appel téléphonique d'une simple impulsion des doigts sur une pastille, sans enlever le gant. Plus besoin de chercher votre lecteur dans votre poche. Comment est-ce que cela fonctionne ?<br><br></p>
					<p>C'est simplissime : il faut coller le pouce et l'index pour lancer la connexion avec le téléphone. Ces deux doigts permettent alors de recevoir un appel. Avec les autres, vous pouvez faire varier le volume sonore, ou changer les pistes musicales de votre appareil.<br><br></p>
					<p>Ingénieux, il s'agit donc d'un simple gant avec kit mains libres intégré qui va vous permettre d'écouter de la musique, de téléphoner ou encore de répondre aux appels par de simples contacts au bout des doigts.</p>
					<br>
				</article>
			</section>
		</div>
		<?php include("footer.html"); ?>
	</body>
</html>
