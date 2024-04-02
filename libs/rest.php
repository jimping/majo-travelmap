<?php

function get_rest_query_for_post_type($post_type)
{
    $args = [
        'post_type' => $post_type,
        'posts_per_page' => -1, // Gibt alle Beiträge zurück
    ];
    $posts = get_posts($args);
    $data = array();

    foreach ($posts as $post) {
        $post_meta = get_post_meta($post->ID);
        $image = get_the_post_thumbnail_url($post->ID, 'full');
        $locations = wp_get_post_terms($post->ID, 'location', array('fields' => 'all'));

        $locations_data = array_map(function ($location) {
            $meta = get_term_meta($location->term_id);
            $meta_cleaned = [];
            foreach ($meta as $key => $value) {
                $meta_cleaned[$key] = maybe_unserialize($value[0]);
            }
            $location->meta = $meta_cleaned;
            return $location;
        }, $locations);

        $blocks = parse_blocks($post->post_content);

        $data[] = array(
            'id' => $post->ID,
            'teaser' => get_the_excerpt($post->ID),
            'title' => $post->post_title,
            'content' => $post->post_content,
            'image' => $image,
            'blocks' => $blocks,
            'locations' => $locations_data,
            'categories' => wp_get_post_categories($post->ID),
            'tags' => wp_get_post_tags($post->ID),
        );
    }

    return new WP_REST_Response($data, 200);
}
