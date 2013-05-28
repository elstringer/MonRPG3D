<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>

<div class="row_form">
		<label>
				<span class="titreSpanForm"><?php echo Kohana::lang( 'plg_map.show_map' ); ?></span>
				<select name="id_region" class="input-select" >
						<?php if( $region ) : ?>
								<?php foreach( $region as $val ) : ?>
										<option value="<?php echo $val->id; ?>" <?php echo isset( $data->id_region ) && $val->id == $data->id_region ? 'selected="selected"' : ''; ?> style="padding-left:<?php echo $val->level * 12; ?>px;"><?php echo $val->name; ?></option>
								<?php endforeach ?>
						<?php endif ?>
				</select>
		</label>
		<div class="clear"></div>
</div>