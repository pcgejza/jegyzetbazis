AuthWindow = {
    
    authReveal: null,
    getRevealUrl: null,
    openedAuthWindow: false,
    
    
    init: function(){
        this.authReveal = $('.auth-window');
    },
    
    show: function(page){
        if(AuthWindow.openedAuthWindow) return;
        
        this.authReveal.reveal({
            closeOnBackgroundClick: false,
            open: function(){
                AuthWindow.openedAuthWindow = true;
            },
            close: function(){
                AuthWindow.openedAuthWindow = false;
            }
        });
        
        if(this.authReveal.hasClass('not-loaded')){
            $.post(AuthWindow.getRevealUrl,{
                page : page
            }).done(function(data){
                AuthWindow.authReveal.html(data.html);
                AuthWindow.authReveal.removeClass('not-loaded');
                AuthWindow.bindAuthWindowActions();
                AuthWindow.addG();
            });
        }else{
            this.authReveal.find('.window-content .header ul li.'+page).click();
        }
    },
    
    hide: function(){
         this.authReveal.trigger('reveal:close');
    },
    
    bindAuthWindowActions: function(){
        this.authReveal.find('.window-content .header ul li').unbind('click');
        this.authReveal.find('.window-content .header ul li').bind('click', function(){
            if(!$(this).hasClass('active')){
                var active = $(this).siblings('.active');
                var tc = ' .'+$(this).attr('class');
                $(this).addClass('active');
                $(this).siblings('li').removeClass('active');
                $(this).parents('.window-content').find('.content .'+active.attr('class')).fadeOut();
                $(this).parents('.window-content').find('.content'+tc).fadeIn();
           }
        });
        
        this.authReveal.find('.exit').unbind('click');
        this.authReveal.find('.exit').bind('click',function(){
            AuthWindow.hide();
        });
    }, 
    
    addG: function(){
       this.authReveal.find(':input[backtext]').each(function(){
           $(this).addGray($(this).attr('backtext'));
       });
    },
    
}