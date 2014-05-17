/**
 *  UploadCore : A fájl feltöltés 'magja'
 */
UploadCore = {
    
    uploadFilesInput: null,
    files: null,
    selectedFilesArr: [],
    toSendFilesArr: [],
    ID_COUNTER: 0,
    actualADDcount: 0,
    
    uploadedFileIDs: [],
    
    selectedSubjects: [],
    
    helperArr: [],
    
    PROGRESSES: [],
    
    limit: 40,// legnagyobb felölthető fájl mérete (MEGABÁJTBAN!!!)
    noUploadFiles: [],
    
    afterFunctions: {},
    
    init: function(uploadFilesInput) {
        this.uploadFilesInput = uploadFilesInput;
        this.bindUIActions();
    },
    resetFiles: function() {
        this.uploadFilesInput.val("");
        this.files = null;
        this.helperArr = [];
        this.selectedFilesArr = [];
        this.noUploadFiles = [];
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
            var sizeMB = Math.round(UploadCore.files[index].size / 1024 / 1024);
            
            if(sizeMB <= UploadCore.limit){// CSAK AZOKAT A FÁJLOKAT TESSZÜK A LISTÁBA AMELYEK MÉRETE NEM HALADJA MEG A LIMITET!
                UploadCore.selectedFilesArr[index] = {
                    url: dataURL,
                    size: Math.round(UploadCore.files[index].size / 1024),
                    name: UploadCore.files[index].name,
                    type: UploadCore.files[index].type,
                    fileObject: UploadCore.helperArr[index]
                };

                var id = ++UploadCore.ID_COUNTER;
                UploadCore.addElementToToSendFilesArr(id, UploadCore.selectedFilesArr[index]);

                var s = '<tr data-id="'+id+'">';
                 s += '<td title="Kattints az átnevezéshez" class="renameFile">' + UploadCore.selectedFilesArr[index].name + '</td>';
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
                $('.uploadWindowContent .uploadElements table tbody').prepend(s);
              
            }else{
                UploadCore.noUploadFiles.push({
                    name: UploadCore.files[index].name,
                    size: Math.round(UploadCore.files[index].size / 1024)
                });
            }
            
              if(Object.keys(UploadCore.noUploadFiles).length + $('.uploadWindowContent .uploadElements table tbody tr').length == UploadCore.actualADDcount){
                   if(Object.keys(UploadCore.noUploadFiles).length > 0){
                       var t = '';
                       var ft = Object.keys(UploadCore.noUploadFiles).length + ' darab fájlt nem sikerült feltölteni';
                       $.each(Object.keys(UploadCore.noUploadFiles), function(i, f){
                           t += UploadCore.noUploadFiles[f].name + ", mérete : "+ UploadCore.noUploadFiles[f].size +"kb<br>";
                       });
                       t += '<br>Hiba oka: a fájlok mérete nagyobb mint ' + UploadCore.limit + 'MB';
                       InfoPopUp.showInfoPopup({
                           type : 'error',
                           topText : ft,
                           text : t,
                           closeFunction: function(){
                               UploadWindow.showWindow();
                           }
                       });
                   }
                
                    UploadWindow.addQtipToUploads();
                    UploadCore.resetFiles();
                    UploadWindow.hideMiniLoading();
                    UploadWindow.bindTableElementActions();
                    $('.postInputChangeElements').removeClass('hide');
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
                                returnImage += '/jegyzetbazis/web/images/pdf_icon.png';
                                break;
                            }
                        case 'docx':
                        case 'doc':
                            {
                                title = "Dokumentum fájl";
                                returnImage += '/jegyzetbazis/web/images/doc_icon.png';
                                break;
                            }
                        default:
                            title = "Ismeretlen fájl";
                            returnImage += '/jegyzetbazis/web/images/file_icon.png';
                    }
                    break;
                }
            case 'audio':
                title = "Audió fájl";
                returnImage += '/jegyzetbazis/web/images/audio_icon.png';
                break;
            case 'video':
                title = "Videó fájl";
                returnImage += '/jegyzetbazis/web/images/video_icon.png';
                break;
            default:
                title = "Ismeretlen fájl";
                returnImage += '/jegyzetbazis/web/images/file_icon.png';
        }
        returnImage += "' title='"+title+"'>";
        return returnImage;
    },
    uploadToServer: function(){
        if(Object.keys(UploadCore.toSendFilesArr).length > 0){
         $.each(UploadCore.toSendFilesArr, function(key, value)
         {
            if(value == null) return;
            if(typeof($(this)[0].fileObject) !== 'object') return;
             
            var data = new FormData();
            data.append("file", $(this)[0].fileObject);
            data.append("filename", $(this)[0].name);
            
             $.each(Object.keys(UploadCore.selectedSubjects), function(i, f){
                    data.append("subjects[]",UploadCore.selectedSubjects[f]);
             });
            
            UploadCore.addProgressBar(key);
            var k = key;
            UploadCore.PROGRESSES[k] = true;
            $.ajax({
                url: UploadWindow.uploadFILE_URL,
                type: 'POST',
                data: data,
                cache: false,
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
                    UploadCore.afterProgress(k);
                    if(data.success){
                        UploadCore.addComplete(k);
                        UploadCore.uploadedFileIDs.push(data.id);
                        UploadCore.removeElementFromToSendFilesArr(k);
                        if( aft = UploadCore.getAfterFunctionByProgressID(k)){
                            aft.func();
                        }
                        if(!UploadCore.isProgress()){
                            if(typeof(Subjects) != 'undefined'){
                                Subjects.refreshActualPage();
                            }
                        }
                        $('tr[data-id="'+k+'"]').attr('fileId', data.id);
                    }else{
                        console.error('Hiba : '+data.err);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('ERRORS: ' + textStatus);
                }
            });
         });

        }else{
            alert('Minden fájl fel van töltve!');
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
    
    afterProgress: function(progressKey){
       delete this.PROGRESSES[progressKey];
       var l = this.getProcessesLength();
       if(l==0){
           // nincs már több process
       }else{
           console.debug('Még van '+l+'.darab process!');
       }
    },
    
    getProcessesLength: function(){
        return Object.keys(this.PROGRESSES).length;
    },
    
    isProgress: function(){
        return (this.getProcessesLength()==0) ? false : true;
    },
    
    getAfterFunctionByProgressID: function(pid){
        return typeof(this.afterFunctions[pid]) == 'undefined' ? null : this.afterFunctions[pid];
    },
    
    addAfterFunction: function(type,func, progresses){
        $.each(progresses, function(i,a){
            UploadCore.afterFunctions[i] = {
                type : type,
                func : func
            };
        });
    },
    
    addSubject: function(s){
        UploadCore.selectedSubjects[s.id] = s.name;
        if(this.isProgress()){
            var after = function(){
                UploadCore.addSubject(s);
            };
            this.addAfterFunction('subject', after, this.PROGRESSES);
            return;
        }
        if(UploadCore.uploadedFileIDs.length > 0){
            $.post(UploadWindow.updateFilesSubjectsURL,{
                subject : s,
                fileIds : UploadCore.uploadedFileIDs,
                type : 'add'
            }).done(function(data){
                if(!data.err){
                    console.debug('Sikeres tantárgy frissítés');
                }else{
                    console.error('sikertelen frissítés!!!'+data.err);
                }
            });
        }
    },
    
    removeSubject: function(s){
        delete UploadCore.selectedSubjects[s.id];
        if(this.isProgress()){
            var after = function(){
                UploadCore.removeSubject(s);
            };
            this.addAfterFunction('subject', after,this.PROGRESSES);
            return;
        }
        if(UploadCore.uploadedFileIDs.length > 0){
            $.post(UploadWindow.updateFilesSubjectsURL,{
                subject : s,
                fileIds : UploadCore.uploadedFileIDs,
                type : 'remove'
            }).done(function(data){
                if(!data.err){
                    console.debug('Sikeres tantárgy frissítés');
                }else{
                    console.error('sikertelen frissítés!!!'+data.err);
                }
            });
        }
    },
    
    removeElementFromToSendFilesArr: function(key){
        delete this.toSendFilesArr[key];
    },
    
    addElementToToSendFilesArr: function(key, element){
        this.toSendFilesArr[key] = element;
    },
    
    renameFileAjax: function(fileid, name){
        UploadWindow.showMiniLoading();
        $.post(UploadWindow.fileRenameURL, {
            fileid : fileid,
            name : name
        }).done(function(d){
             UploadWindow.hideMiniLoading();
            if(d.err){
                alert('hiba a fájl átnevezése során: '+d.err);
            }else{
                console.log('Fájl sikeresen átnevezve!');
            }
        });
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