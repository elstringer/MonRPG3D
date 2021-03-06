if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

var urlReplace = 'http://'+window.location.hostname+'/';
var container, stats;
var camera, scene, renderer;
var projector, plane, cube;
var mouse2D, mouse3D, ray,
rollOveredFace, isShiftDown = false,
theta = 45, isCtrlDown = false, sizeMap = 40, control, cubes;

var hoverTool = false;
var typeAction = 'no';

var listImg = {}, obstacles = [], modules = [], listCube = {};

var rollOverMesh, rollOverMaterial, voxelPosition = new THREE.Vector3(), tmpVec = new THREE.Vector3();
var i, intersector;
var idClickMaterial;
var dataTextureCube = {};
var MeshFaceMaterial = new THREE.MeshFaceMaterial({
		transparent: true
});

var clock = new THREE.Clock();
		
$(function(){
		init();
		animate();
	
		$('.cubeBackground').click(function() {
				$.facebox({
						ajax: url_script+'mapping/listing_material'
				});
				idClickMaterial = this.id;
		});
		
		$("#selectAction, #controlCube").hover(
				function () {
						hoverTool = true;
				},
				function () {
						hoverTool = false;
				}
				);
	
		$('.material').live('click', function() {
				$('#'+idClickMaterial).attr('src', dir_script+'../images/background/'+this.id);	
				
				if( idClickMaterial == 'bloc_all')
						for(var i=1; i <= 6; i++)
								$('#bloc_'+i).attr('src', dir_script+'../images/background/'+this.id);
				
				idClickMaterial = null;
				
				typeAction = $("input[name='action']:checked").val();
								
				var position = rollOverMesh.position;
				
				dataTextureCube = getTexutreVoxel();
				
				if(typeAction == 'mod')
						return;
				
				scene.remove(rollOverMesh);
						
				rollOverMesh = addCube( dataTextureCube );
				rollOverMesh.position = position;
				
				if( typeAction == 'del' || typeAction == 'no' )
						rollOverMesh.visible = false;
				
				scene.add( rollOverMesh );				
		});
				
		
		$("input[name='action']").live('click', function() {
				scene.remove(rollOverMesh);
				
				typeAction = $("input[name='action']:checked").val();
				if( typeAction == 'no' )
						return;
				else if( typeAction == 'add' ){
						rollOverMesh = addCube( dataTextureCube );
						rollOverMesh.visible = true;
				} else {
						rollOverMesh = new THREE.Mesh( new THREE.CubeGeometry( 51, 51, 51 ), new THREE.MeshBasicMaterial( {
								color: typeAction == 'del' ? 0xff0000 : 0xffff00, 
								opacity: 0.3, 
								transparent: true
						} ) );
				}
		
				scene.add( rollOverMesh );
		});
});



/*
 *WEBGL
 */

