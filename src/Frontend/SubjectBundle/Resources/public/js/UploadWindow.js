UploadWindow = {
    
    uploadWindowReveal: null,
    uploadFilesButton: null,
    sendButton: null,
    files: null,
    allSubject: [],
    
    uploadFILE_URL: null,
    getUploadWindowURL: null,
    
    init: function(){
        this.initVariables();
        this.bindUIActions();
    },
    
    initVariables: function(){
        this.uploadFilesButton = $('.uploadFiles');
        this.uploadWindowReveal = $('.upload-window-reveal');
    },
    
    bindUIActions: function(){
        if(this.uploadFilesButton.length == 0) return;
        
        this.uploadFilesButton.click(function(){
           UploadWindow.uploadWindowReveal.reveal({
               closeOnBackgroundClick: false
           });
           if(UploadWindow.uploadWindowReveal.hasClass('not-loaded')){
               UploadWindow.uploadWindowReveal.addClass('loading-reveal');
               $.post(UploadWindow.getUploadWindowURL).done(function(h){
                   UploadWindow.uploadWindowReveal.removeClass('loading-reveal');
                   UploadWindow.uploadWindowReveal.removeClass('not-loaded');
                   UploadWindow.uploadWindowReveal.html(h);   
                   UploadWindow.sendButton = UploadWindow.uploadWindowReveal.find('.uploadAllFiles');
                   UploadWindow.bindWindowActions();
                   UploadCore.init(UploadWindow.uploadWindowReveal.find('form #upload_file'));
                });
           }
        });
    },
    
    bindWindowActions: function(){
        
        var formFile = this.uploadWindowReveal.find('form #upload_file');
        var form = this.uploadWindowReveal.find('form');
        var formSubjects = this.uploadWindowReveal.find('form .subjects');
        
        formSubjects.tagsInput({
            'defaultText':'add...',
            'height':'100px',
            'width':'300px',
        //first attempt,
            'autocomplete_url': '',
            'autocomplete' :{
                'source':UploadWindow.allSubject
            }
        });

        this.uploadWindowReveal.find('.exit').unbind('click');
        this.uploadWindowReveal.find('.exit').bind('click',function(){
            UploadWindow.hide();
        });
        
        this.uploadWindowReveal.find('.upload').unbind('click');
        this.uploadWindowReveal.find('.upload').bind('click', function(e){
            e.preventDefault();
            formFile.click();
        });
        
        this.sendButton.unbind('click');
        this.sendButton.bind('click', function(){
            UploadCore.uploadToServer();
        });
        
    },
    
    
    
    handleFileSelect: function(){
        this.fileInput = fileInput;
        var formData = new FormData();
        var acceptedImagesNo = 0;
        var countLoadedImages = 0;
        var filesLength = document.getElementById('upload_file').files.length;  
    },
    
    addUploadFilesButton: function(){
        if(this.uploadFilesButton.length == 0){
            $('.page .contentHolder').first().prepend('<span class="upload-icon uploadFiles"></span>');
            this.init();
        }
    },
    
    hide: function(){
        this.uploadWindowReveal.trigger('reveal:close');
    },
    
    addQtipToUploads: function(){
        this.uploadWindowReveal.find('table *[title]').each(function(){
             $(this).qtip({
                show: 'mouseenter',
                hide: 'mouseleave',    
                position: {
                       my: 'bottom center',  // Position my top left...
                       at: 'top center', // at the bottom right of...
                   }, 
            });
        });
    },
    
    showMiniLoading: function(){
        this.uploadWindowReveal.find('table').css('opacity', '0.4');
        this.uploadWindowReveal.find('.mini-loading').removeClass('hide');
    },
    
    hideMiniLoading: function(){
        this.uploadWindowReveal.find('table').css('opacity', '1');
        this.uploadWindowReveal.find('.mini-loading').addClass('hide');
    },
    
    bindTableElementActions: function(){
        this.uploadWindowReveal.find('table .removeRow').unbind('click');
        this.uploadWindowReveal.find('table .removeRow').bind('click', function(){
            $(this).qtip('destroy');
            var thisRow = $(this).parents('tr').first();
            var id = thisRow.data('id');
            thisRow.hide('slow', function(){ 
                thisRow.remove(); 
            });
            delete UploadCore.toSendFilesArr[id];
            if(UploadCore.toSendFilesArr.length==0){
                UploadWindow.sendButton.addClass('hide');
            }
        });
    }
}