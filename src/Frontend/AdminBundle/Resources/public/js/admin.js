$(document).ready(function(){
   if($('#new_docs_text').length > 0){
    CKEDITOR.replace('new_docs_text');
   }
   if($('#edit_docs_text').length > 0){
       CKEDITOR.replace('edit_docs_text');
   }
});