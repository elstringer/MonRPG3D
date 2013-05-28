<?php

defined( 'SYSPATH' ) OR die( 'No direct access allowed.' );

/**
 * Permet de connaitre toutes les informations sur la : animations.
 *
 * @package Animate
 * @author Pasquelin Alban
 * @copyright (c) 2011
 * @license http://www.openrpg.fr/license.html
 * @version 2.0.0
 */
class Animate_Model extends Model {

		/**
		 * Permet de créer une instance et donc de ne pas faire des doublons.
		 * 
		 * @var object protected
		 */
		protected static $instance;

		/**
		 * Permet de ne pas faire des multi appel d'object
		 *
		 * @return class return la classe construite
		 */
		public static function instance()
		{
				if( Animate_Model::$instance === NULL )
						return new Animate_Model;

				return Animate_Model::$instance;
		}

		/**
		 * Faire une sélection sur la table animates.
		 *
		 * @param array les valeur du where
		 * @param integer limit de la requête
		 * @param string colonne sur le trie
		 * @param string colonne sélectionné
		 * @return mixe retourne un object sinon false
		 */
		public function select( $array = false, $limit = false, $orderby = false, $select = false )
		{
				if( $orderby )
						$orderby = array( $orderby => 'ASC' );

				return parent::model_select( 'animates', $array, $limit, $select, $orderby );
		}

		/**
		 * Faire une insertion d'une ligne SQL.
		 *
		 * @param array valeur à insérer
		 * @return	 mixe retourne soit  un ID sinon false
		 */
		public function insert( array $set )
		{
				return parent::model_insert( 'animates', $set );
		}

		/**
		 * Faire une mise à jour d'une ligne.
		 *
		 * @param array valeur à mettre à jour
		 * @param mixe valeur string/array pour le where
		 * @return mixe retourne un object sinon false
		 */
		public function update( array $set, array $where )
		{
				return parent::model_update( 'animates', $set, $where );
		}

		/**
		 * Supprimer une ligne.
		 *
		 * @param mixe string/array pour le where
		 * @return	 bool retour false ou true
		 */
		public function delete( $where )
		{
				return parent::model_delete( 'animates', $where );
		}
}

?>
