<?php	defined(	'SYSPATH'	)	OR	die(	'No direct access allowed.'	);	?>
<div class="row_form">
		<?php	echo	Code_Core::editeur(	'fonction',	isset(	$row->fonction	)	&&	$row->fonction	?	$row->fonction	:	'<?php ?>',	200	);	?>
		<input type="hidden" name="fixePhp" id="fixePhp" value="0" />
</div>