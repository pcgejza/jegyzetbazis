Subjects = {
    
    init: function(){
        console.debug('init subjects');
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.bindTabClickActions();
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
}