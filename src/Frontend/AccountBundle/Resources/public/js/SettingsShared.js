SettingsShared = {
    
    tab: null,
    
    getPageUrl: null,
    
    init: function (){
        this.tab = $('.settings .settingsTabs li');
        
        this.bindUIActions();
        this.bindLoadNewActions();
    },
    
    bindUIActions: function(){
        this.tab.click(function(){
           if(!$(this).hasClass('active')){
               var page = $('a', this).attr('page');
               $(this).siblings('.active').removeClass('active');
               $(this).addClass('active');
               SettingsShared.selectPage(page);
           }
        });
    },
    
    getNotLoadedEmptyHtmlElement: function(page){
        return '<div class="tabContent not-loaded" page="'+page+'"></div>';
    },
    
    addEmptyHtmlElementToPage: function(page){
        $('.settings .settingsContent').append(this.getNotLoadedEmptyHtmlElement(page));
    },
    
    isAddedPage: function(page){
       return $('.settings .settingsContent .tabContent[page="'+page+'"]').length == 0 ? false : true;
    },
    
    selectPage: function(page){
        window.history.pushState(null, null, page+'.html');
        this.changeTitle($('.settingsTabs li.active a[page="'+page+'"]').html());
        if(!this.isAddedPage(page)){
            this.addEmptyHtmlElementToPage(page);
            this.getPageContent(page);
        }
        $('.settings .settingsContent .tabContent.active').removeClass('active').fadeOut();
        $('.settings .settingsContent .tabContent[page="'+page+'"]').addClass('active').fadeIn();
    },
    
    changeTitle: function(value){
      $('.settings .titleHolder span.title').html(value);  
    },
    
    bindLoadNewActions: function(){
        $('.settings form').submit(function(e){
            e.preventDefault();
            var ParentTabContent = $(this).parents('.tabContent').first();
            if(ParentTabContent.hasClass('loading') || !SettingsShared.isValidForm($(this))) return;
            ParentTabContent.removeClass('successfull');
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            var allInputs = $(this).find('input');
            var passInput = ParentTabContent.find('input[type="password"]');
            SettingsShared.removeErrorFromInput(passInput);
            ParentTabContent.addClass('loading');
            allInputs.attr('readonly', 'readonly');
            allInputs.attr('disabled', 'disabled');
            $.ajax({
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data, textStatus, jqXHR){
                    allInputs.removeAttr('readonly').removeAttr('disabled');
                    ParentTabContent.removeClass('loading');
                    if(!data.err){
                        ParentTabContent.html(data);
                        ParentTabContent.addClass('successfull');
                        SettingsShared.bindLoadNewActions();
                    }else{
                       passInput.focus();
                       SettingsShared.addErrorToTextInput(passInput, data.err);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.error('Hiba!!!');
                }
            });
        });
    }, 
    
    addErrorToTextInput: function(input, error){
        if(input.siblings('.error').length == 0){
            input.parent().append('<small class="error">'+error+'</small>');
            input.addClass('redBorder');
        }
    },
    
    removeErrorFromInput: function(input){
        input.siblings('.error').remove();
        input.removeClass('redBorder');
    },
    
    getPageContent: function(page){
        var url = this.getPageUrl + page+ '.html';
        $.ajax({
            type: 'GET',
            url: url,
            data: {fromAjax: true},
            success: function(data){
                $('.settings .settingsContent .tabContent[page="'+page+'"]').removeClass('not-loaded').html(data);
                SettingsShared.bindLoadNewActions();
            }
        });
    },
    
    isValidForm: function(form){
        if(form.hasClass('baseSettingsForm')){
            var ret = true;
            
            var date = form.find('.date-select');
            
            var dateY = date.find('select:eq(0)');
            var dateM = date.find('select:eq(1)');
            var dateD = date.find('select:eq(2)');
            
            if(!(dateY.val()=='' && dateM.val() == '' && dateD.val() == '')){
                if(dateY.val() =='' || dateM.val() == '' || dateD.val() == ''){
                    SettingsShared.addErrorToTextInput(date, 'A teljes születési dátumodat add meg!');
                    ret = false;
                }else{
                     SettingsShared.removeErrorFromInput(date);
                }
            }else{
                     SettingsShared.removeErrorFromInput(date);
            }
            
            return ret;
        }else{
            return true;
        }
    },
}
