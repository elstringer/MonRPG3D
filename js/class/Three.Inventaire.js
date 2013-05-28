THREE.Inventaire = function( sound ) {
		
		/*
		 * recuperer soit la page item - stuff - clé
		 */
		this.getPage = function (type) 
		{
				if( !$('#load_user_'+type+' .list_user_item').length) {
						$('#load_user_'+type).load(url_script+'user/'+type, function () {
								$('#content_elements').width( $('#content_elements .list_user_item').length * 270);
						});
						return;
				}
		
				$('#load_user_'+type+' .list_user_item .close_list').trigger('click');
		};
		
		
		
		/*
		 * gestion du drop item
		 */
		this.dropInventaire = function (obj) 
		{
				var id = obj.id;
								
				$(obj).children('.content-item').each(function() {
						if( !$(this).is('.ui-droppable') ) {
								$(this).droppable({
										accept: '#'+id+' .element-item',
										drop: function( event, ui ) {
												
												var obj = $('#'+ui.draggable[0].id),
												url = url_script+'item/move/'+end(ui.draggable[0].id.split('_'))+'/'+end(this.id.split('_'));
												
												if( $(this).html().replace(/^\s+/g,'') != '')
												{
														var oldDrop = obj.parent('.content-item');
														
														url += '/'+$(this).children('.element-item').attr('id').replace('elementInventaire_','')+'/'+end(oldDrop.attr('id').split('_'));
														
														oldDrop.html($(this).html()).children('.element-item').draggable({
																revert: 'invalid',
																zIndex: 2
														});
														$(this).empty();
												}
												
												obj.remove().clone().prependTo(this).css({
														top:0, 
														left:0
												});
												
												$.get(url);
										}
								})
						}
				});
				
				$('.element-item').css('z-index',0);
		};
		
		
		
		/*
		 * gestion du drag item
		 */
		this.dragInventaire = function( obj ) {
				if(!$(obj).is('.ui-draggable')) {
						$(obj).draggable({
								revert: 'invalid',
								zIndex: 2
						});
				}
		};



		/*
		 * gestion divers en cas d'utilisation item (nombre...)
		 */
		this.using = function ( obj, scene )
		{		
				var id = end(obj.id.split('_'));
				
				obj = $(obj);
				
				var nbr = parseInt(obj.children('.nombre').html()),
				content_lead_id = obj.parents('.global_item').attr('id');
				
				if( content_lead_id == 'global_item_key')
						return;
				
				if( content_lead_id == 'global_item_item')
				{
						nbr--;

						if(nbr <= 0 && !isNaN(nbr) )
						{
								$('.tipsy').remove();
								obj.remove();
						}
						else if( !isNaN(nbr) )
								if(nbr > 1 )
										obj.children('.nombre').html(nbr);
								else
										obj.children('.nombre').remove();
												
						this.usingItem( scene, id, obj );
				}
				else if( content_lead_id == 'global_item_stuff')
						this.usingStuff( scene, id, obj );
		};
		
		
		
		/*
		 * équiper item
		 */
		this.usingItem = function( scene, id, obj ) {
				$.getJSON(url_script+'item/using/'+id+'?'+scene.hero.getData(), function(data) {
						if(data.hp)
								scene.data.my.hp = data.hp;
						if(data.mp)
								scene.data.my.mp = data.mp;
				});	
				this.sound( 'gifle');
		};
		
		
		
		/*
		 * équiper stuff
		 */
		this.usingStuff = function( scene, id, obj ) {
				if( obj.is('.actif'))
				{
						this.sound( 'sabre');
						$.get( url_script+'item/desequiper/'+id+'?'+scene.hero.getData(), function ( data ) {
								data = jQuery.parseJSON(data);
								obj.removeClass('actif').addClass('no_actif');
								if( data.position == 1 )
										scene.hero.person.changeRight();
								else if( data.position == 2 )
										scene.hero.person.changeLeft();
						});
						return;
				}
				
				$.get( url_script+'item/equiper/'+id+'?'+scene.hero.getData(), function( data ) { 
						data = jQuery.parseJSON(data);
						$('.position_'+data.position ).removeClass('actif').addClass('no_actif');
						obj.removeClass('no_actif').addClass('actif');
						if( data.position == 1 )
								scene.hero.person.changeRight( data.id );
						else if( data.position == 2 )
								scene.hero.person.changeLeft( data.id );
				});
				this.sound( 'gifle');		
		};
		
		
		
		/*
		 * supprimer un item
		 */
		this.kill = function( obj ) {
				if(confirm('Vous souhaitez vraiment jeter cet objet ?'))
				{
						var parent = $(obj).parent('.element-item');
						$.get( url_script+'item/delete/'+end(parent.attr('id').split('_')));
						parent.remove();
				}
				this.sound( 'sabre');
		};
		
		
		
		/*
		 * gestion de fermer les elements
		 */
		this.close = function( obj ) {
				$(obj).parent('.list_user_item').remove();
				$('#content_elements').width( $('#content_elements .list_user_item').length * 270);
		};
		
		
		
		/*
		 * gestion de fermer les elements
		 */
		this.closeAll = function( ) {
				$('.list_user_item').fadeOut('slow',function() {
						$('.list_user_item').remove();
						$(this).width(0);
				});
		};
		
		
		
		/*
		 * gestion son et return id purgé
		 */
		this.sound = function( data ) {
				sound.effect( 'system/'+data+'.ogg' );
		};
}