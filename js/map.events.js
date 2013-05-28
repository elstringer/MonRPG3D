$(function() {
		
		/*
		 * MAPPING START
		 */
		load();
				
		$('#actionModule').click(function() {
				scene.messages.push('Chargement d\'une action');
				var module = scene.map.getOverModule( scene.data.my.region, scene.hero.zone );
				overlay.load('actions/'+module.data.module+'?'+scene.hero.getData());
				inventaire.closeAll();
				$('#content_footer, #cible').fadeOut();
		});
		
		$('#content_body').click( function() {
				if($('#overlay_close').length)
						$('#overlay_close').trigger('click');
		});
		
		$(window).unload(function() {
				scene.data.request('user/update', 'GET', scene.hero.getData());
		});
		/*
		 * MAPPING STOP
		 */
		
		
		
		
		/*
		* INVENTAIRE START
		*/
		$('#btn_item_inventaire').click( function() {
				inventaire.getPage('item');
		});
				
		$('#btn_stuff_inventaire').click( function() {
				inventaire.getPage('stuff');
		});
				
		$('#btn_key_inventaire').click( function() {
				inventaire.getPage('key');
		});
		
		$('#btn_job_inventaire').click( function() {
				overlay.load('job' );
		});
		
		$('.close_list').live( 'click', function(){
				inventaire.close(this);
		});
		
		$('.element-item').live('click', function(){
				inventaire.using(this, scene);
		});
				
		$('#content_elements').delegate('.element-item', 'mouseenter', function () {
				inventaire.dragInventaire(this);
		});
		
		$('#content_elements').delegate('.global_item', 'mouseenter', function() {
				inventaire.dropInventaire(this);	
		});
		/*
		 * INVENTAIRE STOP
		 */
		
		
		
		
		/*
		 * QUETE START
		 */
		$('#accepter').live('click', function() {
				overlay.load('actions/quete/add/'+$('#id_quete').val() );
		});

		$('#annul').live('click', function() {
				overlay.load('actions/quete/annul/'+$('#id_quete').val() );
		});
		/*
		 * QUETE STOP
		 */
		
		
		
		
		/*
		 * ITEMS START
		 */
		$('.ramasser').live('click', function() {
				$(this).val('En cours');
				var _this = this;
				$.post(url_script+'actions/object/insert/', {
						object : $(this).siblings('input[name=id_object]').val()
				}, function(data) {
						$(_this).parent().html(data);
						$(_this).remove();
				});
		});	
		/*
		 * ITEMS STOP
		 */
		
		
		
		
		/*
		 * SPELL START
		 */
		$('.select_sort').live('change', function() {
				calcul_sort();
		});
		
		$('#buy_sort').live('click', function() {
				var url = 'actions/sort/insert?ajax=1';
				
				$('select').each(function(){
						url += '&'+$(this).attr('name')+'='+$(this).val();
				});
		
				overlay.load( url );
		});
		/*
		 * SPELL STOP
		 */
		
		
		
		
		/*
		 * SHOP START
		 */
		$('.select_item').live('change', function() {
				calcul_shop();
		});
	
		$('#buy_item').live('click', function() {
				var url = 'actions/shop/insert?ajax=1';

				$('select').each(function(){
						url += '&'+$(this).attr('name')+'='+$(this).val();
				});
				
				overlay.load( url );
		});
	
		$('#sale_item').live('click', function() {
				var url = 'actions/shop/update?ajax=1';

				$('select').each(function(){
						url += '&'+$(this).attr('name')+'='+$(this).val();
				});
				
				overlay.load( url );
		});
		/*
		 * SHOP STOP
		 */
		
		
		
		
		/*
		 * JOB START
		 */
		$('#select_job').live('click', function() {
				var my_job = $('#my_job').val();
				
				if( my_job )
						overlay.load('job/select/'+my_job );
		});
		
		$('.insert_couple').live('click', function() {
				overlay.load('job/couple/'+this.id.replace('couple_', ''), function() {
						refresh_user( true);
				});
		});
/*
		 * JOB STOP
		 */
		
		
});