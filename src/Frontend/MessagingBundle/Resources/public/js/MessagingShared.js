MessagingShared = {
    
    loadingDivHtml: '<div class="loading"></div>',
    
    getPageURL : null,
    
    init: function(){
        
        this.bindUiActions();
        this.initSwitch();
    },
    
    bindUiActions: function(){
        console.log('messagingShared.bindUi');
    },
    
    initSwitch: function(){
        $('.messages .messagesMenu li')
                .unbind('click')
                .bind('click', function(e){
                    e.preventDefault();
                    var a = $(this).find('a');
            
                    if(!$(this).hasClass('active')){
                        $(this).siblings('li').removeClass('active');
                        $(this).addClass('active');
                        MessagingShared.openPage(a.attr('page'), a.attr('getp'),a.attr('href'));
                    }
                });
    },
    
    openPage: function(page,getp, href){
       var messagesList = $('.messages .messagesList');
        var selectedPage= messagesList.find('.p.'+page);
        messagesList.find('.p.active').fadeOut().removeClass('active');
        selectedPage.fadeIn().addClass('active');
        
        if(page != 'newMessage'){
            selectedPage.html(MessagingShared.loadingDivHtml);
            $.post(href, {
                page : getp
            }).done(function(h){
               selectedPage.html(h); 
            });
        }
    }
    
}
