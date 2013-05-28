<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<?php if( $data->image ) : ?>
		<div class="avatarAction" id="avatarAction" style="background-image:url('<?php echo url::base(); ?>images/modules/<?php echo $data->image; ?>');"></div>
<?php endif ?>
<div class="contenerActionStat">
		<?php if( $data->title ) : ?>
				<h1><?php echo $data->title; ?></h1>
		<?php endif ?>
		<p><?php echo Kohana::lang( 'shop.desc_shop' ); ?></p>
</div>
<div class="spacer"></div>
<div class="bontonActionRight center">
		<input type="button" class="button button_vert" onclick="overlay.load( 'actions/shop/show' )" value="<?php echo Kohana::lang( 'shop.button_look' ); ?>"/>
		<input type="button" class="button button_vert" onclick="overlay.load( 'actions/shop/sale' )" value="<?php echo Kohana::lang( 'shop.button_sale' ); ?>"/>
		<?php if( $admin ) : ?>
				<a href="<?php echo url::base(); ?>admin/index.php/elements/show/<?php echo $data->id; ?>"  title="<?php echo Kohana::lang( 'form.edit' ); ?>" target="blank"><img src="<?php echo url::base(); ?>images/orther/edit.png"  alt="<?php echo Kohana::lang( 'form.edit' ); ?>"/></a>
		<?php endif; ?>
</div>