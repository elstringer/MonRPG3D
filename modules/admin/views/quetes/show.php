<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>

<article class="module width_full relative">
		<header>
				<h3 class="tabs_involved"><?php echo $row->title; ?></h3>
		</header>
		<div class="module_content">
				<p class="form-line">
						<label for="title" class="form-label"><?php echo Kohana::lang( 'quete.title' ); ?> :</label>
						<input name="title" id="title" value="<?php echo $row->title; ?>" class="inputbox input-text" type="text" maxlength="50" />
				</p>
				<p class="form-line">
						<label for="status" class="form-label"><?php echo Kohana::lang( 'quete.status' ); ?> :</label>
						<select class="inputbox" name="status" id="status">
								<option value="1" class="vert"><?php echo Kohana::lang( 'form.yes' ); ?></option>
								<option value="0" class="rouge" <?php
if( !$row->status )
		echo 'selected="selected"';
?>><?php echo Kohana::lang( 'form.no' ); ?></option>
						</select>
				</p>
				<p class="form-line">
						<label for="element_detail_id_start" class="form-label"><?php echo Kohana::lang( 'quete.element_start' ); ?> :</label>
						<select name="element_detail_id_start" id="element_detail_id_start" class="inputbox" style="width:320px;">
								<?php if( $module ) : ?>
										<?php foreach( $module as $val ) : ?>
												<option value="<?php echo $val->id; ?>" <?php echo ( $val->id == $row->element_detail_id_start ) ? 'selected="selected"' : ''; ?>><?php echo $val->title; ?></option>
										<?php endforeach ?>
								<?php endif ?>
						</select>
				</p>
				<p class="form-line">
						<label for="element_detail_id_stop" class="form-label"><?php echo Kohana::lang( 'quete.element_stop' ); ?> :</label>
						<select name="element_detail_id_stop" id="element_detail_id_stop" class="inputbox" style="width:320px;">
								<?php if( $module ) : ?>
										<?php foreach( $module as $val ) : ?>
												<option value="<?php echo $val->id; ?>" <?php echo ( $val->id == $row->element_detail_id_stop ) ? 'selected="selected"' : ''; ?>><?php echo $val->title; ?></option>
										<?php endforeach ?>
								<?php endif ?>
						</select>
				</p>
				<p class="form-line">
						<label for="article_id_start" class="form-label"><?php echo Kohana::lang( 'quete.article_start' ); ?> :</label>
						<select name="article_id_start" id="article_id_start" class="inputbox" style="width:320px;">
								<option value="0">--</option>
								<?php if( $articles ) : ?>
										<?php foreach( $articles as $val ) : ?>
												<option value="<?php echo $val->id_article; ?>" <?php echo ( $val->id_article == $row->article_id_start ) ? 'selected="selected"' : ''; ?>><?php echo $val->title; ?></option>
										<?php endforeach ?>
								<?php endif ?>
						</select>
				</p>
				<p class="form-line">
						<label for="article_id_stop" class="form-label"><?php echo Kohana::lang( 'quete.article_stop' ); ?> :</label>
						<select name="article_id_stop" id="article_id_stop" class="inputbox" style="width:320px;">
								<option value="0">--</option>
								<?php if( $articles ) : ?>
										<?php foreach( $articles as $val ) : ?>
												<option value="<?php echo $val->id_article; ?>" <?php echo ( $val->id_article == $row->article_id_stop ) ? 'selected="selected"' : ''; ?>><?php echo $val->title; ?></option>
										<?php endforeach ?>
								<?php endif ?>
						</select>
				</p>
				<p class="form-line">
						<label for="article_id_help" class="form-label"><?php echo Kohana::lang( 'quete.article_help' ); ?> :</label>
						<select name="article_id_help" id="article_id_help" class="inputbox" style="width:320px;">
								<option value="0">--</option>
								<?php if( $articles ) : ?>
										<?php foreach( $articles as $val ) : ?>
												<option value="<?php echo $val->id_article; ?>" <?php echo ( $val->id_article == $row->article_id_help ) ? 'selected="selected"' : ''; ?>><?php echo $val->title; ?></option>
										<?php endforeach ?>
								<?php endif ?>
						</select>
				</p>
				<p class="form-line">
						<label for="type" class="form-label"><?php echo Kohana::lang( 'quete.type' ); ?> :</label>
						<select class="inputbox" name="type" id="type">
								<option value="1" ><?php echo Kohana::lang( 'quete.type_go' ); ?></option>
								<option value="0" <?php echo $row->type == 0 ? 'selected="selected"' : ''; ?> ><?php echo Kohana::lang( 'quete.type_recup' ); ?></option>
						</select>
				</p>
				<div id="option_bot" <?php echo $row->type != 2 ? 'style="display:none"' : ''; ?>>
						<p class="form-line">
								<label for="id_bot" class="form-label"><?php echo Kohana::lang( 'quete.list_bot' ); ?> :</label>
								<select id="id_bot" name="id_bot[]"  size="5" multiple="multiple" class="inputbox" >
										<?php if( $bots ) : ?>
												<?php $listBot = explode( ',', $row->id_bot ); ?>
												<?php foreach( $bots as $val ) : ?>
														<option value="<?php echo $val->id_detail; ?>" <?php echo in_array( $val->id_detail, $listBot ) ? 'selected="selected"' : ''; ?> ><?php echo $val->nom_map; ?></option>
												<?php endforeach ?>
										<?php endif ?>
								</select>
						</p>
				</div>
				<div id="option_objet" <?php echo $row->type != 0 ? 'style="display:none"' : ''; ?>>
						<p class="form-line">
								<label for="id_objet" class="form-label"><?php echo Kohana::lang( 'quete.list_object' ); ?> :</label>
								<select id="id_objet" name="id_objet[]" size="5" multiple="multiple" class="inputbox" >
										<?php if( $items ) : ?>
												<?php $listItem = explode( ',', $row->id_objet ); ?>
												<?php foreach( $items as $val ) : ?>
														<option value="<?php echo $val->id; ?>" <?php echo in_array( $val->id, $listItem ) ? 'selected="selected"' : ''; ?> ><?php echo $val->name; ?></option>
												<?php endforeach ?>
										<?php endif ?>
								</select>
						</p>
						<p class="form-line">
								<label for="nbr_objet" class="form-label"><?php echo Kohana::lang( 'quete.nbr_object' ); ?> :</label>
								<select name="nbr_objet" id="nbr_objet" class="inputbox" >
										<option value="0">--</option>
										<?php for( $n = 1; $n <= 50; $n++ ) : ?>
												<option value="<?php echo $n; ?>" <?php echo ( $n == $row->nbr_objet ) ? 'selected="selected"' : ''; ?>><?php echo sprintf( '%02d', $n ); ?></option>
										<?php endfor ?>
								</select>
						</p>
						<p class="form-line">
								<label for="garde" class="form-label"><?php echo Kohana::lang( 'quete.garde' ); ?> :</label>
								<select class="inputbox" name="garde" id="garde">
										<option value="1" class="vert"><?php echo Kohana::lang( 'form.yes' ); ?></option>
										<option value="0" class="rouge" <?php
										if( !$row->garde )
												echo 'selected="selected"';
										?>><?php echo Kohana::lang( 'form.no' ); ?></option>
								</select>
						</p>
				</div>
				<p class="form-line">
						<label for="niveau" class="form-label"><?php echo Kohana::lang( 'quete.level' ); ?> :</label>
						<select name="niveau" id="niveau" class="inputbox" >
								<?php for( $n = 0; $n <= 100; $n++ ) : ?>
										<option value="<?php echo $n; ?>" <?php echo ( $n == $row->niveau ) ? 'selected="selected"' : ''; ?>><?php echo sprintf( '%02d', $n ); ?></option>
								<?php endfor ?>
						</select>
				</p>
				<p class="form-line">
						<label for="xp" class="form-label"><?php echo Kohana::lang( 'quete.xp', Kohana::lang( 'user.xp' ) ); ?> :</label>
						<input name="xp" id="xp" value="<?php echo $row->xp; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="argent" class="form-label"><?php echo Kohana::lang( 'quete.money', Kohana::lang( 'user.money' ) ); ?> :</label>
						<input name="argent" id="argent" value="<?php echo $row->argent; ?>" class="inputbox input-text" type="text" maxlength="11" />
				</p>
				<p class="form-line">
						<label for="quete_id_parent" class="form-label"><?php echo Kohana::lang( 'quete.quete_obligatoire' ); ?> :</label>
						<select name="quete_id_parent" id="quete_id_parent" class="inputbox" >
								<option value=""><?php echo Kohana::lang( 'quete.no_quete' ); ?> </option>
								<?php if( $quete ) : ?>
										<?php foreach( $quete as $val ) : ?>
												<option value="<?php echo $val->id_quete; ?>" <?php echo ( $val->id_quete == $row->quete_id_parent ) ? 'selected="selected"' : ''; ?>><?php echo $val->title; ?></option>
										<?php endforeach ?>
								<?php endif ?>
						</select>
				</p>
				<p><strong><?php echo Kohana::lang( 'form.add_function' ); ?></strong></p>
				<p><?php echo Kohana::lang( 'quete.info_fonction' ); ?></p>
				<pre>$txt = 'Votre quête est terminée';
$maj['xp'] = $quete-&gt;xp + $this-&gt;user-&gt;xp;<br />$maj['argent'] = $quete-&gt;argent + $this-&gt;user-&gt;argent;
<strong class="rouge">&lt;-- <?php echo Kohana::lang( 'form.your_code' ); ?> --&gt;</strong><br />$this-&gt;user-&gt;update( $maj, $this-&gt;user-&gt;id );</pre>
				<?php echo Code_Core::editeur( 'fonction', $row->fonction ? $row->fonction : '<?php ?>', 200 ); ?>
				<strong class="rouge"><?php echo Kohana::lang( 'form.warning_function' ); ?></strong>

		</div>
		<div class="clear"></div>
</article>
