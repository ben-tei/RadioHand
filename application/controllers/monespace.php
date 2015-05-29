<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MonEspace extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{		
		if(isset($_COOKIE['souvenir'])) /* check la validité du cookie */
		{
			list($user, $time) = explode(':', $_COOKIE['souvenir']);

			$toutEstLa = isset($user) && !empty($user) && isset($time) && ctype_digit($time) && intval($time) >= 0;

			if($toutEstLa)
			{
				$membre = $this->membre->getMembreByCookie($user);

				if($membre) /* si le membre est authentifié */
				{
					$data = array();

					if($time != '0')
					{
						$time = strval(time()+3600);
					}
					setcookie('souvenir', $user . ':' . $time, intval($time), '/', null, false, true);
					$data['membre'] = $membre;
					$this->load->view('viewEspace.php', $data); /* on charge la vue */
				}
			}
			if(!$toutEstLa || !$membre) /* si les infos sont pas bonnes, on détruit le cookie et redirige vers l'accueil */
			{
				delete_cookie('souvenir');
				redirect('', 'refresh');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

	public function deconnexion()
	{
		delete_cookie('souvenir');
		redirect('', 'refresh');
	}

	public function connexion()
	{
		if(isset($_POST['submitHeader']) && isset($_POST['identifiant']) && isset($_POST['password']))
		{
			foreach($_POST as $p) /* validation de formulaire */
			{
				$this->form_validation->set_rules($p, ucfirst($p), 'trim|encode_php_tags|xss_clean');
			}

			if($this->form_validation->run())
			{
				$data = array();
				$membre = $this->membre->getMembreByPseudoEtPswd($_POST['identifiant'], sha1($_POST['password']));

				if(!$membre)
				{
					$data['message'] = "Vous n'existez pas !";
					$this->load->view('viewAccueil.php', $data); /* on charge la vue */
				}
				else if($membre->actif == "0")
				{
					$data['message'] = "Vous n'avez pas confirmé votre compte !";
					$this->load->view('viewAccueil.php', $data); /* on charge la vue */
				}
				else
				{
					$user = sha1(md5($membre->pseudoMembre)); /* création du cookie d'authentification */

					setcookie('souvenir', $user . ':0', 0, '/', null, false, true);

					if($_POST['rememberMe'] == "oui") /* si l'utilisateur a coché la case, on ajoute au cookie 1 heure sur le temps d'expiration */
					{
						$time = time() + 3600;
						setcookie('souvenir', $user . ':' . strval($time), $time, '/', null, false, true);
					}
					
					redirect('monespace', 'refresh');
				}
			}
			else
			{
				redirect('', 'refresh');
			}
		}
	}

	public function updateEspace()
	{
		if(isset($_POST['submitUpdate']) && isset($_POST['pseudoMembre']) && isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['adresse']) && isset($_POST['codep']) && isset($_POST['ville']))
		{
			$allFilled = true;
			
			foreach($_POST as $p) /* validation de formulaire */
			{
				$this->form_validation->set_rules($p, ucfirst($p), 'trim|encode_php_tags|xss_clean');
				if(empty($p))
				{
					$allFilled = false;
				}
			}

			if($this->form_validation->run() && $this->membre->getMembreByPseudo($_POST['pseudoMembre'])) /* si le formulaire est valide et que le membre existe */
			{
				$data = array();
				
				if($allFilled)
				{
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) /* on vérifie le format de l'adresse email */
					{
						if(strlen($_POST['codep']) == 5 && ctype_digit($_POST['codep'])) /* on vérifie le format du code postal */
						{
							$this->membre->updateMembre($_POST['pseudoMembre'], $_POST['email'], $_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['codep'],
								$_POST['ville']);
						}
					}
				}
				
				redirect('monespace', 'refresh');
			}
			else /* si le formulaire n'est pas valide, on actualise la page */
			{
				redirect('monespace', 'refresh');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

}
