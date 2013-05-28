<?php

defined( 'SYSPATH' ) OR die( 'No direct access allowed.' );

class Jobs_Controller extends Template_Controller {

		public function __construct()
		{
				parent::__construct();
				parent::access( 'job' );

				$this->job = job_Model::instance();
		}

		/**
		 * Methode : page de listing générale
		 */
		public function index()
		{
				$this->script = array( 'js/lib/jquery.dataTables', 'js/listing' );

				$this->template->titre = Kohana::lang( 'job.all_jobs' );

				$this->template->contenu = new View( 'jobs/list' );
		}

		/**
		 * Methode : page de détail d'une user
		 */
		public function show( $idjob = false )
		{
				if( !$idjob || !is_numeric( $idjob ) )
						return parent::redirect_erreur( 'jobs' );

				if( !$job = $this->job->select( FALSE, $idjob, TRUE ) )
						return parent::redirect_erreur( 'jobs' );

				$this->script = array( 'js/lib/jquery.validate', 'js/lib/jquery.facebox', 'js/jobs' );

				$this->css = array( 'form', 'job', 'facebox' );

				$this->template->titre = array( Kohana::lang( 'job.all_jobs' ) => 'jobs', Kohana::lang( 'job.show_name', ucfirst( mb_strtolower( $job->name ) ) ) => NULL );

				$this->template->button = TRUE;

				$this->template->navigation = parent::navigation( $idjob, 'id', 'jobs' );

				$this->template->contenu = new View( 'formulaire/form' );
				$this->template->contenu->action = 'jobs/save';
				$this->template->contenu->id = $idjob;
				$this->template->contenu->formulaire = new View( 'jobs/show' );
				$this->template->contenu->formulaire->row = $job;
		}

		/**
		 * Methode : page qui va ajouter une ligne dans la BD et renvois vers la page détail
		 */
		public function insert()
		{
				$idjob = $this->job->insert( array( 'name' => 'job '.time() ) );

				return url::redirect( 'jobs/show/'.$idjob.'?msg='.urlencode( Kohana::lang( 'form.crea_valide' ) ) );
		}

		/**
		 * Méthode : page qui gère la sauvegarde ou le delete avec un renvois soit au détail ou listing
		 */
		public function save( $type = FALSE, $idjob = FALSE )
		{
				if( ($save = $this->input->post() ) !== FALSE )
				{
						if( $type == 'sauve' || $type == 'valid' )
								$this->job->update( $save, $idjob );
						elseif( $type == 'trash' )
								$this->job->delete( $idjob );
				}

				$url = 'jobs/show/'.$idjob;

				if( $type == 'annul' || $type == 'valid' || $type == 'trash' )
						$url = 'jobs';

				return parent::redirect( $url, $type );
		}

		/**
		 * Methode : gestion du listing en ajax
		 */
		public function resultatAjax()
		{
				$this->auto_render = FALSE;

				if( !request::is_ajax() )
						return FALSE;

				$arrayCol = array( 'id', 'name', 'quick', 'protect', 'niveau', 'prix' );

				$searchAjax = Search_Model::instance();

				$arrayResultat = $searchAjax->indexRecherche( $arrayCol, 'jobs', $this->input );

				$display = false;

				foreach( $arrayResultat as $row )
				{
						$url = 'jobs/show/'.$row->id;

						$v[] = '<center>'.$row->id.'</center>';
						$v[] = html::anchor( $url, $row->name );
						$v[] = $row->quick ? '<b class="vert">'.Kohana::lang( 'form.yes' ).'</b>' : '<b class="rouge">'.Kohana::lang( 'form.no' ).'</b>';
						$v[] = $row->protect ? '<b class="vert">'.Kohana::lang( 'form.yes' ).'</b>' : '<b class="rouge">'.Kohana::lang( 'form.no' ).'</b>';
						$v[] = number_format( $row->niveau );
						$v[] = number_format( $row->prix ).' '.Kohana::config( 'game.money' );
						$v[] = '<center>'.html::anchor( $url, html::image( 'images/template/drawings.png', array( 'title' => Kohana::lang( 'form.edit' ), 'class' => 'icon_list' ) ) ).'</center>';

						$display .= '['.parent::json( $v ).'],';

						unset( $v );
				}

				echo $searchAjax->displayRecherche( $display, $this->input->get( 'sEcho' ) );
		}

		/**
		 * Methode :
		 */
		public function vignette( $img = false )
		{
				$this->auto_render = FALSE;

				if( !request::is_ajax() )
						return FALSE;

				$v = new View( 'formulaire/vignette' );
				$v->images = file::listing_dir( DOCROOT.'../images/jobs' );
				$v->selected = $img;
				$v->module = 'jobs';
				$v->width = 24;
				$v->height = 24;
				$v->render( true );
		}

}

?>
