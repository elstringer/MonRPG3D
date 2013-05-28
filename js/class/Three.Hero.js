THREE.Hero = function ( sound, fov, aspect, near, far ) {
		
		THREE.Camera.call( this );

		this.fov = fov !== undefined ? fov : 50;
		this.aspect = aspect !== undefined ? aspect : 1;
		this.near = near !== undefined ? near : 0.1;
		this.far = far !== undefined ? far : 2000;

		this.updateProjectionMatrix();
		
		this.data = false;
		
		this.target = new THREE.Vector3( 0, 0, 0 );
		this.zone = new THREE.Vector3( 0, 0, 0 );
		
		this.battle = new THREE.Battle(sound);
		
		this.person = {};

		this.moveForward = false;
		this.moveBackward = false;
		this.moveLeft = false;
		this.moveRight = false;
		
		this.freeze = true;
		
		this.timeFall = 0;
		
		this.wireframe = false;

		this.mouseX = 0;
		this.mouseY = 0;
		this.mouseStartX = 0;
		this.mouseStartY = 0;
		
		this.gravity = 1;
		this.heightJump = 13;
		this.jump = false;
		
		this.speed = 6;
		this.speedTmp = 0;
		this.currentdirection = {
				x: 0, 
				y: 0
		};
		
		this.sizeElement = 0;
		
		
		/*
		 * Set donnée de la map
		 */
		this.setData = function( data ) {
				this.data = data;
				
				this.sizeElement = this.data.map.size.elements;
				
				this.gravity = this.data.my.gravity;
				this.speed = this.data.my.speed;
				this.currentdirection.x = this.data.my.currentdirection_x;
				this.setPosition( this.data.my.x, this.data.my.y, this.data.my.z );
		
				this.person = new THREE.Person( this.data.my.img, this.data, sound, this.data.my.hand_left, this.data.my.hand_right );
				this.person.name = 'hero';
				this.person.rotation.y = (270 * Math.PI / 180) + this.rotation.y;
		};
		
		
		
		
		/*
		 * Geneate GET for URL hero
		 */
		this.getData = function () {
				var str = '';
				for( var key in this.data.my ) 
						if( key != 'img' && key != 'sorts' )
								str += (key + '=' + this.data.my[key] + '&');
				str = str.slice(0,-1);
				
				return str;
		};
		
		
		/*
		 *	SET position du héro
		 */
		this.setPosition = function( x, y, z ) {
				
				var infoSize = scene.map.getSize( scene.data.my.region );
				var sizeBloc = infoSize.elements;
				var maxX = infoSize.xMax * sizeBloc;
				var maxZ = infoSize.zMax * sizeBloc;
				
				this.zone.set( x, y, z );
				this.position.x = -( maxX / 2) + (x * sizeBloc + (sizeBloc /2));
				this.position.y = y * sizeBloc  + (sizeBloc *2);
				this.position.z = -( maxZ / 2) + (z * sizeBloc + (sizeBloc /2));
		};
		
		
		
		/*
		 *	SET la rotation du héro
		 */
		this.setRotation = function( x ) {
				this.currentdirection.x = x;
		};
		

		
		/*
		 * Position de la sourie
		 */
		this.onMouseMove = function ( event ) {
				this.mouseX = this.battle.mouseX = event.pageX - (window.innerWidth / 2);
				this.mouseY = this.battle.mouseY = event.pageY - (window.innerHeight / 2);
		};
		


		/*
		 * On relache le click sourie
		 */
		this.onMouseUp = function ( event ) {
		};



		/*
		 * On appuie le click sourie
		 */
		this.onMouseDown = function ( event ) {
				if( this.battle.cut )
						return;
				
				this.battle.cut = -3;
				this.battle.typeAngle *= -1;
		};
		
		
		
		/*
		 * On appuie sur une touche du clavier
		 */
		this.onKeyDown = function ( event ) {
				if( !this.battle.shoot ) {				
						if(event.keyCode > 48 && event.keyCode <= 57 ) {
								var touchNum = event.keyCode - 48;
								if( this.data.my.sorts[touchNum] && this.data.my.mp >= this.data.my.sorts[touchNum].mp ) {
										this.data.my.mp -= parseInt(this.data.my.sorts[touchNum].mp);
										this.data.my.xp += parseInt(this.data.my.sorts[touchNum].xp);
										this.battle.dataSpell = this.data.my.sorts[touchNum];
										scene.messages.push('Sortilège : '+this.battle.dataSpell.name+' <img src="'+dir_script+'images/sorts/'+this.battle.dataSpell.image+'" height="20" width="20" />');
										this.battle.shoot = -3;
										this.battle.typeAngle *= -1;
								}
						}
				}
				
				switch( event.keyCode ) {
						case 38 : case 122 : case 119 : case 90 : case 87 : // Flèche haut, z, w, Z, W
								if(this.moveForward) 
										break;

								sound.move( true );
								this.moveForward = true;
								break;
						case 37 : case 113 : case 97 : case 81 : case 65 : // Flèche gauche, q, a, Q, A
								if(this.moveLeft) 
										break;
								
								this.moveLeft = true;
								break;
						case 40 : case 115 : case 83 : // Flèche bas, s, S
								if(this.moveBackward) 
										break;
								
								sound.move( true );
								this.moveBackward = true;
								break;
						case 39 : case 100 : case 68 : // Flèche droite, d, D
								if(this.moveRight) 
										break;
								
								this.moveRight = true;
								break;
						case 32:
								if( this.jump )
										break;
								
								this.jump = true;
								this.currentdirection.y = this.heightJump;
								
								sound.play( 'system/016-Jump02.ogg', this.position );
								break;
						case 16 :
								this.freeze = false;
								break;
				}
		};
		
		
		
		/*
		 * On relache une touche du clavier
		 */
		this.onKeyUp = function ( event ) {				
				switch( event.keyCode ) {
						case 38 : case 122 : case 119 : case 90 : case 87 : // Flèche haut, z, w, Z, W
								sound.move( false );
								this.moveForward = false;
								break;
						case 37 : case 113 : case 97 : case 81 : case 65 : // Flèche gauche, q, a, Q, A
								this.moveLeft = false;
								break;
						case 40 : case 115 : case 83 : // Flèche bas, s, S
								sound.move( false );
								this.moveBackward = false;
								break;
						case 39 : case 100 : case 68 : // Flèche droite, d, D
								this.moveRight = false;
								break;
						case 16 :
								this.freeze = true;
								break;
				}
				
				this.saveSession();
		};


		
		/*
		 * UPDATE du héro
		 */
		this.update = function( scene ) {	
				var infoSize = scene.map.getSize( scene.data.my.region );
				var sizeBloc = infoSize.elements;
				var maxX = infoSize.xMax * sizeBloc;
				var maxZ = infoSize.zMax * sizeBloc;
				var x = this.position.x;
				var z = this.position.z;
				var y = this.position.y;
				
				if ( this.moveForward ) {
						this.speedTmp+= 0.2;
						x += Math.sin(this.currentdirection.x/360*Math.PI*2) * this.speedTmp;
						z += Math.cos(this.currentdirection.x/360*Math.PI*2) * this.speedTmp;
						this.mouseX = this.mouseY = 0;
				}
				else if ( this.moveBackward ) {
						this.speedTmp+= 0.1;
						x -= Math.sin(this.currentdirection.x/360*Math.PI*2) * this.speedTmp;
						z -= Math.cos(this.currentdirection.x/360*Math.PI*2) * this.speedTmp;
						this.mouseX = this.mouseY = 0;
				} 
				else
						this.speedTmp = 0;

				if( this.speedTmp < 0 )
						this.speedTmp = 0;
				else if( this.speedTmp > this.speed )
						this.speedTmp = this.speed;
				
				var middle = sizeBloc / 2;
				var dirXx = Math.floor( ( (x + ( x > this.position.x ? middle : -middle ) ) + (maxX / 2 ) ) / sizeBloc) + 1;
				var dirXy = Math.floor( y / sizeBloc);
				var dirXz = Math.floor( (z + (maxZ / 2 ) ) / sizeBloc) + 1;
				if ( scene.map.hasObstacle(dirXx, dirXy, dirXz, scene.data.my.region) || scene.map.hasObstacle(dirXx, dirXy - 1, dirXz, scene.data.my.region) ) {
						x = this.position.x;	
						this.speedTmp-= 0.2;
				} 
				
				
				var dirZx = Math.floor( (x + (maxX / 2 ) ) / sizeBloc) + 1;
				var dirZy = Math.floor( y / sizeBloc);
				var dirZz = Math.floor( ( (z + ( z > this.position.z ? middle : -middle ) ) + (maxZ / 2 ) ) / sizeBloc) + 1;
				if ( scene.map.hasObstacle(dirZx, dirZy, dirZz, scene.data.my.region) || scene.map.hasObstacle(dirZx, dirZy - 1, dirZz, scene.data.my.region) ) {
						z = this.position.z;
						this.speedTmp-= 0.2;
				} 
				
				if(x <  -(maxX / 2 ) + sizeBloc / 2)
						x = -(maxX / 2 ) + sizeBloc / 2;
				else if(x > maxX / 2 - (sizeBloc / 2) )
						x = maxX / 2 - (sizeBloc / 2);
				
				if(z < -(maxZ / 2 ) + sizeBloc / 2)
						z = -(maxZ / 2 ) + sizeBloc / 2;
				else if(z > maxZ / 2 - (sizeBloc / 2) )
						z = maxZ / 2 - (sizeBloc / 2);
				
				y += this.currentdirection.y -= this.gravity;
				
				var dirYx = Math.floor( (x + (maxX / 2 ) ) / sizeBloc) + 1;
				var dirYy = Math.floor( y / sizeBloc);
				var dirYz = Math.floor( (z + (maxZ / 2 ) ) / sizeBloc) + 1;
				if ( scene.map.hasObstacle(dirYx, dirYy+ 1, dirYz, scene.data.my.region) || scene.map.hasObstacle(dirYx, dirYy- 1, dirYz, scene.data.my.region)  ) {
						y = Math.floor( this.position.y / sizeBloc ) * sizeBloc;	
						this.jump = false;
						this.currentdirection.y = 0;
				}
				
				if(y < 100)
						y = 100;

				if( this.timeFall > 300 && this.position.y == y) {
						scene.alert = this.timeFall * 10;
						scene.messages.push( 'Chute de '+( Math.round(this.timeFall) /100 )+'m' );
						this.data.my.hp -= Math.round( Math.round(this.timeFall) /100 );
						this.timeFall = 0;
				}
				
				if( this.position.y != y && this.position.y > y )
						this.timeFall += this.position.y - y;
				else
						this.timeFall = 0;
				
				var newRotation = (this.currentdirection.x + 270  % 360 ) * Math.PI / 180;

				if( this.person.position.x != x || this.person.position.y != y - 50 || this.person.position.z != z || this.person.rotation.y != newRotation ){
						this.person.update( this.speedTmp >= 3 ? 1 : 0 );
						sound.audioMove.volume = 0.1;
				}
				else
						sound.audioMove.volume = 0;
				
				
				this.zone.set( Math.floor( (x + (maxX / 2 ) ) / sizeBloc) + 1, Math.floor(y  / sizeBloc) - 1, Math.floor((z + (maxZ / 2 ) ) / sizeBloc) + 1);
				this.position.set( x, y, z );
				this.person.position.set( x, y - 50, z );
				this.person.rotation.y = newRotation;


				//Look
				if ( this.moveLeft )
						this.currentdirection.x +=  1.5;
				else if ( this.moveRight)
						this.currentdirection.x -=  1.5;
				else if (!this.freeze)
						this.currentdirection.x -=  this.mouseX / 100 / 1.5;

				this.target.x = Math.round( x + ( Math.sin( 2 * Math.PI * (this.currentdirection.x / 360)) * this.far ) );
				this.target.z = Math.round( z + ( Math.cos( 2 * Math.PI * (this.currentdirection.x / 360)) * this.far ) );
				this.target.y = this.freeze ? this.target.y :  -this.mouseY * 20;

				this.lookAt( this.target );
				
				this.battle.update( scene, this.person, this );
				
				this.save( scene );
		};
		
		
		/*
		 * Sauvegarde de la session 
		 */
		this.save = function( scene ) {
				if( Date.now() % 60 == 0 )
						if( scene.data.my.mp < 100 )
								scene.data.my.mp++;
						else if( scene.data.my.hp < 100 )
								scene.data.my.hp++;
				
				scene.data.my.x = this.zone.x;
				scene.data.my.y = this.zone.y;
				scene.data.my.z = this.zone.z;
				scene.data.my.speed = this.speed;
				scene.data.my.gravity = this.gravity;
				scene.data.my.currentdirection_x = this.currentdirection.x % 360;
		};
		
		
		/*
		 * Sauvegarde de la session 
		 */
		this.saveSession = function() {
				if(sessionStorage.currentdirection_x != this.currentdirection.x % 360)
						sessionStorage.currentdirection_x = this.currentdirection.x % 360;				
		};
		
		
		
		/*
		 * Delete session 
		 */
		this.deleteSession = function() {
				sessionStorage.removeItem('currentdirection_x');
		};
		
		/*
		 * EVENT 
		 */
		document.getElementById('cible').addEventListener( 'mouseup', bind( this, this.onMouseUp ), false );
		document.getElementById('cible').addEventListener( 'mousedown', bind( this, this.onMouseDown ), false );
		document.getElementById('cible').addEventListener( 'mousemove', bind( this, this.onMouseMove ), false );
		window.addEventListener( 'keydown', bind( this, this.onKeyDown ), false );
		window.addEventListener( 'keyup', bind( this, this.onKeyUp ), false );
};

