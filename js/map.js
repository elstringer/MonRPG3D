var SIMULE_KEY;

if ( ! Detector.webgl ) 
		Detector.addGetWebGLMessage();
		
/*
 * Chargement des classes
 */

var stats;

var scene = new THREE.Scene();
scene.data = new THREE.Loader();
scene.clock = new THREE.Clock();
scene.renderer = {};
scene.bots = {};
scene.items = {};
scene.users = {};
scene.messages = [];
scene.alert = false;


var sound = new THREE.Sound();
var node = new THREE.Node(scene.data, scene);
var overlay = new THREE.Overlay();
var inventaire = new THREE.Inventaire( sound );
/*
 * Chargement des données et pré-traitement
 */
function load( ) {
		info(scene.data.stateLoad);
		
		if( !scene.data.getCompleted() ) {
				$('#content_loading').html( scene.data.stateLoad );
				setTimeout('load()', 10);
				return;
		} else
				$('#content_loading').html('Initialisation des données');

		if(scene.data.my.niveau == 0 && scene.data.my.logins < 2){
				$('#helpLoading').fadeIn().click(function() {
						$(this).fadeOut(500, function() {
								$(this).remove();
						})
				});
				
				$('#helpLoading area').hover(
						function () {
								$('#help_keyboard').attr('src', dir_script+'images/template/'+this.id+'.png');
						},
						function () {
								$('#help_keyboard').attr('src', dir_script+'images/template/keyboardHelp.png');
						}
						);
		}else
				$('#helpLoading').remove();
		
		node.init();
		init();
}


/*
 * Initialisation de la map
 */
function init() {			
		info('Chargement des données à 100%');
		info('Chargement de '+number_format(scene.data.nbrMap)+' région(s)');
		info('Chargement de '+number_format(scene.data.nbrBot)+' bot(s)');
		info('Chargement de '+number_format(scene.data.nbrElements)+' élement(s)');
		info('Chargement de '+number_format(scene.data.nbrItems)+' item(s)');
		

		if( debug ) {
				stats = new Stats();
				stats.domElement.style.position = 'absolute';
				stats.domElement.style.top = '0px';
				stats.domElement.style.right = '0px';
				$('body').append( stats.domElement );
		}
		
		scene.fog = new THREE.FogExp2( 0x8f98ba, 0.0001 );
		
		scene.map = new THREE.Map( sound );
		scene.map.setData( scene.data );
		scene.map.loadUnivers( scene );
		
		scene.hero = new THREE.Hero( sound, 60, window.innerWidth / window.innerHeight, 1, 8000 );
		scene.hero.setData( scene.data );
		scene.add( scene.hero.person );
		
		var bots = scene.map.getBots( scene.data.my.region )
		for( var keyBot in bots ) {
				var bot = new THREE.Bot( bots[keyBot], sound );
				bot.setData( scene.data );
				scene.bots[bot.id] = bot;
				scene.add( bot.person );
		}
		
		/*
		for( key in data.map.items ) {
				var item = new THREE.Item( data, sound, data.map.items[key].img );
				item.setPosition( data.map.items[key].x, data.map.items[key].y, data.map.items[key].z );
				scene.add( item );
				scene.items[item.id] = item;
		}*/
		
		scene.add( scene.map.getAmbience() );
		scene.add( scene.map.getLight() );
		scene.add( scene.map.getSun() );
		scene.add( scene.map.getMoon() );
		
		scene.renderer = new THREE.WebGLRenderer({
				clearColor : scene.data.map.colorBackground,
				antialias	 : false
		});
		scene.renderer.setSize( window.innerWidth, window.innerHeight );
		scene.renderer.shadowMapEnabled = true;
		scene.renderer.sortObjects = false;
		
		$('#content_body').html( scene.renderer.domElement );
		$('#content_loading').empty();
		
		if( scene.data.map.music )
				sound.ambience( scene.data.map.music );
		
		animate();
		
		window.addEventListener( 'resize', onWindowResize, false );
}



