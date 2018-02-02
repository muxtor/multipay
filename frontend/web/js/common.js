jQuery(document).ready(function(){
//    $('[name="SignupForm[phone]"]').mask('+994(###) ###-##-##');
    
    
$('.b-spoiler').on('click', function(){
$(this).toggleClass('b-active'); 
$('.b-spoiler-text', this).slideToggle('fast');   
});
$("body").click(function(event){
if($('.b-message-popup').css('opacity')=='1'){
if($(event.target).closest(".b-message-popup").length === 0){
$('.b-message-popup').animate({
opacity: 0,
}, 300, function(){
$('.b-message-popup').removeClass('b-show');
});		
}
}
});
$('.b-message-icon').click(function(e){
e.preventDefault();
$('.b-message-popup').addClass('b-show');
$('.b-message-popup').animate({
opacity: 1,
}, 300);
});
$('.b-auth-login').click(function(e){
//e.preventDefault();
$('.b-auth-dropdown').slideToggle('fast');
});

});