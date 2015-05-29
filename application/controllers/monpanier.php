<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MonPanier extends CI_Controller
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
					$data = array();

					if($time != '0')
					{
						$time = strval(time()+3600);
					}
					setcookie('souvenir', $user . ':' . $time, intval($time), '/', null, false, true);
					$data['membre'] = $membre;
					$data['produits'] = array();
					$data['panier'] = $this->lignepanier->getLignePanierByUser($membre->pseudoMembre);
					$data['total'] = $this->lignepanier->getTotalByUser($membre->pseudoMembre);
					$data['totauxLignes'] = $this->lignepanier->getAllTotauxLignesByUser($membre->pseudoMembre);
					$i = 0;
					while($i < count($data['panier']))
					{
						array_push($data['produits'], $this->produit->getProduitById($data['panier'][$i]->idProduit));
						$data['qteMax'][$i] = $data['produits'][$i]->qteProduit + $data['panier'][$i]->qteProduit;
						$i++;
					}
					$this->load->view('viewPanierAvecJS.php', $data);
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
			$this->load->view('viewErrorEmptyPanier.php');
		}
	}
	
	public function noJS()	// est appelée à la place de index() lorsque JavaScript est désactivé
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
					$data['produits'] = array();
					$data['panier'] = $this->lignepanier->getLignePanierByUser($membre->pseudoMembre);
					$data['total'] = $this->lignepanier->getTotalByUser($membre->pseudoMembre);
					$data['totauxLignes'] = $this->lignepanier->getAllTotauxLignesByUser($membre->pseudoMembre);
					$i = 0;
					while($i < count($data['panier']))
					{
						array_push($data['produits'], $this->produit->getProduitById($data['panier'][$i]->idProduit));
						$data['qteMax'][$i] = $data['produits'][$i]->qteProduit + $data['panier'][$i]->qteProduit;
						$i++;
					}
					$this->load->view('viewPanierSansJS.php', $data);
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
			$this->load->view('viewErrorEmptyPanier.php');
		}
	}

	public function addProduitToLigne()
	{
		if($this->input->post('submitPanier'))
		{
			if(ctype_digit($_POST['quantite']) && intval($_POST['quantite']) > 0)	// si la quantité est un entier positif
			{
				foreach($_POST as $p)	/* validation de formulaire */
				{
					$this->form_validation->set_rules($p, ucfirst($p), 'trim|encode_php_tags|xss_clean');
				}

				if($this->form_validation->run() && $this->membre->getMembreByPseudo($_POST['pseudoMembre']) && $this->produit->getProduitById($_POST['idProduit']))	// si le formulaire est valide et que le membre et le produit existent
				{
					if($_POST['quantite'] <= $this->produit->getQteProduitById($_POST['idProduit']))	// si la quantité demandée est inférieur à la quantité en stock
					{
						$alreadyCreated = $this->lignepanier->getLignePanierByUserAndProduit($_POST['pseudoMembre'], $_POST['idProduit']);

						if(!$alreadyCreated)
						{
							$this->lignepanier->addLignePanier($_POST['pseudoMembre'], $_POST['idProduit'], $_POST['quantite']);	// si la ligne n'existe pas, on l'ajoute
						}
						else
						{
							$this->lignepanier->addQteToLignePanier($_POST['pseudoMembre'], $_POST['idProduit'], $_POST['quantite']);	// si la ligne existe, on modifie sa quantité
						}

						redirect('monpanier', 'refresh');
					}
					else
					{
						redirect($_SERVER['HTTP_REFERER'], 'refresh');	// si la quantité demandée est supérieur à la quantité en stock, on actualise la page
					}
				}
				else	// si le formulaire n'est pas valide, on actualise la page
				{
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			}
			else	// si la quantité n'est pas un entier positif
			{
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

	public function deleteLigne()
	{
		if(isset($_POST['submitSuppression']))
		{
			foreach($_POST as $p)	/* validation de formulaire */
			{
				$this->form_validation->set_rules($p, ucfirst($p), 'trim|encode_php_tags|xss_clean');
			}

			if($this->form_validation->run() && $this->membre->getMembreByPseudo($_POST['pseudoMembre']) && $this->produit->getProduitById($_POST['idProduit']))	// si le formulaire est valide et que le membre et le produit existent
			{
				$qteActuelleLignePanier = $this->lignepanier->getQteLigneByProduitAndUser($_POST['pseudoMembre'], $_POST['idProduit']);
				
				if($qteActuelleLignePanier)	// s'il existe bien une quantité
				{
					$this->lignepanier->delLigne($_POST['pseudoMembre'], $_POST['idProduit']);	// on supprime la ligne panier
					$this->produit->addQteToProduit($_POST['idProduit'], $qteActuelleLignePanier);	// on augmente le stock du produit en question
					
					if(isset($_POST['sousTotChaqueLigne']))	// si tableau JavaScript il y a
					{
						$sousTotChaqueLigne = explode(",", $_POST['sousTotChaqueLigne']);
						$i = 0;
						$nouveauTotal = 0;
						while($i < count($sousTotChaqueLigne))
						{
							$nouveauTotal = $nouveauTotal + intval($sousTotChaqueLigne[$i]);
							$i++;
						}
						header('Content-Type: application/x-json; charset=utf-8');
						echo(json_encode($nouveauTotal));
					}
					else
					{
						redirect('monpanier', 'refresh');
					}
				}
				else
				{
					redirect('monpanier', 'refresh');
				}
			}
			else
			{
				redirect('', 'refresh');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

	public function updateLigne()
	{
		if(isset($_POST['qtePanier']))
		{
			if(ctype_digit($_POST['qtePanier']) && intval($_POST['qtePanier']) > 0)	// si la quantité est un entier positif
			{
				foreach($_POST as $p)	/* validation de formulaire */
				{
					$this->form_validation->set_rules($p, ucfirst($p), 'trim|encode_php_tags|xss_clean');
				}

				if($this->form_validation->run() && $this->membre->getMembreByPseudo($_POST['pseudoMembre']) && $this->produit->getProduitById($_POST['idProduit']))	// si le formulaire est valide et que le membre et le produit existent
				{
					if(isset($_POST['sousTotChaqueLigne']))	// si tableau JavaScript il y a
					{
						$sousTotChaqueLigne = explode(",", $_POST['sousTotChaqueLigne']);
						$i = 0;
						$nouveauTotal = 0;
						while($i < count($sousTotChaqueLigne))
						{
							$nouveauTotal = $nouveauTotal + intval($sousTotChaqueLigne[$i]);
							$i++;
						}
						
						$qteActuelleLignePanier = $this->lignepanier->getQteLigneByProduitAndUser($_POST['pseudoMembre'], $_POST['idProduit']);
						
						if($qteActuelleLignePanier)	// s'il existe bien une quantité
						{
							if(intval($_POST['qtePanier']) - intval($qteActuelleLignePanier) > $this->produit->getQteProduitById($_POST['idProduit']))	// si la quantité demandée est supérieur à la quantité en stock, on actualise la page
							{
								header('Content-Type: application/x-json; charset=utf-8');
								echo(json_encode('Plus assez en stock'));
							}
							else	// si la quantité demandée est inférieur à la quantité en stock
							{
								$this->lignepanier->updateLignePanier($_POST['pseudoMembre'], $_POST['idProduit'], $_POST['qtePanier']);
								header('Content-Type: application/x-json; charset=utf-8');
								echo(json_encode(array(intval($_POST['qtePanier']) * intval($_POST['prixProduit']), $nouveauTotal)));	// on renvoie le nouveau sous total de la ligne ainsi que le nouveau total de la commande
							}
						}
						else
						{
							redirect('monpanier', 'refresh');
						}
					}
					else
					{
						$qteActuelleLignePanier = $this->lignepanier->getQteLigneByProduitAndUser($_POST['pseudoMembre'], $_POST['idProduit']);
						
						if($qteActuelleLignePanier)	// s'il existe bien une quantité
						{
							if(intval($_POST['qtePanier']) - intval($qteActuelleLignePanier) <= $this->produit->getQteProduitById($_POST['idProduit']))	// si la quantité demandée est inférieur à la quantité en stock
							{
								$this->lignepanier->updateLignePanier($_POST['pseudoMembre'], $_POST['idProduit'], $_POST['qtePanier']);
							}
						}
						
						redirect('monpanier', 'refresh');
					}
				}
				else	// si le formulaire n'est pas valide, on actualise la page
				{
					redirect('monpanier', 'refresh');
				}
			}
			else	// si la quantité n'est pas un entier positif
			{
				redirect('monpanier', 'refresh');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

}
