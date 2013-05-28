<?php	defined(	'SYSPATH'	)	OR	die(	'No direct access allowed.'	);	?>

<?php	if(	$data->image	)	:	?>
		<div class="avatarAction" id="avatarAction" style="background-image:url('<?php	echo	url::base();	?>images/modules/<?php	echo	$data->image;	?>');"></div>
<?php	endif	?>
<div class="contenerActionStat">
		<h1><?php	echo	$article->title;	?>
		<?php if( $admin ) : ?>
						<a href="<?php echo url::base(); ?>admin/index.php/articles/show/<?php echo $article->id_article; ?>"  title="<?php	echo	Kohana::lang(	'form.edit'	);	?>" target="blank"><img src="<?php echo url::base(); ?>images/orther/edit.png"  alt="<?php	echo	Kohana::lang(	'form.edit'	);	?>"/></a>
				<?php endif; ?></h1>
		<?php	echo	article::edit_user($article->article, $username);	?> 
</div>
<div class="spacer"></div>
