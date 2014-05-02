Shared = {
    
    loadingHtmlImage: null,
    defaultProfileImageMale: null,
    defaultProfileImageFemale: null,
    
    qtipStyleClass: null,
    qtipStyleClass2: null,
    visitActionUrl: null,
    ip:null,
    uploadAvatarUrl: null,
    
    init: function(){
        Shared.qtipStyleClass = "qtip-custom-orange";
        Shared.qtipStyleClass2 = "qtip-rounded qtip-shadow";
        
        Header.init();
        InfoPopUp.init();
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        
        this.bindVisitActions();
    },
    
    
    bindVisitActions: function(){
        
        this.hideVisitorsCount();
        var timer = $.timer(function() {
            Shared.refreshVisit();
        });
        timer.set({ time : 1000*30, autostart : true });
        
        $('.visitorsCount').hover(function(){
           Shared.showVisitorsCount();
        }, function(){
            Shared.hideVisitorsCount();
        });
    },
    
    hideVisitorsCount: function(){
        var vDiv = $('.visitorsCount');
        var $rigthPos = vDiv.css('right');
        vDiv.animate({
            'right' : -110
        }, 100);
    },
    
    showVisitorsCount: function(){
        var vDiv = $('.visitorsCount');
        var $rigthPos = vDiv.css('right');
        vDiv.animate({
            'right' : -70
        }, 100);
    },
    
    refreshVisit: function(){
        $.post(Shared.visitActionUrl, function(data){
            $('.visitorsCount').html(data);
        });
    },
    
    
}