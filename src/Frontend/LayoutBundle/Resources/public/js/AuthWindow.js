AuthWindow = {
    
    authReveal: null,
    getRevealUrl: null,
    openedAuthWindow: false,
    
    
    init: function(){
        this.authReveal = $('.auth-window');
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
                AuthWindow.addG();
            });
        }else{
            this.authReveal.find('.window-content .header ul li.'+page).click();
        }
    },
    
    hide: function(){
         this.authReveal.trigger('reveal:close');
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
           if(AuthWindow.validateRegistrationForm($(this))==false) return;
           
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data, textStatus, jqXHR) 
                {
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    
                }
            });
        });
    },
    
    validateRegistrationForm: function(form){
        var name, email, pass1, pass2, nickname;
        
        var errors = [];
        
        var errorDiv = this.authReveal.find('.errors');
        
        name = form.find('.name');
        email = form.find('.email');
        pass1 = form.find('.password div:eq(0) input');
        pass2 = form.find('.password div:eq(1) input');
        nickname = form.find('.nickname');
        
        if(!name.hasClass('changed')){
            errors.push('Add meg a neved!');
        }else{
            // ok
        }
        
        if(!email.hasClass('changed')){
            errors.push('Add meg az email címed!');
        }else{
            if(!AuthWindow.isValidEmailAddress(email.val())){
                errors.push('Az email cím formátuma nem megfelelő!');
            }else{
                //ok
            }
        }
        
        if(pass1.val().length==0){
           errors.push('Add meg a jelszavad!');
        }else{
            if(pass1.val().length<6){
                errors.push('A jelszó minimum 6 karakter kell hogy legyen!');
            }else{
                //ok
            }
        }
        
        if(pass1.val() != pass2.val()){
            errors.push('A jelszavak nem egyeznek!');
        }else{
            // ok
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
            errorDiv.fadeIn();
            
            return false; ////// TÖRLENDŐ
            
            return true;
        }
    },
    
    isValidEmailAddress: function(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    },
}