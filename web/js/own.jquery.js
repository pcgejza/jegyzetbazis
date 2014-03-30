// szürke szöveg hozzáadás ha üres a textarea
$.fn.addGray = function (t) {
        var tx = (typeof(t) == 'undefined') ? 'írj valamit...' : t;
	if (!this.hasClass('grayT')) {
		this.addClass('grayT');
		this.val(tx);
		this.css('color', '#ccc');
		
		this.bind({
			focus: function(e){
				if (!$(this).hasClass('changed')) {
					$(this).val('');
				}
				$(this).css('color', '#000');
			},
			blur: function(){
				if ($(this).val().length == 0) {
					$(this).val(tx);
					$(this).css('color', '#ccc');
					$(this).removeClass('changed');
				}else{
					$(this).addClass('changed');
				}
			}
		});
        }
    return this; // return `this` for chainability
};

