<?php

defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Controller public des métiers.
 *
 * @package Action
 * @author Pasquelin Alban
 * @copyright	 (c) 2011
 * @license http://www.openrpg.fr/license.html
 */
class Job_Controller extends Template_Controller {

		/**
		 * Page de détail d'un personnage
		 * 
		 * @return  void
		 */
		public function index()
		{
				$this->auto_render = FALSE;

				if( !request::is_ajax() || !$this->user )
						return FALSE;

				if( !$this->user->job_id )
				{
						$v = new View( 'job/selection' );
						$v->jobs = Job_Model::instance()->select();
						$v->render( TRUE );
						return;
				}

				$listItem = $listCouple = FALSE;

				$job = Job_Model::instance()->select( $this->user->job_id, 1 );

				if( ($items = Item_Model::instance()->select( $this->user->id ) ) !== FALSE )
						foreach( $items as $item )
								$listItem[$item->id] = $item;

				if( ($couples = Item_Model::instance()->link_select( $this->user->job_id, $this->user->niveau ) ) !== FALSE )
						foreach( $couples as $couple )
								if( isset( $listItem[$couple->items_id_one] ) && $listItem[$couple->items_id_one]->nbr >= $couple->nbr_one && isset( $listItem[$couple->items_id_two] ) && $listItem[$couple->items_id_two]->nbr >= $couple->nbr_two && !isset( $listCouple[$couple->items_id_two.'-'.$couple->items_id_one] ) )
										$listCouple[$couple->items_id_one.'-'.$couple->items_id_two] = $couple;

				$v = new View( 'job/index' );
				$v->job = $job;
				$v->my_item = $listItem;
				$v->couples = $listCouple;
				$v->render( TRUE );
		}

		/**
		 * choix du metier
		 * 
		 * @return  void
		 */
		public function select( $id = FALSE )
		{
				$this->auto_render = FALSE;

				if( !request::is_ajax() || !$this->user )
						return FALSE;

				$this->user->job_id = $id;
				$this->user->update();

				self::index();
		}

		/**
		 * executer un couplage
		 * 
		 * @return  void
		 */
		public function couple( $id = FALSE )
		{
				$this->auto_render = FALSE;

				if( !request::is_ajax() || !$this->user )
						return FALSE;

				if( ($couple = Item_Model::instance()->link_select_simple( array( 'items_link.id' => $id ), 1 ) ) )
				{
						$this->user->xp += $couple->items_link_xp;
						$this->user->update();

						for( $n = 0; $n < $couple->nbr_one; $n++ )
								Item_Model::instance()->user_delete( $this->user->id, $couple->items_id_one );

						for( $n = 0; $n < $couple->nbr_two; $n++ )
								Item_Model::instance()->user_delete( $this->user->id, $couple->items_id_two );

						if( ($position = Item_Model::instance()->select( $this->user->id, $couple->items_id_result, 1 ) ) )
						{
								if( $position->protect )
								{
										echo '<div class="alert_action">'.Kohana::lang( 'job.no_stuff').'</div>';

										self::index();
										return;
								}
								$position = $position->item_position;
						}
						else
								$position = 0;

						Item_Model::instance()->user_insert( $this->user->id, $couple->items_id_result, $position );
				}

				echo '<div class="alert_action">'.Kohana::lang( 'job.crea_item').'</div>';

				self::index();
		}

}

?>