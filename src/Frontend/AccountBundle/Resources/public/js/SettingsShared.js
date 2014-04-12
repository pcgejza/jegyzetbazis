SettingsShared = {
    
    tab: null,
    
    getPageUrl: null,
    
    init: function (){
        this.tab = $('.settings .settingsTabs li');
        
        this.bindUIActions();
        this.bindLoadNewActions();
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
        window.history.pushState(null, null, page+'.html');
        this.changeTitle($('.settingsTabs li.active a[page="'+page+'"]').html());
        if(!this.isAddedPage(page)){
            this.addEmptyHtmlElementToPage(page);
            this.getPageContent(page);
        }
        $('.settings .settingsContent .tabContent.active').removeClass('active').fadeOut();
        $('.settings .settingsContent .tabContent[page="'+page+'"]').addClass('active').fadeIn();
    },
    
    changeTitle: function(value){
      $('.settings .titleHolder span.title').html(value);  
    },
    
    bindLoadNewActions: function(){
        $('.settings form').submit(function(e){
            e.preventDefault();
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            $.ajax({
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data, textStatus, jqXHR){
                    console.debug('SUCCESS!');
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.error('Hiba!!!');
                }
            });
        });
    }, 
    
    getPageContent: function(page){
        var url = this.getPageUrl + page+ '.html';
        $.ajax({
            type: 'GET',
            url: url,
            data: {fromAjax: true},
            success: function(data){
                $('.settings .settingsContent .tabContent[page="'+page+'"]').removeClass('not-loaded').html(data);
                SettingsShared.bindLoadNewActions();
            }
        });
    },
}