<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<?php if( $data->image ) : ?>
		<div class="avatarAction" id="avatarAction" style="background-image:url('<?php echo url::base(); ?>images/modules/<?php echo $data->image; ?>');"></div>
<?php endif ?>
<div class="contenerActionStat">
		<h1><?php echo Kohana::lang( 'object.title_object' ); ?> 
				<?php if( $admin ) : ?>
						<a href="<?php echo url::base(); ?>admin/index.php/elements/show/<?php echo $data->id; ?>"  title="<?php	echo	Kohana::lang(	'form.edit'	);	?>" target="blank"><img src="<?php echo url::base(); ?>images/orther/edit.png"  alt="<?php	echo	Kohana::lang(	'form.edit'	);	?>"/></a>
				<?php endif; ?></h1>
		<p><?php echo Kohana::lang( 'object.content_object' ); ?></p>
</div>
<div class="center">
		<input type="button" class="button button_vert show_object" onclick="overlay.load( 'actions/object/show' )"  value="<?php echo Kohana::lang( 'object.button_look' ); ?>"/>
</div>
<div class="spacer"></div>