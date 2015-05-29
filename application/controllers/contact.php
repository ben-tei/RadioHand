<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller
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
					$this->load->view('viewContact.php', $data); /* on charge la vue */
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
			$this->load->view('viewContact.php');
		}
	}

	public function sendMail()
	{
		if(isset($_POST['submitContact']) && isset($_POST['email']) && isset($_POST['sujet']) && isset($_POST['message']))
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

			if($this->form_validation->run()) /* si le formulaire est valide */
			{
				$data = array();
				
				if($allFilled)
				{
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) /* on vérifie le format de l'adresse email */
					{
						$this->mail->send($_POST['email'], 'radiohand@yopmail.com', $_POST['sujet'], $_POST['message']); /* envoi du mail */

						setcookie('mail', 'jenesaispasquoimettre', 0, '/', null, false, true);
						redirect('contact/mailEnvoye', 'refresh');
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
				$this->load->view('viewContact.php', $data);
			}
			else /* si le formulaire n'est pas valide, on actualise la page */
			{
				redirect('contact', 'refresh');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

	public function mailEnvoye()
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
					$data = array();

					if($time != '0')
					{
						$time = strval(time()+3600);
					}
					setcookie('souvenir', $user . ':' . $time, intval($time), '/', null, false, true);
					$data['membre'] = $membre;
				}
			}
			if(!$toutEstLa || !$membre)	/* si les infos sont pas bonnes, on détruit le cookie et redirige vers l'accueil */
			{
				delete_cookie('souvenir');
				redirect('', 'refresh');
			}
		}

		if(isset($_COOKIE['mail']))	/* si l'utilisateur vient d'envoyer un mail */
		{
			delete_cookie('mail'); /* on supprime le cookie */

			if(isset($data['membre'])) /* si le membre est connecté */
			{
				$this->load->view('viewConfirmationMail.php', $data);
			}
			else
			{
				$this->load->view('viewConfirmationMail.php');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

}
