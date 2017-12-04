jQuery(document).ready(function($){
    if($('.settings_page_acf-options-jumpstart').length){
        $.post(ajaxurl, "action=option_page_info", function(response){
            $('.settings_page_acf-options-jumpstart #post-body-content .inside').prepend(response).after("<hr>Jumpstart | Regis Philibert  @2016<br><small>Wordpress starter theme.</small>");
            $('.settings_page_acf-options-jumpstart h1').addClass('jumpstart-page-title').append("<small>Wordpress starter theme</small>");
        });
    }
});