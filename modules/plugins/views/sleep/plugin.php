<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<?php if( $data->image ) : ?>
		<div class="avatarAction" id="avatarAction" style="background-image:url('<?php echo url::base(); ?>images/modules/<?php echo $data->image; ?>');"></div>
<?php endif ?>
<div class="contenerActionStat">
		<h1><?php echo Kohana::lang( 'sleep.title_view' ); ?></h1>
		<p><?php echo Kohana::lang( 'sleep.desc_sleep' ); ?>
				<?php if( $data->action_map->prix ) : ?>
						<?php echo Kohana::lang( 'sleep.price_sleep' ); ?> <strong class="rouge"><?php echo number_format( $data->action_map->prix ).' '.Kohana::config( 'game.money' ); ?></strong>
				<?php endif ?>
		</p>
</div>
<div class="spacer"></div>
<div class="center">
		<input type="button" class="button button_vert" onclick="overlay.load( 'actions/sleep/show' )" value="<?php echo Kohana::lang( 'sleep.action_sleep' ); ?>"/>
</div>
<div class="spacer"></div>