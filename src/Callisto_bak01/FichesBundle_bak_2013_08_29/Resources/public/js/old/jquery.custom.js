
	$(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto({
			animationSpeed: 'fast', /* fast/slow/normal */
			padding: 20, /* padding for each side of the picture */
			opacity: 0, /* Value betwee 0 and 1 */
			showTitle: false, /* true/false */
			allowresize: true, /* true/false */
            modal:false,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'facebook' /* light_rounded / dark_rounded / light_square / dark_square */
		});
        
        
         //FICHES ASSOCIEES
	   
	    $('a#addRowFiche').click(function(){
			$('table#associated').append('<tr><td></td><td><input type="text" class="admin" name="id_fiche_associee[]" size="5" /></td><td></td></tr>');
		});
		
		
		 //FICHIERS ASSOCIES
	   
	    $('a#addRowFile').click(function(){
        
            var oRows = document.getElementById('associatedfiles').getElementsByTagName('tr');
            var number = oRows.length;
            
            var limit = 1;
                    
            if (limit == number) {
			$('table#associatedfiles').append('<tr><td></td><td><input type="file" class="admin" name="fichier_associe[]" size="50" /></td></tr>');
            limit = limit-1;            
            }
		});
        
      		
		 	   
	});

