<?php
/*
Plugin Name: Related-Post
Description: This plugin will add a section titled "Related Posts" at the end of each post's content, displaying a list of related posts based on the categories of the current post. You can adjust the number of related posts displayed and the ordering logic as needed.
Version: 1.0
Author: Rajib
github: url (https://github.com/HassanRajib)
*/

class Related_Posts_Plugin {

    function __construct() {
        add_filter('the_content', array($this, 'display_related_posts'));
    }

    function display_related_posts($content) {
        global $post;

        // Get current post categories
        $categories = wp_get_post_categories($post->ID);

        // Query related posts based on current post categories
        $related_args = array(
            'category__in' => $categories,
            'post__not_in' => array($post->ID),
            'posts_per_page' => 5, // Change the number of related posts displayed
            'orderby' => 'rand', // You can change the ordering logic if needed
        );
        $related_query = new WP_Query($related_args);

        // Check if there are related posts
        if ($related_query->have_posts()) {
            $content .= '<div class="related-posts">';
            $content .= '<h2>Related Posts</h2>';
            $content .= '<ul>';
            while ($related_query->have_posts()) {
                $related_query->the_post();
                $content .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            $content .= '</ul>';
            $content .= '</div>';
            wp_reset_postdata(); // Reset the post data
        }

        return $content;
    }
}

// Instantiate the plugin class
$related_posts_plugin = new Related_Posts_Plugin();