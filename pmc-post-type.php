<?php

namespace PMC\PostType;

require_once( 'pmc-widget.php' );

/**
 * Plugin Name: PMC Custom Post Type Widget
 * Description: Register a new custom post type.<br/> A widget which show up to 5 most recent posts, maximum of 30 days old, for posts of that custom post type.<br/>Display the post title, post featured image thumbnail, and author name.<br/> The post title and image should link to the post. If placed in a post's sidebar, make sure the current post is not included in the list.
 * Author: Bill Van Pelt
 */

class pmcPostType {
    
    public function __construct() {
        add_action( 'init', array($this, 'registerPostType') );
        add_action( 'widgets_init', array($this, 'registerWidget') );
        add_action( 'wp_enqueue_scripts', array($this, 'pmcScriptsStyles') );
    }
    
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
    
    public function registerWidget() {
        register_widget( 'PMC\Widget\pmcWidget' );
    }
    
    public function pmcScriptsStyles() {
        wp_enqueue_style( 'style-name', plugins_url( 'css/pmc.css', __FILE__ ) );
    }
}

$pmcposttype = new pmcPostType();