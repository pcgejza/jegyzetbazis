/**
 *  UploadCore : A fájl feltöltés 'magja'
 *  
 *  
 */
UploadCore = {
    
    uploadFilesInput: null,
    files: null,
    selectedFilesArr: [],
    toSendFilesArr: [],
    ID_COUNTER: 0,
    actualADDcount: 0,
    
    
    helperArr: [],
    
    init: function(uploadFilesInput) {
        this.uploadFilesInput = uploadFilesInput;
        this.bindUIActions();
    },
    resetFiles: function() {
        this.uploadFilesInput.val("");
        this.files = null;
        this.helperArr = [];
        this.selectedFilesArr = [];
    },
    bindUIActions: function() {

        this.uploadFilesInput.change(function(event) {
            UploadCore.files = event.target.files;
            UploadCore.filesProcessing();
        });

    },
    filesProcessing: function() {
        if (UploadCore.files.length > 0) {
            UploadWindow.showMiniLoading();
            UploadCore.actualADDcount = UploadCore.files.length + $('.uploadWindowContent .uploadElements table tbody tr').length;
            $.each(UploadCore.files, function(i, f) {
                UploadCore.helperArr[i]=f;
                UploadCore.getFilePathWithFileReader(f, i);
            });
        } else {
            console.error('Nincs fájl kiválasztva!');
        }
    },
    /**
     * selectedFilesArr összeállítás 
     * 
     * @param {file} file Maga a file
     */
    getFilePathWithFileReader: function(file, Ind, l) {
        var reader = new FileReader();
        reader.onload = function(event) {
            var dataURL = event.target.result;
            var index = Ind;
            UploadCore.selectedFilesArr[index] = {
                url: dataURL,
                size: Math.round(UploadCore.files[index].size / 1024),
                name: UploadCore.files[index].name,
                type: UploadCore.files[index].type,
                fileObject: UploadCore.helperArr[index]
            };
            
            var id = ++UploadCore.ID_COUNTER;
            UploadCore.toSendFilesArr[id] = UploadCore.selectedFilesArr[index];
        
            var s = '<tr data-id="'+id+'">';
             s += '<td title="Kattints az átnevezéshez">' + UploadCore.selectedFilesArr[index].name + '</td>';
            if (UploadCore.selectedFilesArr[index].type.substr(0, strpos( UploadCore.selectedFilesArr[index].type, '/')) == 'image') {
              s += '<td><img src="' +  UploadCore.selectedFilesArr[index].url + '"></td>';
            } else {
                s += '<td>' + UploadCore.getImageByType(UploadCore.selectedFilesArr[index].type) + '</td>';
            }
            s += '<td>' + UploadCore.selectedFilesArr[index].size + 'kb</td>';
            s += '<td>' + UploadCore.selectedFilesArr[index].type + '</td>';
            s += '<td><span class="removeRow" title="Törlés"></span></td>';
            s += '</tr>';
            
            $('.uploadWindowContent .uploadElements').removeClass('hide');
            $('.uploadWindowContent .uploadElements table tbody').append(s);
            if($('.uploadWindowContent .uploadElements table tbody tr').length == UploadCore.actualADDcount){
                UploadWindow.addQtipToUploads();
                UploadCore.resetFiles();
                UploadWindow.hideMiniLoading();
                UploadWindow.bindTableElementActions();
                UploadWindow.sendButton.removeClass('hide');
            }
        };
        
        reader.readAsDataURL(file);
    },
    getImageByType: function(type) {
        var preType = type.substr(0, strpos(type, '/'));
        var postType = type.substr(strpos(type, '/')+1);
        var returnImage = "<img src='";
        var title = "";
        switch (preType) {
            case 'application':
                {
                    switch (postType) {
                        case 'pdf':
                            {
                                title = "pdf fájl";
                                returnImage += '/symfony/web/images/pdf_icon.png';
                                break;
                            }
                        case 'docx':
                        case 'doc':
                            {
                                title = "Dokumentum fájl";
                                returnImage += '/symfony/web/images/doc_icon.png';
                                break;
                            }
                        default:
                            title = "Ismeretlen fájl";
                            returnImage += '/symfony/web/images/file_icon.png';
                    }
                    break;
                }
            case 'audio':
                title = "Audió fájl";
                returnImage += '/symfony/web/images/audio_icon.png';
                break;
            case 'video':
                title = "Videó fájl";
                returnImage += '/symfony/web/images/video_icon.png';
                break;
            default:
                title = "Ismeretlen fájl";
                returnImage += '/symfony/web/images/file_icon.png';
        }
        returnImage += "' title='"+title+"'>";
        return returnImage;
    },
    uploadToServer: function(){
        if(Object.keys(UploadCore.toSendFilesArr).length > 0){
         $.each(UploadCore.toSendFilesArr, function(key, value)
         {
            if(typeof($(this)[0].fileObject) !== 'object') return;
             
            var data = new FormData();
            data.append("file", $(this)[0].fileObject);
            UploadCore.addProgressBar(key);
            var k = key;
            $.ajax({
                url: UploadWindow.uploadFILE_URL,
                type: 'POST',
                data: data,
                cache: false,
                
            //@TODO start here
                xhr: function() {  // custom xhr
                    var id = key;
                    var myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){ // check if upload property exists
                        myXhr.upload.addEventListener('progress',
                        function(evt){
                            UploadCore.updateProgressBar(evt, id)
                        }
                        , false); // for handling the progress of the upload
                    }
                    return myXhr;
                },
                
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR){
                    if(data.success){
                        console.debug(data.wp);
                        UploadCore.addComplete(k);
                    }else{
                        console.error('Hiba : '+data.err);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('ERRORS: ' + textStatus);
                }
            });
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

        }else{
            alert(' NO UPLOAD!');
        }
    },
    
    addProgressBar: function(id) {
        var progressBar = '<div class="progressBar" progressID="'+id+'"></div>';
        $('table tr[data-id="'+id+'"] td').last().html(progressBar);
        $('table tr[data-id="'+id+'"] td .progressBar').progressbar({
            value: 0
         });
    },
    
    addComplete: function(id){
        var ci = '<span class="complete-icon"></div>';
        $('table tr[data-id="'+id+'"] td').last().html(ci);
    },
    
    updateProgressBar: function(evt,id){
        if (evt.lengthComputable) {
                var percentComplete = Math.round( 100 * (evt.loaded / evt.total));
                 $('table tr[data-id="'+id+'"] td .progressBar').progressbar({
                    value: percentComplete
                 });
        }
    },
}
function strpos(haystack, needle, offset) {
    //  discuss at: http://phpjs.org/functions/strpos/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Onno Marsman
    // improved by: Brett Zamir (http://brett-zamir.me)
    // bugfixed by: Daniel Esteban
    //   example 1: strpos('Kevin van Zonneveld', 'e', 5);
    //   returns 1: 14

    var i = (haystack + '')
            .indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}