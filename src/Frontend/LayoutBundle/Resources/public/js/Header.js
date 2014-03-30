Header = {
    
    searchInput: null,
    searchResults: null,
    searchResults: [] , // ebben tárolom a keresés eredményeit
    
    init: function(){
        this.searchInput = $('#search-on-page');
        this.searchResults = $('.searchHolder .searchResults');
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.searchInput.addGray('Keress rá emberekre, tárgyakra, tananyagokra...');
        
        this.searchInput.on({
            keydown: function(c){
                console.debug(c.keyCode);
            },
            keyup: function(c){
                var thisVal = $(this).val();
                var thisValLength = thisVal.length;
                
                if(thisValLength > 0){
                    Header.showSearchResults();
                    Header.showLoadingToSearchResults();
                    //Header.search(thisVal);
                }else{
                    Header.hideSearchResults();
                }
            },
        })
    },
    
    showSearchResults: function(){
        this.searchResults.removeClass('hide');
    },
    
    hideSearchResults: function(){
        this.searchResults.addClass('hide');
    },
    
    addHtmlToSearchResults: function(html){
        this.searchResults.html(html);
    },
    
    showLoadingToSearchResults: function(){
        if(!this.searchResults.hasClass('loading')){
            this.addHtmlToSearchResults(Shared.loadingHtmlImage);
            this.searchResults.addClass('loading');
        }
    },
    
    hideLoadingFromSearchResults : function(){
        this.searchResults.removeClass('loading');
    },
    
    search: function(text){
        var text = "valami szöveg";
        if(typeof(Header.searchResults[text]) == 'undefined'){
            Header.searchResults[text] = text;
        }
        
        Header.addHtmlToSearchResults(text);
    },
}