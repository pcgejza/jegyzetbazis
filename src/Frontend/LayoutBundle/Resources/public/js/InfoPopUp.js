InfoPopUp = {
    
    popupElement: null,
    
    init: function(){
        this.popupElement = $('#informationPopUp');
        this.bindUIActions();
    },
    
    bindUIActions:function(){
        console.debug('bindUI');
        $('.close-reveal-modal', this.popupElement).click(InfoPopUp.hideInfoPopUp());
    },
    
    
    showInfoPopup: function(obj){
        var type = (typeof(obj) == 'undefined' || typeof(obj.type) == 'undefined') ? 'típus nélküli' : obj.type;
        var topText = (typeof(obj) == 'undefined' || typeof(obj.topText) == 'undefined') ? 'Címsor' : obj.topText;
        var closeTime = (typeof(obj) == 'undefined' || typeof(obj.closeTime) == 'undefined') ? false : obj.closeTime;
        
        this.popupElement.find('.topHolder').html(topText);
       
        this.popupElement.reveal();
        
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