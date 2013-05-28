<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>

<div class="row_form">
		<label> <span class="titreSpanForm"><?php echo Kohana::lang( 'action.choose_article' ); ?></span>
				<select name="id_article" class="input-select" >
						<option value="0">--</option>
						<?php if( $article ) : ?>
								<?php foreach( $article as $val ) : ?>
										<option value="<?php echo $val->id_article; ?>" <?php echo isset( $data->id_article ) && $val->id_article == $data->id_article ? 'selected="selected"' : ''; ?> ><?php echo $val->name; ?></option>
								<?php endforeach ?>
						<?php endif ?>
				</select>
		</label>
		<div class="clear"></div>
</div>
<div class="row_form">
		<label> <span class="titreSpanForm"><?php echo Kohana::lang( 'action.quete_object' ); ?></span>
				<select name="quete_id" class="input-select" >
						<option value="0">--</option>
						<?php if( $quete ) : ?>
								<?php foreach( $quete as $val ) : ?>
										<option value="<?php echo $val->id_quete; ?>" <?php echo isset( $data->quete_id ) && $data->quete_id == $val->id_quete ? 'selected="selected"' : ''; ?>><?php echo $val->title; ?></option>
								<?php endforeach ?>
						<?php endif ?>
				</select>
		</label>
		<div class="clear"></div>
</div>
<div class="row_form">
		<label><span class="titreSpanForm"><?php echo Kohana::lang( 'editeur.form_label_image' ); ?></span>
				<select name="image" id="image" class="input-select" >
						<option value="0" >--</option>
						<?php if( $images ) : ?>
								<?php foreach( $images as $val ) : ?>
										<option value="<?php echo $val; ?>" <?php echo isset( $row->image ) && $val == $row->image ? 'selected="selected"' : ''; ?>><?php echo $val; ?></option>
								<?php endforeach ?>
						<?php endif ?>
				</select>
		</label>
		<div class="spacer"></div>
</div>
<div class="row_form">
		<label> <span class="titreSpanForm"><?php echo Kohana::lang( 'action.object_select' ); ?></span>
				<select id="items" name="items[]" size="5" multiple="multiple" class="inputbox" >
						<?php if( $items ) : ?>
								<?php foreach( $items as $val ) : ?>
										<option value="<?php echo $val->id; ?>" <?php echo isset( $data->items ) && is_array( $data->items ) && in_array( $val->id, $data->items ) ? 'selected="selected"' : ''; ?> ><?php echo $val->name; ?></option>
								<?php endforeach ?>
						<?php endif ?>
				</select>
		</label>
		<div class="clear"></div>
</div>