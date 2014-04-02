InfoPopUp = {
    
    popupElement: null,
    
    init: function(){
        this.popupElement = $('#informationPopUp');
    },
    
    showInfoPopup: function(obj){
        var type = (typeof(obj) == 'undefined' || typeof(obj.type) == 'undefined') ? 'típus nélküli' : obj.type;
        var topText = (typeof(obj) == 'undefined' || typeof(obj.topText) == 'undefined') ? 'Címsor' : obj.topText;
       
        this.popupElement.find('.topHolder').html(topText);
       
        this.popupElement.reveal();
    },
    
    hideInfoPopUp: function(){
          this.popupElement.trigger('reveal:close');
    },
    
    
    
}