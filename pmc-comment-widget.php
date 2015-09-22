<?php

namespace PMC\Widget;

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class pmcCommentWidget extends \WP_Widget {

    public function __construct() {

        parent::__construct(
            'pmc_comment_widget',
            __('PMC Comment Widget', 'pmc_widget_domain'),
            array( 'description' => __( 'Shows up to 1 post with the highest comment count by the selected author AND up to 1 most recent comment by the same author AND the author\'s gravatar', 'pmc_widget_domain' ), )
        );

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
                
        $title = apply_filters( 'widget_title', $instance['title'] );
        $author = $instance['author'];

        if (!empty($author)) {
            echo $args['before_widget']; ?>

            <div class="pmc-header">
                Most Commented From <?php echo ucwords(get_author_name($author)); ?>
            </div><?php
            
            /**
             * Post by auther
             */
            $pmc_args = array(
                'posts_per_page' => 1,
                'orderby' => 'comment_count',
                'order' => 'DESC',
                'post_status' => 'publish',
                'author' => $author,
                'post__not_in' => array($post->ID)
            );            
            
            $query = new \WP_Query( $pmc_args );

            if ( $query->have_posts() ) { ?>
                <ul class="pmc"><?php
                while ($query->have_posts()) {
                    $query->the_post(); ?>
                    <li>
                        <div class="pmc-wrapper">
                            <a href="<?php the_permalink(); ?>" class="pmc-title"><?php the_title(); ?></a>
                            <div class="img-wrapper"><a href="<?php the_permalink(); ?>"><?php echo has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID(), 'thumbnail' ) : '<img src="' . plugins_url( 'images/placeholder.png', __FILE__ ) . '" width="150" height="150" alt="Default" />'; ?></a></div><?php
                            $authors = array(ucwords(get_author_name()));
                            if (is_plugin_active('co-authors-plus/co-authors-plus.php')) {
                                $coauthors = get_coauthors(get_the_ID());
                                if (!empty($coauthors)) {
                                    $authors = array();
                                    foreach($coauthors as $coauthor) {
                                        if (!empty($coauthor->display_name)) {
                                            $authors[] = ucwords($coauthor->display_name);
                                        }
                                    }
                                }
                            } ?>
                            <span class="pmc-author">Author: <?php echo implode(', ', $authors); ?></span>
                        </div>
                    </li><?php
                }
                wp_reset_postdata();
                ?>
                </ul><?php
            }


            /**
             * Latest comment by auther
             */
            $comment_args = array(
                'orderby' => 'comment_date',
                'order' => 'DESC',
                'post_status' => 'publish',
                'user_id' => $author,
                'status' => 'all',
                'number' => '1'
            );
            $comments = get_comments($comment_args);

            if ( !empty($comments) ) { ?>
                <div class="pmc-header">
                    Latest Comment From <?php echo ucwords(get_author_name($author)); ?>
                </div>
                <ul class="pmc"><?php
                foreach($comments as $comment) {
                    $comment_length = strlen($comment->comment_content); ?>
                    <li>
                        <div class="pmc-wrapper">
                            <div class="pmc-author-wraper">
                                <a href="<?php echo get_author_posts_url($author); ?>"><?php echo get_avatar( $author, '45'); ?></a>
                            </div>
                            <div class="pmc-comment-wrapper">
                                <a href="<?php echo get_permalink($comment->comment_post_ID); ?>#div-comment-<?php echo $comment->comment_ID; ?>"><?php
                                echo substr( strip_tags( $comment->comment_content ), 0, 200 );
                                if ($comment_length > 200) {
                                    echo '...';
                                } ?>
                                </a>
                            </div>
                        </div>
                    </li><?php
                }
                
                wp_reset_postdata(); ?>
                </ul><?php
            }

            echo $args['after_widget'];

        }
    }

    /**
     * Setup the admin form to grab the options from the user
     *
     * @param object $instance
     */
    public function form( $instance ) {
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'PMC', 'pmc_widget_domain' );
        $show_title = !empty( $instance[ 'show_title' ] ) ? 'checked="checked"' : '';
        $author = !empty( $instance[ 'author' ] ) ? $instance['author'] : false;
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e( 'Author:' ); ?></label>
        <?php wp_dropdown_users(array('name' => $this->get_field_name( 'author' ), 'selected' => $author, 'id' => $this->get_field_id( 'author' ), 'class' => 'widefat', 'show_option_none' => 'Select Author')); ?>
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
        $instance['author'] = !empty( $new_instance['author'] ) ? $new_instance['author'] : '';
        return $instance;
    }
}