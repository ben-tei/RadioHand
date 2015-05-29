<?php

class Commande extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function addCommande($pseudoMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre))
		{
			$this->db->set('pseudoMembre', $pseudoMembre)
			->set('dateCommande', date("Y-m-d"))
			->insert('commande');

			$query = $this->db->query('SELECT max(idCommande) as lastId FROM commande WHERE pseudoMembre = "' . $pseudoMembre . '"');
			$row = $query->row_array();

			return $row['lastId'];
		}
	}

	public function getAllCommandesByUser($pseudoMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre))
		{
			$query = $this->db->query('SELECT * FROM commande WHERE pseudoMembre = "' . $pseudoMembre . '"');

			return $query->result();
		}
	}

}