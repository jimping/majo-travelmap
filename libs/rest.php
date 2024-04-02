<?php

function get_rest_query_for_post_type($post_type, $request)
{
    $with_locations = $request->get_param('with_locations');
    $with_content = $request->get_param('with_content');

    $args = [
        'post_status' => 'publish',
        'post_type' => $post_type,
        'posts_per_page' => -1, // Gibt alle Beiträge zurück
    ];

    // Überprüfen, ob with_locations=1 gesetzt ist und die Abfrage dementsprechend anpassen
    if (!empty($with_locations) && $with_locations == 1) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'location',
                'field'    => 'term_id',
                'terms'    => 'NOT EMPTY', // Holt Beiträge, die in der Taxonomie 'location' Begriffe haben
                'operator' => 'EXISTS' // Stellt sicher, dass die Beiträge Begriffe in dieser Taxonomie haben
            ),
        );
    }

    $posts = get_posts($args);

    $data = array();

    foreach ($posts as $post) {
        $post_meta = get_post_meta($post->ID);
        $locations = wp_get_post_terms($post->ID, 'location', array('fields' => 'all'));

        $allThumbnailSizes = get_intermediate_image_sizes();
        $thumbnailSizes = ['full' => get_the_post_thumbnail_url($post->ID, 'full')];

        foreach ($allThumbnailSizes as $size) {
            $thumbnailSizes[$size] = get_the_post_thumbnail_url($post->ID, $size);
        }

        $locations_data = array_map(function ($location) {
            $meta = get_term_meta($location->term_id);
            $meta_cleaned = [];
            foreach ($meta as $key => $value) {
                $meta_cleaned[$key] = maybe_unserialize($value[0]);
            }
            $location->meta = $meta_cleaned;
            return $location;
        }, $locations);

        $entry = array(
            'id' => $post->ID,
            'link' => get_permalink($post->ID),
            'teaser' => get_the_excerpt($post->ID),
            'title' => $post->post_title,
            'image' => $thumbnailSizes,
            'locations' => $locations_data,
        );

        if (!empty($with_content) && $with_content == 1) {
            $entry['content'] = apply_filters('the_content', $post->post_content);
            $entry['blocks'] = parse_blocks($post->post_content);
            $entry['categories'] = wp_get_post_categories($post->ID);
            $entry['tags'] = wp_get_post_tags($post->ID);
        }

        $data[] = $entry;

    }

    return new WP_REST_Response($data, 200);
}