/*
 * Rendu du canvas
 */
function render() {
		
		if( scene == undefined )
				return;

		if( $('#overlay_global').is(':visible') || overlay.loadData ){
				if( $('#actionModule').is(':visible') )
						$('#actionModule').hide();
		} else {
				scene.hero.update( scene );
				updateHeroVisual();
				node.send( scene.data.my, scene.hero.person );
		}

		/* Users */
		var listUsers = node.listUser();
		for( var keyUser in listUsers ) {
				if(listUsers[keyUser].region == scene.data.my.region) {
						if( listUsers[keyUser] ) {
								if( scene.users[keyUser] == undefined ){
										scene.users[keyUser] = new THREE.User( listUsers[keyUser], scene.data, sound );
										scene.add( scene.users[keyUser].person );
								}
								else
										scene.users[keyUser].update( listUsers[keyUser] );
						} else if( !listUsers[keyUser] && scene.users[keyUser] != undefined ) {
								scene.remove(scene.users[keyUser].person);
								delete scene.users[keyUser];
								node.deleteUser( keyUser );
						}
				}
		}
		
		/* Items */
		for( var keyItem in scene.items)
				scene.items[keyItem].update( scene );

		/* Bots */
		for( var keyBot in scene.bots)
				scene.bots[keyBot].update( scene );
		
		for( var keyChildren in scene.children ) {
				if( scene.children[keyChildren].name != 'hero' && scene.children[keyChildren] instanceof THREE.Person ) {
						var person = scene.children[keyChildren].position;
						if(scene.hero.position.x > person.x - 150 && scene.hero.position.x < person.x + 150
								&& scene.hero.position.y > person.y - 150 && scene.hero.position.y < person.y + 150 
								&& scene.hero.position.z > person.z - 150 && scene.hero.position.z < person.z + 150 ){
								scene.children[keyChildren].text.visible = true;
						}
						else
								scene.children[keyChildren].text.visible = false;
				}
		}
		
		scene.map.update( scene );
		
		sound.update( scene.hero );
		
		scene.renderer.render( scene, scene.hero );
		
		if( debug ) {
				var info = scene.renderer.info;
				$('#debugWebGL').html('<b>Memory Geometrie</b> : '+info.memory.geometries+' - <b>Memory programs</b> : '+info.memory.programs+' - <b>Memory textures</b> : '+info.memory.textures+' - <b>Render calls</b> : '+info.render.calls+' - <b>Render vertices</b> : '+info.render.vertices+' - <b>Render faces</b> : '+info.render.faces+' - <b>Render points</b> : '+info.render.points);
		}
}



/*
 * Ajout des éléments sur la map
 */
function reloadMap() {
		scene.map.loadUnivers( scene );
		scene.hero.setPosition( scene.data.my.x, scene.data.my.y, scene.data.my.z );
		scene.hero.person.changeLeft( scene.data.my.hand_left );
		scene.hero.person.changeRight( scene.data.my.hand_right );
		
		for( var keyBotRemove in scene.bots) {
				scene.remove(scene.bots[keyBotRemove].person);
				delete scene.bots[keyBotRemove];
		}
		
		var bots = scene.map.getBots( scene.data.my.region )
		for( var keyBotAdd in bots ) {
				var bot = new THREE.Bot( bots[keyBotAdd], sound );
				bot.setData( scene.data );
				scene.bots[bot.id] = bot;
				scene.add( bot.person );
		}
		
}
	


/*
 * Loop pour l'animation
 */
function animate() {
		requestAnimationFrame( animate );
		
		if( scene == undefined )
				return;
		
		render();
		
		if( debug )
				stats.update();
}



/*
 * Traitement du resize de la fenetre
 */
function onWindowResize() {
		scene.hero.aspect = window.innerWidth / window.innerHeight;
		scene.hero.updateProjectionMatrix();
		scene.renderer.setSize( window.innerWidth, window.innerHeight );
}
	
	