THREE.Hero.prototype = Object.create( THREE.Camera.prototype );

THREE.Hero.prototype.setLens = function ( focalLength, frameHeight ) {

		if ( frameHeight === undefined ) frameHeight = 24;

		this.fov = 2 * Math.atan( frameHeight / ( focalLength * 2 ) ) * ( 180 / Math.PI );
		this.updateProjectionMatrix();

};

THREE.Hero.prototype.setViewOffset = function ( fullWidth, fullHeight, x, y, width, height ) {

		this.fullWidth = fullWidth;
		this.fullHeight = fullHeight;
		this.x = x;
		this.y = y;
		this.width = width;
		this.height = height;

		this.updateProjectionMatrix();

};

THREE.Hero.prototype.updateProjectionMatrix = function () {

		if ( this.fullWidth ) {
				var aspect = this.fullWidth / this.fullHeight;
				var top = Math.tan( this.fov * Math.PI / 360 ) * this.near;
				var bottom = -top;
				var left = aspect * bottom;
				var right = aspect * top;
				var width = Math.abs( right - left );
				var height = Math.abs( top - bottom );

				this.projectionMatrix.makeFrustum(
						left + this.x * width / this.fullWidth,
						left + ( this.x + this.width ) * width / this.fullWidth,
						top - ( this.y + this.height ) * height / this.fullHeight,
						top - this.y * height / this.fullHeight,
						this.near,
						this.far
						);

		} else
				this.projectionMatrix.makePerspective( this.fov, this.aspect, this.near, this.far );
};
