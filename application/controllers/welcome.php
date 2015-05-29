<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = array();

		if(isset($_COOKIE['souvenir'])) /* check la validité du cookie */
		{
			list($user, $time) = explode(':', $_COOKIE['souvenir']);

			$toutEstLa = isset($user) && !empty($user) && isset($time) && ctype_digit($time) && intval($time) >= 0;

			if($toutEstLa)
			{
				$membre = $this->membre->getMembreByCookie($user);

				if($membre) /* si le membre est authentifié */
				{
					if($time != '0')
					{
						$time = strval(time()+3600);
					}
					setcookie('souvenir', $user . ':' . $time, intval($time), '/', null, false, true);
					$data['membre'] = $membre;
					$this->load->view('viewAccueil.php', $data); /* on charge la vue */
				}
			}
			if(!$toutEstLa || !$membre) /* si les infos sont pas bonnes, on détruit le cookie et redirige vers l'accueil */
			{
				delete_cookie('souvenir');
				redirect('', 'refresh');
			}
		}
		else if(isset($_GET['pseudo']) && isset($_GET['cle']) && !empty($_GET['pseudo']) && !empty($_GET['cle'])) // activation du compte via le lien présent dans le mail
		{
			if($_GET['cle'] == $this->membre->getCleByPseudo($_GET['pseudo']))
			{
				if($this->membre->getActifByPseudo($_GET['pseudo']) == "0")
				{
					$this->membre->updateActif($_GET['pseudo']);
					$data['message'] = "Votre compte a bien été activé !";
				}
				else
				{
					$data['message'] = "Votre compte a déjà été activé !";
				}
			}
			else
			{
				$data['message'] = "La clé ne correspond pas !";
			}
			$this->load->view('viewAccueil.php', $data);
		}
		else
		{
			$this->load->view('viewAccueil.php');
		}
	}

	public function notFound() // error 404
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
					$this->load->view('view404.php', $data); /* on charge la vue */
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
			$this->load->view('view404.php');
		}
	}
	
}
