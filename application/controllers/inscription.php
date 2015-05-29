<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inscription extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{		
		if(isset($_COOKIE['souvenir']))	/* check la validité du cookie */
		{
			list($user, $time) = explode(':', $_COOKIE['souvenir']);

			$toutEstLa = isset($user) && !empty($user) && isset($time) && ctype_digit($time) && intval($time) >= 0;

			if($toutEstLa)
			{
				$membre = $this->membre->getMembreByCookie($user);

				if($membre)	/* si le membre est authentifié */
				{
					if($time != '0')
					{
						$time = strval(time()+3600);
					}
					setcookie('souvenir', $user . ':' . $time, intval($time), '/', null, false, true);
					
					redirect('', 'refresh');	/* on redirige vers l'accueil car il est connecté */
				}
			}
			if(!$toutEstLa || !$membre)	/* si les infos sont pas bonnes, on détruit le cookie et redirige vers l'accueil */
			{
				delete_cookie('souvenir');
				redirect('', 'refresh');
			}
		}
		else
		{
			$this->load->view('viewInscription.php');
		}
	}

	public function createMembre()
	{
		if(isset($_POST['submitInscription']) && isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['mdp2']) && isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['adresse']) && isset($_POST['codep']) && isset($_POST['ville']))
		{
			$allFilled = true;
			
			foreach($_POST as $p)	/* validation de formulaire */
			{
				$this->form_validation->set_rules($p, ucfirst($p), 'trim|encode_php_tags|xss_clean');
				if(empty($p))
				{
					$allFilled = false;
				}
			}

			if($this->form_validation->run())	// si le formulaire est valide
			{
				$data = array();
				
				if($allFilled)
				{
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))	// on vérifie le format de l'adresse email
					{
						if(strlen($_POST['pseudo']) > 2)	// on vérifie la taille du pseudo
						{
							if(strlen($_POST['mdp']) > 4 && strlen($_POST['mdp2']) > 4)	// on vérifie la taille des mots de passe
							{
								if($_POST['mdp'] == $_POST['mdp2'])	// on vérifie l'égalité des mots de passe
								{
									if(strlen($_POST['codep']) == 5 && ctype_digit($_POST['codep']))	// on vérifie le format du code postal
									{
										if(preg_match('#^[a-z0-9]+$#i', $_POST['pseudo']) && preg_match('#^[a-z0-9]+$#i', $_POST['mdp']) && preg_match('#^[a-z0-9]+$#i', $_POST['mdp2']))	// on vérifie si les champs contiennent des caractères spéciaux
										{
											$cle = sha1(microtime(TRUE)*100000);	// Génération aléatoire d'une clé
											$alreadyCreated = $this->membre->addMembre($_POST['pseudo'], sha1($_POST['mdp']), $_POST['email'], $_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['codep'], $_POST['ville'], $cle);

											if(!$alreadyCreated)
											{
												header('Content-Type: application/x-json; charset=utf-8');
												echo(json_encode("Pseudo déjà utilisé !"));
											}
											else
											{
												$sujet = "Activer votre compte" ;

												$message = 'Bienvenue sur RadioHand,

												Pour activer votre compte, veuillez cliquer sur le lien ci dessous.

												http://php-radiohand.rhcloud.com/?pseudo=' . urlencode($_POST['pseudo']) . '&cle=' . urlencode($cle) . '';

												$this->mail->send('radiohand@yopmail.com', $_POST['email'], $sujet, $message);	/* envoi du mail */

												setcookie('success', 'jenesaispasquoimettre', 0, '/', null, false, true);
												
												redirect('inscription/success', 'refresh');
											}
										}
										else
										{
											$data['message'] = "Certains champs contiennent des caractères spéciaux !";
										}
									}
									else
									{
										$data['message'] = "Le code postal est invalide !";
									}
								}
								else
								{
									$data['message'] = "Les mots de passe sont différents !";
								}
							}
							else
							{
								$data['message'] = "Les mots de passes sont trop courts !";
							}
						}
						else
						{
							$data['message'] = "Le pseudo est trop court !";
						}
					}
					else
					{
						$data['message'] = "L'adresse email est invalide !";
					}
				}
				else
				{
					$data['message'] = "Tous les champs sont obligatoires !";
				}
				$this->load->view('viewInscription.php', $data);
			}
			else	// si le formulaire n'est pas valide, on actualise la page
			{
				redirect('inscription', 'refresh');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

	public function success()
	{
		if(isset($_COOKIE['success']))	/* si l'utilisateur vient de s'inscrire */
		{
			delete_cookie('success');
			$this->load->view('viewConfirmationInscription.php');
		}
		else
		{
			redirect('', 'refresh');
		}
	}

}
