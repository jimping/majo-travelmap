<?php

add_action('rest_api_init', function () {
    register_rest_route('travelmap/v1', '/campingsites/', array(
        'methods' => 'GET',
        'callback' => 'get_campingsites',
    ));
});

function get_campingsites($data)
{
    return get_rest_query_for_post_type('campingsite');

}
