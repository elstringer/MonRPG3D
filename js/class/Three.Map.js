THREE.Map = function ( sound ) {

		THREE.Object3D.call( this );
				
		this.wireframe = false;
		
		this.timeCycle = 0.000005;
		
		this.distanceAstre = 4000;
		
		this.ambient = false;
		
		this.sun = false;
		
		this.moon = false;
		
		this.light = false;
		
		this.regions = {};
		
		this.listImg = {};
		
		this.listCube = {};
		
		
		/*
		 * Lumière du soleil
		 */
		this.getLight = function() {
				
				this.light = new THREE.SpotLight( 0xffffff );
				this.light.shadowCameraNear = this.distanceAstre;
				this.light.shadowCameraFar = this.distanceAstre * 4;
				this.light.shadowCameraFov = 40;
				this.light.castShadow = true;
				this.light.shadowBias = 0.001;
				this.light.shadowDarkness = 0.5;
				this.light.shadowCameraVisible = this.wireframe;
				
				return this.light;
		};
		
		
		/*
		 * La forme du soleil soleil
		 */
		this.getSun = function() {
				this.sun = new THREE.Mesh(new THREE.SphereGeometry(500, 10, 10), new THREE.MeshBasicMaterial({
						color: 0xffe404,
						wireframe : this.wireframe, 
						transparent : true
				}));
				this.sun.position = this.light.position.clone();
				
				return this.sun;
		};
		
		
		/*
		 * La forme de la lune
		 */
		this.getMoon = function() {
				this.moon = new THREE.Mesh(new THREE.SphereGeometry(150, 10, 10), new THREE.MeshBasicMaterial({
						color: 0xffffff,
						wireframe : this.wireframe, 
						transparent : true
				}));
				this.moon.position = this.light.position.clone();
				this.moon.position.x *= -1;
				this.moon.position.y *= -1;
				this.moon.position.z *= -1;
				
				return this.moon;
		};
		
		
		/*
		 * Lumière d'ambience
		 */
		this.getAmbience = function() {
				this.ambient = new THREE.AmbientLight( 0x444444 );
				return this.ambient;
		};
		
		
		/*
		 * Aplat du sol et des murs de limitation de terrain
		 */
		this.setData = function( data ) {
				info('Traitement des régions');
				for( var keyUnivers in data.maps ) {
						var map = data.maps[keyUnivers].map;
												
						var univers = new THREE.Object3D();
						var geometry = new THREE.Geometry();
				
						var material = new THREE.Texture( map.materials, new THREE.UVMapping(), THREE.ClampToEdgeWrapping, THREE.ClampToEdgeWrapping, THREE.NearestFilter );
						material.wrapS = material.wrapT = THREE.RepeatWrapping;
						material.repeat.set( map.size.xMax, map.size.zMax );
						material.needsUpdate = true;
				
								
						var mesh = new THREE.Mesh( new THREE.PlaneGeometry(map.size.xMax * map.size.elements * 2, map.size.zMax * map.size.elements * 2), new THREE.MeshLambertMaterial( {
								map : material,
								wireframe : this.wireframe
						} ));
						mesh.position.y = map.size.elements/2;
						mesh.rotation.x = -90 * Math.PI / 180;
						mesh.receiveShadow = true;
						univers.add(mesh);
								
						material = new THREE.Texture( map.univers, new THREE.UVMapping(), THREE.ClampToEdgeWrapping, THREE.ClampToEdgeWrapping, THREE.NearestFilter );
						material.wrapS = material.wrapT = THREE.RepeatWrapping;
						material.needsUpdate = true;
				
						var face = new THREE.PlaneGeometry(map.size.xMax * map.size.elements, map.size.yMax * map.size.elements);
						var materialMesh = new THREE.MeshLambertMaterial( {
								map : material,
								wireframe : this.wireframe, 
								transparent : true
						} );
				
						var nz = new THREE.Mesh( face, materialMesh);
						nz.position.z -= map.size.zMax * map.size.elements / 2;
						nz.position.y = map.size.yMax * map.size.elements / 2;
						univers.add(nz);
				
						var pz = new THREE.Mesh( face, materialMesh);
						pz.position.z = map.size.zMax * map.size.elements / 2;
						pz.position.y = map.size.yMax * map.size.elements / 2;
						pz.rotation.y = -180 * Math.PI / 180;
						univers.add(pz);
				
						var nx = new THREE.Mesh( face, materialMesh);
						nx.position.x -= map.size.xMax * map.size.elements / 2;
						nx.position.y = map.size.yMax * map.size.elements / 2;
						nx.rotation.y = 90 * Math.PI / 180;
						univers.add(nx);

						var px = new THREE.Mesh( face, materialMesh);
						px.position.x = map.size.xMax * map.size.elements / 2;
						px.position.y = map.size.yMax * map.size.elements / 2;
						px.rotation.y = -90 * Math.PI / 180;
						univers.add(px);
				
						var obstacles = {};
						var modules = {};
						for ( var x = 0; x <= map.size.xMax + 1; x ++ ) {
								obstacles[x] = {};
								for ( var y = 0; y <= map.size.yMax + 1; y ++ ) {
										obstacles[x][y] = {};
										for ( var z = 0; z <= map.size.zMax + 1; z ++ )
												obstacles[x][y][z] = y == 0 ? true: false;
								}
						}
				
						for ( var keyEl in map.elements )
								obstacles[map.elements[keyEl].x][map.elements[keyEl].y][map.elements[keyEl].z] = true;
				
						for ( var keyModule in map.modules )
								modules[map.modules[keyModule].x+'-'+map.modules[keyModule].y+'-'+map.modules[keyModule].z] = map.modules[keyModule];
								
						for ( var keyEle in map.elements ) {
								var row = map.elements[keyEle];
								var cube = this.addCube( row, obstacles, map );
								
								cube.position.x = -(map.size.xMax * map.size.elements / 2 ) + row.x * map.size.elements - (map.size.elements/2);
								cube.position.y = row.y * map.size.elements;
								cube.position.z = -(map.size.zMax * map.size.elements / 2 ) + row.z * map.size.elements - (map.size.elements/2);
						
								THREE.GeometryUtils.merge( geometry, cube );
						}
				
						var element = new THREE.Mesh( geometry, new THREE.MeshFaceMaterial() );
						element.castShadow = true;
						element.receiveShadow = true;
					//	element.matrixAutoUpdate = false;
					//	element.updateMatrix();
				
						univers.add(element);
						univers.name = 'map';
						
						this.regions[keyUnivers] = {
								univers : univers, 
								obstacles : obstacles, 
								modules : modules,  
								bots : data.maps[keyUnivers].bots.list, 
								size : map.size
						};
				}
		};
		
		
		/*
		 * GET data for obstacles map current
		 */
		this.getObstacles = function( id ) {
				return this.regions['region_'+id].obstacles;
		}
		
		
		/*
		 * GET data for module map current
		 */
		this.getModules = function( id ) {
				return this.regions['region_'+id].modules;
		}
		
		
		/*
		 * GET data for univers map current
		 */
		this.getUnivers = function( id ) {
				return this.regions['region_'+id].univers;
		}
		
		
		/*
		 * GET data for bots map current
		 */
		this.getBots = function( id ) {
				return this.regions['region_'+id].bots;
		}
		
		
		/*
		 * GET data for size map current
		 */
		this.getSize = function( id ) {
				return this.regions['region_'+id].size;
		}
		
		
		
		/*
		 * Delete session 
		 */
		this.hasObstacle = function(x, y, z, id) {
				var obstacles = this.regions['region_'+id].obstacles;
				if( obstacles[x] != undefined && obstacles[x][y] != undefined && obstacles[x][y][z] != undefined && obstacles[x][y][z] ) {
						return true;
				}
				return false;
		};
		
		
		/*
		 * Charger map current
		 */
		this.loadUnivers = function( scene ) {
				for( var key in scene.children )
						if( scene.children[key].name == 'map'){
								scene.remove(scene.children[key]);
								break;
						}
				
				scene.add( this.getUnivers(scene.data.my.region) );
		}
		
		
		/*
		 * Mise a jour des différents éléments du terrain
		 * Lumière soleil
		 * Forme soleil
		 * Forme lune
		 * Effet de brume
		 */
		this.update = function( scene ) {	
				var angle = Date.now() * this.timeCycle;
				var x = this.distanceAstre * Math.cos(angle);
				var y = this.distanceAstre * Math.sin(angle);
				
				if( this.light ) {
						this.light.position.set(x * 2, y * 2, 0);
		
						if(this.light.position.y <= 0){
								if( scene.fog.density < 0.001)
										scene.fog.density += 0.00001;
								this.light.shadowDarkness = 0.2;
						} else {
								if( scene.fog.density > 0.0001)
										scene.fog.density -= 0.00001;
								this.light.shadowDarkness = 0.5;
						}	
				}
				
				if( this.sun )
						this.sun.position.set(x, y, 0);
		
				if( this.moon )
						this.moon.position.set(x * -1, y * -1, 0);
		};
		

		/*
		 * Tool load image / gestion en cache
		 */
		this.loadTexture = function( path ) {
				if( this.listImg[path.src] !== undefined  )
						return this.listImg[path.src];

				var material = new THREE.MeshLambertMaterial( {
						map: new THREE.Texture( path, new THREE.UVMapping(), THREE.ClampToEdgeWrapping, THREE.ClampToEdgeWrapping, THREE.NearestFilter, THREE.LinearMipMapLinearFilter ), 
						ambient: 0xbbbbbb,
						wireframe : this.wireframe, 
						transparent : true
				} );
				material.map.needsUpdate = true;
				
				return this.listImg[path.src] = material;
		};
		

		/*
		 * Create element 
		 */
		this.addCube = function ( row, obstacles, map ) {
				var title = [];
				var faces = {
						px: obstacles[row.x+1][row.y][row.z] ? false : true, 
						nx: obstacles[row.x-1][row.y][row.z] ? false : true, 
						py: obstacles[row.x][row.y+1][row.z] ? false : true, 
						ny: obstacles[row.x][row.y-1][row.z] ? false : true, 
						pz: obstacles[row.x][row.y][row.z+1] ? false : true, 
						nz: obstacles[row.x][row.y][row.z-1] ? false : true
				};
				
				if( Object.prototype.toString.apply( row.materials ) === '[object Array]' ) {
						for( keyImg in row.materials )
								if( row.materials){
										title.push(row.materials[keyImg].src);
										row.materials[keyImg] =  this.loadTexture( row.materials[keyImg] );
								}
				}
				else if ( row.materials ){
						title.push(row.materials.src);
						row.materials =  this.loadTexture( row.materials );
				}
				
				title = title.join('-')+'-'+faces.px+'-'+faces.nx+'-'+faces.py+'-'+faces.ny+'-'+faces.pz+'-'+faces.nz;
				
				if( this.listCube[title] !== undefined  )
						return this.listCube[title];
				
				return this.listCube[title] = new THREE.Mesh( new THREE.CubeGeometry( map.size.elements, map.size.elements, map.size.elements, 0, 0, 0, row.materials, faces ) );					
		};
		
		
		this.getOverModule = function ( regionID, position ) {
				if( this.regions['region_'+regionID].modules[position.x+'-'+position.y+'-'+position.z] != undefined )
						return this.regions['region_'+regionID].modules[position.x+'-'+position.y+'-'+position.z];
				
				return false;
		};
};

THREE.Map.prototype = Object.create( THREE.Object3D.prototype );