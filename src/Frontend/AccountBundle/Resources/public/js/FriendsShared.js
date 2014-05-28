/*
 * Barátokhoz kapcsolódó funkciók vannak ebben az objektumban definiálva
 */
FriendsShared = {
    
    addOrRemoveURL: null,
    
    init: function(){
      
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.bindMarkButton();
    },
    
    /*
     * A barát gomb funkcióinak felüldefiniálása
     */
    bindMarkButton: function(){
        $('.friendMarking').unbind('click');
        $('.friendMarking').bind('click', function(){
          var thisElement = $(this);
          var userID = $(this).attr('userid');
          var type = $(this).attr('type') ? $(this).attr('type') : null;
          
          var qtip = thisElement.parents('.qtip').first();
          if(qtip.length>0){
            qtip.qtip('destroy');
            thisElement = $('.myFriend[userid="'+userID+'"]');
            thisElement.removeClass('myFriend');
            thisElement.removeClass('down-ico');
            thisElement.addClass('friend-mark-button friendMarking');
            FriendsShared.bindMarkButton();
          }
          thisElement = thisElement.parents('.friend-button-holder').first();
          thisElement.addClass('loading');
          $.post(FriendsShared.addOrRemoveURL, {
              userID : userID,
              type : type
          }).done(function(data){
             if(!data.err){
                thisElement.html(data.buttonHtml);
                thisElement.removeClass('loading');
                return; 
                 
                if(thisElement.hasClass('add')){
                    thisElement.removeClass('add');
                    thisElement.addClass('remove');
                }else{
                    thisElement.removeClass('remove');
                    thisElement.addClass('add');
                }
             }else{
                 alert('hiba : '+data.err);
             }
          });
        });
        
    },
    
    
}