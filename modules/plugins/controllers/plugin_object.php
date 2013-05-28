<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Gérer les objets sur la map.
 *
 * @package Action_object
 * @author Pasquelin Alban
 * @copyright (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Plugin_Object_Controller extends Action_Controller {

		/**
		 * Affiche l'alerte pour ramasser un objet.
		 * 
		 * @return  void
		 */
		public function index()
		{
				if( !$list_object = self::validate( FALSE ) )
				{
						echo 'Vous n\'avez pas encore accès à cette ressource.';
						return FALSE;
				}
				$v = new View( 'object/plugin' );
				$v->list_object = $list_object;
				$v->data = $this->data;
				$v->admin = in_array( 'admin', $this->role->name );
				$v->render( TRUE );
		}

		/**
		 * Affiche la liste des objets qu'on peut ramasser.
		 * 
		 * @return  void
		 */
		public function show()
		{
				$list_object_user = FALSE;

				if( ($user_object = Item_Model::instance()->select( $this->user->id ) ) !== FALSE )
						foreach( $user_object as $row )
								$list_object_user[$row->id] = $row;

				$article = $this->data->action_map->id_article ? Article_Model::instance()->select( array( 'id_article' => $this->data->action_map->id_article, 'status' => 1 ), 1 ) : FALSE;

				$v = new View( 'object/plugin_show' );
				$v->list_object = self::validate();
				$v->list_object_user = $list_object_user;
				$v->article = $article;
				$v->admin = in_array( 'admin', $this->role->name );
				$v->render( TRUE );
		}

		/**
		 * Enregistrer le ramassage de l'objet.
		 * 
		 * @return  void
		 */
		public function insert()
		{
				if( !$id_object = $this->input->post( 'object' ) )
						return FALSE;

				$history = History_Model::instance();
				$item = Item_Model::instance();

				if( $history->select( $this->user->id, $this->data->id, $id_object, 'object' ) )
						if( !self::validate_quete_objet( $id_object ) )
								return FALSE;

				if( !$obj = $item->select( FALSE, $id_object, 1 ) )
						return FALSE;

				if( !$obj->cle )
						$history->user_insert( $this->user->id, $this->data->id, $id_object, 'object' );

				$position = 0;

				if( ($position_exist = $item->select( $this->user->id, $id_object ) ) !== FALSE )
						$position = $position_exist->item_position;

				$item->user_insert( $this->user->id, $id_object, $position );

				echo '<b class="vert">'.Kohana::lang( 'object.collect' ).'</b>';
		}

		/**
		 * Affiche bien que les objets qui n'ont jamais été ramassé.
		 * 
		 * @param bool permet la redirection ou non
		 * @return array liste des objets valides
		 */
		private function validate( $redirect = TRUE )
		{
				if( !isset( $this->data->action_map->items ) || !$this->data->action_map->items )
				{
						if( $redirect )
								return self::redirection( Kohana::lang( 'object.error' ) );

						return FALSE;
				}

				if( $this->data->action_map->quete_id )
				{
						if( ($verif_quete = Quete_Model::instance()->quete_user( $this->user->id, $this->data->action_map->quete_id ) ) === FALSE )
								return fALSE;

						if( $verif_quete->status != 2 )
								return FALSE;
				}

				if( ($list_history = History_Model::instance()->select( $this->user->id, $this->data->id, FALSE, 'object' ) ) !== FALSE )
						foreach( $list_history as $row )
								if( ( $key = array_search( $row->element_id, $this->data->action_map->items ) ) !== FALSE )
										if( !self::validate_quete_objet( $row->element_id ) )
												unset( $this->data->items[$key] );

				if( !$this->data->action_map->items || (!$list_object = Item_Model::instance()->in( $this->data->action_map->items ) ) )
				{
						if( $redirect )
								return self::redirection( Kohana::lang( 'object.no_new_explore' ) );

						return FALSE;
				}

				return $list_object;
		}

		/**
		 * Gérer exception
		 * 
		 * @param bool permet la redirection ou non
		 * @return array liste des objets valides
		 */
		private function validate_quete_objet( $id_element )
		{
				if( !Item_Model::instance()->select( $this->user->id, $id_element ) )
				{
						if( ( $list_quete = Quete_Model::instance()->quete_user_join( $this->user->id ) ) !== FALSE )
						{
								$list_objet = FALSE;

								foreach( $list_quete as $o )
										if( $o->status == 1 )
												$list_objet[] = $o->id_objet;

								if( !$list_objet )
										return FALSE;

								$liste_actif_objet = explode( ',', implode( ',', $list_objet ) );

								if( ( $key = array_search( $id_element, $liste_actif_objet ) ) !== FALSE )
										return TRUE;
						}
				}
				return FALSE;
		}

}

?>