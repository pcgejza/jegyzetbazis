/**
 *  UploadCore : A fájl feltöltés 'magja'
 *  
 *  
 */
UploadCore = {
    
    
    uploadFilesInput: null,
    files: null,
    eventTarget: null,
    selectedFilesArr: [],
    
    init: function(uploadFilesInput){
       this.uploadFilesInput = uploadFilesInput; 
       this.bindUIActions();
    },
    
    resetFiles: function(){
        this.uploadFilesInput = null;
        this.files = null;
        this.selectedFilesArr = [];
    },
    
    bindUIActions: function(){
        
        this.uploadFilesInput.change(function(event){
            UploadCore.eventTarget = event.target;
            UploadCore.files = event.target.files;
            UploadCore.filesProcessing();
        });

        /*
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
        }*/
        
    },
    
    
    filesProcessing: function(){
        if(UploadCore.files.length>0){
            var aFunc = function(){
                console.debug(UploadCore.selectedFilesArr);
                var list = "";
                $.each(UploadCore.selectedFilesArr, function(i,f){
                   var s = '<tr>';
                   s += '<td>'+f.name+'</td>';
                   s += '<td><img src="'+f.url+'"></td>';
                   s += '<td>'+f.size+'kb</td>';
                   s += '<td>'+f.type+'</td>';
                   s += '</tr>';
                   list += s;
                });
                
                $('.uploadWindowContent table').append(list);
            };
            $.each(UploadCore.files, function(i, f){
                UploadCore.getFilePathWithFileReader(f,i, aFunc);
            });
        }else{
            console.error('Nincs fájl kiválasztva!');
        }
    },
    
    /**
     * selectedFilesArr összeállítás 
     * 
     * @param {file} file Maga a file
     */
    getFilePathWithFileReader: function(file, index, afterFunction){
        var reader = new FileReader();
        reader.onload = function(event){
          var dataURL = event.target.result;
          UploadCore.selectedFilesArr[index] = { 
              url : dataURL,
              size: Math.round(UploadCore.files[index].size / 1024),
              name: UploadCore.files[index].name,
              type: UploadCore.files[index].type
          };
          if(UploadCore.selectedFilesArr.length == UploadCore.files.length)
              afterFunction();
        };
        reader.readAsDataURL(file);
    }
}