<?php	defined(	'SYSPATH'	)	OR	die(	'No direct access allowed.'	);	?>

<h2><?php	echo	$data->title;	?></h2>
<?php	if(	$data->article	)	:	?>
		<h3><?php	echo	$data->article->title;	?>
				<?php if( $admin ) : ?>
						<a href="<?php echo url::base(); ?>admin/index.php/quetes/show/<?php echo $data->id_quete; ?>"  title="<?php	echo	Kohana::lang(	'form.edit'	);	?>" target="blank"><img src="<?php echo url::base(); ?>images/orther/edit.png"  alt="<?php	echo	Kohana::lang(	'form.edit'	);	?>"/></a>
				<?php endif; ?></h3>
		<?php	$article	=	explode(	'<hr id="system-readmore" />',	$data->article->article	);	?>
		<?php	if(	$article	&&	count(	$article	)	>	1	)	:	?>
				<div class="coda-slider-wrapper">
						<div class="coda-slider preload" id="coda-slider-1">
								<?php	foreach(	$article	as	$key	=>	$row	)	:	?>
										<div class="panel">
												<div class="panel-wrapper">
														<div class="titleCoda"><?php	echo	Kohana::lang(	'article.page',	(1	+	$key	)	);	?></div>
														<div><?php	echo	article::edit_user(	$row,	$username	);	?></div>
												</div>
										</div>
								<?php	endforeach	?>
						</div>
				</div>
		<?php	else	:	?>
				<?php	echo	article::edit_user(	$article[0],	$username	);	?>
		<?php	endif	?>
<?php	endif	?>
<?php	if(	$data->info_stop	)	:	?>
		<div><?php	echo	Kohana::lang(	'quete.locate_quete'	);	?> <strong class="vert"><?php	echo	$data->info_stop->title;	?></strong></div>
<?php	else	:	?>
		<div><?php	echo	Kohana::lang(	'quete.how_stop_quete'	);	?></div>
<?php	endif	?>
<?php	if(	$data->type	==	0	&&	$data->items	)	:	?>
		<h3><?php	echo	Kohana::lang(	'quete.liste_object_quete'	);	?></h3>
		<p><?php	echo	Kohana::lang(	'quete.now_have_object',	number_format(	$data->nbr_objet	)	);	?></p>
		<ul>
				<?php	foreach(	$data->items	as	$row	)	:	?>
						<li><img src="<?php	echo	url::base();	?>images/items/<?php	echo	$row->image;	?>" width="24" height="24" id="imageItem" align="middle" style="margin-right:10px;"/> <?php	echo	number_format(	$data->nbr_objet	);	?> X <?php	echo	$row->name;	?></li>
				<?php	endforeach	?>
		</ul>
<?php	endif	?>
<div class="formButton">
		<input type="hidden" id="id_quete" value="<?php	echo	$data->id_quete;	?>"/>
		<?php	if(	$data->valid	==	0	)	:	?>
				<input type="button" id="accepter" class="button button_vert" value="<?php	echo	Kohana::lang(	'quete.button_accept'	);	?>"/>
		<?php	elseif(	$data->valid	==	1	)	:	?>
				<input type="button" id="annul" class="button button_rouge" value="<?php	echo	Kohana::lang(	'quete.button_stop'	);	?>"/>
		<?php	endif	?>
</div>