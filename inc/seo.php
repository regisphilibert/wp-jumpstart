<?php
class themeSEO extends jsSEO
{

    public function customData(){
        /* Override default data here.
        $this->data->twitter_handle = '';
        if($title){
            $this->data->title = $title;
        }
        $this->data->twitter_handle = '';
        */
    }

    public function customMetas(){
        /* 
        Add custom metas here (custom fields etc...) like exemple below:
        $this->metas['property="fb:app_id"'] = "1585438595085151";
        */

    }

}

add_action('template_redirect', 'theme_start_seo', 50);

function theme_start_seo(){
    new themeSEO;
}