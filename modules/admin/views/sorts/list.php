<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<article class="module width_full relative">
		<header>
				<h3 class="tabs_involved"><?php echo Kohana::lang( 'menu.sort_fight' ); ?></h3>
		</header>
		<table id="json_sorts" class="datatable" cellspacing="0">
				<thead>
						<tr>
								<th width="50"><?php echo Kohana::lang( 'form.id' ); ?></th>
								<th width="50"><?php echo Kohana::lang( 'form.image' ); ?></th>
								<th><?php echo Kohana::lang( 'sort.name' ); ?></th>
								<th width="80"><?php echo Kohana::lang( 'sort.level' ); ?></th>
								<th width="150"><?php echo Kohana::lang( 'sort.attack_min' ); ?></th>
								<th width="150"><?php echo Kohana::lang( 'sort.attack_max' ); ?></th>
								<th width="80"><?php echo Kohana::lang( 'sort.price' ); ?></th>
								<th width="50"></th>
						</tr>
				</thead>
				<tbody>
						<tr>
								<td colspan="7" class="dataTables_empty"><?php echo Kohana::lang( 'form.loading' ); ?></td>
						</tr>
				</tbody>
		</table>
		<div class="clear"></div>
</article>