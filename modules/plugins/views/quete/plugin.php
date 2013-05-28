<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' );  ?>
<?php if( $data->image ) : ?>
		<div class="avatarAction" id="avatarAction" style="background-image:url('<?php echo url::base(); ?>images/modules/<?php echo $data->image; ?>');"></div>
<?php endif ?>
<div class="contenerActionStat">
		<h1><?php echo $data->title; ?>
				<?php if( $admin ) : ?>
						<a href="<?php echo url::base(); ?>admin/index.php/elements/show/<?php echo $data->id; ?>"  title="<?php	echo	Kohana::lang(	'form.edit'	);	?>" target="blank"><img src="<?php echo url::base(); ?>images/orther/edit.png"  alt="<?php	echo	Kohana::lang(	'form.edit'	);	?>"/></a>
				<?php endif; ?></h1>
		<h3><?php echo $list_quete->title; ?>
				<?php if( $admin ) : ?>
						<a href="<?php echo url::base(); ?>admin/index.php/quetes/show/<?php echo $list_quete->id_quete; ?>"  title="<?php	echo	Kohana::lang(	'form.edit'	);	?>" target="blank"><img src="<?php echo url::base(); ?>images/orther/edit.png"  alt="<?php	echo	Kohana::lang(	'form.edit'	);	?>"/></a>
				<?php endif; ?></h3>
</div>
<div class="center">
		<?php
		$url = 'actions/quete/show/'.$list_quete->id_quete;

		switch( $list_quete->valid )
		{
				case 0 :
						$value = Kohana::lang( 'quete.look_show' );
						break;
				case 1 :
						$value = Kohana::lang( 'quete.actif_quete' );
						break;
				case 2 :
						$url = 'actions/quete/valid/'.$list_quete->id_quete;
						$value = Kohana::lang( 'quete.valide_quete' );
						break;
				case 3 :
						$value = Kohana::lang( 'quete.stop_quete' );
						break;
		}
		?>
		<input type="button" class="button button_vert" onclick="overlay.load( '<?php echo $url; ?>' )" value="<?php echo $value; ?>"/>
</div>
<div class="spacer"></div>