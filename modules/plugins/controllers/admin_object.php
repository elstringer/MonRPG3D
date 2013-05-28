<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Changement des matires des sorts sur la map.
 *
 * @package Action_sort
 * @author Pasquelin Alban
 * @copyright (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Admin_Object_Controller extends Controller {

		/**
		 * Méthode :
		 */
		public function index( &$view )
		{
				$view->items = Item_Model::instance()->select();
				$view->article = Article_Model::instance()->select( array( 'article_category_id' => 2, 'status' => 1 ) );
				$view->quete = Quete_Model::instance()->select();
				$view->images = file::listing_dir( DOCROOT.'../images/items' );
		}

}

?>