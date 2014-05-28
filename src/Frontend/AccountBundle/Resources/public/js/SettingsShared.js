/*
 * A beállítások oldalhoz lévő funkciók vannak ebben az objektumban definiálva
 */
SettingsShared = {
    
    tab: null,
    
    getPageUrl: null,
    schools: [],
    
    init: function (){
        this.tab = $('.settings .settingsTabs li');
        
        this.bindUIActions();
        this.bindLoadNewActions();
        this.addAutocompleteToSchool();
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
    
    /*
     * Az iskolák részhez a szövegkiegészítő hozzáadása
     */
    addAutocompleteToSchool: function(){
        if(Object.keys(this.schools).length > 0){
            $('.addSchoolsAutocomplete').autocomplete({
             source: SettingsShared.schools
            });
        }
        console.debug(this.schools);
    },
    // nem betöltött elem html kódjának lekérdezése
    getNotLoadedEmptyHtmlElement: function(page){
        return '<div class="tabContent not-loaded" page="'+page+'"></div>';
    },
    // üres html elem hozzáadása a beállítások törzsébe
    addEmptyHtmlElementToPage: function(page){
        $('.settings .settingsContent').append(this.getNotLoadedEmptyHtmlElement(page));
    },
    // be van-e töltve az oldal?
    isAddedPage: function(page){
       return $('.settings .settingsContent .tabContent[page="'+page+'"]').length == 0 ? false : true;
    },
    // oldal kiválasztása
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
    // a felső cím módosítása
    changeTitle: function(value){
      $('.settings .titleHolder span.title').html(value);  
    },
    
    /*
     * Új beállítások oldal lekérdezése esetén meghívásra kerül ez a függvény
     * ami felüldefiniálja az új aloldalon lévő gombok funkcióit
     */
    bindLoadNewActions: function(){
        $('.settings form').submit(function(e){
            e.preventDefault();
            var ParentTabContent = $(this).parents('.tabContent').first();
            
            if(ParentTabContent.hasClass('loading') || !SettingsShared.isValidForm($(this))) return;
            ParentTabContent.removeClass('successfull');
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            var allInputs = $(this).find('input');
            var passInput = ParentTabContent.find('input[type="password"]').last();
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
                    if(ParentTabContent.attr('page') === 'avatar-beallitasok'){
                        SettingsAvatar.bindUploadedAvatarsClickActions();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.error('Hiba!!!');
                }
            });
        });
        
    }, 
    // hiba hozzáadása a szöveges mezőhöz
    addErrorToTextInput: function(input, error){
        if(input.siblings('.error').length == 0){
            input.parent().append('<small class="error">'+error+'</small>');
            input.addClass('redBorder');
        }
    },
    // hiba eltávolíása a szöveges mezőről
    removeErrorFromInput: function(input){
        input.siblings('.error').remove();
        input.removeClass('redBorder');
    },
    
    // az oldal tartalmának lekérdezése
    getPageContent: function(page){
        var url = this.getPageUrl + page+ '.html';
        $.ajax({
            type: 'GET',
            url: url,
            data: {fromAjax: true},
            success: function(data){
                $('.settings .settingsContent .tabContent[page="'+page+'"]').removeClass('not-loaded').html(data);
                SettingsShared.bindLoadNewActions();
                if(page == 'alap-beallitasok')
                    SettingsShared.addAutocompleteToSchool();
            }
        });
    },
    
    /*
     *  Űrlapok validációs vizsgálata 
     */
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
        }else if(form.hasClass('changePassword')){
            var p1 = form.find('INPUT.pass1');
            var p2 = form.find('INPUT.pass2');
            var ret = true;
                    
            if(p1.val().length < 6){
                ret = false;
                SettingsShared.addErrorToTextInput(p1, 'A jelszó minimum 6 karakter hosszú legyen!');
            }else{
                SettingsShared.removeErrorFromInput(p1);
            }
                    
            if(p1.val() != p2.val()){
                ret = false;
                SettingsShared.addErrorToTextInput(p2, 'A jelszavak nem egyeznek!');
            }else{
                SettingsShared.removeErrorFromInput(p2);
            }
                   
            return ret;
        }else{
            return true;
        }
    },
}
