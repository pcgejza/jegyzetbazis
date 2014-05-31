/*
 * A fejléchez szükséges jquery függvények
 */
Header = {
    
    postID: 0,
    headerUserBox: null,
    
    loginButton: null,
    registrationButton: null,
    headerHolder: null,
    menuHolder: null,
    
    init: function(){
        this.headerHolder = $('body .page .headerHolder');
        this.loginButton = $('.rightHolder .login');
        this.registrationButton = $('.rightHolder .registration');
        this.headerUserBox = $('.userHeaderElement',this.headerHolder);
        this.menuHolder = $('.menu',this.headerHolder);
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        
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
    
    // fejléc beállítása
    setHeader: function(html){
        this.headerHolder.html(html);
        this.init();
    },
    // fejléchez szükséges felugró fülek hozzáadása
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
    },
    // a menühöz szükséges felugrók hozzáadása
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