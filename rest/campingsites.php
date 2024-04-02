<?php

add_action('rest_api_init', function () {
    register_rest_route('travelmap/v1', '/campingsites/', array(
        'methods' => 'GET',
        'callback' => 'get_campingsites',
    ));
});

function get_campingsites($data)
{
    $posts_data = [];
    $args = [
        'post_type' => 'campingsites',
        'posts_per_page' => -1, // Gibt alle Beiträge zurück
    ];
    $posts = get_posts($args);

    foreach ($posts as $post) {
        $post_meta = get_post_meta($post->ID);
        $post_thumbnail = get_the_post_thumbnail_url($post->ID, 'full');

        $posts_data[] = [
            'title' => $post->post_title,
            'teaser' => get_the_excerpt($post->ID),
            'content' => $post->post_content,
            'thumbnail' => $post_thumbnail,
            'blocks' => parse_blocks($post->post_content),
            'lat' => isset($post_meta['lat']) ? $post_meta['lat'][0] : '',
            'lng' => isset($post_meta['lng']) ? $post_meta['lng'][0] : '',
        ];


    }

    return new WP_REST_Response($posts_data, 200);
}
