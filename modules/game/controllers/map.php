<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Controller public de la map. Pour afficher la map.
 *
 * @package	 Map
 * @author Pasquelin Alban
 * @copyright	 (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Map_Controller extends Authentic_Controller {

		private $region = FALSE;

		public function __construct()
		{
				parent::__construct();
				parent::login();

				$this->auto_render = FALSE;

				//	if( !request::is_ajax() )
				//		return FALSE;
		}

		/**
		 * Générer un JSON
		 *
		 * @return void
		 */
		public function index( $render = TRUE )
		{
				$json = new View( 'map/json' );
				$json->my = $this->_dataMyUser();

				foreach( Region_Model::instance()->select() as $row )
				{
						$this->region = $row;
						$v = new stdClass;
						$v->map = $this->_dataMap();
						$v->bots = $this->_dataBots();
						$list[] = $v;
				}
				$json->regions = $list;
				$json->items = Item_Model::instance()->select();

				return $json->render( $render );
		}

		/**
		 * Data de la map.
		 * 
		 * @return  object
		 */
		private function _dataMap()
		{
				$elements = $modules = $items = FALSE;

				if( ($rows = Map_Model::instance()->select( array( 'region_id' => $this->region->id ), FALSE ) ) !== FALSE )
				{
						foreach( $rows as $row )
						{
								if( !$row->module_map )
										$elements[] = '{"x" : '.$row->x.', "z" : '.$row->z.', "y" : '.$row->y.', "materials" : [ "'.$row->background_px.'", "'.$row->background_nx.'", "'.$row->background_py.'", "'.$row->background_ny.'", "'.$row->background_pz.'", "'.$row->background_nz.'"	] }';
								else
								{
										$action = json_encode( @unserialize( $row->action_map ) );
										$modules[] = '{"x" : '.$row->x.', "z" : '.$row->z.', "y" : '.$row->y.', "data" : '.$action.' }';
								}
						}
				}

				$v = new stdClass;
				$v->region = $this->region;
				$v->elements = $elements ? implode( ',', $elements ) : FALSE;
				$v->modules = $modules ? implode( ',', $modules ) : FALSE;
				$v->items = $items ? implode( ',', $items ) : FALSE;

				return $v;
		}

		/**
		 * Data de mon user.
		 * 
		 * @return  object
		 */
		private function _dataMyUser()
		{
				$list = $listStuffs = FALSE;

				if( ($stuffs = Sort_Model::instance()->user( $this->user->id ) ) !== FALSE )
						foreach( $stuffs as $stuff )
								if( $stuff->order )
										$list[$stuff->order] = $stuff;

				$listStuffs = array( 0 );

				for( $n = 1; $n < 9; $n++ )
						$listStuffs[] = isset( $list[$n] ) ? $list[$n] : 0;

				if( ($leftHand = Item_Model::instance()->select( false, $this->user->item_id_handLeft, 1 ) ) )
						$leftHand->image = $leftHand->image;

				if( ($rightHand = Item_Model::instance()->select( false, $this->user->item_id_handRight, 1 ) ) )
						$rightHand->image = $rightHand->image;

				$v = new stdClass;
				$v->user = $this->user;
				$v->user->x--;
				$v->user->y--;
				$v->user->z--;
				$v->stuff = json_encode( $listStuffs );
				$v->hand_left = $this->user->item_id_handLeft;
				$v->hand_right = $this->user->item_id_handRight;

				return $v;
		}

		/**
		 * Data des bots.
		 * 
		 * @return  object
		 */
		private function _dataBots()
		{
				$list = FALSE;

				$this->bot = Bot_Model::instance();

				$image = file::listing_dir( DOCROOT.'images/character' );

				$rows_sorts = Sort_Model::instance()->select( array( 'niveau <=' => $this->region->bot_niveau ) );

				$rows_items_attack = Item_Model::instance()->selectType( array( 'position' => 1, 'niveau <=' => $this->region->bot_niveau ) );
				$rows_items_defense = Item_Model::instance()->selectType( array( 'position' => 2, 'niveau <=' => $this->region->bot_niveau ) );

				// TODO Voir les armes bots

				$number_bot = rand( $this->region->bot_nbr_min, $this->region->bot_nbr_max );

				for( $n = 0; $n < $number_bot; $n++ )
				{
						if( rand( 0, 100 ) > Kohana::config( 'bot.pourcent_no_generate_bot' ) )
						{
								$list_sorts = FALSE;
								$hp = rand( $this->region->bot_hp_min, $this->region->bot_hp_max );
								$mp = rand( $this->region->bot_mp_min, $this->region->bot_mp_max );

								if( !$hp || !$mp )
										continue;

								if( $rows_sorts )
										foreach( $rows_sorts as $row_sorts )
												if( rand( 0, 1 ) && $row_sorts->mp <= $mp )
														$list_sorts[] = $row_sorts->id;

								$v = new stdClass;
								$v->id = $n;
								$v->x = rand( 1, $this->region->x - 1 );
								$v->y = rand( 1, $this->region->y - 1 );
								$v->z = rand( 1, $this->region->z - 1 );
								$v->region_id = $this->user->region_id;
								$v->user_id = $this->user->id;
								$v->image = $image[array_rand( $image )];
								$v->hp_max = $hp;
								$v->hp = $hp;
								$v->mp_max = $mp;
								$v->mp = $mp;
								$v->battle = rand(0,100) > 10 ? 1 : 0;
								$v->leak = rand(0,100);
								$v->attaque = rand( $this->region->bot_attaque_min, $this->region->bot_attaque_max );
								$v->defense = rand( $this->region->bot_defense_min, $this->region->bot_defense_max );
								$v->argent = rand( $this->region->bot_argent_min, $this->region->bot_argent_max );
								$v->xp = rand( $this->region->bot_xp_min, $this->region->bot_xp_max );
								$v->niveau = $this->region->bot_niveau;
								$v->sorts = $list_sorts ? implode( ',', $list_sorts ) : FALSE;
								$v->hand_left = $rows_items_defense ? $rows_items_defense[array_rand( $rows_items_defense )]->id : 0;
								$v->hand_right = $rows_items_attack ? $rows_items_attack[array_rand( $rows_items_attack )]->id : 0;

								$list[] = $v;
						}
				}

				return $list;
		}

}

?>