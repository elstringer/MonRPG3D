<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Commerçant acheter/vendre sur la map.
 *
 * @package Action_shop
 * @author Pasquelin Alban
 * @copyright (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Plugin_Shop_Controller extends Action_Controller {

		/**
		 * Affiche l'alerte de présentation d'un vendeur.
		 * 
		 * @return  void
		 */
		public function index()
		{
				$v = new View( 'shop/plugin' );
				$v->data = $this->data;
				$v->admin = in_array( 'admin', $this->role->name );
				$v->render( TRUE );
		}

		/**
		 * Affiche la liste des objets qu'on peut acheter.
		 * 
		 * @return  void
		 */
		public function show()
		{
				if( !isset( $this->data->action_map->items ) || !$this->data->action_map->items )
						return false;

				$item = Item_Model::instance();

				$listItem = $item->in( $this->data->action_map->items );

				$itemProtect = FALSE;

				if( ($items = $item->select( $this->user->id ) ) !== FALSE )
						foreach( $items as $row )
								if( $row->protect )
										$itemProtect[$row->id] = TRUE;

				$v = new View( 'shop/plugin_show' );
				$v->listItem = $listItem;
				$v->itemProtect = $itemProtect;
				$v->data = $this->data;
				$v->user = $this->user;
				$v->admin = in_array( 'admin', $this->role->name );
				$v->render( TRUE );
		}

		/**
		 * Traitement insertion des achats.
		 * 
		 * @return  void
		 */
		public function insert()
		{
				$txt = Kohana::lang( 'shop.no_buy' );

				if( ($list = $this->input->get( 'item' ) ) !== FALSE )
				{
						$listItem = Item_Model::instance()->in( $this->data->action_map->items );

						$somme = 0;
						$insert_list = FALSE;

						foreach( $listItem as $row )
								if( isset( $list[$row->id] ) && $list[$row->id] > 0 )
								{
										$insert_list[$row->id] = $list[$row->id];
										$somme += $list[$row->id] * $row->prix;
								}

						if( $somme <= $this->user->argent )
						{
								foreach( $insert_list as $key => $row )
										for( $n = 0; $n < $row; $n++ )
										{
												if( ($position = Item_Model::instance()->select( $this->user->id, $key, 1 ) ) )
														$position = $position->item_position;
												else
														$position = 0;

												Item_Model::instance()->user_insert( $this->user->id, $key, $position );
										}

								$this->user->argent -= $somme;
								$this->user->update();

								History_Model::instance()->user_insert( $this->user->id, $this->data->id, $somme, 'shop' );

								$txt = Kohana::lang( 'shop.total_buy', number_format( $somme ), Kohana::config( 'game.money' ) );
						}
				}

				echo $txt;
				
				echo '<script>';
				echo 'scene.data.my.argent = '.$this->user->argent.';';
				echo '</script>';
		}

		/**
		 * Affiche la liste des objets qu'on peut vendre.
		 * 
		 * @return  void
		 */
		public function sale()
		{
				$arrayStuff = FALSE;

				if( !isset( $this->data->action_map->items ) || !$this->data->action_map->items )
						return FALSE;

				if( !($items = Item_Model::instance()->select( $this->user->id ) ) !== FALSE )
				{
						echo 'Vous n\'avez aucun objet à vendre.';
						return FALSE;
				}

				foreach( $items as $stuff )
						if( !$stuff->cle )
								$arrayStuff[$stuff->item_id] = $stuff;

				$v = new View( 'shop/plugin_sale' );
				$v->listItem = $arrayStuff;
				$v->data = $this->data;
				$v->user = $this->user;
				$v->admin = in_array( 'admin', $this->role->name );
				$v->render( TRUE );
		}

		/**
		 * Traitement update des ventes.
		 * 
		 * @return  void
		 */
		public function update()
		{
				$txt = Kohana::lang( 'shop.no_sale' );

				if( ($list = $this->input->get( 'item' ) ) !== FALSE )
				{
						$listItem = Item_Model::instance()->select( $this->user->id );

						$somme = 0;
						$insert_list = FALSE;

						foreach( $listItem as $row )
								if( isset( $list[$row->id] ) && $list[$row->id] > 0 )
								{
										$insert_list[$row->id] = $list[$row->id];
										$somme += $list[$row->id] * ( isset( $this->data->action_map->price ) && $this->data->action_map->price ? round( $row->prix - ( $row->prix * ( $this->data->action_map->price / 100 ) ) ) : $row->prix );
								}

						if( $somme > 0 )
						{
								foreach( $insert_list as $key => $row )
										for( $n = 0; $n < $row; $n++ )
												Item_Model::instance()->user_delete( $this->user->id, $key );

								$this->user->argent += $somme;
								$this->user->update();

								History_Model::instance()->user_insert( $this->user->id, $this->data->id, $somme, 'sale' );

								$txt = Kohana::lang( 'shop.total_sale', number_format( $somme ), Kohana::config( 'game.money' ) );
								
								echo '<script>';
								echo 'scene.data.my.argent = '.$this->user->argent.';';
								echo '</script>';
						}
				}

				echo $txt;
		}

}

?>