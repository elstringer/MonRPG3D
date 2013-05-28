$(function(){
		/*
		 * MENU TOP LEFT
		 */
		$('#menu_user').live('click', function() {
				overlay.load('user' );
		});
		
		$('#menu_update').live('click', function() {
				overlay.load('user/show/update' );
		});
		
		$('#menu_badge').live('click', function() {
				overlay.load('user/show/badge' );
		});
		
		$('#overlay_close').live( 'click', function(){
				overlay.close();
		});
		
		$('#control_logout').live('click', function() {
				redirect( url_script + 'logout' );
		});
});
