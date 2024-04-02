<?php

add_action('rest_api_init', function () {
    register_rest_route('travelmap/v1', '/trips/', array(
        'methods' => 'GET',
        'callback' => 'get_trips',
    ));
});

function get_trips($data)
{
    return get_rest_query_for_post_type('trip');
}
