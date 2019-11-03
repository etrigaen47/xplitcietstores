jQuery(window).scroll(function(){
    var vscroll = jQuery(this).scrollTop();
    //console.log(vscroll);
    jQuery('#logotext').css({
       "transform" : "translate(0px, "+vscroll/2+"px)"
    });
});
//the above function, cause the logotext to stay in place, in the center of the
//page while the page is being scrolled