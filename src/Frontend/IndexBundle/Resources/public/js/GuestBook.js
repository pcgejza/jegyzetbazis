GuestBook = {
    
    newButton: null,
    newForm: null,
    sendBooking: null,
    textarea: null,
    sendBookingURL: null,
    cancelButton: null,
    progress: false,
    
    init: function(){
        this.newButton = $('.guest-book .newBooking .newLabel');
        this.newForm = $('.guest-book .newBooking .newForm');
        this.sendBooking = this.newForm.find('.sendBooking');
        this.cancelButton = this.newForm.find('.cancel');
        this.textarea = this.newForm.find('textarea');
        
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        
        this.textarea.addGray('Írj egy megjegyzést vagy véleményt...');
        /*
        this.newButton.click(function(){
           $(this).addClass('hide');
           GuestBook.newForm.removeClass('hide');
        });
        */
        this.sendBooking.click(function(e){
            e.preventDefault();
            var textarea = $(this).siblings('textarea');
            if(textarea.hasClass('changed')){
                if(GuestBook.progress) return; // ha a küldés folyamatban van akkor nem lehet elküldeni újra
                GuestBook.progress = true;
                var loadinDiv = $(this).siblings('.loading-mini').removeClass('hide');
                var val = textarea.val();
                $.post(GuestBook.sendBookingURL, {
                   text : val 
                }).done(function(data){
                    loadinDiv.addClass('')
                    if(!data.err){
                        GuestBook.progress = false;
                        loadinDiv.addClass('hide');
                        textarea.val('').blur();
                        $('.guest-book .entries').prepend(data.newRow);
                   }else{
                       alert('Hiba : '+data.err);
                   }
                });
            }else{
                alert('adj meg egy szöveget!');
            }
        });
        
    },
    
    afterLogin: function(){
      $('.guest-book .newBooking').fadeIn();  
    },
    
}