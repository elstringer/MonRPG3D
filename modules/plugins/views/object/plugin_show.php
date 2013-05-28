<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>

<?php if( $article ) : ?>
		<h2><?php echo $article->title; ?></h2>
		<?php $article = explode( '<hr id="system-readmore" />', $article->article ); ?>
		<?php if( $article && count( $article ) > 1 ) : ?>
				<div class="coda-slider-wrapper">
						<div class="coda-slider preload" id="coda-slider-1">
								<?php foreach( $article as $key => $row ) : ?>
										<div class="panel">
												<div class="panel-wrapper">
														<div class="titleCoda"><?php echo Kohana::lang( 'article.page', (1 + $key ) ); ?></div>
														<div><?php echo $row; ?></div>
												</div>
										</div>
								<?php endforeach ?>
						</div>
				</div>
		<?php else : ?>
				<?php echo $article[0]; ?>
		<?php endif ?>
<?php endif ?>
<?php if( $list_object ) : ?>
		<?php foreach( $list_object as $key => $row ) : ?>
				<?php if( !isset( $list_object_user[$row->id] ) || $list_object_user[$row->id]->cle == 1 ) : ?>
						<table width="95%">
								<tr>
										<?php if( $admin ) : ?>
												<td valign="top" width="30"><a href="<?php echo url::base(); ?>admin/index.php/items/show/<?php echo $row->id; ?>"  title="<?php echo Kohana::lang( 'form.edit' ); ?>" target="blank"><img src="<?php echo url::base(); ?>images/orther/edit.png"  alt="<?php echo Kohana::lang( 'form.edit' ); ?>"/></a></td>
										<?php endif; ?>
										<td valign="top" width="30"><img src="<?php echo url::base(); ?>images/items/<?php echo $row->image; ?>" width="24" height="24" class="imgItem" id="objet_<?php echo $row->id; ?>"/></td>
										<td><h2>
														<?php echo $row->name; ?> 
												</h2>
												<p><?php echo $row->comment; ?></p></td>
										<td valign="top" align="right">
												<?php if( isset( $list_object_user[$row->id] ) && $list_object_user[$row->id]->cle == 1 ) : ?>
														<b class="rouge"><?php echo Kohana::lang( 'object.you_have_cle' ); ?></b>
												<?php else : ?>
														<input type="hidden" name="id_object" value="<?php echo $row->id; ?>" />
														<input type="button" class="button button_vert ramasser" value="<?php echo Kohana::lang( 'object.button_take_object' ); ?>"/>
												<?php endif ?>
										</td>
								</tr>
						</table>
				<?php endif ?>
		<?php endforeach ?>
		<?php
 endif ?>