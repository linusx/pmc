<?php

namespace PMC\Widget;

class pmcWidget extends \WP_Widget {

    public function __construct() {

        parent::__construct(
            'pmc_widget',
            __('PMC Widget', 'pmc_widget_domain'),
            array( 'description' => __( 'Show up to 5 most recent posts, maximum of 30 days old', 'pmc_widget_domain' ), )
        );

        add_filter('posts_where', array($this, 'filterWhere') );
    }

    /**
     * Make sure we only grab the posts from the last 30 days.
     *
     * @param string $where
     * @return string
     */
    public function filterWhere($where = '') {
        $where .= " AND wp_posts.post_date > '" . date('Y-m-d', strtotime('-30 days')) . "'";
        return $where;
    }

    /**
     * Display the widget on the page
     *
     * @global type $post
     * @param array $args
     * @param object $instance
     */
    public function widget( $args, $instance ) {
        global $post;

        $current_post = $post;

        $title = apply_filters( 'widget_title', $instance['title'] );
        $per_page = ( is_front_page() && is_home() || is_front_page() ) ? 5 : 6;

        echo $args['before_widget'];

        if ( !empty($instance['show_title']) ) {
            if ( ! empty( $title ) ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
        }

        $pmc_args = array(
            'posts_per_page'   => $per_page,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'pmc',
            'post_status'      => 'publish'
        );

        $query = new \WP_Query( $pmc_args );

        if ( $query->have_posts() ) { ?>
            <ul class="pmc"><?php
            while ($query->have_posts()) {
                $query->the_post();
                if ($current_post->ID !== get_the_ID()) {
                    $thumb = get_the_post_thumbnail(get_the_ID(), 'thumbnail' ); ?>
                    <li>
                        <div class="pmc-wrapper">
                            <a href="<?php the_permalink(); ?>" class="pmc-title"><?php the_title(); ?></a>
                            <a href="<?php the_permalink(); ?>"><?php echo $thumb; ?></a>
                            <span class="pmc-author">Author: <?php echo ucwords(get_author_name()); ?></span>
                        </div>
                    </li><?php
                }
            }
            wp_reset_postdata();
            ?>
            </ul><?php
        }

        echo $args['after_widget'];
    }

    /**
     * Setup the admin form to grab the options from the user
     *
     * @param object $instance
     */
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

    /**
     * Save the information from the admin form
     *
     * @param object $new_instance
     * @param object $old_instance
     * @return object
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['show_title'] = ( ! empty( $new_instance['show_title'] ) ) ? strip_tags( $new_instance['show_title'] ) : 0;
        return $instance;
    }
}