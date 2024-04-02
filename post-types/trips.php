<?php

/**
 * Registers the `trips` post type.
 */
function trips_init()
{
    register_post_type(
        'trips',
        [
            'labels'                => [
                'name'                  => __('Trips', 'travelmap'),
                'singular_name'         => __('Trips', 'travelmap'),
                'all_items'             => __('All Trips', 'travelmap'),
                'archives'              => __('Trips Archives', 'travelmap'),
                'attributes'            => __('Trips Attributes', 'travelmap'),
                'insert_into_item'      => __('Insert into Trips', 'travelmap'),
                'uploaded_to_this_item' => __('Uploaded to this Trips', 'travelmap'),
                'featured_image'        => _x('Featured Image', 'trips', 'travelmap'),
                'set_featured_image'    => _x('Set featured image', 'trips', 'travelmap'),
                'remove_featured_image' => _x('Remove featured image', 'trips', 'travelmap'),
                'use_featured_image'    => _x('Use as featured image', 'trips', 'travelmap'),
                'filter_items_list'     => __('Filter Trips list', 'travelmap'),
                'items_list_navigation' => __('Trips list navigation', 'travelmap'),
                'items_list'            => __('Trips list', 'travelmap'),
                'new_item'              => __('New Trips', 'travelmap'),
                'add_new'               => __('Add New', 'travelmap'),
                'add_new_item'          => __('Add New Trips', 'travelmap'),
                'edit_item'             => __('Edit Trips', 'travelmap'),
                'view_item'             => __('View Trips', 'travelmap'),
                'view_items'            => __('View Trips', 'travelmap'),
                'search_items'          => __('Search Trips', 'travelmap'),
                'not_found'             => __('No Trips found', 'travelmap'),
                'not_found_in_trash'    => __('No Trips found in trash', 'travelmap'),
                'parent_item_colon'     => __('Parent Trips:', 'travelmap'),
                'menu_name'             => __('Trips', 'travelmap'),
            ],
            'public'                => true,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'supports'              => [
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'custom-fields'
            ],
            'has_archive'           => true,
            'rewrite'               => true,
            'query_var'             => true,
            'menu_position'         => null,
            'menu_icon'             => 'dashicons-book-alt',
            'show_in_rest'          => true,
            'rest_base'             => 'trips',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'taxonomies' 			=> ['post_tag', 'category', 'location']
        ]
    );

}

add_action('init', 'trips_init');

/**
 * Sets the post updated messages for the `trips` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `trips` post type.
 */
function trips_updated_messages($messages)
{
    global $post;

    $permalink = get_permalink($post);

    $messages['trips'] = [
        0  => '', // Unused. Messages start at index 1.
        /* translators: %s: post permalink */
        1  => sprintf(__('Trips updated. <a target="_blank" href="%s">View Trips</a>', 'travelmap'), esc_url($permalink)),
        2  => __('Custom field updated.', 'travelmap'),
        3  => __('Custom field deleted.', 'travelmap'),
        4  => __('Trips updated.', 'travelmap'),
        /* translators: %s: date and time of the revision */
        5  => isset($_GET['revision']) ? sprintf(__('Trips restored to revision from %s', 'travelmap'), wp_post_revision_title((int) $_GET['revision'], false)) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        /* translators: %s: post permalink */
        6  => sprintf(__('Trips published. <a href="%s">View Trips</a>', 'travelmap'), esc_url($permalink)),
        7  => __('Trips saved.', 'travelmap'),
        /* translators: %s: post permalink */
        8  => sprintf(__('Trips submitted. <a target="_blank" href="%s">Preview Trips</a>', 'travelmap'), esc_url(add_query_arg('preview', 'true', $permalink))),
        /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
        9  => sprintf(__('Trips scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Trips</a>', 'travelmap'), date_i18n(__('M j, Y @ G:i', 'travelmap'), strtotime($post->post_date)), esc_url($permalink)),
        /* translators: %s: post permalink */
        10 => sprintf(__('Trips draft updated. <a target="_blank" href="%s">Preview Trips</a>', 'travelmap'), esc_url(add_query_arg('preview', 'true', $permalink))),
    ];

    return $messages;
}

add_filter('post_updated_messages', 'trips_updated_messages');

/**
 * Sets the bulk post updated messages for the `trips` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `trips` post type.
 */
function trips_bulk_updated_messages($bulk_messages, $bulk_counts)
{
    global $post;

    $bulk_messages['trips'] = [
        /* translators: %s: Number of Trips. */
        'updated'   => _n('%s Trips updated.', '%s Trips updated.', $bulk_counts['updated'], 'travelmap'),
        'locked'    => (1 === $bulk_counts['locked']) ? __('1 Trips not updated, somebody is editing it.', 'travelmap') :
                        /* translators: %s: Number of Trips. */
                        _n('%s Trips not updated, somebody is editing it.', '%s Trips not updated, somebody is editing them.', $bulk_counts['locked'], 'travelmap'),
        /* translators: %s: Number of Trips. */
        'deleted'   => _n('%s Trips permanently deleted.', '%s Trips permanently deleted.', $bulk_counts['deleted'], 'travelmap'),
        /* translators: %s: Number of Trips. */
        'trashed'   => _n('%s Trips moved to the Trash.', '%s Trips moved to the Trash.', $bulk_counts['trashed'], 'travelmap'),
        /* translators: %s: Number of Trips. */
        'untrashed' => _n('%s Trips restored from the Trash.', '%s Trips restored from the Trash.', $bulk_counts['untrashed'], 'travelmap'),
    ];

    return $bulk_messages;
}

add_filter('bulk_post_updated_messages', 'trips_bulk_updated_messages', 10, 2);
