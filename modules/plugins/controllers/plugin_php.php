<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Afficher et faire tourné du PHP sur la map.
 *
 * @package Action_php
 * @author Pasquelin Alban
 * @copyright (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Plugin_Php_Controller extends Action_Controller {

		/**
		 * Permet de placer du php dans l'alerte.
		 * 
		 * @return  void
		 */
		public function index()
		{
				if( $this->data->fonction )
						eval( '?>'.$this->data->fonction.'<?php' );
		}

}

?>