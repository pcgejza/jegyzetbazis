Header = {
    
    searchInput: null,
    searchResults: null,
    searchResults: [] , // ebben tárolom a keresés eredményeit
    searchURL : null,
    postID: 0,
    
    loginButton: null,
    registrationButton: null,
    
    init: function(){
        this.searchInput = $('#search-on-page');
        this.searchResults = $('.searchHolder .searchResults');
        this.loginButton = $('.rightHolder .login');
        this.registrationButton = $('.rightHolder .registration');
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
                    Header.search(thisVal);
                }else{
                    Header.hideSearchResults();
                }
            },
            focusout: function(){
                Header.searchInput.val('');
                Header.hideSearchResults();
            }
        });
        
        this.loginButton.click(function(){
            AuthWindow.show('login');
        });
        
        this.registrationButton.click(function(){
            AuthWindow.show('registration');
        });
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
        Header.showLoadingToSearchResults();
        if(typeof(Header.searchResults[text]) == 'undefined'){
            $.post(Header.searchURL, {text : text}).done(
                function(h){
                    Header.searchResults[text] = h;
                    if(Header.searchInput.val() == text){
                        Header.hideLoadingFromSearchResults();
                        Header.addHtmlToSearchResults(Header.searchResults[text]);
                    }
            });
        }else{
            Header.hideLoadingFromSearchResults();
            Header.addHtmlToSearchResults(Header.searchResults[text]); 
        }
    },
    
    
}