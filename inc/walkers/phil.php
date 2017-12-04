<?php
/**
 * Our custom walker class -- basically the whole purpose of this plugin.
 *
 * @package WordPress
 * @subpackage CSST_Nav
 * @since CSST Nav 1.0
 */

/**
 * @see https://core.trac.wordpress.org/browser/tags/4.2.2/src//wp-includes/nav-menu-template.php#L0
 */
class Phil_Nav_Walker extends Walker_Nav_Menu {

    // We'll grab a little triangle icon to use for our submenu toggles, just wait.
    public $icon = '';

    // Will hold the css namespace for our class.
    public $css_class = 'phi-MainNav';

    public function __construct() {

        // Set up the namespace for our class.
        //$this -> css_class = strtolower( __CLASS__ );


    }

    /**
     * Append the opening html for a nav menu item, and the menu item itself.
     *
     * @param string $output Passed by reference. The output for all of the preceding menu items.
     * @param object $item   The Post object for this menu item.
     * @param int    $depth  The number of levels deep we are in submenu-land.
     * @param array  $args   An array of arguments for wp_nav_menu().
     * @param int    $id     Allegedly the current item ID -- seems to always just be 0.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        // The CSS class for our menu.
        $class = $this -> css_class;

        //$item_class = 'a1n-MainMenu-item' . '-item';

        // The html that this method will append to the menu.
        $item_output = '';

        // Grab the class names for the menu item.
        $classes = $item -> classes;

        // Rename them as per my preferences.

        // Add our class for this method.
        $classes[]= $item_class;

        // Expose the classes to filtering.
        apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );

        // Convert the classes into a string for output.
        $classes_str  = implode( ' ', $classes );

        // Grab the opening html for the menu item, which we specified in wp_nav_menu() in our shortcode.
        $before = $args -> before;

        // Merge our css classes into the menu item.
        $before = sprintf( $before, $classes_str );

        // Add the opening html tag to the output for this item.
        $item_output .= $before.'<div class="' . $class . '__item '.$classes_str.'">';

        // Atts for the link itself.
        $atts = array();
        $atts['title']  = esc_attr( $item -> attr_title );
        $atts['target'] = esc_attr( $item -> target );
        $atts['rel']    = esc_attr( $item -> xfn );
        $atts['href']   = esc_url(  $item -> url );

        // Expose the atts to filtering.
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        // Combine the atts into a string for inserting into the link tag.
        $atts_str = '';
        foreach ( $atts as $k => $v ) {
            if ( empty( $v ) ) { continue; }
            $atts_str .= " $k='$v' ";
        }

        // The clickable text for the link.
        $label = apply_filters( 'the_title', $item -> title, $item -> ID );

        // Finally!  Add the link to the menu item.
        $item_output .= "<a $atts_str class='$item_class-link $item_class-text_link'>$label</a>";

        /**
         * Append this menu item to the menu.
         * Since output is passed by reference, we don't need to return anything.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

    }

    /**
     * Append the closing html for a menu item.
     *
     * @param string $output Passed by reference. @see start_el().
     * @param int    $depth  Depth of menu item. @see start_el().
     * @param array  $args   An array of arguments. @see start_el().
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {

        // Grab the closing html that we defined in the shortcode cb.
        $after = $args -> after;

        // Passed by reference, thus no need to return a value.
        $output .= '</div>'.$after;

    }

    /**
     * Provide the opening markup for a new menu within our menu (AKA a submenu).
     *
     * @param string $output Passed by reference. @see start_el().
     * @param int    $depth  Depth of menu item. @see start_el().
     * @param array  $args   An array of arguments. @see start_el().
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {

        // The CSS class for our menu.
        $class = $this -> css_class;

        // Merge our css classes into the menu item.
        $before_submenu = "<div class='". $class . "__sub'>";

        // Append the toggle link and the hidden submenu to the nav menu.
        $output .= "
            $before_submenu
        ";

    }

    /**
     * This oddly named fellow does nothing other than end a submenu.
     *
     * @param string $output Passed by reference. @see start_el().
     * @param int    $depth  Depth of menu item. @see start_el().
     * @param array  $args   An array of arguments. @see start_el().
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {

        // Grab the closing html that we defined in the shortcode cb.
        $after = "</div>";

        // Passed by reference, thus no need to return a value.
        $output .= $after;

    }

}


/**
 * A class with static methods for formatting strings.
 *
 * @package WordPress
 * @subpackage CSST_Nav
 * @since CSST Nav 1.0
 */

class CSST_Nav_Formatting  {

    /**
     * Take an array of classes, prepend a prefix to each,
     * apply some naming preferences to each, sanitize each,
     * and return the new array.
     *
     * @param  $prefix string A prefix to append to each class.
     * @param  $classes array An array of css classes (strings).
     * @return array    An array of css classes (strings), renamed.
     */
    public static function rename_css_classes( $prefix = '', $classes = array() ) {

        // This will hold the renamed classes.
        $out = array();

        // For each css class...
        foreach( $classes as $class ) {

            // If it's empty, bail.
            if( empty( $class ) ) { continue; }

            /**
             * Let's use underscores for everything in the suffix,
             * just to be consistent.
             */
            $class = str_replace( '-', '_', $class );

            // If there is a prefix, prepend it.
            if( ! empty( $prefix ) ) {
                $class = $prefix . "-$class";
            }

            // If the class is non-empty, append it.
            if( ! empty( $class ) ) {
                $out[] = $class;
            }

        }

        // Sanitize each class name.
        $out = array_map( 'sanitize_html_class', $out );

        // Lowercase each class name.
        $out = array_map( 'strtolower', $out );

        // No duplicates.
        array_unique( $out );

        // Mind if I sort them alphabetically?
        asort( $out );

        return $out;

    }

}