<?php	defined(	'SYSPATH'	)	OR	die(	'No direct access allowed.'	);	?>


<article class="module width_3_quarter">
		<header><h3 class="tabs_involved"><?php echo Kohana::lang( 'form.title_default' ); ?></h3>
		</header>
		<div class="module_content">
		<p class="form-line">
				<label for="name" class="form-label"><?php	echo	Kohana::lang(	'item.name'	);	?> :</label>
				<input name="name" id="name" value="<?php	echo	$row->name;	?>" class="inputbox input-text" type="text" maxlength="50" />
		</p>
		<p class="form-line">
				<label for="comment" class="form-label"><?php	echo	Kohana::lang(	'item.desc'	);	?> : <span class="p-lower"><?php	echo	Kohana::lang(	'form.minus'	);	?></span></label>
				<textarea name="comment" id="comment" class="inputbox input-textarea" style="height:100px;"><?php	echo	$row->comment;	?></textarea>
		</p>	
		</div>
</article>
<article class="module width_quarter">
		<header><h3><?php echo $row->name; ?></h3></header>
		<div class="module_content">
				<div class="label">
						<label><?php	echo	Kohana::lang(	'item.id'	);	?> :</label>
						<?php	echo	$row->id;	?></div>
		</div>
</article>
<div class="spacer"></div>
<script>
		var name_required = "<?php	echo	Kohana::lang(	'form.name_required'	);	?>",
		name_minlength = "<?php	echo	Kohana::lang(	'form.name_minlength'	);	?>",
		name_maxlength = "<?php	echo	Kohana::lang(	'form.name_maxlength'	);	?>",
		comment_required = "<?php	echo	Kohana::lang(	'form.comment_required'	);	?>",
		comment_minlength = "<?php	echo	Kohana::lang(	'form.comment_minlength'	);	?>",
		comment_maxlength = "<?php	echo	Kohana::lang(	'form.comment_maxlength'	);	?>";
</script> 
