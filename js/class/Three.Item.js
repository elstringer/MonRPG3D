THREE.Item = function ( data, sound, img  ) {

		THREE.Object3D.call( this );
		
		this.geometry = new THREE.Object3D();
		
		this.size = 24;
		
		this.dept = 1;
		
		this.speedRotation = 0.02;
				
		this.distLook = 500;
		
		this.distPickUp = 10;
		
		this.visible = false;
		
		this.wireframe = false;
		
		
		/*
		 * SET la position de l'item
		 */
		this.setPosition = function( x, y, z, no_calcul ) {
				if(no_calcul) {
						this.position.set(x, y, z);
						return;
				}
				var middleSize = data.map.size.elements/2;
				this.position.x = -( data.map.size.xMax * middleSize) + (x * data.map.size.elements) + middleSize;
				this.position.y = y * data.map.size.elements + data.map.size.elements;
				this.position.z = -( data.map.size.zMax * middleSize) + (z * data.map.size.elements) + middleSize;						
		};



		/*
		 * UPDATE
		 */
		this.update = function( scene ) {		
				this.geometry.rotation.y += this.speedRotation;
		};
		
		
		
		/*
		 * Chargement de la matiere
		 */
		this.material = function ( canvas ) {
				var material = new THREE.MeshLambertMaterial( {
						map: new THREE.Texture( canvas, new THREE.UVMapping(), THREE.ClampToEdgeWrapping, THREE.ClampToEdgeWrapping, THREE.NearestFilter, THREE.LinearMipMapLinearFilter ), 
						wireframe : this.wireframe, 
						transparent: true
				} );
				material.map.needsUpdate = true;
				
				return material;
		};
		
		
		
		/*
		 * Genere l'item a partir d'une image
		 */
		this.drawCanvas = function( context ) {
		
				var cubes = [];
				var listPixel =[];
				var item  = new THREE.Geometry();

				for ( var i = 0; i < 16; i++ ) {
						cubes[ i ] = new THREE.CubeGeometry( 1, 1, this.dept, 0, 0, 0, undefined, {
								px: ( i & 8 ) == 8, 
								nx: ( i & 4 ) == 4, 
								py: ( i & 2 ) == 2, 
								ny: ( i & 1 ) == 1, 
								pz: false, 
								nz: false
						} );
				}
				
				
				for(var x=0; x < this.size; x++)
						for(var y=0; y < this.size; y++)
								if(context.getImageData(0, 0, this.size, this.size).data[(x+y*this.size)*4+3] !== 0)										
										listPixel.push({
												x : x, 
												y : y
										});
				
				for( key in listPixel) {
						var row = listPixel[key];
						
						var px = listPixel[row.x-1] != undefined && listPixel[row.x-1][row.y] ? 0 : 1;
						var nx = listPixel[row.x+1] != undefined && listPixel[row.x+1][row.y] ? 0 : 1; 
						var py = listPixel[row.x][row.y-1] != undefined && listPixel[row.x][row.y-1] ? 0 : 1; 
						var ny = listPixel[row.x][row.y+1] != undefined && listPixel[row.x][row.y+1] ? 0 : 1;
						
						var pixel = new THREE.Mesh( cubes[ px * 8 + nx * 4 + py * 2 + ny ] );
						pixel.position.x = -row.x + (this.size/2) - 0.5;
						pixel.position.y = -row.y + (this.size/2) - 0.5;
						
						THREE.GeometryUtils.merge(item, pixel);
				}
				
				return item;
		}
		
		
		/*
		 * Constructor item
		 */
		var canvasPile = window.document.createElement('canvas');
		var contextPile = canvasPile.getContext('2d');
		
		var canvasFace = window.document.createElement('canvas');
		var contextFace = canvasFace.getContext('2d');
		
		canvasPile.width = canvasFace.width = this.size;
		canvasPile.height = canvasFace.height = this.size;

		contextPile.drawImage(img, 0, 0, this.size, this.size);
		contextFace.scale(-1, 1);
		contextFace.drawImage(img, this.size * -1, 0, this.size, this.size);
		
		// épaisseur de l'item
		var blocCanvas = new THREE.Mesh( this.drawCanvas( contextPile ), new THREE.MeshBasicMaterial({
				color : 0x25050e,
				wireframe : this.wireframe
		}));	
		blocCanvas.castShadow = true;
		this.geometry.add( blocCanvas );
				
		// pile de l'item
		var pile = new THREE.Mesh( new THREE.PlaneGeometry(this.size,this.size), this.material( canvasPile ) );
		pile.receiveShadow = true;
		pile.position.z = -(this.dept/2 + 0.005);
		pile.rotation.y = -180 * Math.PI / 180;
		pile.matrixAutoUpdate = true;
		pile.updateMatrix();
		this.geometry.add(pile);
			
		// face de l'item'
		var face = new THREE.Mesh( new THREE.PlaneGeometry(this.size,this.size), this.material( canvasFace ));
		face.receiveShadow = true;
		face.position.z =  + (this.dept/2 + 0.005);
		face.matrixAutoUpdate = true;
		face.updateMatrix();
		this.geometry.add(face);
		
		this.add(this.geometry);
}

THREE.Item.prototype = Object.create( THREE.Object3D.prototype );