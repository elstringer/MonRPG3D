<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Controller public des users.
 *
 * @package Action
 * @author Pasquelin Alban
 * @copyright	 (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class User_Controller extends Template_Controller {

		public function __construct()
		{
				parent::__construct();
				parent::login();

				$this->auto_render = FALSE;
		}

		/**
		 * Page de détail du user
		 * 
		 * @return  void
		 */
		public function index()
		{
				$v = new View( 'user/profil' );
				$v->user = $this->user;
				$v->render( TRUE );
		}

		/**
		 * Page de détail des items du user
		 * 
		 * @return  void
		 */
		public function item()
		{
				$list = FALSE;

				if( ($items = Item_Model::instance()->select( $this->user->id ) ) !== FALSE )
						foreach( $items as $item )
								if( !$item->cle && !$item->protect )
										if( !isset( $list[$item->item_position] ) )
												$list[$item->item_position] = $item;
										else
												for( $n = 0; $n < 10; $n++ )
														if( !isset( $list[$n] ) )
														{
																$list[$n] = $item;
																break;
														}

				$v = new View( 'user/item' );
				$v->items = $list;
				$v->title = 'ITEM';
				$v->id_js = 'item';
				$v->render( TRUE );
		}

		/**
		 * Page de détail des items du user
		 * 
		 * @return  void
		 */
		public function stuff()
		{
				$list = FALSE;

				if( ($items = Item_Model::instance()->select( $this->user->id ) ) !== FALSE )
						foreach( $items as $item )
								if( $item->protect )
										if( !isset( $list[$item->item_position] ) )
												$list[$item->item_position] = $item;
										else
												for( $n = 0; $n < 10; $n++ )
														if( !isset( $list[$n] ) )
														{
																$list[$n] = $item;
																break;
														}


				$v = new View( 'user/item' );
				$v->items = $list;
				$v->title = 'STUFF';
				$v->id_js = 'stuff';
				$v->render( TRUE );
		}

		/**
		 * Page de détail des items du user
		 * 
		 * @return  void
		 */
		public function key()
		{
				$list = FALSE;

				if( ($items = Item_Model::instance()->select( $this->user->id ) ) !== FALSE )
						foreach( $items as $item )
								if( $item->cle )
										if( !isset( $list[$item->item_position] ) )
												$list[$item->item_position] = $item;
										else
												for( $n = 0; $n < 10; $n++ )
														if( !isset( $list[$n] ) )
														{
																$list[$n] = $item;
																break;
														}


				$v = new View( 'user/item' );
				$v->items = $list;
				$v->title = 'KEY';
				$v->id_js = 'key';
				$v->render( TRUE );
		}

		/**
		 * Sauvegarder le nouvel mot de passe d'un user
		 * 
		 * @return  void
		 */
		public function update_pwd()
		{
				$new_pwd = $this->input->post( 'new_pwd' );

				if( strlen( $new_pwd ) <= 4 )
						$msg = Kohana::lang( 'user.error_pwd' );
				else
				{
						$this->user->update( array( 'password' => Auth::instance()->hash_password( $new_pwd ) ) );
						$msg = Kohana::lang( 'user.valid_change_pwd' );
				}
		}

		/**
		 * Page de détail d'un personnage
		 * 
		 * @return  void
		 */
		public function show( $type )
		{
				$v = new View( 'user/'.$type );
				$v->stats = Statistiques_Model::instance()->user_show( $this->user->id );
				$v->user = $this->user;
				$v->modif = TRUE;

				$v->render( TRUE );
		}

		public function update( $noScript = false )
		{
				$this->user->x = $this->input->get( 'x', $this->user->x );
				$this->user->y = $this->input->get( 'y', $this->user->y );
				$this->user->z = $this->input->get( 'z', $this->user->z );
				$this->user->region_id = $this->input->get( 'region', $this->user->region_id );
				$this->user->currentdirection_x = $this->input->get( 'currentdirection_x', $this->user->currentdirection_x );
				$this->user->gravity = $this->input->get( 'gravity', $this->user->gravity );
				$this->user->speed = $this->input->get( 'speed', $this->user->speed );
				$this->user->hp_max = $this->input->get( 'hpMax', $this->user->hp_max );
				$this->user->hp = $this->input->get( 'hp', $this->user->hp );
				$this->user->mp_max = $this->input->get( 'mpMax', $this->user->mp_max );
				$this->user->mp = $this->input->get( 'mp', $this->user->mp );
				$this->user->niveau = $this->input->get( 'niveau', $this->user->niveau );
				$this->user->xp = $this->input->get( 'xp', $this->user->xp );
				$this->user->argent = $this->input->get( 'argent', $this->user->xp );
				$this->user->update();

				if( !$noScript )
				{
						echo '{};';
						echo 'scene.data.my.x = '.$this->user->x.';'."\n";
						echo 'scene.data.my.y = '.$this->user->y.';'."\n";
						echo 'scene.data.my.z = '.$this->user->z.';'."\n";
						echo 'scene.data.my.region = '.$this->user->region_id.';'."\n";
						echo 'scene.data.my.argent = '.$this->user->argent.';'."\n";
						echo 'scene.data.my.gravity = '.$this->user->gravity.';'."\n";
						echo 'scene.data.my.speed = '.$this->user->speed.';'."\n";
						echo 'scene.data.my.hp = '.$this->user->hp.';'."\n";
						echo 'scene.data.my.hp_max = '.$this->user->hp_max.';'."\n";
						echo 'scene.data.my.mp = '.$this->user->mp.';'."\n";
						echo 'scene.data.my.mp_max = '.$this->user->mp_max.';'."\n";
						echo 'scene.data.my.niveau = '.$this->user->niveau.';'."\n";
						echo 'scene.data.my.xp = '.$this->user->xp.';'."\n";
						echo 'scene.data.my.xpMax = '.$this->user->niveau_suivant().';'."\n";
				}
		}

}

?>