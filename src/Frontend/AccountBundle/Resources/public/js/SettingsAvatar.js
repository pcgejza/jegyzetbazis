/*
 * A beállításokon belül lévő avatár beállításokhoz szükséges funkciók 
 * vannak ebben az objektumban definiálva
 */
SettingsAvatar = {
    
    fileInput: null,
    selectedAvatarImgElement: null,
    uploadNewAvatarButton: null,
    uploadedAvatarsHolder: null,
    loadingBoxHTML: '<div class="uploaded-image loading">'
                    +'<img src="/jegyzetbazis/web/images/loading1.gif">'
                    +'</div>',
    uploadID: 0,
    
    init: function(){
        this.fileInput = $('.uploadAvatarInput');
        this.uploadedAvatarsHolder = $('.settingsPage .uploadedAvatarsHolder');
        this.selectedAvatarImgElement = $('.settingsPage .current-selected-avatar img');
        this.uploadNewAvatarButton = this.uploadedAvatarsHolder.find('.add-new');
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.uploadNewAvatarButton.click(function(){
           SettingsAvatar.fileInput.click();
        });
        
        this.fileInput.change(function(e){
            SettingsAvatar.uploadFile(e.target.files[0]);
        });
        
        this.bindUploadedAvatarsClickActions();
    },
    
    /*
     * Fájl feltöltésére szolgáló metódus
     */
    uploadFile: function(f){
        
        var formD = new FormData();
        formD.append('image',f);
        this.fileInput.val(null); // a file input kinullázása
        var uploadID = this.addNewLoadingDiv();
        
        $.ajax({
                type: 'POST',
                data: formD,
                cache: false,
                contentType: false,
                processData: false,
                url: Shared.uploadAvatarUrl,
        }).done(function(data){
            if(!data.err){
                SettingsAvatar.uploadedAvatarsHolder
                        .find('*[uploadID="'+uploadID+'"] img')
                            .parent()
                            .removeClass('loading')
                            .end()
                        .attr('src', data.avatarSRC)
                        .attr('avatar200', data.avatar200SRC)
                        .attr('avatarid', data.avatarId);
                
                SettingsAvatar.bindUploadedAvatarsClickActions();
            }else{
                alert('Hiba : '+data.err);
            }
        });
    },
    
    /*
     * A feltöltött avatár képekhez egy új betöltő kép hozzáadása
     */
    addNewLoadingDiv: function(){
       this.uploadedAvatarsHolder
               .find('.new-files')
                   .prepend(
                        $(SettingsAvatar.loadingBoxHTML)
                                .attr('uploadID', ++SettingsAvatar.uploadID)
                                .addClass('loading')
                   );
       return this.uploadID;
    },
    
    /*
     * Az avatár képekre kattintva események felüldefiniálása
     */
    bindUploadedAvatarsClickActions: function(){
       this.uploadedAvatarsHolder
               .find('.uploaded-image:not(.add-new)')
                    .unbind('click')
               .bind('click', function(){
                   if($(this).hasClass('loading')){
                       console.error('Még tölti felfelé!!');
                       return;
                   }
                   var src200 = $(this).find('img').attr('avatar200');
                   var avatarId = $(this).find('img').attr('avatarid');
                   SettingsAvatar.selectedAvatarImgElement.attr('src', src200);
                   $('input[type="hidden"].selectedAvatarId').val(avatarId);
                });
    },
}