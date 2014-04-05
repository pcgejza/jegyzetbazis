Header = {
    
    searchInput: null,
    searchResults: null,
    searchResults: [] , // ebben tárolom a keresés eredményeit
    searchURL : null,
    postID: 0,
    headerUserBox: null,
    
    loginButton: null,
    registrationButton: null,
    headerHolder: null,
    menuHolder: null,
    
    init: function(){
        this.headerHolder = $('body .page .headerHolder');
        this.searchInput = $('#search-on-page');
        this.searchResults = $('.searchHolder .searchResults');
        this.loginButton = $('.rightHolder .login');
        this.registrationButton = $('.rightHolder .registration');
        this.headerUserBox = $('.userHeaderElement',this.headerHolder);
        this.menuHolder = $('.menu',this.headerHolder);
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
        
        if(this.headerUserBox.length > 0){
            this.addQtipToHeader();
        }
        
        this.addQtipsToMenu();
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
    
    setHeader: function(html){
        this.headerHolder.html(html);
        this.init();
    },
    
    addQtipToHeader: function(){
      var h = $('.userHeaderqtip', this.headerUserBox).html();
      var un = $('.userHeaderElement .userName').html();
       this.headerUserBox.qtip({
           content: {
                text: h,
                title: un
            },
            show: 'click',
            hide: 'click',    
            position: {
                   my: 'top center',  // Position my top left...
                   at: 'bottom center', // at the bottom right of...
                   target:this.headerUserBox // my target
               }, 
            style: {
                def: false,
                classes: Shared.qtipStyleClass+" "+Shared.qtipStyleClass2,
                width: this.headerUserBox.width()
            }
        });
          
        this.searchInput.qtip({
         show: 'focus',
         hide: 'blur'
        });
          
    },
    
    addQtipsToMenu: function(){
        this.menuHolder.find('.menu-element').each(function(){
            $(this).qtip({
            show: 'mouseenter',
            hide: 'mouseleave',    
            position: {
                   my: 'top center',  // Position my top left...
                   at: 'bottom center', // at the bottom right of...
               }, 
            });
        });
    },
}