<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Pour que l'utilisateur récupère du HP et MP.
 *
 * @package Action_sleep
 * @author Pasquelin Alban
 * @copyright (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Plugin_Sleep_Controller extends Action_Controller {

		/**
		 * Affiche l'alerte de présentation pour dormir.
		 * 
		 * @return  void
		 */
		public function index()
		{
				$v = new View( 'sleep/plugin' );
				$v->data = $this->data;
				$v->render( TRUE );
		}

		/**
		 * Traitement de l'action dormir.
		 * 
		 * @return  void
		 */
		public function show()
		{
				if( !$this->data->action_map->prix || ( is_numeric( $this->data->action_map->prix ) && $this->user->argent >= $this->data->action_map->prix ) )
				{
						$this->user->argent -= $this->data->action_map->prix;
						$this->user->hp = $this->user->hp_max;
						$this->user->mp = $this->user->mp_max;

						$this->user->update();

						History_Model::instance()->user_insert( $this->user->id, $this->data->id, FALSE, 'sleep' );

						echo Kohana::lang( 'sleep.yes_sleep' );
				}
				else
						echo Kohana::lang( 'sleep.no_money_sleep' );

				echo '<script>';
				echo 'scene.data.my.mp = '.$this->user->mp.';';
				echo 'scene.data.my.hp = '.$this->user->hp.';';
				echo 'scene.data.my.argent = '.$this->user->argent.';';
				echo '</script>';
		}

}

?>