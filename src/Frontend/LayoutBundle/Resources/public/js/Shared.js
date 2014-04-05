Shared = {
    
    loadingHtmlImage: null,
    defaultProfileImageMale: null,
    defaultProfileImageFemale: null,
    
    qtipStyleClass: null,
    qtipStyleClass2: null,
    
    init: function(){
        Shared.qtipStyleClass = "qtip-custom-orange";
        Shared.qtipStyleClass2 = "qtip-rounded qtip-shadow";
        
        Header.init();
        InfoPopUp.init();
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        
    }
    
}