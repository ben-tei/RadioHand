<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogue extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = array();

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
					$data['membre'] = $membre;
				}
			}
			if(!$toutEstLa || !$membre)	/* si les infos sont pas bonnes, on détruit le cookie et redirige vers l'accueil */
			{
				delete_cookie('souvenir');
				redirect('', 'refresh');
			}
		}

		$config = array();

		$config['base_url'] = base_url() . "catalogue/index";	/* CodeIgniter Pagination */
		$config['total_rows'] = $this->produit->record_count();
		$config['per_page'] = 2;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = round($choice);

		$this->pagination->initialize($config);

		$data['allProduits'] = $this->produit->getAllProduits($config["per_page"], $this->uri->segment(3));	/* on récupère les produits */

		$data['pages'] = $this->pagination->create_links();

		$this->load->view('viewCatalogue.php', $data);	/* on charge la vue */
	}

	public function getProduit()
	{
		if(isset($_GET["idProduit"]) && strlen($_GET["idProduit"]) > 0 && ctype_digit($_GET["idProduit"]) && $_GET["idProduit"] > 0 && $_GET["idProduit"] <= $this->produit->record_count())
		{
			$data = array();

			if(isset($_COOKIE['souvenir']))	/* check la validité du cookie */
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
					}
				}
				if(!$toutEstLa || !$membre) /* si les infos sont pas bonnes, on détruit le cookie et redirige vers l'accueil */
				{
					delete_cookie('souvenir');
					redirect('', 'refresh');
				}
			}

			$data['produit'] = $this->produit->getProduitById(addslashes($_GET["idProduit"]));
			$data['allProduits'] = $this->produit->getAllProduits($this->produit->record_count(), 0);
			$this->load->view('viewProduit.php', $data);
		}
		else
		{
			redirect('', 'refresh');
		}
	}

	public function recherche()
	{
		if(isset($_GET["q"]))
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
					}
				}
				if(!$toutEstLa || !$membre) /* si les infos sont pas bonnes, on détruit le cookie et redirige vers l'accueil */
				{
					delete_cookie('souvenir');
					redirect('', 'refresh');
				}
			}

			$data['produits'] = $this->produit->getAllProduitsMatchWith(addslashes($_GET["q"]));
			$data['allProduits'] = $this->produit->getAllProduits($this->produit->record_count(), 0);
			$this->load->view('viewRecherche.php', $data);
		}
		else
		{
			redirect('', 'refresh');
		}
	}

}
