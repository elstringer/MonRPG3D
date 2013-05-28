<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<div class="contenerActionStat">
		<h1><?php echo Kohana::lang( 'plg_map.look_map' ); ?></h1>
		<p><?php echo Kohana::lang( 'plg_map.look_desc' ); ?></p>
</div>
<div class="bontonActionRight">
		<input type="button" class="button button_vert show_map" onclick="overlay.load( 'actions/map/show' )" value="<?php echo Kohana::lang( 'plg_map.look_map' ); ?>"/>
</div>
<div class="spacer"></div>