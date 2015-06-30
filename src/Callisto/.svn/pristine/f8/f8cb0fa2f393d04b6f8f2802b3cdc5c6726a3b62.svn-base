
	$(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto({
			animationSpeed: 'fast', /* fast/slow/normal */
			padding: 40, /* padding for each side of the picture */
			opacity: 0, /* Value betwee 0 and 1 */
			showTitle: false, /* true/false */
			allowresize: true, /* true/false */
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'dark_rounded' /* light_rounded / dark_rounded / light_square / dark_square */
		});
		
		// On créer notre tablesorter sur l'id mytable
	   /* Et on trie (au départ) par la première colonne
	   if($("#mytable").size() > 0) {
	   $("#mytable").tablesorter({sortList:[[0,0]], widgets: ['zebra']}).tablesorterPager({container: $("#pager")});
	   // On ajoute la pagination sur l'id #pager
	   }*/
	   
	   //INGREDIENTS
	   $('a#addRowIng').click(function(){
									  
			var number = parseInt($('table#ingredients input.number:last').val());
			var number_next = number+1;
			
			$('table#ingredients').append('<tr class="'+number_next+'"><td><input type="hidden" class="number" value="'+number_next+'" /><a class="removeRow Ing">'+number_next+'</a></td><td><input name="poids[]" type="text" size="5" value="" /></td><td><input name="pc[]" type="text" size="2" value="" /></td><td><input name="matiere[]" type="text" size="" value="" /></td><td><input name="indication[]" type="text" size="" value="" /></td><td><input type="hidden" class="hidden" name="pression['+number+']" value="" /><input name="pression['+number+']" value="on" type="checkbox"  /></td></tr>');
	   
		});
	   
	   $('a.removeRow.Ing').live('click',function() {						  
			var number = parseInt($(this).text());
			if ($('table#ingredients tr').size() != 3) {
				$('table#ingredients tr.'+number+'').remove();
			}
			
		});
        
        
         //INGREDIENTS2
	   $('a#addRowIng2').click(function(){
									  
			var number = parseInt($('table#ingredients2 input.number:last').val());
			var number_next = number+1;
			
			$('table#ingredients2').append('<tr class="'+number_next+'"><td><input type="hidden" class="number" value="'+number_next+'" /><a class="removeRow Ing">'+number_next+'</a></td><td><input name="poids2[]" type="text" size="5" value="" /></td><td><input name="pc2[]" type="text" size="2" value="" /></td><td><input name="matiere2[]" type="text" size="" value="" /></td><td><input name="indication2[]" type="text" size="" value="" /></td><td><input type="hidden" class="hidden" name="pression2['+number+']" value="" /><input name="pression2['+number+']" value="on" type="checkbox"  /></td></tr>');
	   
		});
	   
	   $('a.removeRow.Ing2').live('click',function() {						  
			var number = parseInt($(this).text());
			if ($('table#ingredients2 tr').size() != 3) {
				$('table#ingredients2 tr.'+number+'').remove();
			}
			
		});

	   
	   //OBSERVATION
	   $('a#addRowObs').click(function(){
									  
			var number = parseInt($('table#observation input.number:last').val());
			var number_next = number+1;
			
			$('table#observation').append('<tr class="'+number_next+'"><td><input type="hidden" class="number" value="'+number_next+'" /><a class="removeRow Obs">'+number_next+'</a></td><td><input name="observation[]" type="text" size="75" /></td></tr>');
		});
	   
	   
	   $('a.removeRow.Obs').live('click',function() {									  
			var number = parseInt($(this).text());
			if ($('table#observation tr').size() != 2) {
				$('table#observation tr.'+number+'').remove();
			}
		});
	   
	   //DESCRIPTION
	   $('a#addRowDes').click(function(){
									  
			var number = parseInt($('table#description input.number:last').val());
			var number_next = number+1;
			
			$('table#description').append('<tr class="'+number_next+'"><td><input type="hidden" class="number" value="'+number_next+'" /><a class="removeRow Des">'+number_next+'</a></td><td><input name="description[]" type="text" size="65" /></td><td><input type="hidden" class="hidden" name="pression3['+number+']" value="" /><input name="pression3['+number+']" value="on" type="checkbox"  /></td></tr>');
		});
	   
	   
	   $('a.removeRow.Des').live('click',function() {									  
			var number = parseInt($(this).text());
			if ($('table#description tr').size() != 2) {
				$('table#description tr.'+number+'').remove();
			}
		});
        
    //ASSOCIATED LINKS
	   $('a#addRowAssoc').click(function(){
									  
			var number = parseInt($('table#assoc input.number:last').val());
			var number_next = number+1;
			
			$('table#assoc').append('<tr class="'+number_next+'"><td><input type="hidden" class="number" value="'+number_next+'" /><a class="removeRow Assoc">'+number_next+'</a></td><td><input name="descriptionassoc[]" type="text" size="75" /></td></tr>');
		});
	   
	   
	   $('a.removeRow.Assoc').live('click',function() {									  
			var number = parseInt($(this).text());
			if ($('table#assoc tr').size() != 2) {
				$('table#assoc tr.'+number+'').remove();
			}
		});

	   
	   //APPRENTI
	   
	    $('a#addRowApp').click(function(){
			$('table#apprentis').append('<tr><td></td><td><input type="text" class="admin" name="nom[]" /></td><td><input type="text" class="admin" name="prenom[]" /></td><td><input type="text" class="admin" name="email[]" /></td><td><input type="text" class="admin" name="password[]" /></td></tr>');
		});
		
		//FORMATEUR
		 $('a#addRowFor').click(function(){
			$('table#formateurs').append('<tr><td></td><td><input type="text" class="admin" name="nom[]" /></td><td><input type="text" class="admin" name="prenom[]" /></td><td><input type="text" class="admin" name="email[]" /></td><td><input type="text" class="admin" name="password[]" /></td></tr>');
		});
	   
	   
	   
	   
	   
	});

