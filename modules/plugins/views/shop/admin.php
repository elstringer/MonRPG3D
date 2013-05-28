<?php	defined(	'SYSPATH'	)	OR	die(	'No direct access allowed.'	);	?>

<div class="row_form">
		<label><span class="titreSpanForm"><?php	echo	Kohana::lang(	'action.level_enter_boutique'	);	?></span>
				<select name="niveau_min" class="input-select" >
						<?php	for(	$n	=	0;	$n	<=	100;	$n++	)	:	?>
								<option value="<?php	echo	$n;	?>" <?php	echo	isset(	$data->niveau_min	)	&&	$data->niveau_min	==	$n	?	'selected="selected"'	:	'';	?>><?php	echo	sprintf(	'%02d',	$n	);	?></option>
						<?php	endfor	?>
				</select>
		</label>
		<div class="clear"></div>
</div>
<div class="row_form">
		<label><span class="titreSpanForm"><?php	echo	Kohana::lang(	'action.pourcent_sale'	);	?></span>
				<select name="price" class="input-select" >
						<?php	for(	$n	=	0;	$n	<=	50;	$n++	)	:	?>
								<option value="<?php	echo	$n;	?>" <?php	echo	isset(	$data->price	)	&&	$data->price	==	$n	?	'selected="selected"'	:	'';	?>><?php	echo	sprintf(	'%02d',	$n	);	?></option>
						<?php	endfor	?>
				</select>
		</label>
		<div class="clear"></div>
</div>
<div class="row_form">
		<label>
				<span class="titreSpanForm"><?php	echo	Kohana::lang(	'action.listing_object'	);	?></span>
				<select name="items[]" size="5" multiple="multiple" class="input-select input-select-multiple" >
						<?php	if(	$items	)	:	?>
								<?php	foreach(	$items	as	$val	)	:	?>
										<option value="<?php	echo	$val->id;	?>" <?php	echo	isset(	$data->items	)	&&	is_array(	$data->items	)	&&	in_array(	$val->id,	$data->items	)	?	'selected="selected"'	:	'';	?> ><?php	echo	$val->name;	?></option>
								<?php	endforeach	?>
						<?php	endif	?>
				</select>
		</label>
		<div class="clear"></div>
</div>
<p><?php	echo	Kohana::lang(	'action.alert_level_boutique'	);	?></p>