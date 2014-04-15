Subjects = {
    
    rightHolder: null,
    PROGRESS: false,
    
    init: function(){
        this.rightHolder = $('.page .contentHolder .rightHolder');
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.bindTabClickActions();
        this.bindSubjectsMenuActions();
        this.addQtips();
        this.bindSubjectActions();
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
          Subjects.getPage(u, 1, name);
      });
    },
    
    getPage: function(url, page, name){
          if(this.PROGRESS) return;
          
          Subjects.rightHolder.addClass('loading');
          this.PROGRESS = true;
          $.post(url,{
            page : page
          }).done(function(html){
             Subjects.rightHolder.removeClass('loading');
             Subjects.rightHolder.html(html);
             Subjects.PROGRESS = false;
             Subjects.bindTabClickActions();
             Subjects.setActualSubjectName(name);
             Subjects.addQtips();
             Subjects.bindSubjectActions();
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
              $('.singleSubject .singleSubjectContent div:visible:eq(0)').hide();
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
    
    bindSubjectActions: function(){
        $('.uploads .paginator .pagination span').click(function(e){
            e.preventDefault();
            var u = $(this).find('a').attr('href');
            var page = u.substr(u.length-1);
            var url = $('.subjects .subjectsContent li.selected a').attr('href');
            if(url.length > 1){
                var name = $('.subjects .subjectsContent li.selected a').html();
                window.history.pushState(null, null, u);
                Subjects.getPage(url, page, name);
            }
        });
    },
    
    refreshActualPage: function(){
        var u = $('.subjects li.selected a').attr('href');
        var name = $('.subjects li.selected a').html();
        if(u.length>1){
            var page = $('.uploads .paginator .pagination .current').html();
            Subjects.getPage(u, page, name);
        }
    },
    
}