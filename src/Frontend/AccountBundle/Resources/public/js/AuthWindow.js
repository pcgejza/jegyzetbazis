AuthWindow = {
    
    authReveal: null,
    
    getRevealUrl: null,
    emalCheckUrl: null,
    
    headerHolder: null,
    
    openedAuthWindow: false,
    
    PROCESS: [],
    
    schools: [],
    
    init: function(){
        this.authReveal = $('.auth-window');
        this.headerHolder = $('.page .headerHolder');
    },
    
    show: function(page){
        if(AuthWindow.openedAuthWindow) return;
        
        this.authReveal.reveal({
            closeOnBackgroundClick: false,
            open: function(){
                AuthWindow.openedAuthWindow = true;
            },
            close: function(){
                AuthWindow.openedAuthWindow = false;
            }
        });
        
        if(this.authReveal.hasClass('not-loaded')){
            $.post(AuthWindow.getRevealUrl,{
                page : page
            }).done(function(data){
                AuthWindow.authReveal.html(data.html);
                AuthWindow.authReveal.removeClass('not-loaded');
                AuthWindow.bindAuthWindowActions();
                AuthWindow.bindRegistrationFormActions();
                AuthWindow.bindLoginActions();
                AuthWindow.addG();
                AuthWindow.addAutocompleteToRegistration();
            });
        }else{
            this.authReveal.find('.window-content .header ul li.'+page).click();
        }
    },
    
    hide: function(){
         this.authReveal.trigger('reveal:close');
    },
    
    addAutocompleteToRegistration: function(){
        $('.registration-form .school-area').autocomplete({
             source: AuthWindow.schools
        });
    },
    
    bindAuthWindowActions: function(){
        this.authReveal.find('.window-content .header ul li').unbind('click');
        this.authReveal.find('.window-content .header ul li').bind('click', function(){
            if(!$(this).hasClass('active')){
                var active = $(this).siblings('.active');
                var tc = ' .'+$(this).attr('class');
                $(this).addClass('active');
                $(this).siblings('li').removeClass('active');
                $(this).parents('.window-content').find('.content .'+active.attr('class')).fadeOut();
                $(this).parents('.window-content').find('.content'+tc).fadeIn();
           }
        });
        
        this.authReveal.find('.exit').unbind('click');
        this.authReveal.find('.exit').bind('click',function(){
            AuthWindow.hide();
        });
    }, 
    
    addG: function(){
       this.authReveal.find(':input[backtext]').each(function(){
           $(this).addGray($(this).attr('backtext'));
       });
    },
    
    bindRegistrationFormActions: function(){
        var registrationForm = this.authReveal.find('.registration form.registration-form');
        registrationForm.submit(function(e){
           e.preventDefault();
           if(AuthWindow.isProcess() || AuthWindow.validateRegistrationForm($(this))==false) return;
            AuthWindow.showLoadingToForm(registrationForm);
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            if(!$(this).find('.nickname').hasClass('changed')) postData[4].value = "";
       
            $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data, textStatus, jqXHR) 
                {
                    Header.setHeader(data.header);
                    AuthWindow.hide();
                    AuthWindow.hideLoadingFromForm(registrationForm);
                    InfoPopUp.showInfoPopup({
                        type :      'info',
                        topText :   'Sikeres regisztráció!',
                        text : 'Köszönjük hogy regisztráltál az Jegyzetbázisra, jó tanulást!',
                        closeFunction: function(){
                                location.reload();
                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    InfoPopUp.showInfoPopup({
                        type : 'error'
                    }); 
                }
            });
        });
        
        registrationForm.find('.inputs .email').bind('blur',function(){
            var value = $(this).val();
            var vLength = value.length;
            
            if(vLength > 0 && AuthWindow.isValidEmailAddress(value)){
                AuthWindow.checkEmail($(this));
            }
        });
        
        registrationForm.find('.inputs .nickname').bind('blur',function(){
            var value = $(this).val();
            var vLength = value.length;
            
            if(vLength > 0){
                AuthWindow.checkNickname($(this));
            }
        });
        
    },
    
    validateRegistrationForm: function(form){
        var name, email, pass1, pass2, nickname;
        
        var errors = [];
        
        var errorDiv = this.authReveal.find('.registration-form .errors');
        
        name = form.find('.name');
        email = form.find('.email');
        pass1 = form.find('.password div:eq(0) input');
        pass2 = form.find('.password div:eq(1) input');
        nickname = form.find('.nickname');
        
        if(!name.hasClass('changed')){
            errors.push('Add meg a neved!');
            name.addClass('faulty');
        }else{
            name.removeClass('faulty');
        }
        
        if(nickname.hasClass('exist')){
           errors.push('Ez a nicknév már létezik, adj meg másikat!');
           nickname.addClass('faulty');
        }else{
            nickname.removeClass('faulty');
        }
        
        if(!email.hasClass('changed')){
            errors.push('Add meg az email címed!');
            email.addClass('faulty');
        }else{
            if(!AuthWindow.isValidEmailAddress(email.val())){
                errors.push('Az email cím formátuma nem megfelelő!');
                email.addClass('faulty');
            }else{
                if(email.hasClass('exist')){
                    email.addClass('faulty');
                    errors.push('Ezzel az email címmel már regisztráltak!');
                }else{
                    email.removeClass('faulty');
                }
            }
        }
        
        if(pass1.val().length==0){
           errors.push('Add meg a jelszavad!');
           pass1.addClass('faulty');
        }else{
            if(pass1.val().length<6){
                pass1.addClass('faulty');
                errors.push('A jelszó minimum 6 karakter kell hogy legyen!');
            }else{
                pass1.removeClass('faulty');
            }
        }
        
        if(pass1.val() != pass2.val()){
            errors.push('A jelszavak nem egyeznek!');
            pass1.addClass('faulty');
            pass2.addClass('faulty');
        }else{
            if(!pass1.hasClass('faulty')){
                pass1.removeClass('faulty');
            }
            pass2.removeClass('faulty');
        }
        
        if(errors.length>0){
            var oH = "<ul>";
            $.each(errors, function(i, v){
                oH += "<li>"+v+"</li>";
            });
            oH += "</ul>"
            errorDiv.html(oH);
            errorDiv.fadeIn();
            return false
        }else{
            errorDiv.fadeOut();
            return true;
        }
    },
    
    checkEmail: function(inputElement){
        var value = inputElement.val();
        
        var p = AuthWindow.setProcess();
        AuthWindow.showLoadingToForm(inputElement.parents('form').first());
        $.post(this.emalCheckUrl, 
        {
            email : value
        }).done(function(data){
            if(data.exist){
                inputElement.addClass('exist');
            }else{
                inputElement.removeClass('exist');
            }
            AuthWindow.setProcess(p);
            AuthWindow.hideLoadingFromForm(inputElement.parents('form').first());
        });
    },
    
    checkNickname: function(inputElement){
        var value = inputElement.val();
        
        var p = AuthWindow.setProcess();
        AuthWindow.showLoadingToForm(inputElement.parents('form').first());
        $.post(this.nicknameCheckUrl, 
        {
            nickname : value
        }).done(function(data){
            if(data.exist){
                inputElement.addClass('exist');
            }else{
                inputElement.removeClass('exist');
            }
            AuthWindow.setProcess(p);
            AuthWindow.hideLoadingFromForm(inputElement.parents('form').first());
        });
    },
    
    // van-e függőben lévő process?
    isProcess: function(){
        return Object.keys(this.PROCESS).length>0 ? true : false;
    },
    
    setProcess: function(PID){
        if(typeof(PID) === 'undefined'){
            var datetime = new Date();
            var PID = datetime.getTime();
            this.PROCESS[PID] = true;
            return PID;
        }else{
            delete this.PROCESS[PID];
            return true;
        }
    },
    
    showLoadingToForm: function(form){
        form.find('.buttons').addClass('loading');
        form.addClass('loading');
    },
    
    hideLoadingFromForm: function(form){
        form.find('.buttons').removeClass('loading');
        form.removeClass('loading');
    },
    
    // EMAIL ELLENŐRZŐ függvény a karakterekből
    // TODO: vigyük ki majd a globális függvényekhez
    isValidEmailAddress: function(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    },
    
    
    bindLoginActions: function(){
        var loginForm = this.authReveal.find('.login-form');
        loginForm.submit(function(e){
           e.preventDefault();
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
             AuthWindow.showLoadingToForm(loginForm);
             
            var errorDiv = AuthWindow.authReveal.find('.login-form .errors');
            
            $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data, textStatus, jqXHR) 
                {
                    AuthWindow.hideLoadingFromForm(loginForm);
                    if(!data.err){
                        Header.setHeader(data.header);
                        AuthWindow.hide();
                        InfoPopUp.showInfoPopup({
                            topText : 'Sikeres bejelentkezés',
                            text: 'Sikeresen bejelentkeztél az oldalra!',
                            closeTime : 2,
                            closeFunction: function(){
                                location.reload();
                            }
                        });
                        if(typeof(UploadWindow) != 'undefined'){
                            UploadWindow.addUploadFilesButton();
                        }
                        if(typeof(GuestBook) != 'undefined'){
                            GuestBook.afterLogin();
                        }
                    }else{
                        var oH = "<ul><li>"+data.err+"</li></ul>";
                        errorDiv.html(oH);
                        errorDiv.fadeIn();
                    }
                   /* AuthWindow.hideLoadingFromForm(loginForm);
                    InfoPopUp.showInfoPopup({
                        type :      'ok',
                        topText :   'Sikeres bejelentkezés!'
                    });
                    */
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert('HIBA: '+textStatus);
                }
            });
        });
    }
    
}