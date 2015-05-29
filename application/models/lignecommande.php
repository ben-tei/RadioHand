<?php

class LigneCommande extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function addLigneCommande($idCommande, $idProduit, $prixProduit, $qteProduit, $nomImage, $libelleProduit)
	{
		if(isset($idCommande) && !empty($idCommande) && isset($idProduit) && !empty($idProduit) && isset($prixProduit) && !empty($prixProduit) && isset($qteProduit) && !empty($qteProduit) && isset($nomImage) && !empty($nomImage) && isset($libelleProduit) && !empty($libelleProduit))
		{
			$this->db->set('idCommande', $idCommande)
			->set('idProduit', $idProduit)
			->set('qteProduit', intval($qteProduit))
			->set('prixProduit', intval($prixProduit))
			->set('nomImage', $nomImage)
			->set('libelleProduit', $libelleProduit)
			->insert('lignecommande');
		}
	}

	public function getLigneCommandeByIdCommande($idCommande)
	{
		if(isset($idCommande) && !empty($idCommande))
		{
			$query = $this->db->query('SELECT * FROM lignecommande WHERE idCommande = "' . $idCommande . '"');

			return $query->result();
		}
	}

	public function getMontantByIdCommande($idCommande)
	{
		if(isset($idCommande) && !empty($idCommande))
		{
			$query = $this->db->query('SELECT SUM(qteProduit * prixProduit) as montant FROM lignecommande WHERE idCommande = "' . $idCommande . '"');
			$row = $query->row_array();

			return $row['montant'];
		}
	}

}