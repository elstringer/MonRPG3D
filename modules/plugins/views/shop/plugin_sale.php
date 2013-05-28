<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<?php if( $listItem ) : ?>
		<form id="formBuy">
				<table width="500" class="table_shop">
						<?php foreach( $listItem as $row ) : ?>
										<tr>
												<?php if( $admin ) : ?>
														<td valign="top" width="30"><a href="<?php echo url::base(); ?>admin/index.php/items/show/<?php echo $row->id; ?>"  title="<?php	echo	Kohana::lang(	'form.edit'	);	?>" target="blank"><img src="<?php echo url::base(); ?>images/orther/edit.png"  alt="<?php	echo	Kohana::lang(	'form.edit'	);	?>"/></a></td>
												<?php endif; ?>
												<td width="30"><img src="<?php echo url::base(); ?>images/items/<?php echo $row->image; ?>" width="24" height="24" /></td>
												<td><label for="item_<?php echo $row->id; ?>"><span class="titreSpanForm">
																		<?php echo $row->name; ?>
																</span></label></td>
												<td align="left" width="100" class="<?php echo $row->hp || $row->attaque || $row->mp || $row->defense ? 'vert' : 'rouge'; ?>">
														<?php
														if( $row->hp )
																echo $row->hp.' '.Kohana::lang( 'user.hp' );
														else if( $row->attaque )
																echo $row->attaque.' '.Kohana::lang( 'user.attack' );

														if( ( $row->hp || $row->attaque ) && ( $row->mp || $row->defense ) )
																echo ' & ';

														if( $row->mp )
																echo $row->mp.' '.Kohana::lang( 'user.mp' );
														else if( $row->defense )
																echo $row->defense.' '.Kohana::lang( 'user.defense' );
														?>
												</td>
												<td align="right" width="70" class="orange"><span class="prix">
																<?php echo isset( $data->price ) && $data->price ? round( $row->prix - ( $row->prix * ( $data->price / 100 ) ) ) : $row->prix; ?>
														</span>
														<?php echo Kohana::config( 'game.money' ); ?></td>
												<td align="right" width="50"><?php if( $row->protect ) : ?>
																<select class="input-select select_item" name="item[<?php echo $row->id; ?>]" id="item_<?php echo $row->id; ?>">
																		<option value="0" class="rouge">--</option>
																		<option value="1" class="vert">1</option>
																</select>
														<?php else : ?>
																<select name="item[<?php echo $row->id; ?>]" id="item_<?php echo $row->id; ?>" class="input-select select_item" >
																		<option value="0">--</option>
																		<?php for( $n = 1; $n <= $row->nbr; $n++ ) : ?>
																				<option value="<?php echo $n; ?>">
																						<?php echo sprintf( '%02d', $n ); ?>
																				</option>
																		<?php endfor ?>
																</select>
														<?php endif ?></td>
										</tr>
						<?php endforeach ?>
						<tr>
								<td colspan="<?php echo $admin ? 4 : 3; ?>" align="right"><strong class="rouge">
												<?php echo Kohana::lang( 'shop.total' ); ?>
										</strong></td>
								<td align="right"><b><span id="prix_total">0</span></b>
										<?php echo Kohana::config( 'game.money' ); ?></td>
								<td></td>
						</tr>
				</table>
		</form>
		<div class="formButton">
				<input type="button" id="sale_item" class="button button_vert" value="<?php echo Kohana::lang( 'shop.button_sale' ); ?>"/>
				<input type="button" onclick="overlay.load( 'actions/shop' )" class="button button_rouge" value="<?php echo Kohana::lang( 'form.annul' ); ?>"/>
		</div>
		<?php endif	?>
