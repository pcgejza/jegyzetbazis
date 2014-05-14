Search = {
    
    oldCategory: "",
    
    init: function(){
        this.bindUiActions();
    },
    
    bindUiActions: function(){
      // autocomplete hozzáadás
        $('#search-header').autocomplete({
            source: function (request, response) { //AJAX-al kérjük le az adatokat
                $('#search-header').addClass('loading');
                
                $.ajax({
                    url: $('#search-header').attr('href'),	
                    data: { searchText: request.term, maxResults: 10 },
                    dataType: "json",
                    type: 'POST',
                    success: function (data) {
                        response($.map(data, function (item) { //feldolgozzuk az adatokat
                            return {
                                name: item.name,
                                link : item.link,
                                category : item.category
                            };
                        }));
                     $('#search-header').removeClass('loading');  
                    }
                })
            },
            select: function (event, ui) { //Termék kiválasztása
                if(ui.item)
                $('#search-header').val(ui.item.name); //Kiválasztás esetén berakjuk az input mezőbe-be az értéket
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) { //Adatok megjelenítése
            var name = "";
            if(item.name.length > 20){ //Levágjuk a neveket, ha hosszabbak mint 20 karakter
                if(item.category == 'Fájlok'){
                     name = item.name.substring(0,40) + "..." + " ."+item.name.substring(item.name.length-3);
                }else
                   name = item.name.substring(0,40) + "...";
            }else{
               name = item.name;
            }
            
            if(Search.oldCategory != item.category){
                ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                Search.oldCategory = item.category;
            }
            
            var inner_html = '<a href="'+item.link+'">'+ name + '</a>';
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append(inner_html)
                    .appendTo(ul);
        };
        
    },
}