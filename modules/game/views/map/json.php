<?php defined( "SYSPATH" ) OR die( "No direct access allowed." ); ?>
{
	"my" : {
		"id" : <?php echo $my->user->id; ?>,
		"username" : "<?php echo $my->user->username; ?>",
		"x" : <?php echo $my->user->x; ?>,
		"y" : <?php echo $my->user->y; ?>,
		"z" : <?php echo $my->user->z; ?>,
		"logins" : <?php echo $my->user->logins; ?>,
		"gravity" : <?php echo number_format($my->user->gravity, 2); ?>,
		"speed" : <?php echo $my->user->speed; ?>,
		"currentdirection_x" : <?php echo $my->user->currentdirection_x; ?>,
		"img" : "<?php echo $my->user->avatar; ?>",
		"hp" : <?php echo $my->user->hp; ?>,
		"hpMax" : <?php echo $my->user->hp_max; ?>,
		"mp" : <?php echo $my->user->mp; ?>,
		"mpMax" : <?php echo $my->user->mp_max; ?>,
		"niveau" : <?php echo $my->user->niveau; ?>,
		"argent" : <?php echo $my->user->argent; ?>,
		"xp" : <?php echo $my->user->xp; ?>,
		"xpMax" : <?php echo $my->user->niveau_suivant(); ?>,
		"region" : <?php echo $my->user->region_id; ?>,
		"attack" : 100,
		"defense" : 100,
		"sorts" : <?php echo $my->stuff; ?>, 
		"hand_left" : <?php echo $my->hand_left ? $my->hand_left : 0; ?>,
		"hand_right" : <?php echo $my->hand_right ? $my->hand_right : 0; ?>
	},
	"maps" : {
		<?php foreach( $regions as $keyRegion => $region ) : ?>
		"region_<?php echo $region->map->region->id; ?>" : {
			"map" : {
						"id" : "<?php echo $region->map->region->id; ?>",
						"materials" : "<?php echo str_replace('images/background/', '', $region->map->region->background); ?>",
						"colorBackground" : "<?php echo $region->map->region->background_color; ?>",
						"univers" :  "<?php echo str_replace('images/background/', '', $region->map->region->background_univers); ?>",
						"music" :  "<?php echo $region->map->region->music; ?>",
						"size" : {
								"elements" : 50,
								"xMin" : 0,"zMin" : 0,"yMin" : 0,
								"xMax" : <?php echo $region->map->region->x; ?>,"zMax" : <?php echo $region->map->region->z; ?>,"yMax" : <?php echo $region->map->region->y; ?>
						},
						"elements" :  [ <?php echo str_replace('images/background/', '', $region->map->elements); ?> ],
						"modules" :  [ <?php echo $region->map->modules; ?> ],
						"items" :  [ <?php echo $region->map->items; ?> ]
				},
			"bots" : { 
					"list" : [
					<?php if( $region->bots ) : ?>
							<?php foreach( $region->bots as $key => $region->bot ) : ?>
						 {
							"id" : <?php echo $region->bot->id; ?>,
							"x" : <?php echo $region->bot->x; ?>,
							"y" : <?php echo $region->bot->y; ?>,
							"z" : <?php echo $region->bot->z; ?>,
							"battle" : <?php echo $region->bot->battle; ?>,
							"leak" : <?php echo $region->bot->leak; ?>,
							"img" : "<?php echo $region->bot->image; ?>",
							"hp" : <?php echo $region->bot->hp; ?>,
							"hpMax" : <?php echo $region->bot->hp_max; ?>,
							"mp" : <?php echo $region->bot->mp; ?>,
							"mpMax" : <?php echo $region->bot->mp_max; ?>,
							"attack" : <?php echo $region->bot->attaque; ?>,
							"defense" : <?php echo $region->bot->defense; ?>, 
							"hand_left" : <?php echo $region->bot->hand_left ? $region->bot->hand_left : 0; ?>,
							"hand_right" : <?php echo $region->bot->hand_right ? $region->bot->hand_right : 0; ?>
						} <?php if( $key < count($region->bots) - 1) echo ","; ?>
							<?php endforeach ?>
					<?php endif ?>
				]
			}
		} <?php if( $keyRegion < count($regions) - 1) echo ","; ?>
		<?php endforeach ?>		
	},
	"items" : {
		<?php foreach( $items as $keyItem => $item ) : ?>
		"item_<?php echo $item->id; ?>" : <?php echo json_encode($item); ?><?php if( $keyItem < count($items) - 1) echo ","; ?>
		<?php endforeach ?>		
	}
}
