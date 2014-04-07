Shared = {
    
    loadingHtmlImage: null,
    defaultProfileImageMale: null,
    defaultProfileImageFemale: null,
    
    qtipStyleClass: null,
    qtipStyleClass2: null,
    visitActionUrl: null,
    ip:null,
    
    init: function(){
        Shared.qtipStyleClass = "qtip-custom-orange";
        Shared.qtipStyleClass2 = "qtip-rounded qtip-shadow";
        
        Header.init();
        InfoPopUp.init();
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        
        var timer = $.timer(function() {
            Shared.refreshVisit();
        });
         timer.set({ time : 1000*5, autostart : true });
    },
    
    refreshVisit: function(){
        $.post(Shared.visitActionUrl,{ip:Shared.ip});
    },
    
}