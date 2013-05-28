<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Controller public des items. Il permet de gerer les items
 * d'un utilisateur (add/delete/use).
 *
 * @package Item
 * @author Pasquelin Alban
 * @copyright	 (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Item_Controller extends Template_Controller {

		/**
		 * Permet de faire passer l'object item sur toutes les méthodes.
		 * 
		 * @var object private
		 */
		private $item;

		public function __construct()
		{
				parent::__construct();
				parent::login();

				$this->auto_render = FALSE;

				if( !request::is_ajax() )
						die();

				$this->user->x = $this->input->get( 'x', $this->user->x );
				$this->user->y = $this->input->get( 'y', $this->user->y );
				$this->user->z = $this->input->get( 'z', $this->user->z );
				$this->user->region_id = $this->input->get( 'region', $this->user->region_id );
				$this->user->currentdirection_x = $this->input->get( 'currentdirection_x', $this->user->currentdirection_x );

				$this->item = Item_Model::instance();
		}

		/**
		 * Mise a jour d'un objet apres un drag n drop.
		 * 
		 * @param integer ID élément en drag
		 * @param integer	 D position ou on drop
		 * @param integer ID élément qui se trouve sur la position drop
		 * @param integer	 ID position initial de l'élémént qu'on drag
		 * @return  void
		 */
		public function move( $id_drag, $id_drop, $id_drag_old = FALSE, $id_drop_old = FALSE )
		{
				$this->item->user_update( array( 'item_position' => $id_drop ), $this->user->id, $id_drag );

				if( $id_drag_old !== FALSE && $id_drop_old !== FALSE )
						$this->item->user_update( array( 'item_position' => $id_drop_old ), $this->user->id, $id_drag_old );
		}

		/**
		 * Utiliser un objet.
		 * 
		 * @param integer	 ID de l'item
		 * @param bool Ajax ou non
		 * @return  void
		 */
		public function using( $item_id )
		{
				if( !$item_id )
						return FALSE;

				if( ($item = $this->item->select( $this->user->id, $item_id, 1 ) ) !== FALSE )
				{
						$result = FALSE;

						if( $item->hp )
						{
								$this->user->hp += $item->hp;

								if( $this->user->hp > $this->user->hp_max )
										$this->user->hp = $this->user->hp_max;

								$result['hp'] = $this->user->hp;

								$this->item->user_delete( $this->user->id, $item_id );
						}

						if( $item->mp )
						{
								$this->user->mp += $item->mp;

								if( $this->user->mp > $this->user->mp_max )
										$this->user->mp = $this->user->mp_max;

								$result['mp'] = $this->user->mp;

								$this->item->user_delete( $this->user->id, $item_id );
						}

						if( $result )
						{
								$this->user->update();

								History_Model::instance()->user_insert( $this->user->id, FALSE, $item_id, 'using_item' );

								echo json_encode( $result );
						}
				}
		}

		/**
		 * Supprimer un objet.
		 * 
		 * @param integer ID item
		 * @return  void
		 */
		public function delete( $item_id )
		{
				if( !$item_id )
						return FALSE;

				$this->item->user_delete( $this->user->id, $item_id );
		}

		/**
		 * Placer un équipement sur le personnage.
		 * 
		 * @param integer ID item
		 * @return  void
		 */
		public function equiper( $item_id )
		{
				if( !$item_id )
						return FALSE;

				if( ($item = $this->item->select( $this->user->id, $item_id, 1 )) !== FALSE )
				{
						if( ($items = $this->item->select( $this->user->id )) !== FALSE )
								foreach( $items as $row )
										if( $row->position == $item->position )
												$this->item->user_update( array( 'using' => 0 ), $this->user->id, $row->item_id );

						$this->item->user_update( array( 'using' => 1 ), $this->user->id, $item->id );

						echo json_encode( $item );

						if( $item->position == 1 )
						{
								$this->user->item_id_handRight = $item->id;
								$this->user->update();
						}
						elseif( $item->position == 2 )
						{
								$this->user->item_id_handLeft = $item->id;
								$this->user->update();
						}
				}
		}

		/**
		 * Retirer un equipement du personnage.
		 * 
		 * @param integer ID item
		 * @return  void
		 */
		public function desequiper( $item_id )
		{
				if( !$item_id )
						return FALSE;

				if( ($item = $this->item->select( $this->user->id, $item_id, 1 ) ) !== FALSE )
						$this->item->user_update( array( 'using' => 0 ), $this->user->id, $item->id );

				echo json_encode( $item );
				if( $item->position == 1 )
				{
						$this->user->item_id_handRight = '';
						$this->user->update();
				}
				elseif( $item->position == 2 )
				{
						$this->user->item_id_handLeft = '';
						$this->user->update();
				}
		}

}

?>