function init() {

		container = document.getElementById('main');
				
		camera = new THREE.CombinedCamera( window.innerWidth, window.innerHeight, 60, 1, 8000 );
		
		controls = new THREE.FirstPersonControls( camera, container );

		controls.object.position.y = 100;
		controls.object.position.x = -(dataRegion.x * 50 / 2);
		controls.object.position.z = -(dataRegion.z * 50 / 2);
		controls.movementSpeed = 800;
		controls.lookSpeed = 0.1;
		controls.lookVertical = true;
		controls.lookVertical = true;

		scene = new THREE.Scene();
		scene.fog = new THREE.FogExp2( dataRegion.background_color, 0.0006 );
		

		// roll-over helpers
		rollOverMesh = addCube( getTexutreVoxel() );
		rollOverMesh.position.y += 50 / 2;
		rollOverMesh.position.x += 50 / 2;
		rollOverMesh.position.z += 50 / 2;
		rollOverMesh.visible = false;
		scene.add( rollOverMesh );

		// picking

		projector = new THREE.Projector();

		// grid				
		console.log(dir_script+'../'+dataRegion.background);
		var material = THREE.ImageUtils.loadTexture( dir_script+'../'+dataRegion.background );
		material.wrapS = material.wrapT = THREE.RepeatWrapping;
		material.repeat.set( dataRegion.x, dataRegion.z );
		material.magFilter= THREE.NearestFilter;
		material.minFilter = THREE.LinearMipMapLinearFilter;
		
				
		var plane = new THREE.Mesh( new THREE.PlaneGeometry( dataRegion.x * 50, dataRegion.z * 50, dataRegion.x, dataRegion.z), new THREE.MeshBasicMaterial( {
				map : material
		} ));
		plane.rotation.x = - Math.PI / 2;
		plane.position.y = -1;
		plane.name = 'planeBackground';
				
		scene.add( plane );

		plane = new THREE.Mesh( new THREE.PlaneGeometry( dataRegion.x * 50, dataRegion.z * 50, dataRegion.x, dataRegion.z ), new THREE.MeshBasicMaterial( {
				color: 0xffffff, 
				wireframe: true
		} ) );
		plane.rotation.x = - Math.PI / 2;
		plane.name = 'planeGrille';
		scene.add( plane );
				
		for ( keyEl in dataElements )
				obstacles.push(dataElements[keyEl]);
				
		getCubes();

		mouse2D = new THREE.Vector3( 0, 10000, 0.5 );

		// Lights
		var ambientLight = new THREE.AmbientLight( 0xffffff );
		scene.add( ambientLight );

		var directionalLight = new THREE.DirectionalLight( 0xffffff );
		directionalLight.position.set( 1, 0.75, 0.5 ).normalize();
		scene.add( directionalLight );

		renderer = new THREE.WebGLRenderer({
				clearColor : dataRegion.background_color,
				antialias		: false, 
				clearAlpha: true
		});
		
		renderer.setSize( window.innerWidth, window.innerHeight );

		container.appendChild( renderer.domElement );

		stats = new Stats();
		stats.domElement.style.position = 'absolute';
		stats.domElement.style.top = '60px';
		stats.domElement.style.right = '20px';
		container.appendChild( stats.domElement );
		
		container.addEventListener( 'mousemove', onDocumentMouseMove, false );
		container.addEventListener( 'mouseup', onDocumentMouseUp, false );
		window.addEventListener( 'resize', onWindowResize, false );
}



/*
 * Ajout des cubes sur la map + le sol (clear la map auto avant l'execution)
 */
function getCubes() {
		container.style.display = 'none';
				
		if( renderer )
				for( var key in scene.children )
						if( scene.children[key].name == 'cube' )
								scene.remove(scene.children[key]);		
		
		var geometry = new THREE.Geometry();
		
		for( key in obstacles)
		{
				var row = obstacles[key];
				
				var dataSend = {
						background_px: dir_script+'../'+row.materials[0],
						background_nx: dir_script+'../'+row.materials[1], 
						background_py: dir_script+'../'+row.materials[2], 
						background_ny: dir_script+'../'+row.materials[3], 
						background_pz: dir_script+'../'+row.materials[4], 
						background_nz: dir_script+'../'+row.materials[5]
				};

				var voxel = addCube( dataSend );
						
				voxel.position.x = (row.x - 1) * 50 + 25 - ( dataRegion.x * 50 / 2);
				voxel.position.y = row.y * 50 - 25;
				voxel.position.z = (row.z - 1) * 50 + 25 - ( dataRegion.z * 50 / 2);
					
				THREE.GeometryUtils.merge( geometry, voxel );
		}
		
		cubes = new THREE.Mesh( geometry, MeshFaceMaterial );
		cubes.name = 'cube';
		scene.add( cubes );
		container.style.display = 'block';
}



/*
 * Cr√©er un cube
 */
