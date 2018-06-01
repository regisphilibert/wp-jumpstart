<?php 
class jsRegister{

    function __construct(){
        add_action('init', [$this, 'post_types']);
        add_action('init', [$this, 'taxonomies']);
    }


    function post_types() {

        /*  Substance */
        $labels = array(
            'name'                => __( 'Thingies', 'tx' ),
            'singular_name'       => __( 'Thingy', 'text-domain' ),
            'add_new'             => __( 'Add Thingy', 'tx', 'tx' ),
            'add_new_item'        => __( 'Add a new Thingy', 'tx' ),
            'edit_item'           => __( 'Edit Thingy', 'text-domain' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'menu_icon'           => 'dashicons-carrot',
            'rewrite'             => array('slug'=>'thingy'),
        );

        register_post_type( 'js_thingy', $args );

    }

    function taxonomies(){
        $taxonomies = $this->get_tax_args();
        foreach($taxonomies as $id => $taxo ){
            $labels = array(
                'name'                    => _x( $taxo['labels']['plural'], $taxo['labels']['plural'], 'tx' ),
                'singular_name'            => isset($taxo['labels']['singular_loc']) ? $taxo['labels']['singular_loc'] :  _x( $taxo['labels']['singular'], $taxo['labels']['singular'], 'tx' ),
                'search_items'            => __( 'Rechercher '.$taxo['labels']['plural'], 'tx' ),
                'popular_items'            => __( $taxo['labels']['plural'], 'tx' ),
                'all_items'                => __( 'Toutes les '.$taxo['labels']['plural'], 'tx' ),
    /*            'parent_item'            => __( 'Parent Singular Name', 'tx' ),
                'parent_item_colon'        => __( 'Parent Singular Name', 'tx' ),*/
                'edit_item'                => __( 'Editer le/la '.$taxo['labels']['singular'], 'tx' ),
                'update_item'            => __( 'Mettre à jour le/la '.$taxo['labels']['singular'], 'tx' ),
                'add_new_item'            => __( 'Ajouter '.$taxo['labels']['singular'], 'tx' ),
                'new_item_name'            => __( 'Nouveau '.$taxo['labels']['singular'], 'tx' ),
    /*            'add_or_remove_items'    => __( 'Add or remove Plural Name', 'tx' ),
                'choose_from_most_used'    => __( 'Choose from most used tx', 'tx' ),*/
                'menu_name'                => __( "- ".$taxo['labels']['plural'], 'tx' ),
            );
            $args = array(
                'labels'            => $labels,
                'public'            => true,
                'show_in_nav_menus' => true,
                'show_admin_column' => true,
                'hierarchical'      => $taxo['hierarchical'],
                'show_tagcloud'     => true,
                'show_ui'           => true,
                'query_var'         => true,
                'rewrite'           => array('slug'=>$taxo['slug']),
                'query_var'         => true
            );
            register_taxonomy( 'tax-'.$id, $taxo['post_types'] ? $taxo['post_types'] : ['tx_ressource'], $args );
        }
    }

    private function get_tax_args(){
        return [
            'region'=>[
                "labels"=>[
                    "plural"=>"Cat Thingies", 
                    "singular"=>"Cat Thingy"
                ],
                "slug"=>"cat-thingy",
                'hierarchical'=>true,
                'post_types'=>['js_thingy']
            ]
        ];
    }
}

new jsRegister;
