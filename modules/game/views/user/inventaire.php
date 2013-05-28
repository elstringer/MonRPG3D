<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>

<div id="content_inventaire">
		<div id="content_sup_inventaire"><div id="user_xp_detail"><span class="valueMoyenneGraph"></span>/<span class="valueMaxGraph"></span></div></div>
		<div id="user_xp"><?php echo graphisme::BarreGraphique( $user->xp, $niveau_suivant, 770 ); ?></div>
		<div id="btn_item_inventaire" class="button_menu_inventaire"></div>
		<div id="btn_stuff_inventaire" class="button_menu_inventaire"></div>
		<div id="btn_key_inventaire" class="button_menu_inventaire"></div>
		<div id="btn_job_inventaire" class="button_menu_inventaire"></div>
		<?php for( $n = 8; $n >= 1; $n-- ) : ?>
				<div id="btn_sort_<?php echo $n ?>" class="button_menu_inventaire launch_sort_inventaire"></div>
		<?php endfor ?>
</div>