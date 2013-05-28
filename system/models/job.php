<?php

 defined( 'SYSPATH' ) OR die( 'No direct access allowed.' );

 /**
	* Permet de connaitre toutes sur les jobs
	*
	* @package Job
	* @author Pasquelin Alban
	* @copyright (c) 2011
	* @license http://www.openrpg.fr/license.html
	* @version 2.0.0
	*/
 class Job_Model extends Model {

	 /**
		* Permet de créer une instance et donc de ne pas faire des doublons.
		* 
		* @var object protected
		*/
	 protected static $instance;

	 /**
		* Permet de ne pas faire des multi appel d'object.
		*
		* @return class return la classe construite
		*/
	 public static function instance()
	 {
		 if( Job_Model::$instance === NULL )
			 return new Job_Model;

		 return Job_Model::$instance;
	 }

	 /**
		* Faire une sélection sur la table job en joiture avec users_jobs.
		*
		* @param integer ID du job
		* @param integer limite des lignes
		* @return mixe retourne un object sinon false
		*/
	 public function select( $job_id = false, $limit = false )
	 {
		 if( $job_id )
			 $this->db->where( 'jobs.id', $job_id );

		 if( $limit )
			 $this->db->limit( $limit );

		 $query = $this->db->from( 'jobs' )->get();

		 return $query->count() ? (!$limit || $limit > 1 ? $query : $query->current() ) : FALSE;
	 }

	 /**
		* Faire une insertion d'une ligne SQL.
		*
		* @param array valeur à insérer
		* @return	 mixe retourne soit  un ID sinon false
		*/
	 public function insert( array $set )
	 {
		 return parent::model_insert( 'jobs', $set );
	 }

	 /**
		* Faire une mise à jour d'une ligne.
		*
		* @param array valeur à mettre à jour
		* @param integer ID de la ligne
		* @return mixe retourne un object sinon false
		*/
	 public function update( array $set, $job_id )
	 {
		 return parent::model_update( 'jobs', $set, array( 'id' => $job_id ) );
	 }

	 /**
		* Supprimer une ligne.
		*
		* @param integer ID de la ligne
		* @return	 bool retour false ou true
		*/
	 public function delete( $job_id )
	 {
		 return parent::model_delete( 'jobs', array( 'id' => $job_id ) );
	 }

	 /**
		* Faire une sélection sur les jobs en mode IN array.
		*
		* @param array ID des objets
		* @return mixe retourne un object sinon false
		*/
	 public function in( array $in )
	 {
		 $query = $this->db->from( 'jobs' )->in( 'id', $in )->get();

		 return $query->count() ? $query : FALSE;
	 }
 }

?>