function lookMessage( txt ) {
		var id = scene.clock.oldTime+'_'+random(0,100);
		$('#notifications').append('<div id="'+id+'" class="notifications">'+txt+'</div>');
		$('#'+id).fadeIn(400).delay(2000).fadeOut(3000, function() {
				$(this).remove();
		});
}



/*
 * UPDATE INTERFACE USER / GRAPHIQUE
 */
var loadMove = false;
function updateHeroVisual() {
		if( loadMove)
				return;
		
		// simuler toucher numbre lors d'un clique sur un sort'
		if( SIMULE_KEY ){
				scene.hero.onKeyDown(  {
						keyCode : SIMULE_KEY
				} );
				SIMULE_KEY= null;
		}
		
		//	fenetre action module	
		var module = scene.map.getOverModule( scene.data.my.region, scene.hero.zone );
		if( module && module.data.module == 'move') {
				loadMove = true;
				overlay.load('actions/'+module.data.module+'?'+scene.hero.getData(), function(data) {
						loadMove = false;
						
						if( data == 'no')
								return;

						if( module.data.title )
								scene.messages.push(module.data.title);
						else
								scene.messages.push('Changement de lieu');
						
				});
				$('#actionModule').fadeIn();
		} else if ($('#overlay_global').is(':visible') || !module ){
				$('#actionModule').fadeOut();
		}else if( module && !$('#actionModule').is(':visible') )
				$('#actionModule').fadeIn();


		// lecture de message a afficher
		if( scene.messages ) {
				for( var keyMsg in scene.messages){
						lookMessage(scene.messages[keyMsg]);
						scene.messages.splice(keyMsg,1);
				}
		}
		
		
		// Vue en rouge en cas d'accident'
		if( scene.alert ) {
				if( !$('#alertUser').is(':visible') ) {
						sound.play('system/159-Skill03.ogg', scene.hero.position );
						$('#alertUser').show().fadeOut( Math.round(scene.alert) );
				}
				scene.alert = false;
		}
		
		
		// afficher le message en cas d'envois de sort
		for( var keySpell in scene.data.my.sorts ) {
				if( keySpell == 0)
						continue; 
				
				if( scene.data.my.sorts[keySpell] == 0 )
						$('#btn_sort_'+keySpell).empty();
				else if( !$('#btn_sort_'+keySpell).html() )
						$('#btn_sort_'+keySpell).html('<img id="img_sort_'+keySpell+'" src="'+dir_script+'images/sorts/'+scene.data.my.sorts[keySpell].image+'" onclick="clickSpell(this)" />');
				else if( scene.data.my.sorts[keySpell].mp > scene.data.my.mp )
						$('#img_sort_'+keySpell).addClass('noMPSpell');
				else
						$('#img_sort_'+keySpell).removeClass('noMPSpell');
		}
		
		
		// changement de niveau
		if( scene.data.my.xp >= scene.data.my.xpMax ) {
				scene.data.request('user/update?'+scene.hero.getData() );
				scene.messages.push('Vous êtes passé au niveau : '+scene.data.my.niveau);
		}
		
		// gestion de la visibilité des controls
		if( $('#content_footer').is(':hidden') )
				$('#content_footer, #cible').fadeIn();
		
		
		// update de donnée hero
		$('#user_argent').html( number_format(scene.data.my.argent) +' $' );
		$('#user_niveau').html( scene.data.my.niveau );
		$('#user_xp_detail > .valueMoyenneGraph').html( scene.data.my.xp );
		$('#user_xp_detail > .valueMaxGraph').html( scene.data.my.xpMax );
		
		set_barre( '#user_hp', scene.data.my.hp );
		set_barre( '#user_mp', scene.data.my.mp );
		set_barre( '#content_inventaire', scene.data.my.xp );
}

function clickSpell(_this) {
		SIMULE_KEY = parseInt(_this.id.replace('img_sort_', '')) + 48;
}