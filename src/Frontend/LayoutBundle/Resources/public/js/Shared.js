Shared = {
    
    loadingHtmlImage: null,
    defaultProfileImageMale: null,
    defaultProfileImageFemale: null,
    
    qtipStyleClass: null,
    qtipStyleClass2: null,
    visitActionUrl: null,
    ip:null,
    uploadAvatarUrl: null,
    downloadFileUrl: null,
    
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
        
        var timer = $.timer(function() {
            Shared.refreshVisit();
        });
        timer.set({ time : 1000*30, autostart : true });
        
        $('.visitorsCount').hover(function(){
           Shared.moveRightTab($(this), 70);
        }, function(){
            Shared.moveRightTab($(this), 110);
        });
        
        $('.downloadsCount').hover(function(){
           Shared.moveRightTab($(this), 70);
        }, function(){
            Shared.moveRightTab($(this), 210);
        });
    },
    
    moveRightTab: function(vDiv, px){
        var $rigthPos = vDiv.css('right');
        console.log(px);
        vDiv.animate({
            'right' : - (px)
        }, 200);
    },
    
    refreshVisit: function(){
        $.post(Shared.visitActionUrl, function(data){
            $('.visitorsCount').html(data);
        });
    },
    
}