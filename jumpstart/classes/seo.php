<?php

class jsSEO
{

    public $data;

    public $post;

    public $singular;

    public $metas;

    public function __construct($post_id = false){
        $this->singular = $post_id;
        $this->data = new stdClass();
        $this->data->twitter_handle = '@' . THEME_SHORTNAME;
        if(!$post_id){
            global $post;
            $this->post = $post;
        } else {
            $this->post = get_post($post_id);
        }
        $this->get_data();
        $this->populate_metas();
        add_action( 'wp_head', [$this, 'add_metas_to_head'] );
    }

    public function customData(){}

    public function customMetas(){}

    private function get_data(){

        $this->data->locale = get_locale();

        if(!$this->is_singular()){
            $this->data->url = get_bloginfo('url');
            $this->data->title = get_bloginfo( 'title' );
            $this->data->type = 'website';
            $this->data->description = get_bloginfo( 'description');
        }
        if($this->is_singular()){
            $this->data->url = get_permalink( $this->post->ID );
            $this->data->title = $this->post->post_title;
            if($post->post_excerpt != ''){
                $this->data->description = $this->post->post_excerpt;
            }
            else{
                $this->data->description = shortenIt(wp_strip_all_tags($this->post->post_content), 150);
            }
            if(has_post_thumbnail($this->post)){
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($this->post->ID), 'large');
                $this->data->image = new stdClass();
                $this->data->image->url = $image[0];
                $this->data->image->width = $image[1];
                $this->data->image->height = $image[2];
            }
        }

        if(in_array($this->post->post_type, ['post'])){
            $this->data->type = 'article';

        }

        $this->customData();

    }

    public function populate_metas(){
        $metas['name="twitter:card"'] = "summary";
        $metas['name="twitter:site"'] = $this->data->twitter_handle;
        $metas['name="twitter:title"'] = $this->data->title;
        $metas['name="twitter:description"']= $this->data->description;
        $metas['property="og:locale"'] = $this->data->locale;
        $metas['property="og:site_name"'] = get_bloginfo('name');
        $metas['property="og:title"'] = $this->data->title;
        $metas['property="og:type"'] = $this->data->type;
        $metas['property="og:description"'] = $this->data->description;
        $metas['property="og:url"'] = $this->data->url;
        if(is_object($this->data->image)){
            $this->metas['name="twitter:image"'] = $this->data->image->url;
            $this->metas['property="og:image"'] = $this->data->image->url;
            $this->metas['property="og:image:width"'] = $this->data->image->width;
            $this->metas['property="og:image:height"'] = $this->data->image->height;
        } else {
            $metas['property="og:image"'] = $this->data->image;
            $metas['name="twitter:image"'] = $this->data->image;
        }
        $this->metas = $metas;

        $this->customMetas();
    }

    public function add_metas_to_head(){
        foreach($this->metas as $key => $value){
            echo '<meta ' . $key . ' content="' . $value .'"/>';
        }
    }

    public function is_singular(){
        if($this->singular){
            return true;
        }
        if(is_front_page()){
            return false;
        }
        return is_singular();
    }
}