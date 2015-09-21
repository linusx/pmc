<?php

namespace PMC\Widget;

class pmcWidget extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'pmc_widget', 
            __('PMC Widget', 'pmc_widget_domain'), 
            array( 'description' => __( 'Show up to 5 most recent posts, maximum of 30 days old', 'pmc_widget_domain' ), )
        );        
    }
    
    public function widget( $args, $instance ) {
        global $post;
        
        $current_post = $post_id = $GLOBALS['post']->ID;
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];
        
        if ( !empty($instance['show_title']) ) {
            if ( ! empty( $title ) ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
        }

        $pmc_args = array(
            'posts_per_page'   => 5,
            'offset'           => 0,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'exclude'          => [$current_post],
            'post_type'        => 'pmc',
            'post_status'      => 'publish'
        );
        $posts = get_posts( $pmc_args );
        
        if (!empty($posts)) { ?>
            <ul class="pmc"><?php
            foreach( $posts as $post ) {
                setup_postdata( $post );
                $thumb = get_the_post_thumbnail(get_the_ID(), 'thumbnail' ); ?>
                <li>
                    <div class="pmc-wrapper">
                        <a href="<?php the_permalink(); ?>" class="pmc-title"><?php the_title(); ?></a>
                        <a href="<?php the_permalink(); ?>"><?php echo $thumb; ?></a>
                        <span class="pmc-author">Author: <?php echo ucwords(get_author_name()); ?></span>
                    </div>
                </li><?php
            } 
            wp_reset_postdata();
            ?>
            </ul><?php
        }
        
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'PMC', 'pmc_widget_domain' );
        $show_title = !empty( $instance[ 'show_title' ] ) ? 'checked="checked"' : '';
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'show_title' ); ?>" name="<?php echo $this->get_field_name( 'show_title' ); ?>" type="checkbox" value="1" <?php echo esc_attr( $show_title ); ?> />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['show_title'] = ( ! empty( $new_instance['show_title'] ) ) ? strip_tags( $new_instance['show_title'] ) : 0;
        return $instance;
    }
}