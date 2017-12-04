
/*! jQuery Rot13 Plugin - v0.1.0 - 2012-04-24
* https://github.com/kadamwhite/jquery.rot13
* Copyright (c) 2012 K.Adam White; Licensed MIT */
//(function(a){var b=function(b){var c=b.split("");return a.map(c,function(a){var b;return a.match(/[A-Za-z]/)?(b=a.charCodeAt(0),b<97?b=(b-52)%26+65:b=(b-84)%26+97,String.fromCharCode(b)):a}).join("")};a.fn.rot13=function(){return a(this).each(function(){var c=a(this).html();return c.match(/[<>]/)?(c=c.replace(/^([^<]*)</,b),c=c.replace(/>([^<]*)</g,b),c=c.replace(/>([^<]*)$/,b)):c=b(c),a(this).html(c)}),this},a.rot13=function(a){return b(a)}})(jQuery);

//+ Jonas Raoni Soares Silva
//@ http://jsfromhell.com/string/rot13 [rev. #1]

String.prototype.rot13 = function(){
    return this.replace(/[a-zA-Z]/g, function(c){
        return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
    });
};

/*
* arlette
* Popup javascript reservé au debug_user avec le même style et fonctionnalité qu'en php.
* @param : Comme pour php, message, type (notice, warning, danger), title.
* @return true ou false...
*/
function arlette(message, type, title){
    type = typeof type !== 'undefined' ? type : 'default';
    title = typeof title !== 'undefined' ? title : 'Alert!';
    alert_box = $("<div style='display:none;' class='debug-alert-container "+type+"'><div class='debug-alert-js debug-alert "+type+"'><nav><a class='expend-alert'>< - ></a> | <a class='close-alert'>X</a></nav><div class='debug-alert-title'>"+title+"</div><div class='debug-alert-content'>"+message+ "</div></div></div>");
    if(is_debug_user()){
        $("body").prepend(alert_box);
        setTimeout(function(){
           alert_box.fadeIn(150);
        }, 20);
    }
}


/*
* is_debug_user
* Vérfiie que l'utilisateur courant est "debug".
* @return true ou false...
*/
function is_debug_user(){
    if(typeof debug_user == "undefined"){
        return false;
    }
    return debug_user == 1 ? true : false;
}

jQuery(document).ready(function ($) {
    /*********************************************
                LES DEBUGS ALERTS...
    *********************************************/
    $(document).on('click', ".debug-alert-title", function(){
        if(!$(this).parents('.debug-alert').hasClass('expended')){
            $(this).next("div").slideToggle(250);
            $(this).parent('.debug-alert').toggleClass('minimized');
        }
    });
    $(document).on('click', ".close-alert", function(){
        alert_box = $(this).parents('.debug-alert');
        if(alert_box.hasClass('expended')){
            alert_box.removeClass('expended');
        }
        else{
            if(alert_box.hasClass('debug-alert-js')){
                alert_box.parents('.debug-alert-container').fadeOut(150, function(){
                    $(this).remove();
                });
            }
            else{
                alert_box.animate({opacity:0}, 250, function(){
                    $(this).slideUp(250);
                });
            }
        }
    });
    $(document).on('click', ".expend-alert", function(){
        alert_box = $(this).parents('.debug-alert');
        alert_box.toggleClass('expended');
    });

    /*********************************************
                SOCIAL POPUP
    *********************************************/
    //WHaaaaat ? :/
    //Uncaught TypeError: Object .social-popup has no method 'apply'
/*    $(document).on('click','.social-popup').click(function(){
        window.open($(this).attr('href'), "Social", "width=650,height=500");
        return false;
    });*/

    /*********************************************
                DISPLAY DEBUG ?
    *********************************************/
    if(is_debug_user() && display_debug){
        $("html").addClass('jumpstart-debug');
    }
    /*********************************************
                PROTECTION (lié à tool-box.php:l291)
    *********************************************/
    //Retreive the rot13 encoded data-attr for user and domain, decode them and merge them into a beautiful botproof email adress.
    if($('.add-email').length){
        $(".add-email").each(function(){
            usermail= $(this).data("user");
            domain = $(this).data("domain");
            usermail = usermail.rot13();
            domain = domain.rot13();
            $(this).html("<a href='mailto:"+usermail+"@"+domain+"'>"+usermail+"@"+domain+"</a>");
        });
    }
});