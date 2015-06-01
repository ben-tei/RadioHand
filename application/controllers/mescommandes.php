<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MesCommandes extends CI_Controller
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
					$data['allCommandes'] = $this->commande->getAllCommandesByUser($membre->pseudoMembre);
					$i = 0;
					foreach($data['allCommandes'] as $commande):
						$data['ligne'][$i] = $this->lignecommande->getLigneCommandeByIdCommande($commande->idCommande);
						$data['montant'][$i] = $this->lignecommande->getMontantByIdCommande($commande->idCommande);
						$i++;
					endforeach;
					$this->load->view('viewCommandes.php', $data); /* on charge la vue avec les commandes */
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

	public function createCommande()
	{
		if($this->input->post('submitCommande'))
		{
			foreach($_POST as $p) /* validation de formulaire */
			{
				$this->form_validation->set_rules($p, ucfirst($p), 'trim|encode_php_tags|xss_clean');
			}
			
			$membre = $this->membre->getMembreByPseudo($_POST['pseudoMembre']);

			if($this->form_validation->run() && $membre) /* si le formulaire est valide et que le membre existe */
			{
				$panier = $this->lignepanier->getLignePanierByUser($_POST['pseudoMembre']);
				
				if(count($panier) > 0) /* si des lignes panier existent */
				{
					$idCommande = $this->commande->addCommande($_POST['pseudoMembre']);
					$i = 0;
					$message = 'Merci pour votre achat ! A  bientôt !<br><br>';
					$message .= 'Numéro de Commande : ' . strval($idCommande) . '<br><br>';
					while($i < count($panier))
					{
						$produit = $this->produit->getProduitById($panier[$i]->idProduit);
						$this->lignecommande->addLigneCommande($idCommande, $panier[$i]->idProduit, $produit->prixProduit, $panier[$i]->qteProduit, $produit->nomImage, $produit->libelleProduit);
						$this->lignepanier->delLigne($_POST['pseudoMembre'], $panier[$i]->idProduit);
						$message .= 'Article : ' . strval($produit->libelleProduit) . '<br>';
						$message .= 'Quantité : ' . strval($panier[$i]->qteProduit) . '<br>';
						$message .= 'Prix : ' . strval($produit->prixProduit) . ' €<br><br>';
						$i++;
					}
					
					$message .= 'Total : ' . strval($this->lignecommande->getMontantByIdCommande($idCommande)) . ' €';
					
					$sujet = 'Recapitulatif de la commande';

					$this->mail->send('radiohand@yopmail.com', $membre->emailMembre, $sujet, $message); /* envoi du mail */
					
					setcookie('confirmation', 'jenesaispasquoimettre', 0, '/', null, false, true);
													
					redirect('mescommandes/confirmationCommande', 'refresh');
				}
				else
				{
					redirect('monpanier', 'refresh');
				}
			}
			else /* si le formulaire n'est pas valide, on redirige sur le panier */
			{
				redirect('monpanier', 'refresh');
			}
		}
		else
		{
			redirect('', 'refresh');
		}
	}

	public function confirmationCommande()
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
				}
			}
			if(!$toutEstLa || !$membre) /* si les infos sont pas bonnes, on détruit le cookie et redirige vers l'accueil */
			{
				delete_cookie('souvenir');
				redirect('', 'refresh');
			}
		}

		if(isset($_COOKIE['confirmation'])) /* si l'utilisateur vient de confirmer sa commande */
		{
			delete_cookie('confirmation');			
			$this->load->view('viewConfirmationCommande.php', $data);
		}
		else
		{
			redirect('', 'refresh');
		}
	}

}