function addCube(dataSend) {									
		var path = dataSend.background_px+dataSend.background_nx+dataSend.background_py+dataSend.background_ny+dataSend.background_pz+dataSend.background_nz;
		if( listCube[path] == undefined  )
				listCube[path] = {
						scr : path,
						cube : new THREE.CubeGeometry( 50, 50, 50, 0, 0, 0, [
								loadTexture( dataSend.background_px ),
								loadTexture( dataSend.background_nx ),
								loadTexture( dataSend.background_py ),
								loadTexture( dataSend.background_ny ),
								loadTexture( dataSend.background_pz ),
								loadTexture( dataSend.background_nz )
								] )
				};
				
		var mesh = new THREE.Mesh( listCube[path].cube, MeshFaceMaterial );
		mesh.name = 'cube';
		return mesh;
}



/*
 * Resize de la fenetre
 */
function onWindowResize() {
		camera.setSize( window.innerWidth, window.innerHeight );
		camera.updateProjectionMatrix();

		renderer.setSize( window.innerWidth, window.innerHeight );
}



/*
 * Detection collision voxel
 */
function getRealIntersector( intersects ) {

		for( i = 0; i < intersects.length; i++ )
				if ( intersects[ i ].object != rollOverMesh && intersects[ i ].distance < 3000 )
						return intersects[ i ];
		
		return null;
}



/*
 * Calcul de la position du voxel (add / mod)
 */
function setVoxelPosition( intersector, type ) {
		
		if(intersector.distance > 3000)
				return;
		
		tmpVec.copy( intersector.face.normal );

		voxelPosition.add( intersector.point, intersector.object.matrixRotationWorld.multiplyVector3( tmpVec ) );
		
		var position = voxelPosition;
		
		if(!type) {
				position = intersector.point;
				position.x -= intersector.face.normal.x > 0 ? 50 : 0;
				position.y -= intersector.face.normal.y > 0 ? 50 : 0;
				position.z -= intersector.face.normal.z > 0 ? 50 : 0;
		}
		
		voxelPosition.x = Math.floor( position.x / 50 ) * 50 + 25;
		voxelPosition.y = Math.floor( position.y / 50 ) * 50 + 25;
		voxelPosition.z = Math.floor( position.z / 50 ) * 50 + 25;
}



/*
 * D√©placement de la sourie
 */
function onDocumentMouseMove( event ) {
		event.preventDefault();
		
		if(typeAction == 'no')
				return;

		mouse2D.x = ( event.clientX / window.innerWidth ) * 2 - 1;
		mouse2D.y = - ( event.clientY / window.innerHeight ) * 2 + 1;
		
		ray = projector.pickingRay( mouse2D.clone(), camera );

		var intersects = ray.intersectObjects( scene.children );
		if ( intersects.length > 0 ) {
				intersector = getRealIntersector( intersects );
				if ( intersector ) {
						setVoxelPosition( intersector, (typeAction == 'del' || typeAction == 'edit' ? false : true) );
						rollOverMesh.position = voxelPosition;
				}
		}
}



