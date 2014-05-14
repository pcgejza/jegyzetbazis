UniversalTabs = {
    
    tabHolder: null,
    tabElement: null,
    tabContentHolder: null,
    
    init: function(){
        
        this.tabHolder = $('.universal-tabs');
        this.tabElement = this.tabHolder.find('.universal-tabs-header ul li a');
        this.tabContentHolder = this.tabHolder.find('.universal-tabs-content');
        this.allTabContents = this.tabContentHolder.find('.tab');
        
        this.bindUIActions();
        
        return this;
    },
    
    bindUIActions: function(){
        /*
        this.setTabHolderWidth(600);
        this.setTabElementWidth(100);
        */
        this.tabElement
                .unbind('click')
                .bind('click', function(){
                    var tab = $(this).attr('tab');
                    var tabLi = $(this).parent();
                    if(!tabLi.hasClass('active')){
                        tabLi.siblings('li').removeClass('active');
                        tabLi.addClass('active');
                        UniversalTabs.allTabContents.fadeOut().removeClass('active');
                        UniversalTabs.tabContentHolder.find('.tab.'+tab).fadeIn().addClass('active');
                    }
                });
    },
    
    // maga a teljes váltó meg minden szélessége
    setTabHolderWidth: function(px){
        this.tabHolder.css('width', px);
    },
    
    // a fülek szélessége
    setTabElementWidth: function(px){
        this.tabElement.css('width', px);
    },
}