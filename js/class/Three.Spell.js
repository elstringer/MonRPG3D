THREE.Spell = function (  size, radius, segments, rings, color, scene ) {

		THREE.Object3D.call( this );
		
		this.particles = new THREE.Geometry();
		
		this.geometry = new THREE.Geometry();
		
		this.size = size !== undefined ? size : 5;
		
		this.radius = radius !== undefined ? radius : 10;
		
		this.segments = segments !== undefined ? segments : 25;
		
		this.rings = rings !== undefined ? rings : 15;
		
		this.color = color !== undefined ? color : '#555555';
		
		this.hit = 0;
		
		eval('this.correctColor = 0x'+this.color.replace('#', '' ) );
				
		this.material = new THREE.ParticleBasicMaterial( {
				size: this.size, 
				sizeAttenuation: false,
				color : this.correctColor
		} );
		
		
		
		/*
		 * SET position du spell
		 */
		this.setPosition = function( x, y, z, no_calcul ) {
				if(no_calcul) {
						this.position.set(x, y, z);
						return;
				}
				this.position.x = -( scene.data.map.size.xMax * scene.data.map.size.elements / 2) + (x * scene.data.map.size.elements) + (scene.data.map.size.elements/2);
				this.position.y = y * scene.data.map.size.elements + scene.data.map.size.elements;
				this.position.z = -( scene.data.map.size.zMax * scene.data.map.size.elements / 2) + (z * scene.data.map.size.elements) + (scene.data.map.size.elements/2);						
		};
		
		
		
		/*
		 * UPDATE
		 */
		this.update = function( scene ) {	
				if( this.particles.rotation != undefined )
						this.particles.rotation.z += 0.2;
				this.material.size -= 0.1;
		};
		
		
		/*
		 * Constructor item
		 */
		this.particles = new THREE.ParticleSystem( new THREE.SphereGeometry(this.radius, this.segments, this.rings), this.material );
		this.particles.sortParticles = true;
		this.add( this.particles );
		
		this.castShadow = true;
		this.receiveShadow = true;
}

THREE.Spell.prototype = Object.create( THREE.Object3D.prototype );