<?php	defined(	'SYSPATH'	)	OR	die(	'No direct access allowed.'	);	?>

<div class="row_form">
		<label><span class="titreSpanForm"><?php	echo	Kohana::lang(	'action.price_sleep'	);	?></span>
				<input type="text" class="input-text" name="prix" value="<?php	echo	isset(	$row->prix	)	?	$row->prix	:	false;	?>" size="20" maxlength="11" />
		</label>
		<div class="clear"></div>
</div>
