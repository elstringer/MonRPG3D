THREE.Battle = function( sound ) {

		THREE.Object3D.call( this );
		
		this.bullets = [];
		
		this.shoot = false;
		
		this.cut = false;
		
		this.distanceShootMax =  500;
		
		this.latence =  400;
		
		this.lastShootTime = new Date().getTime();
		
		this.sizeSphere = 15;
		
		this.typeAngle = 1;
		
		this.dataSpell = {};



		/*
		 * Création d'un sort
		 */
		this.addMagic = function( scene, person, camera ) {	
				
				var bullet =  new THREE.Spell( this.dataSpell.size, this.dataSpell.radius, this.dataSpell.segments, this.dataSpell.rings, this.dataSpell.color, scene );
		
				if( camera ) {
						bullet.setPosition( camera.position.x, camera.position.y, camera.position.z, true );
						bullet.rotation.set( camera.rotation.x, camera.rotation.y, camera.rotation.z );
						bullet.start = camera.position.clone();
				} else {
						bullet.setPosition( person.position.x, person.position.y + 50, person.position.z, true );
						bullet.rotation.set( person.rotation.x, person.rotation.y + 270 * Math.PI / 180, person.rotation.z );
						bullet.start = person.position.clone();
				}
				bullet.speed = 10;
				bullet.gravity = 0;
				bullet.visible = true;
				bullet.contact = false;
				bullet.speedRotation = 0.05;
				
				this.bullets.push(bullet);
				scene.add(bullet);
		}
		
		
		
		/*
		 * Update
		 */
		this.update = function( scene, person, camera ) {	
				// HAND				
				if( this.cut ){
						if( this.cut == -3 ) {
								sound.play( 'system/sabre.ogg', person.position );
								person.rightarm.rotation.z = -3;
						}
						person.rightarm.rotation.z = this.cut -= 0.15;
						person.rightarm.rotation.x = this.typeAngle * 0.5;
						
						if(person.rightarm.rotation.z < -6 ) {
								var xPerson =  ( scene.data.map.size.xMax / 2) + Math.floor(person.position.x / scene.data.map.size.elements) + 1;
								var yPerson = (person.position.y / scene.data.map.size.elements);
								var zPerson =  ( scene.data.map.size.zMax / 2) + Math.floor(person.position.z / scene.data.map.size.elements) + 1;
								var degrePerson = Math.abs((person.rotation.y * (180/Math.PI)) % 360);
								var newX =  xPerson;
								var newZ =  zPerson;
								
								if( degrePerson < 30 || degrePerson > 330) {
										newX++;
								} else if( degrePerson < 60) {
										newX++;
										newZ++;
								} else if( degrePerson < 120) {
										newZ++;
								} else if( degrePerson < 150) {
										newX--;
										newZ++;
								} else if( degrePerson < 210) {
										newX--;
								} else if( degrePerson < 240) {
										newX--;
										newZ--;
								} else if( degrePerson < 300) {
										newZ--;
								} else {
										newX++;
										newZ--;
								} 
								
								for( var keyChild in scene.bots)
										if( scene.bots[keyChild].zone.x == newX && scene.bots[keyChild].zone.y == yPerson && scene.bots[keyChild].zone.z == newZ
												|| scene.bots[keyChild].zone.x == xPerson && scene.bots[keyChild].zone.y == yPerson && scene.bots[keyChild].zone.z == zPerson ) 
												this.calculationAttackBot( scene, keyChild );
									
								this.cut = false;
						}
						person.leftarm.rotation.z = 0.3;
						person.leftarm.rotation.x = -0.2;
				} 
				// SPELL
				else if( this.shoot ) {
						if( this.shoot == -3 ){
								var newTime= new Date().getTime();
				
								if( newTime - this.lastShootTime < this.latence )
										return;
								
								sound.play( this.dataSpell.sound, person.position );
								
								person.leftarm.rotation.z = -2;
								
								this.addMagic( scene, person, camera );
								this.lastShootTime = newTime;
						}

						person.leftarm.rotation.z = this.shoot -= 0.15;
						person.leftarm.rotation.x = this.typeAngle * 0.3;
						
						if(person.leftarm.rotation.z < -6 )
								this.shoot = false;
				
						person.rightarm.rotation.z = 0.3;
						person.rightarm.rotation.x = -0.2;
				}
				
				// ANIMATE BULLETS
				if(this.bullets.length) {
						var infoSize = scene.map.getSize( scene.data.my.region );
						var sizeBloc = infoSize.elements;
						var maxX = infoSize.xMax * sizeBloc;
						var maxZ = infoSize.zMax * sizeBloc;
						var limitMinY = (sizeBloc / 2) + this.sizeSphere;

						for (var i = 0; i < this.bullets.length; i++) {
								var x = this.bullets[i].position.x;
								var y = this.bullets[i].position.y;
								var z = this.bullets[i].position.z;
								
								if( y < this.sizeSphere / 2 )
										y = this.sizeSphere / 2;

								if( this.bullets[i].hit > 100 
										|| (this.bullets[i].position.y == limitMinY && this.bullets[i].speed < 0 ) 
										|| this.bullets[i].position.y < limitMinY
										|| x <  -(maxX / 2 ) + (sizeBloc / 2 )
										|| x > maxX / 2 - ( sizeBloc / 2) 
										|| z < -(maxZ / 2 ) + ( sizeBloc / 2 )
										|| z > maxZ / 2 - (sizeBloc / 2) 
										|| scene.map.hasObstacle( Math.floor( (x  + (maxX / 2 ) ) / sizeBloc) + 1, Math.floor( (y + limitMinY ) / sizeBloc), Math.floor( ( z + (maxZ / 2 ) ) / sizeBloc) + 1, scene.data.my.region) ) {
										if( this.remove(scene, i, x, y, z) )
												return;
								}
	
								this.bullets[i].translateZ(-this.bullets[i].speed);
								this.bullets[i].translateY(this.bullets[i].gravity);
								this.bullets[i].gravity -= 0.3;
								this.bullets[i].update( scene );
								this.bullets[i].hit++;
						}
				}
		};
		
		
		/*
		 * Die spell
		 */
		this.remove = function (scene, key, x, y, z) {
				this.bullets[key].material.opacity -= 0.1;
				this.bullets[key].particles.rotation.y = 0;

				if( this.bullets[key].material.opacity > 0 ) {						
						this.bullets[key].scale.x++;
						this.bullets[key].scale.y++;
						this.bullets[key].scale.z++;
						return false;
				}				
				
				this.calculationAttackSpell( scene, x, y, z );
				
				sound.play( 'system/123-Thunder01.ogg', this.bullets[key].position );
				scene.remove(this.bullets[key]);
				this.bullets.splice(key,1);
				return true;
		};
		
		
		
		/*
		 * Calcul attaque à la main hero > bot
		 */
		this.calculationAttackBot = function ( scene, id ) {
				var attack = scene.data.my.attack;
				var defense = scene.bots[id].data.defense;
				
				if( scene.data.items['item_'+scene.data.my.hand_right] != undefined )
						attack += parseInt(scene.data.items['item_'+scene.data.my.hand_right].attaque);
				
				if( scene.data.items['item_'+scene.bots[id].data.hand_left] != undefined )
						defense += parseInt(scene.data.items['item_'+scene.bots[id].data.hand_left].defense);
				
				info('attaque : '+ attack+' - défense : '+ defense);
				if( defense >= attack ) {
						scene.messages.push('Défense supérieur à l\'attaque');
						return;
				}

				var calculation = attack - defense;
				scene.messages.push('Attaque de '+calculation+' pt(s)');
				
				scene.bots[id].data.hp -= calculation;
				
				if( scene.bots[id].data.hp <= 0 ){
						scene.data.my.xp -= scene.bots[id].data.hp;
						scene.data.my.argent += random(((scene.data.my.niveau + 1) * 10), ((scene.data.my.niveau + 1) * 100));
						scene.data.my.attack++;

						scene.messages.push('Bots '+id+' vient de mourir');
						sound.play( 'system/douleur.ogg', scene.bots[id].person.position );
						scene.bots[id].remove = true;
				}
		}
		
		
		
		/*
		 * Calcul attaque avec un sort bot/hero
		 */
		this.calculationAttackSpell= function ( scene, x, y, z ) {
				var attack = random(parseInt(this.dataSpell.attack_min), parseInt(this.dataSpell.attack_max));

				// bot
				for( var id in scene.bots) {
						if( scene.bots[id].position.x > x - 100 && scene.bots[id].position.x < x + 100
								&& scene.bots[id].position.y > y - 100 && scene.bots[id].position.y < y + 100
								&& scene.bots[id].position.z > z - 100 && scene.bots[id].position.z < z + 100) {

								var defenseBot = scene.bots[id].data.defense;
								if( scene.data.items['item_'+scene.bots[id].data.hand_left] != undefined )
										defenseBot += parseInt(scene.data.items['item_'+scene.bots[id].data.hand_left].defense);
				
								console.log('attack', attack, 'defenseBot', defenseBot);
								if( defenseBot >= attack ) {
										scene.messages.push('Cette magie n\'a aucun effet');
										return;
								}

								var calculationBot = attack - defenseBot;
								scene.messages.push('Attaque de '+calculationBot+' pt(s)');
				
								scene.bots[id].data.hp -= calculationBot;
				
								if( scene.bots[id].data.hp <= 0 ){
										scene.data.my.xp -= scene.bots[id].data.hp;
										scene.data.my.argent += random(((scene.data.my.niveau + 1) * 10), ((scene.data.my.niveau + 1) * 100));
										scene.data.my.attack++;
										
										scene.messages.push('Bots '+id+' vient de mourir');
										sound.play( 'system/douleur.ogg', scene.bots[id].person.position );
										scene.bots[id].remove = true;
								}
						}
				}
				
				// hero
				if( scene.hero.position.x > x - 100 && scene.hero.position.x < x + 100
						&& scene.hero.position.y > y - 100 && scene.hero.position.y < y + 100
						&& scene.hero.position.z > z - 100 && scene.hero.position.z < z + 100 ) {

						var defenseUser = scene.data.my.defense;
						if( scene.data.items['item_'+scene.data.my.hand_left] != undefined )
								defenseUser += parseInt(scene.data.items['item_'+scene.data.my.data.hand_left].defense);
				
						console.log('attack', attack, 'defenseUser', defenseUser);
						if( defenseUser >= attack ) {
								scene.messages.push('Une magie sans effet sur vous');
								return;
						}

						var calculationUser = attack - defenseUser;
						scene.messages.push('Attaque sur vous de '+calculationUser+' pt(s)');
				
						scene.data.my.hp -= calculationUser;
				
						if( scene.data.my.hp <= 0 ){
								scene.messages.push('Game over');
						}						
				}
		}
};

THREE.Battle.prototype = Object.create( THREE.Object3D.prototype );