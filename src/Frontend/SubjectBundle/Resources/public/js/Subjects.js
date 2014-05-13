Subjects = {
    
    rightHolder: null,
    PROGRESS: false,
    loadingImageHTML: '<img class="loadingImage" src="/symfony/web/images/loading1.gif">',
    
    init: function(){
        this.rightHolder = $('.page .contentHolder .rightHolder:eq(0)');
        this.bindUIActions();
        SubjectFilters.bindUIActions();
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
          var name = $(this).html();
          $(this).parents('li').siblings('li.selected').qtip('destroy');
          $(this).parents('li').siblings('li.selected').removeClass('selected');
          $(this).parents('li').addClass('selected');
          
          Subjects.updateUrl(true);
          Subjects.getPage(u, 1, name);
      });
    },
    
    getPage: function(url, page, name, sortBy){
          if(this.PROGRESS) return;
          
          Subjects.rightHolder.addClass('loading');
          Subjects.rightHolder.append(Subjects.loadingImageHTML);
          
          this.PROGRESS = true;
          $.post(url,{
            page : page,
            sortBy : typeof(sortBy) === 'undefined' ? null : sortBy
          }).done(function(html){
             Subjects.rightHolder.removeClass('loading');
             Subjects.rightHolder.find('.loadingImage').remove();
             Subjects.rightHolder.html(html);
             Subjects.PROGRESS = false;
             Subjects.bindTabClickActions();
             Subjects.setActualSubjectName(name);
             Subjects.addQtips();
             Subjects.bindSubjectActions();
             SubjectFilters.bindUIActions();
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
            
            $('.paginator .pagination span').removeClass('current');
            $(this).addClass('current');
            
            if(url.length > 1){
                var name = $('.subjects .subjectsContent li.selected a').html();
                Subjects.updateUrl();
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
    
    updateUrl: function(newPage){
        var URL = $('.subjectsContent li.selected a').attr('href');
        if(newPage!==true){
           var page = ($('.paginator .pagination SPAN.current').length>0) ?  ($('.paginator .pagination SPAN.current a').length>0) ? parseInt($('.paginator .pagination SPAN.current a').html()):  parseInt($('.paginator .pagination SPAN.current').html()) : 1;
           var sort = $('.rightHolder .sortBy').val();
           URL += "?page="+page+"&sortBy="+sort;
        }
        window.history.pushState(null, null, URL);
    },
    
}

function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');
  }
  else {
    return uri + separator + key + "=" + value;
  }
}

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}

SubjectFilters = {
    bindUIActions: function(){
        $('.uploads .sortBy')
                .unbind('change')
                .bind('change', function(e){
                    Subjects.updateUrl();
                    var page = getURLParameter('page')==null ? 1 : getURLParameter('page');
                    var u = $('.subjects li.selected a').attr('href');
                    var name = $('.subjects li.selected a').html();
                    Subjects.getPage(u,page, name, $(this).val());
                });
    },
}