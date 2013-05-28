function calcul_shop()
{
		var somme = 0,
		prix_total = $('#prix_total');
	
		$('.select_item').each(function() {
		
				var prix = $(this).parents('tr').find('.prix').html(),
				value = $(this).val();

				if( value > 0 )
						somme += parseInt(prix) * value;
			
		});
	
		prix_total.html(somme);
		
		if(!$('#argent_user').length)
		{
				prix_total.attr('class','vert');
		}
		else if(somme > 0 && somme <= $('#argent_user').val())
		{
				$('#show_buy').show();
				prix_total.attr('class','vert');
		}
		else
		{
				$('#show_buy').hide();
				if(somme > 0)
						prix_total.attr('class','rouge');
				else
						prix_total.removeClass('rouge vert');
		}
}