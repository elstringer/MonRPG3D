function calcul_sort()
{
		var somme = 0,
		prix_total = $('#prix_total');
	
		$('.select_sort').each(function() {
		
				var prix = $(this).parents('tr').find('.prix').html(),
				value = $(this).val();

				if( value > 0 )
						somme += parseInt(prix) * value;
			
		});
	
		prix_total.html(somme);
	
		if(somme > 0 && somme <= $('#argent_user').val())
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