<?php

class LignePanier extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function delLigne($pseudoMembre, $idProduit)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($idProduit) && !empty($idProduit))
		{
			$this->db->delete('lignepanier', array('pseudoMembre' => $pseudoMembre, 'idProduit' => $idProduit));
		}
	}

	public function addLignePanier($pseudoMembre, $idProduit, $qteProduit)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($idProduit) && !empty($idProduit) && isset($qteProduit) && !empty($qteProduit))
		{
			$this->db->set('pseudoMembre', $pseudoMembre)
			->set('idProduit', $idProduit)
			->set('qteProduit', intval($qteProduit))
			->set('datePanier', date("Y-m-d"))
			->insert('lignepanier');
		}
	}

	public function addQteToLignePanier($pseudoMembre, $idProduit, $qteProduit)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($idProduit) && !empty($idProduit) && isset($qteProduit) && !empty($qteProduit))
		{
			$qteExistante = $this->getQteLigneByProduitAndUser($pseudoMembre, $idProduit);

			$nouvelleQte = intval($qteExistante) + intval($qteProduit);

			$this->updateLignePanier($pseudoMembre, $idProduit, $nouvelleQte);
		}
	}

	public function updateLignePanier($pseudoMembre, $idProduit, $qteProduit)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($idProduit) && !empty($idProduit) && isset($qteProduit) && !empty($qteProduit))
		{
			$data = array(
				'qteProduit' => $qteProduit,
				'datePanier' => date("Y-m-d")
			);

			$array = array('pseudoMembre' => $pseudoMembre, 'idProduit' => $idProduit);
			$this->db->where($array); 
			$this->db->update('lignepanier', $data);
		}
	}

	public function getLignePanierByUserAndProduit($pseudoMembre, $idProduit)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($idProduit) && !empty($idProduit))
		{
			$query = $this->db->query('SELECT * FROM lignepanier WHERE pseudoMembre = "' . $pseudoMembre . '" AND idProduit = "' . $idProduit . '"');

			return $query->row();
		}
	}

	public function getLignePanierByUser($pseudoMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre))
		{
			$query = $this->db->query('SELECT * FROM lignepanier WHERE pseudoMembre = "' . $pseudoMembre . '"');

			return $query->result();
		}
	}

	public function getQteLigneByProduitAndUser($pseudoMembre, $idProduit)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($idProduit) && !empty($idProduit))
		{
			$query = $this->db->query('SELECT qteProduit FROM lignepanier WHERE pseudoMembre = "' . $pseudoMembre . '" AND idProduit = "' . $idProduit . '"');
			$row = $query->row_array();
			
			if(!$row)
			{
				$row['qteProduit'] = null;
			}

			return $row['qteProduit'];
		}
	}

	public function getTotalByUser($pseudoMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre))
		{
			$query = $this->db->query('SELECT SUM(l.qteProduit * p.prixProduit) as total FROM lignepanier l, produit p WHERE pseudoMembre = "' . $pseudoMembre . '" AND l.idProduit = p.idProduit');
			$row = $query->row_array();

			if(!$row['total'])
			{
				$row['total'] = 0;
			}

			return $row['total'];
		}
	}

	public function getAllTotauxLignesByUser($pseudoMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre))
		{
			$query = $this->db->query('SELECT (l.qteProduit * p.prixProduit) as soustotal FROM lignepanier l, produit p WHERE pseudoMembre = "' . $pseudoMembre . '" AND l.idProduit = p.idProduit');

			return $query->result();
		}
	}

}