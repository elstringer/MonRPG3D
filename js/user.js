$(function() {
		$('#modif_pwd').live('click', function(){
				
				var new_pwd = $('#new_pwd').val();
				
				$('#repeat_new_pwd, #new_pwd').removeClass('border-rouge');
				
				if( new_pwd == '')
				{
						$('#new_pwd').addClass('border-rouge');
						return;
				}
				
				if( new_pwd != $('#repeat_new_pwd').val() )
				{
						$('#repeat_new_pwd, #new_pwd').addClass('border-rouge');
						return;
				}
		
				$.post(url_script+'user/update_pwd', {
						'new_pwd' : new_pwd
				}, function( data ) {
						$('body').append(data);
						msg();
						$('#repeat_new_pwd, #new_pwd').val('');
				});
		});
});