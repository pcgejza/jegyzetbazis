FriendsShared = {
    
    ismerosnekJelolesGomb: null,
    
    init: function(){
      
        this.ismerosnekJelolesGomb = $('.friendMarking');
        this.bindUIActions();
    },
    
    bindUIActions: function(){
        this.bindMarkButton();
    },
    
    bindMarkButton: function(){
        this.ismerosnekJelolesGomb.unbind('click');
        this.ismerosnekJelolesGomb.bind('click', function(){
           alert('barátnak jelölés!'); 
        });
    },
    
}