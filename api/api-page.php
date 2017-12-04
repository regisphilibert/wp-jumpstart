<?php

$api_actions = array(
    'test_1'=>array(
        'strong'=>'Simple teste',
        'small'=>"pour voir si les actions fonctionnent sur l'environement",
        'function'=>'test',
        'button_text'=>'Super...',
        'button_type'=>'default'
    )
);
?>
<div class="wrap">
    <h2><?php echo THEME_NAME; ?> API</h2>
    <div class="ap-admin-wrap api-options-ui">
        <table>
            <thead>
                <tr>
                    <th>Fonction</th>
                    <th class="align-right">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($api_actions as $id => $action): ?>
                <tr id="<?php echo $id; ?>" class="<?php echo isset($action['display_type']) ? $action['display_type'] : 'ready' ?>">
                    <td><strong><?php echo $action['strong'] ?></strong> <small><?php echo $action['small'] ?></small>
                        <code><?php bloginfo( 'url' ) ?>/api/{api_key}/<?php echo $action['function'] ?></code>
                    </td>
                    <td class="align-right action"><button class="ap-admin-btn btn-<?php echo $action['button_type']; ?> loading-button" data-text="<?php echo $action['button_text']; ?>" data-response="true" data-url="<?php bloginfo( 'url' ) ?>/api/<?php echo API_KEY; ?>/<?php echo $action['function'] ?>"><?php echo $action['button_text']; ?></button></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<script>

jQuery(document).ready(function ($) {
    /*********************************************
                LES DEBUGS ALERTS...
    *********************************************/
    $(".debug-alert-title").click(function(){
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

});

</script>