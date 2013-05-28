<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<section id="content_info_user">
		<div id="user_niveau" class="jaune"><?php echo $user->niveau; ?></div>

		<div id="user_username"><?php echo $user->username; ?></div>

		<div id="user_hp"><?php echo graphisme::BarreGraphique( $user->hp, $user->hp_max, 155, Kohana::lang( 'user.hp' ) ); ?></div>

		<div id="user_mp"><?php echo graphisme::BarreGraphique( $user->mp, $user->mp_max, 155, Kohana::lang( 'user.mp' ) ); ?></div>
</section>
<div id="user_argent_content">
		<span class="titre"><?php echo Kohana::lang( 'user.money' ); ?> :</span> 
		<span id="user_argent"><?php echo number_format( $user->argent ).' '.Kohana::config( 'game.money' ); ?></span>
</div>
