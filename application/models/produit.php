<?php

class Produit extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getProduitById($idProduit)
	{
		if(isset($idProduit) && !empty($idProduit))
		{
			$query = $this->db->query('SELECT * FROM produit WHERE idProduit = "' . $idProduit . '"');

			return $query->row();
		}
	}

	public function getAllProduits($limit, $start)
	{
		if(isset($limit) && !empty($limit) && isset($start))
		{
			$this->db->limit($limit, $start);
			$query = $this->db->get("produit");

			$data = array();

			foreach ($query->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	public function getAllProduitsMatchWith($recherche)
	{
		if(isset($recherche))
		{
			$req = "SELECT * FROM produit WHERE descriptifProduit LIKE '%$recherche%' UNION SELECT * FROM produit WHERE libelleProduit LIKE '%$recherche%'";
			$query = $this->db->query($req);

			return $query->result();
		}
	}

	public function getQteProduitById($idProduit)
	{
		if(isset($idProduit) && !empty($idProduit))
		{
			$query = $this->db->query('SELECT qteProduit FROM produit WHERE idProduit = "' . $idProduit . '"');
			$row = $query->row_array();

			return $row['qteProduit'];
		}
	}

	public function getPrixProduitById($idProduit)
	{
		if(isset($idProduit) && !empty($idProduit))
		{
			$query = $this->db->query('SELECT prixProduit FROM produit WHERE idProduit = "' . $idProduit . '"');
			$row = $query->row_array();

			return $row['prixProduit'];
		}
	}

	public function getNomImageProduitById($idProduit)
	{
		if(isset($idProduit) && !empty($idProduit))
		{
			$query = $this->db->query('SELECT nomImage FROM produit WHERE idProduit = "' . $idProduit . '"');
			$row = $query->row_array();

			return $row['nomImage'];
		}
	}

	public function getLibelleProduitById($idProduit)
	{
		if(isset($idProduit) && !empty($idProduit))
		{
			$query = $this->db->query('SELECT libelleProduit FROM produit WHERE idProduit = "' . $idProduit . '"');
			$row = $query->row_array();

			return $row['libelleProduit'];
		}
	}

	public function addQteToProduit($idProduit, $qteProduit)
	{
		if(isset($idProduit) && !empty($idProduit) && isset($qteProduit) && !empty($qteProduit))
		{
			$nouvelleQte = intval($qteProduit) + intval($this->getQteProduitById($idProduit));

			$data = array(
				'qteProduit' => $nouvelleQte
			);

			$array = array('idProduit' => $idProduit);
			$this->db->where($array); 
			$this->db->update('produit', $data);
		}
	}

	public function record_count()
	{
		return $this->db->count_all("produit");
	}

}