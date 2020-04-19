$('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
$(function(){
	window.onwheel = function(){ return false; }
})