function onDocumentMouseUp( event ) {
		event.preventDefault();
		
		if(typeAction == 'no' || hoverTool || !ray.intersectObjects( scene.children ).length ) 
				return;

		var coordonnee = {		
				x : Math.floor( (voxelPosition.x + (dataRegion.x * 50 / 2))/50 ) + 1,
				y : Math.floor( (voxelPosition.y) / 50 ) + 1,
				z : Math.floor( (voxelPosition.z + (dataRegion.z * 50 / 2))/50 ) + 1,
				region_id : dataRegion.id,
				materials : [
				dataTextureCube.background_px,
				dataTextureCube.background_nx, 
				dataTextureCube.background_py, 
				dataTextureCube.background_ny, 
				dataTextureCube.background_pz, 
				dataTextureCube.background_pz
				]
		};
		var setCoordonnee = coordonnee;
		
		typeAction = $("input[name='action']:checked").val();

		if ( typeAction == 'del' ) {
				$.post(url_script+'mapping/remove/', coordonnee);
				for( key in obstacles)
						if( obstacles[key].x == setCoordonnee.x && obstacles[key].y == setCoordonnee.y && obstacles[key].z == setCoordonnee.z )
								delete obstacles[key];
				setCoordonnee = false;
				
				getCubes();
				return;
		} 

		if ( typeAction == 'edit' ) {
				$.facebox({
						ajax: url_script+'mapping/form/'+dataRegion.id+'/'+coordonnee.x+'/'+coordonnee.y+'/'+coordonnee.z
				});
				return;
		} 
		
		if ( typeAction == 'mod' ) {
				for(key in coordonnee.materials)
						coordonnee.materials[key] = 'images/background/module.png';
						
				setCoordonnee = coordonnee;
				$.facebox({
						ajax: url_script+'mapping/form/'+dataRegion.id+'/'+coordonnee.x+'/'+coordonnee.y+'/'+coordonnee.z
				});
				
				obstacles.push(setCoordonnee);
		} 
		else {
				for(key in coordonnee.materials)
						coordonnee.materials[key] = coordonnee.materials[key].replace(urlReplace,'');
						
				$.post(url_script+'mapping/add/', coordonnee);
				
				obstacles.push(setCoordonnee);
		}
		
		if( scene.children.length < 200 ) {
				var dataSend = {
						background_px: dir_script+'../'+coordonnee.materials[0],
						background_nx: dir_script+'../'+coordonnee.materials[1], 
						background_py: dir_script+'../'+coordonnee.materials[2], 
						background_ny: dir_script+'../'+coordonnee.materials[3], 
						background_pz: dir_script+'../'+coordonnee.materials[4], 
						background_nz: dir_script+'../'+coordonnee.materials[5]
				};
				
				var voxel = addCube( dataSend );
						
				voxel.position.x = (coordonnee.x - 1) * 50 + 25 - ( dataRegion.x * 50 / 2);
				voxel.position.y = coordonnee.y * 50 - 25;
				voxel.position.z = (coordonnee.z - 1) * 50 + 25 - ( dataRegion.z * 50 / 2);
				
				scene.add(voxel);
		}
		else
				getCubes();
}



/*
 * Traitement de l'animation
 */
function animate() {
		requestAnimationFrame( animate );
		if( !$('#facebox').is(':visible') )
				render();
		stats.update();
}



/*
 * Render
 */
function render() {
		var info = renderer.info;
		//console.log('Memory Geometrie : '+info.memory.geometries+' - Memory programs : '+info.memory.programs+' - Memory textures : '+info.memory.textures+' - Render calls : '+info.render.calls+' - Render vertices : '+info.render.vertices+' - Render faces : '+info.render.faces+' - Render points : '+info.render.points);
		//console.log(info.render.vertices,info.render.faces);
		controls.update( clock.getDelta(), dataRegion );
		renderer.render( scene, camera );
}


	
/*
 * Chargement des textures
 */
function getTexutreVoxel() {
		return dataTextureCube =  {
				background_px: document.getElementById('bloc_2').src,
				background_nx: document.getElementById('bloc_4').src,
				background_py: document.getElementById('bloc_1').src,
				background_ny: document.getElementById('bloc_6').src,
				background_pz: document.getElementById('bloc_3').src,
				background_nz: document.getElementById('bloc_5').src
		};
}

	
/*
 * Chargement des textures
 */
function loadTexture( path ) {
		
		if( listImg[path] !== undefined  )
				return listImg[path].mesh;
		
		var texture  = THREE.ImageUtils.loadTexture( path );
		texture.wrapS = texture.wrapT = THREE.ClampToEdgeWrapping;
		texture.magFilter= THREE.NearestFilter;
		texture.minFilter = THREE.LinearMipMapLinearFilter;
				
		listImg[path] = {
				scr : path,
				mesh : new THREE.MeshLambertMaterial( {
						map: texture, 
						ambient: 0xbbbbbb,
						transparent: true
				} )
		};
				
		return listImg[path].mesh;
}


