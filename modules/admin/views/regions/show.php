<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<article class="module width_3_quarter">
		<header><h3 class="tabs_involved"><?php echo $row->name; ?> - <a href="<?php echo url::base( TRUE ); ?>mapping/panel/<?php echo $row->id; ?>" ><?php echo Kohana::lang( 'region.edit_map' ); ?></a></h3>
		</header>
		<div class="module_content">
				<p class="form-line">
						<label for="name" class="form-label"><?php echo Kohana::lang( 'region.name' ); ?> :</label>
						<input name="name" id="name" value="<?php echo $row->name; ?>" class="inputbox input-text" type="text" maxlength="50" />
				</p>
				<p class="form-line">
						<label for="comment" class="form-label"><?php echo Kohana::lang( 'region.desc' ); ?> : <span class="p-lower"><?php echo Kohana::lang( 'form.minus' ); ?></span></label>
						<textarea name="comment" id="comment" class="inputbox input-textarea" style="height:100px;"><?php echo $row->comment; ?></textarea>
				</p>
				<p class="form-line">
						<label for="id_parent" class="form-label"><?php echo Kohana::lang( 'region.parent_map' ); ?> :</label>
						<select name="id_parent" id="id_parent" class="inputbox" >
								<option value="" ><?php echo Kohana::lang( 'region.prim_map' ); ?></option>
								<?php if( $listing ) : ?>
										<?php foreach( $listing as $val ) : ?>
												<option value="<?php echo $val->id; ?>" <?php
								if( $val->id == $row->id_parent )
										echo 'selected="selected"';
												?> <?php
								if( $val->id == $row->id )
										echo 'disabled="disabled""';
												?> style="padding-left:<?php echo $val->level * 12; ?>px;"><?php echo $val->name; ?></option>
														<?php endforeach ?>
												<?php endif ?>
						</select>
				</p>
				<p class="form-line">
						<label for="x" class="form-label"><?php echo Kohana::lang( 'region.nbr_case' ); ?> X :</label>
						<input name="x" id="x" value="<?php echo $row->x; ?>" class="inputbox input-text" type="text" maxlength="50" />
				</p>
				<p class="form-line">
						<label for="y" class="form-label"><?php echo Kohana::lang( 'region.nbr_case' ); ?> Y :</label>
						<input name="y" id="y" value="<?php echo $row->y; ?>" class="inputbox input-text" type="text" maxlength="50" />
				</p>
				<p class="form-line">
						<label for="z" class="form-label"><?php echo Kohana::lang( 'region.nbr_case' ); ?> Z :</label>
						<input name="z" id="z" value="<?php echo $row->z; ?>" class="inputbox input-text" type="text" maxlength="50" />
				</p>

				<p class="form-line">
						<label class="form-label"><?php echo Kohana::lang( 'region.image_bg' ); ?> :</label>
						<input type="button" id="list_vignette_background" class="button" value="<?php echo Kohana::lang( 'form.selected_list' ); ?>" />
						<input type="hidden" value="<?php echo $row->background; ?>" id="background" name="background"/>
				</p>

				<p class="form-line">
						<label for="music" class="form-label"><?php echo Kohana::lang( 'region.music' ); ?> :</label>
						<select name="music" id="music" class="inputbox" >
								<option value="" ><?php echo Kohana::lang( 'region.no_music' ); ?></option>
								<?php if( $music ) : ?>
										<?php foreach( $music as $val ) : ?>
												<option value="<?php echo $val; ?>" <?php
								if( $val == $row->music )
										echo 'selected="selected"';
												?> ><?php echo str_replace( '.ogg', '', str_replace( '_', ' ', $val ) ); ?></option>
														<?php endforeach ?>
												<?php endif ?>
						</select>
				</p>

				<p class="form-line">
						<label class="form-label"><?php echo Kohana::lang( 'region.image_fight' ); ?> :</label>
						<input type="button" id="list_vignette_fight" class="button" value="<?php echo Kohana::lang( 'form.selected_list' ); ?>" />
						<input type="hidden" value="<?php echo $row->fight_terrain; ?>" id="fight_terrain" name="fight_terrain"/>
				</p>

				<p class="form-line">
						<label for="music_fight" class="form-label"><?php echo Kohana::lang( 'region.music_fight' ); ?> :</label>
						<select name="music_fight" id="music_fight" class="inputbox" >
								<option value="" ><?php echo Kohana::lang( 'region.no_music' ); ?></option>
								<?php if( $music ) : ?>
										<?php foreach( $music as $val ) : ?>
												<option value="<?php echo $val; ?>" <?php
								if( $val == $row->music_fight )
										echo 'selected="selected"';
												?> ><?php echo str_replace( '.ogg', '', str_replace( '_', ' ', $val ) ); ?></option>
														<?php endforeach ?>
												<?php endif ?>
						</select>
				</p>
		</div>
</article>	
<article class="module width_quarter">
		<header><h3><?php echo Kohana::lang( 'form.info_sup' ); ?></h3></header>
		<div class="module_content">
				<div class="message">
						<div class="label">
								<label><?php echo Kohana::lang( 'region.id' ); ?> :</label>
								<?php echo $row->id; ?></div>
				</div>
		</div>
