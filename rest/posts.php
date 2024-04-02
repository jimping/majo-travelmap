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
    $args = array(
        'post_type' => 'post', // oder 'trips', wenn es ein benutzerdefinierter Post-Typ ist
        'posts_per_page' => -1, // Holen aller Posts
    );
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
            'title' => $post->post_title,
            'description' => $post->post_content,
            'image' => $image,
            'blocks' => $blocks,
            'locations' => $locations_data,
        );
    }

    return new WP_REST_Response($data, 200);
}
