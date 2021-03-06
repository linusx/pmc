<?php

namespace PMC\PostType;

require_once( 'pmc-widget.php' );
require_once( 'pmc-taxonomy-widget.php' );
require_once( 'pmc-comment-widget.php' );

/**
 * Plugin Name: PMC Widgets
 * Description: Register a new custom post type.<br/> A widget which show up to 5 most recent posts, maximum of 30 days old, for posts of that custom post type.<br/>Display the post title, post featured image thumbnail, and author name.<br/> The post title and image should link to the post. If placed in a post's sidebar, make sure the current post is not included in the list.
 * Author: Bill Van Pelt
 */

class pmcPostType {

    public static $cache_group = 'pmc';
    public static $cache_expire = 3600;

    public function __construct() {
        add_action( 'init', array($this, 'pmcInit') );
        add_action( 'widgets_init', array($this, 'registerWidgets') );
        add_action( 'wp_enqueue_scripts', array($this, 'pmcScriptsStyles') );
    }

    /**
     * Initialize PMC
     */
    public function pmcInit() {
        $this->registerPostType();
        $this->registerTaxonomy();
    }

    /**
     * Register PMC taxonomy
     */
    public function registerTaxonomy() {
        register_taxonomy(
            'pmcbrand',
            array('pmc','post'),
            array(
                'label' => __( 'PMC Brand' ),
                'rewrite' => array( 'slug' => 'pmcbrand' ),
                'hierarchical' => true
            )
        );
    }

    /**
     * Register PMC Post Type
     */
    public function registerPostType() {
        $labels = array(
            'name' => _x( 'PMC', 'pmc' ),
            'singular_name' => _x( 'PMC', 'pmc' ),
            'add_new' => _x( 'Add New', 'pmc' ),
            'add_new_item' => _x( 'Add New PMC', 'pmc' ),
            'edit_item' => _x( 'Edit PMC', 'pmc' ),
            'new_item' => _x( 'New PMC', 'pmc' ),
            'view_item' => _x( 'View PMC', 'pmc' ),
            'search_items' => _x( 'Search PMC\'s', 'pmc' ),
            'not_found' => _x( 'No pmc\'s found', 'pmc' ),
            'not_found_in_trash' => _x( 'No pmc\'s found in Trash', 'pmc' ),
            'parent_item_colon' => _x( 'Parent PMC:', 'pmc' ),
            'menu_name' => _x( 'PMC\'s', 'pmc' ),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => 'Allows the user to create pmc\'s',
            'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post'
        );

        register_post_type( 'pmc', $args );
    }

    /**
     * Register the PMC Post Type Widget
     */
    public function registerWidgets() {
        register_widget( 'PMC\Widget\pmcWidget' );
        register_widget( 'PMC\Widget\pmcTaxonomyWidget' );
        register_widget( 'PMC\Widget\pmcCommentWidget' );
    }

    /**
     * Add Custom Styles
     */
    public function pmcScriptsStyles() {
        wp_enqueue_style( 'style-name', plugins_url( 'css/pmc.css', __FILE__ ) );
    }
}

$pmcposttype = new pmcPostType();