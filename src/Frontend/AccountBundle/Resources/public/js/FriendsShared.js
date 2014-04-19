FriendsShared = {
    
    addOrRemoveURL: null,
    
    init: function(){
      
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.bindMarkButton();
        this.addQtipToFriendButtons();
    },
    
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
          
          thisElement.addClass('loading');
          $.post(FriendsShared.addOrRemoveURL, {
              userID : userID,
              type : type
          }).done(function(data){
             if(!data.err){
                thisElement.html(data.text);
                thisElement.removeClass('loading');
                 
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
    
    addQtipToFriendButtons: function(){
       $('.myFriend').each(function(){
          var uid = $(this).attr('userid');
          var ht = $('.qtip-to-myfriend[userid="'+uid+'"]').html();
           $(this).qtip({
           content: {
                text: ht,
            },
            show: 'click',
            hide:  {
              event: 'click'
            },   
            position: {
                   my: 'top center',  // Position my top left...
                   at: 'bottom center', // at the bottom right of...
                   target:this.headerUserBox // my target
               }, 
            style: {
                def: false,
                classes: Shared.qtipStyleClass
            },
            events: {
                render: function(){
                    FriendsShared.bindMarkButton();
                }
            }
        });
       });
    },
    
}