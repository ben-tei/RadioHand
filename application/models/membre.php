<?php

class Membre extends CI_Model 
{

	public function __construct()
	{
		parent::__construct();
	}

	public function addMembre($pseudoMembre, $pswdMembre, $emailMembre, $nomMembre, $prenomMembre, $adresseMembre, $codepMembre, $villeMembre, $cle)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($pswdMembre) && !empty($pswdMembre) && isset($emailMembre) && !empty($emailMembre) && isset($nomMembre) && !empty($nomMembre) && isset($prenomMembre) && !empty($prenomMembre) && isset($adresseMembre) && !empty($adresseMembre) && isset($codepMembre) && !empty($codepMembre) && isset($villeMembre) && !empty($villeMembre) && isset($cle) && !empty($cle))
		{
			$query = $this->db->query('SELECT * FROM membre WHERE pseudoMembre = ' . $this->db->escape($pseudoMembre));
			$array = $query->result();

			if(count($array) == 0)
			{	
				$Q =  $this->db->set('pseudoMembre', $pseudoMembre)
				->set('pswdMembre', $pswdMembre)
				->set('emailMembre', $emailMembre)
				->set('nomMembre', $nomMembre)
				->set('prenomMembre',  $prenomMembre)
				->set('adresseMembre', $adresseMembre)
				->set('codepMembre', $codepMembre)
				->set('villeMembre', $villeMembre)
				->set('cle', $cle)
				->set('actif', "0")
				->insert('membre');
			}
			else
			{
				$Q = null;
			}
			
			return $Q;
		}
	}

	public function getMembreByCookie($pseudoHash)
	{
		if(isset($pseudoHash) && !empty($pseudoHash))
		{
			$query = $this->db->query('SELECT * FROM membre WHERE sha1(md5(pseudoMembre)) = ' . $this->db->escape($pseudoHash));

			return $query->row();
		}
	}

	public function getMembreByPseudo($pseudoMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre))
		{
			$query = $this->db->query('SELECT * FROM membre WHERE pseudoMembre = ' . $this->db->escape($pseudoMembre));

			return $query->row();
		}
	}

	public function getMembreByPseudoEtPswd($pseudoMembre, $pswdMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($pswdMembre) && !empty($pswdMembre))
		{
			$query = $this->db->query('SELECT * FROM membre WHERE pseudoMembre = ' . $this->db->escape($pseudoMembre) . ' AND pswdMembre = ' . $this->db->escape($pswdMembre));

			return $query->row();
		}
	}

	public function updateMembre($pseudoMembre, $emailMembre, $nomMembre, $prenomMembre, $adresseMembre, $codepMembre, $villeMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre) && isset($emailMembre) && !empty($emailMembre) && isset($nomMembre) && !empty($nomMembre) && isset($prenomMembre) && !empty($prenomMembre) && isset($adresseMembre) && !empty($adresseMembre) && isset($codepMembre) && !empty($codepMembre) && isset($villeMembre) && !empty($villeMembre))
		{
			$data = array(
				'emailMembre' => $emailMembre,
				'nomMembre' => $nomMembre,
				'prenomMembre' => $prenomMembre,
				'adresseMembre' => $adresseMembre,
				'codepMembre' => $codepMembre,
				'villeMembre' => $villeMembre
			);

			$array = array('pseudoMembre' => $pseudoMembre);
			$this->db->where($array); 
			$this->db->update('membre', $data);
		}
	}

	public function updateActif($pseudoMembre)
	{
		if(isset($pseudoMembre) && !empty($pseudoMembre))
		{
			$data = array(
				'actif' => "1"
			);

			$array = array('pseudoMembre' => $pseudoMembre);
			$this->db->where($array); 
			$this->db->update('membre', $data);
		}
	}

}