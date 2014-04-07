UploadWindow = {
    
    uploadWindowReveal: null,
    uploadFilesButton: null,
    getUploadWindowURL: null,
    files: null,
    allSubject: [],
    
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
                   UploadWindow.bindWindowActions();
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
        
        formFile.change(prepareUpload);

        function prepareUpload(event)
        {
          UploadWindow.files = event.target.files;
        }
        
        form.submit(function(e){
            e.preventDefault();
            uploadFiles(e);
        });
        
        
        function uploadFiles(event){
            event.stopPropagation(); // Stop stuff happening
            event.preventDefault(); // Totally stop stuff happening

            var data = new FormData();
            $.each(UploadWindow.files, function(key, value)
            {
                    data.append("files[]", value);
            });
    
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(data, textStatus, jqXHR)
                {
                        if(typeof data.error === 'undefined')
                        {
                                alert('no error');
                        }
                        else
                        {
                                // Handle errors here
                                console.log('ERRORS: ' + data.error);
                        }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                        // Handle errors here
                        console.log('ERRORS: ' + textStatus);
                        // STOP LOADING SPINNER
                }
            });
        }
        
        
        
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
    
}