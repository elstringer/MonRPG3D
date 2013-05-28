<?php

defined( 'SYSPATH' ) OR die( 'No direct access allowed.' );

class Sorts_Controller extends Template_Controller {

		private $sort;

		public function __construct()
		{
				parent::__construct();
				parent::access( 'sort' );

				$this->sort = Sort_Model::instance();
		}

		/**
		 * Methode : page de listing générale
		 */
		public function index()
		{
				$this->script = array( 'js/lib/jquery.dataTables', 'js/listing' );

				$this->template->titre = Kohana::lang( 'sort.all_sorts' );

				$this->template->contenu = new View( 'sorts/list' );
		}

		/**
		 * Methode : page de détail d'une user
		 */
		public function show( $idSort = false )
		{
				if( !$idSort || !is_numeric( $idSort ) )
						return parent::redirect_erreur( 'sorts' );

				if( !$sort = $this->sort->select( array( 'id' => $idSort ), 1 ) )
						return parent::redirect_erreur( 'sorts' );

				$this->script = array( 'js/lib/jquery.validate', 'js/lib/jquery.facebox', 'js/sorts' );

				$this->css = array( 'form', 'sort', 'facebox' );

				$this->template->titre = array( Kohana::lang( 'sort.all_sorts' ) => 'sorts', Kohana::lang( 'sort.show_name', ucfirst( mb_strtolower( $sort->name ) ) ) => NULL );

				$this->template->button = TRUE;

				$this->template->navigation = parent::navigation( $idSort, 'id', 'sorts' );

				$this->template->contenu = new View( 'formulaire/form' );
				$this->template->contenu->action = 'sorts/save';
				$this->template->contenu->id = $idSort;
				$this->template->contenu->formulaire = new View( 'sorts/show' );
				$this->template->contenu->formulaire->row = $sort;
		}

		/**
		 * Methode : page qui va ajouter une ligne dans la BD et renvois vers la page détail
		 */
		public function insert()
		{
				$this->auto_render = FALSE;

				$idSort = $this->sort->insert( array( 'name' => 'sort '.time() ) );

				return url::redirect( 'sorts/show/'.$idSort.'?msg='.urlencode( Kohana::lang( 'form.crea_valide' ) ) );
		}

		/**
		 * Méthode : page qui gère la sauvegarde ou le delete avec un renvois soit au détail ou listing
		 */
		public function save( $type = FALSE, $idItem = FALSE )
		{
				$this->auto_render = FALSE;

				if( ($save = $this->input->post() ) !== FALSE )
				{
						if( isset( $save['effect'] ) && $save['effect'] )
								$save['effect'] = str_replace( '.png', '', $save['effect'] );

						if( $type == 'sauve' || $type == 'valid' )
								$this->sort->update( $save, $idItem );
						elseif( $type == 'trash' )
								$this->sort->delete( $idItem );
				}

				$url = 'sorts/show/'.$idItem;

				if( $type == 'annul' || $type == 'valid' || $type == 'trash' )
						$url = 'sorts';

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

				$arrayCol = array( 'id', 'image', 'name', 'niveau', 'attack_min', 'attack_max', 'prix' );

				$searchAjax = Search_Model::instance();

				$arrayResultat = $searchAjax->indexRecherche( $arrayCol, 'sorts', $this->input );

				$display = false;

				foreach( $arrayResultat as $row )
				{
						$url = 'sorts/show/'.$row->id;

						$v[] = '<center>'.$row->id.'</center>';
						$v[] = '<center><img src="'.url::base().'../images/sorts/'.$row->image.'" width="24" height="24" id="imageSort" class="imageSort" /></center>';
						$v[] = html::anchor( $url, $row->name );
						$v[] = number_format( $row->niveau );
						$v[] = $row->attack_min;
						$v[] = $row->attack_max;
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
				$v->images = file::listing_dir( DOCROOT.'../images/sorts' );
				$v->selected = $img;
				$v->module = 'sorts';
				$v->width = 40;
				$v->height = 40;
				$v->render( true );
		}

}

?>
