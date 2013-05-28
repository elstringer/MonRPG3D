<?php

defined( 'SYSPATH' ) OR die( 'No direct access allowed.' );

/**
 * Permet de connaitre toutes les informations sur un : sort.
 *
 * @package Sort
 * @author Pasquelin Alban
 * @copyright (c) 2011
 * @license http://www.openrpg.fr/license.html
 * @version 2.0.0
 */
class Sort_Model extends Model {

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
				if( Sort_Model::$instance === NULL )
						return new Sort_Model;

				return Sort_Model::$instance;
		}

		/**
		 * Faire une sélection sur la table sort.
		 *
		 * @param mixe les valeur du where
		 * @param integer limit de la requête
		 * @return mixe retourne un object sinon false
		 */
		public function select( $array = FALSE, $limit = FALSE )
		{
				return parent::model_select( 'sorts', $array, $limit );
		}

		/**
		 * Faire une mise à jour d'une ligne.
		 *
		 * @param array valeur à mettre à jour
		 * @param integer ID de la ligne
		 * @return mixe retourne un object sinon false
		 */
		public function update( array $set, $sorts_id )
		{
				return parent::model_update( 'sorts', $set, array( 'id' => $sorts_id ) );
		}

		/**
		 * Faire une insertion d'une ligne SQL.
		 *
		 * @param array valeur à insérer
		 * @return	 mixe retourne soit  un ID sinon false
		 */
		public function insert( array $set )
		{
				return parent::model_insert( 'sorts', $set );
		}

		/**
		 * Supprimer une ligne.
		 *
		 * @param integer ID de la ligne
		 * @return	 bool retour false ou true
		 */
		public function delete( $sorts_id )
		{
				return parent::model_delete( 'sorts', array( 'id' => $sorts_id ) );
		}

		/**
		 * Faire une insertion d'un sort pour un user.
		 *
		 * @param integer ID de l'utilisateur
		 * @param integer ID du sort
		 * @return	 mixe retourne soit  un ID sinon false
		 */
		public function insert_user( $user_id, $sorts_id, $order = false  )
		{
				return parent::model_insert( 'users_sorts', array( 'user_id' => $user_id, 'sort_id' => $sorts_id, 'order' => $order ) );
		}

		/**
		 * Supprimer un sort utilisateur.
		 *
		 * @param integer ID de l'utilisateur
		 * @param integer ID du sort
		 * @return	 bool retour false ou true
		 */
		public function delete_user( $user_id, $sorts_id)
		{
				return parent::model_delete( 'users_sorts', array( 'user_id' => $user_id, 'sort_id' => $sorts_id ) );
		}

		/**
		 * Supprimer tous les sorts d'un utilisateur.
		 *
		 * @param integer ID de l'utilisateur
		 * @return	 bool retour false ou true
		 */
		public function user_delete_all( $user_id )
		{
				return parent::model_delete( 'users_sorts', array( 'user_id' => $user_id ) );
		}

		/**
		 * Faire une sélection des sorts d'un utilisateur.
		 *
		 * @param integer ID de l'utilisateur
		 * @return mixe retourne un object sinon false
		 */
		public function user( $user_id )
		{
				$query = $this->db->from( 'sorts' )
								->join( 'users_sorts', 'users_sorts.sort_id', 'sorts.id' )
								->where( 'users_sorts.user_id', $user_id )
								->get();

				return $query->count() ? $query : FALSE;
		}

		/**
		 * Faire une sélection sur les sorts  en mode IN array.
		 *
		 * @param array ID des sorts
		 * @return mixe retourne un object sinon false
		 */
		public function in( array $in )
		{
				$query = $this->db->from( 'sorts' )->in( 'id', $in )->get();

				return $query->count() ? $query : FALSE;
		}

}

?>
