<?php


add_action('rest_api_init', function () {
    register_rest_route('travelmap/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'get_travelmap_posts',
        'permission_callback' => '__return_true',
    ));
});

function get_travelmap_posts($request)
{
    return get_rest_query_for_post_type('post');
}
