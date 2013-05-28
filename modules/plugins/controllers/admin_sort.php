<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Changement des marchands sur la map.
 *
 * @package Action_shop
 * @author Pasquelin Alban
 * @copyright (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Admin_Sort_Controller extends Controller {

		/**
		 * Méthode :
		 */
		public function index( &$view )
		{
				$view->sorts = Sort_Model::instance()->select();
		}

}

?>