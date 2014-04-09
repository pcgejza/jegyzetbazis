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
    
    init: function(uploadFilesInput) {
        this.uploadFilesInput = uploadFilesInput;
        this.bindUIActions();
    },
    resetFiles: function() {
        this.uploadFilesInput.val("");
        this.files = null;
        this.selectedFilesArr = [];
    },
    bindUIActions: function() {

        this.uploadFilesInput.change(function(event) {
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
    filesProcessing: function() {
        if (UploadCore.files.length > 0) {
            UploadCore.actualADDcount = UploadCore.files.length + $('.uploadWindowContent .uploadElements table tbody tr').length;
            $.each(UploadCore.files, function(i, f) {
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
                fileObject: UploadCore.files[index]
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