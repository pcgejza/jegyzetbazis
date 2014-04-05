Subjects = {
    
    rightHolder: null,
    
    init: function(){
        this.rightHolder = $('.page .contentHolder .rightHolder');
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.bindTabClickActions();
        this.bindSubjectsMenuActions();
        this.addQtips();
    },
    
    bindSubjectsMenuActions: function(){
      var subjectsMenu = $('.subjects .subjectsContent');
      var oneSubject = subjectsMenu.find('li a');
      
      oneSubject.unbind('click');
      oneSubject.bind('click',function(e){
          e.preventDefault();
          var u = $(this).attr('href');
          window.history.pushState(null, null, u);
          var name = $(this).html();
          $(this).parents('li').siblings('li.selected').qtip('destroy');
          $(this).parents('li').siblings('li.selected').removeClass('selected');
          $(this).parents('li').addClass('selected');
          $.post(u).done(function(html){
             Subjects.rightHolder.html(html);
             Subjects.bindTabClickActions();
             Subjects.setActualSubjectName(name);
             Subjects.addQtips();
          });
      });
    },
    
    bindTabClickActions: function(){
       var tabContent = $('.singleSubject .singleSubjectTabs');
       var tabLIs = tabContent.find('ul li');
       tabLIs.unbind('click');
       tabLIs.bind('click', function(){
          if(!$(this).hasClass('active')){
              $(this).siblings('li').removeClass('active');
              $(this).addClass('active');
              $('.singleSubject .singleSubjectContent div:visible').hide();
              $('.singleSubject .singleSubjectContent div.'+$(this).find('a').attr('class')).fadeIn();
          } 
       });
    },
    
    setActualSubjectName: function(name){
        $('.actualSubjectName').html(name);
    },
    
    
    addQtips: function(){
        $('.subjects .subjectsContent li.selected').each(function(){
                var t = $(this).find('a');
                $(this).qtip({
                    content: 'Ez van kiválasztva',
                    show: 'mouseenter',
                    hide: 'mouseleave',    
                    position: {
                           my: 'left center',  // Position my top left...
                           at: 'right center', // at the bottom right of...
                            target:t // my target
                   }, 
                   style:{
                    def: false,
                    classes: Shared.qtipStyleClass+" "+Shared.qtipStyleClass2,
                    }
                });
        });
    },
}