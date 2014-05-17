InfoPopUp = {
    
    popupElement: null,
    
    init: function(){
        this.popupElement = $('#informationPopUp');
        this.bindUIActions();
      //  this.showInfoPopup(); //TESZT
    },
    
    bindUIActions:function(){
        console.debug('bindUI');
        $('.close-reveal-modal', this.popupElement).click(InfoPopUp.hideInfoPopUp());
        this.popupElement
                .find('.close-reveal-modal, .buttons .close-popup')
                .click(function(){
                    InfoPopUp.hideInfoPopUp();
                });
    },
    
    
    showInfoPopup: function(obj){
        var type = (typeof(obj) == 'undefined' || typeof(obj.type) == 'undefined') ? 'info' : obj.type;
        var topText = (typeof(obj) == 'undefined' || typeof(obj.topText) == 'undefined') ? '' : obj.topText;
        var text = (typeof(obj) == 'undefined' || typeof(obj.text) == 'undefined') ? '' : obj.text;
        var closeTime = (typeof(obj) == 'undefined' || typeof(obj.closeTime) == 'undefined') ? false : obj.closeTime*1000;
        var closeFunction = (typeof(obj) == 'undefined' || typeof(obj.closeFunction) == 'undefined') ? false : obj.closeFunction;
        
        this.popupElement.find('.topHolder').html(topText);
        this.popupElement.find('.right').html(text);
        this.popupElement.find('.left .icon').hide();
        this.popupElement.find('.left .icon.'+type).show();
       
        this.popupElement.reveal({
            close: function(){
               if(closeFunction){
                   closeFunction();
               }
            }
        });
        
        if(closeTime !== false){
            var timer = $.timer(function() {
                InfoPopUp.hideInfoPopUp();
                timer.stop();
            });
            timer.set({ time : closeTime, autostart : true });
        }
    },
    
    hideInfoPopUp: function(){
          this.popupElement.trigger('reveal:close');
    },
    
    
    
}