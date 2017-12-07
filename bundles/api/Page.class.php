<?php
class phiApiPage {
    public $endpoints;

    function __construct() {
        $this->endpoints = [
            'test_1'=>[
                'strong'=>'Simple teste',
                'small'=>"pour voir si les actions fonctionnent sur l'environement",
                'method'=>'test',
                'button_text'=>'Super...',
                'button_type'=>'default'
            ],
            'test_2'=>[
                'strong'=>'Get post',
                'small'=>"pour voir si on peut passer des arguments",
                'method'=>'get_post',
                'button_text'=>'Super...',
                'button_type'=>'default',
                'args' => [
                    'id'=>1750
                ]
            ]
        ];

        add_action('admin_menu', [$this, 'add_api_admin_page']);

    }

    function build_url($method, $args = []){
        $url = site_url() . '/api/' . API_KEY . '/' . $method;
        if(!empty($args)){
            $url .='?' . http_build_query($args);           
        }
        return $url;
    }
    function fake_url($method, $args = []){
        $url = $this->build_url($method, $args);
        $url = str_replace(API_KEY, '{key}', $url);
        if(!empty($args)){
            foreach($args as $k => $v){
                $url = str_replace($v, "{:$k}", $url);
            }
        }
        return $url;
    }

    public function add_api_admin_page(){
        add_menu_page( THEME_NAME . ' API', THEME_NAME . ' API', 'manage_options', THEME_SHORTNAME . '-api.php', [$this, 'view'], 'dashicons-carrot', 90);
    }

    public function view() { ?>
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
                    <?php foreach($this->endpoints as $id => $action): ?>
                        <tr id="<?php echo $id; ?>" class="<?php echo isset($action['display_type']) ? $action['display_type'] : 'ready' ?>">
                            <td><strong><?php echo $action['strong'] ?></strong> <small><?php echo $action['small'] ?></small>
                                <code><?php echo $this->fake_url($action['method'], $action['args']); ?></code>
                            </td>
                            <td class="align-right action"><button class="ap-admin-btn btn-<?php echo $action['button_type']; ?> loading-button" data-text="<?php echo $action['button_text']; ?>" data-response="true" data-url="<?php echo $this->build_url($action['method'], $action['args']) ?>"><?php echo $action['button_text']; ?></button></td>
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
    <?php }
}
new phiApiPage;