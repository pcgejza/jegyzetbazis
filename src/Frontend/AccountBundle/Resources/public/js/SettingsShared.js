SettingsShared = {
    
    tab: null,
    
    
    init: function (){
        this.tab = $('.settings .settingsTabs li');
        
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.tab.click(function(){
           if(!$(this).hasClass('active')){
               var page = $('a', this).attr('page');
               $(this).siblings('.active').removeClass('active');
               $(this).addClass('active');
               SettingsShared.selectPage(page);
           }
        });
    },
    
    getNotLoadedEmptyHtmlElement: function(page){
        return '<div class="tabContent not-loaded" page="'+page+'"></div>';
    },
    
    addEmptyHtmlElementToPage: function(page){
        $('.settings .settingsContent').append(this.getNotLoadedEmptyHtmlElement(page));
    },
    
    isAddedPage: function(page){
       return $('.settings .settingsContent .tabContent[page="'+page+'"]').length == 0 ? false : true;
    },
    
    selectPage: function(page){
        if(!this.isAddedPage(page)){
            this.addEmptyHtmlElementToPage(page);
        }
        $('.settings .settingsContent .tabContent.active').removeClass('active').fadeOut();
        $('.settings .settingsContent .tabContent[page="'+page+'"]').addClass('active').fadeIn();
    },
}