</article>
<article class="module width_3_quarter">
		<header><h3 class="tabs_involved"><?php echo Kohana::lang( 'region.panel_bot' ); ?></h3>
		</header>
		<div class="module_content">
				<p class="form-line">
						<label for="bot_niveau" class="form-label"><?php echo Kohana::lang( 'region.bot_niveau' ); ?> :</label>
						<select name="bot_niveau" id="bot_niveau" class="inputbox" >
								<?php for( $n = 0; $n <= 100; $n++ ) : ?>
										<option value="<?php echo $n; ?>" <?php echo ( $n == $row->bot_niveau ) ? 'selected="selected"' : ''; ?>><?php echo sprintf( '%02d', $n ); ?></option>
								<?php endfor ?>
						</select>
				</p>
				<p class="form-line">
						<label for="bot_hp_min" class="form-label"><?php echo Kohana::lang( 'region.bot_x_min', Kohana::lang( 'user.hp' ) ); ?> :</label>
						<input name="bot_hp_min" id="bot_hp_min" value="<?php echo $row->bot_hp_min; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_hp_max" class="form-label"><?php echo Kohana::lang( 'region.bot_x_max', Kohana::lang( 'user.hp' ) ); ?> :</label>
						<input name="bot_hp_max" id="bot_hp_max" value="<?php echo $row->bot_hp_max; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_mp_min" class="form-label"><?php echo Kohana::lang( 'region.bot_x_min', Kohana::lang( 'user.mp' ) ); ?> :</label>
						<input name="bot_mp_min" id="bot_mp_min" value="<?php echo $row->bot_mp_min; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_mp_max" class="form-label"><?php echo Kohana::lang( 'region.bot_x_max', Kohana::lang( 'user.mp' ) ); ?> :</label>
						<input name="bot_mp_max" id="bot_mp_max" value="<?php echo $row->bot_mp_max; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_argent_min" class="form-label"><?php echo Kohana::lang( 'region.bot_argent_min' ); ?> :</label>
						<input name="bot_argent_min" id="bot_argent_min" value="<?php echo $row->bot_argent_min; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_argent_max" class="form-label"><?php echo Kohana::lang( 'region.bot_argent_max' ); ?> :</label>
						<input name="bot_argent_max" id="bot_argent_max" value="<?php echo $row->bot_argent_max; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_xp_min" class="form-label"><?php echo Kohana::lang( 'region.bot_x_min', Kohana::lang( 'user.xp' ) ); ?> :</label>
						<input name="bot_xp_min" id="bot_xp_min" value="<?php echo $row->bot_xp_min; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_xp_max" class="form-label"><?php echo Kohana::lang( 'region.bot_x_max', Kohana::lang( 'user.xp' ) ); ?> :</label>
						<input name="bot_xp_max" id="bot_xp_max" value="<?php echo $row->bot_xp_max; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_attaque_min" class="form-label"><?php echo Kohana::lang( 'region.bot_x_min', Kohana::lang( 'user.attack' ) ); ?> :</label>
						<input name="bot_attaque_min" id="bot_attaque_min" value="<?php echo $row->bot_attaque_min; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_attaque_max" class="form-label"><?php echo Kohana::lang( 'region.bot_x_max', Kohana::lang( 'user.attack' ) ); ?> :</label>
						<input name="bot_attaque_max" id="bot_attaque_max" value="<?php echo $row->bot_attaque_max; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_defense_min" class="form-label"><?php echo Kohana::lang( 'region.bot_x_min', Kohana::lang( 'user.defense' ) ); ?> :</label>
						<input name="bot_defense_min" id="bot_defense_min" value="<?php echo $row->bot_defense_min; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="bot_defense_max" class="form-label"><?php echo Kohana::lang( 'region.bot_x_max', Kohana::lang( 'user.defense' ) ); ?>:</label>
						<input name="bot_defense_max" id="bot_defense_max" value="<?php echo $row->bot_defense_max; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
		</div>
</article>
<script>
		var name_required = "<?php echo Kohana::lang( 'form.name_required' ); ?>",
		name_minlength = "<?php echo Kohana::lang( 'form.name_minlength' ); ?>",
		name_maxlength = "<?php echo Kohana::lang( 'form.name_maxlength' ); ?>",
		comment_required = "<?php echo Kohana::lang( 'form.comment_required' ); ?>",
		comment_minlength = "<?php echo Kohana::lang( 'form.comment_minlength' ); ?>",
		comment_maxlength = "<?php echo Kohana::lang( 'form.comment_maxlength' ); ?>",
		x_required = "<?php echo Kohana::lang( 'form.x_required' ); ?>",
		x_numeric = "<?php echo Kohana::lang( 'form.x_numeric' ); ?>",
		x_min = "<?php echo Kohana::lang( 'form.x_min' ); ?>",
		x_max = "<?php echo Kohana::lang( 'form.x_max' ); ?>";
</script> 
