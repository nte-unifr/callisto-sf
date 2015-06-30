$(document).ready(function() {

    $("a.fancybox").fancybox();

    $('#mycarousel').jcarousel({
        vertical: true,
        scroll: 2
    });

    $('#mycarousel1').jcarousel({
        vertical: true,
        scroll: 2
    });

    $('#mycarousel2').jcarousel({
        vertical: true,
        scroll: 2
    });

    $('#mycarousel3').jcarousel({
        vertical: true,
        scroll: 2
    });
    
    $("#toggle").click(function () {
        $("#panier").toggle();
    });

});

function addToBasket(id) {
    var url = document.URL;
    var title = document.getElementById("title").innerHTML;
    var image = document.getElementById("image_principale").src;
    var dates = document.getElementById("dates").innerHTML;
    var categorie = document.getElementById("categorie").innerHTML;
    var conservation = document.getElementById("conservation").innerHTML;
    var source_image = document.getElementById("source_image").innerHTML;
    var data = {"url": url, "title": title, "image": image, "dates": dates, "categorie": categorie, "conservation": conservation, "source_image": source_image};
    $.jStorage.set(id, data);
    displayBasket();
}

function emptyBasket() {
    var conf = confirm("Êtes-vous sur-e de vouloir effacer le panier ?");
    if (conf) {
        $.jStorage.flush();
        document.getElementById("panier-body").innerHTML = "<div class=\"selection\"><ul>Aucune sélection pour l'instant</ul></div>";
    }
}

function removeItem(id) {
    var conf = confirm("Êtes-vous sur-e de vouloir supprimer cette fiche du panier ?");
    if (conf) {
        $.jStorage.deleteKey(id);
        displayBasket();
        var url = location.pathname.split("/");
        var id = url[url.length -1];
        if (!$.jStorage.get(id)) {
            $("#memory").show();
            $("#no-memory").hide();
        }
    }
}

function displayBasket() {
    var index = $.jStorage.index();
    var content = "<div class=\"selection\"><ul>";
    var selection = "";
    for(i=0;i<index.length;i++) {
        var title = $.jStorage.get(index[i]);
        selection += '<li><a href="'+title['url']+'">'+title['title']+'</a> <a href="#" onclick="removeItem('+index[i]+');"><img src="../bundles/callistofiches/images/icons/delete.png" alt="Supprimer"></a></li>';
    }
    selection += "</ul><br />"+'<div class="row"><div class="span4 offset2"><button type="button" class="btn btn-primary" onclick="javascript:emptyBasket()">Effacer le panier</a></button></div>';
    if(index.length > 0) {
        content += selection;
    } else {
        content += "Aucune sélection pour l'instant</ul>";
    }
    content_mail = localStorage.getItem('jStorage');
    // version imprimable
    content += '<div class="span6"><form method="post" action="'+ document.URL.split("/fiche/")[0] +'/print_panier"><textarea id="form_message" style="display: none;" name="form[message]">'+ content_mail +'</textarea><input type="submit" value="Version imprimable du panier" class="btn btn-primary"></form></div></div>';
    content += "</div>";
    document.getElementById("panier-body").innerHTML = content;
    document.getElementById("form_message").innerHTML = content_mail;
    document.getElementById("form_message2").innerHTML = content_mail;
    
    var url = location.pathname.split("/");
    var id = url[url.length -1];
    if ($.jStorage.get(id)) {
        $("#memory").hide();
        $("#no-memory").show();
    }
}

function sendMail() {
    var body = 'Votre sélection Callisto:%0D%0A%0D%0A';
    var index = $.jStorage.index();
    for(i=0;i<index.length;i++) {
        var title = $.jStorage.get(index[i]);
        body += title['title']+'%0D%0A'+title['url']+'%0D%0A%0D%0A';
    }
    var link = "mailto:"
             + "?subject=Votre sélection Callisto"
             + "&body=" + body
    ;
    window.location.href = link;
}
