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
        
        Sizer.init();
        
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

Sizer = {
    
    maxWidth : null,
    
    init: function(){
        this.maxWidth = window.screen.width;
        this.maxHeight = window.screen.height;
        
        
        $(window).resize(function() {
            Sizer.resize();
        });
        
        this.resize();
        
        //$('BODY .headerHolder').css('max')
        
    },
    
    resize: function(){
        var innerWidth = $(window).innerWidth();
        var innerHeigtht = $(window).innerHeight();
        
        var widthOnPercentages = 100 * (innerWidth/this.maxWidth);
        var heightOnPercentages = 100 * (innerHeigtht/this.maxHeight);
        
        if(widthOnPercentages<70){
            $('BODY .headerHolder').css('width', 0.70*this.maxWidth);
            $('BODY .page').css('width', 0.70* (0.8*this.maxWidth) );
        }else{
            $('BODY .headerHolder').css('width', '100%');
            $('BODY .page').css('width', '80%');
        }
        
        $('BODY .rightTab').css('height', 55* (heightOnPercentages/100) );
      
    }
    
}