<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>

<h1><?php echo $data->title ? $data->title : Kohana::lang( 'sort.title_view' ); ?></h1>
<?php if( $listSort ) : ?>
		<form id="formBuy">
				<table class="table_shop">
						<?php foreach( $listSort as $row ) : ?>
								<tr>
										<td width="40"><img src="<?php echo url::base(); ?>images/sorts/<?php echo $row->image; ?>" width="30" height="30" /></td>
										<td width="250"><label for="sort_<?php echo $row->id; ?>"><span class="titreSpanForm"><?php echo $row->name; ?></span></label></td>
										<td width="200" class="<?php echo $row->attack_max ? 'vert' : 'rouge'; ?>"><?php echo Kohana::lang( 'user.scr_attack' ).' '.$row->attack_min.'/'.$row->attack_max; ?> pt(s)</td>
										<td align="right" width="70" class="<?php echo $row->mp ? 'vert' : 'rouge'; ?>"><?php echo $row->mp.' '.Kohana::lang( 'user.mp' ); ?></td>
										<td align="right" width="70" class="orange"><span class="prix"><?php echo $row->prix; ?></span> <?php echo Kohana::config( 'game.money' ); ?></td>
										<td align="right" >
												<?php if( $row->niveau <= $user->niveau && !isset( $sortListUser[$row->id] ) ) : ?>
														<select class="input-select select_sort" name="sort[<?php echo $row->id; ?>]">
																<option value="0" class="rouge">--</option>
																<option value="1" class="vert">1</option>
														</select>
												<?php endif ?>
										</td>
								</tr>
						<?php endforeach ?>
						<tr>
								<td colspan="5" align="right"><strong class="rouge"><?php echo Kohana::lang( 'sort.total' ); ?></strong></td>
								<td align="right"><span id="prix_total">0</span> <?php echo Kohana::config( 'game.money' ); ?></td>
						</tr>
				</table>
		</form>
		<div class="formButton" id="show_buy" style="display:none">
				<input type="hidden" id="argent_user" value="<?php echo $user->argent; ?>"/>
				<input type="button" id="buy_sort" class="button button_vert" value="<?php echo Kohana::lang( 'sort.title_view' ); ?>"/>
		</div>
<?php endif	?>