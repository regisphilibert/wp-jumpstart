jQuery(document).ready(function($){
    if($('.api-options-ui').length){
        apiClearDone = 0;
        $(document).on("click", ".loading-button", function(event){
            clearTimeout(apiClearDone);
            $(".loading-button.done").removeClass('done');
            docTitle = $(document).find('head title');
            if(typeof docTitle.attr('data-original') == 'undefined'){
                docTitle.attr('data-original', docTitle.html())
            }
            else{
                docTitle.html(docTitle.attr('data-original'));
            }
            docTitle.attr('data-original', docTitle.html());
            ajaxContainer = $(".ajax-response-content");
            if(ajaxContainer.length){
                ajaxContainer.slideUp(100, function(){
                    ajaxContainer.parents('.ajax-response').remove();
                })
            }
            that = $(this);
            $.ajax({
                url: $(this).attr('data-url'),
                data:"ui=1",
                beforeSend:function(){
                    that.addClass('loading');
                }
            }).done(function(response){
                that.removeClass('loading').addClass('done').html('Terminé');
                docTitle.html("APM -- Done :) --");
                apiClearDone = setTimeout(function(){
                    that.removeClass('done').html(that.attr('data-text'));
                }, 3000)
                if(that.attr('data-response')){
                    that.parents('tr').after('<tr class="ajax-response"><td colspan="2"><div class="ajax-response-content">'+response+'</div></td></tr>');
                    $('.ajax-response-content').slideDown(250);
                }
            });
           event.preventDefault();
        });
    }
});