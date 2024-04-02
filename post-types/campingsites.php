<?php

/**
 * Registers the `campingsites` post type.
 */
function campingsites_init()
{
    register_post_type(
        'campingsites',
        [
            'labels'                => [
                'name'                  => __('Campingsites', 'travelmap'),
                'singular_name'         => __('Campingsites', 'travelmap'),
                'all_items'             => __('All Campingsites', 'travelmap'),
                'archives'              => __('Campingsites Archives', 'travelmap'),
                'attributes'            => __('Campingsites Attributes', 'travelmap'),
                'insert_into_item'      => __('Insert into Campingsites', 'travelmap'),
                'uploaded_to_this_item' => __('Uploaded to this Campingsites', 'travelmap'),
                'featured_image'        => _x('Featured Image', 'campingsites', 'travelmap'),
                'set_featured_image'    => _x('Set featured image', 'campingsites', 'travelmap'),
                'remove_featured_image' => _x('Remove featured image', 'campingsites', 'travelmap'),
                'use_featured_image'    => _x('Use as featured image', 'campingsites', 'travelmap'),
                'filter_items_list'     => __('Filter Campingsites list', 'travelmap'),
                'items_list_navigation' => __('Campingsites list navigation', 'travelmap'),
                'items_list'            => __('Campingsites list', 'travelmap'),
                'new_item'              => __('New Campingsites', 'travelmap'),
                'add_new'               => __('Add New', 'travelmap'),
                'add_new_item'          => __('Add New Campingsites', 'travelmap'),
                'edit_item'             => __('Edit Campingsites', 'travelmap'),
                'view_item'             => __('View Campingsites', 'travelmap'),
                'view_items'            => __('View Campingsites', 'travelmap'),
                'search_items'          => __('Search Campingsites', 'travelmap'),
                'not_found'             => __('No Campingsites found', 'travelmap'),
                'not_found_in_trash'    => __('No Campingsites found in trash', 'travelmap'),
                'parent_item_colon'     => __('Parent Campingsites:', 'travelmap'),
                'menu_name'             => __('Campingsites', 'travelmap'),
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
            'rest_base'             => 'campingsites',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'taxonomies' 			=> ['post_tag', 'category', 'location']
        ]
    );

}

add_action('init', 'campingsites_init');

/**
 * Sets the post updated messages for the `campingsites` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `campingsites` post type.
 */
function campingsites_updated_messages($messages)
{
    global $post;

    $permalink = get_permalink($post);

    $messages['campingsites'] = [
        0  => '', // Unused. Messages start at index 1.
        /* translators: %s: post permalink */
        1  => sprintf(__('Campingsites updated. <a target="_blank" href="%s">View Campingsites</a>', 'travelmap'), esc_url($permalink)),
        2  => __('Custom field updated.', 'travelmap'),
        3  => __('Custom field deleted.', 'travelmap'),
        4  => __('Campingsites updated.', 'travelmap'),
        /* translators: %s: date and time of the revision */
        5  => isset($_GET['revision']) ? sprintf(__('Campingsites restored to revision from %s', 'travelmap'), wp_post_revision_title((int) $_GET['revision'], false)) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        /* translators: %s: post permalink */
        6  => sprintf(__('Campingsites published. <a href="%s">View Campingsites</a>', 'travelmap'), esc_url($permalink)),
        7  => __('Campingsites saved.', 'travelmap'),
        /* translators: %s: post permalink */
        8  => sprintf(__('Campingsites submitted. <a target="_blank" href="%s">Preview Campingsites</a>', 'travelmap'), esc_url(add_query_arg('preview', 'true', $permalink))),
        /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
        9  => sprintf(__('Campingsites scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Campingsites</a>', 'travelmap'), date_i18n(__('M j, Y @ G:i', 'travelmap'), strtotime($post->post_date)), esc_url($permalink)),
        /* translators: %s: post permalink */
        10 => sprintf(__('Campingsites draft updated. <a target="_blank" href="%s">Preview Campingsites</a>', 'travelmap'), esc_url(add_query_arg('preview', 'true', $permalink))),
    ];

    return $messages;
}

add_filter('post_updated_messages', 'campingsites_updated_messages');

/**
 * Sets the bulk post updated messages for the `campingsites` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `campingsites` post type.
 */
function campingsites_bulk_updated_messages($bulk_messages, $bulk_counts)
{
    global $post;

    $bulk_messages['campingsites'] = [
        /* translators: %s: Number of Campingsites. */
        'updated'   => _n('%s Campingsites updated.', '%s Campingsites updated.', $bulk_counts['updated'], 'travelmap'),
        'locked'    => (1 === $bulk_counts['locked']) ? __('1 Campingsites not updated, somebody is editing it.', 'travelmap') :
                        /* translators: %s: Number of Campingsites. */
                        _n('%s Campingsites not updated, somebody is editing it.', '%s Campingsites not updated, somebody is editing them.', $bulk_counts['locked'], 'travelmap'),
        /* translators: %s: Number of Campingsites. */
        'deleted'   => _n('%s Campingsites permanently deleted.', '%s Campingsites permanently deleted.', $bulk_counts['deleted'], 'travelmap'),
        /* translators: %s: Number of Campingsites. */
        'trashed'   => _n('%s Campingsites moved to the Trash.', '%s Campingsites moved to the Trash.', $bulk_counts['trashed'], 'travelmap'),
        /* translators: %s: Number of Campingsites. */
        'untrashed' => _n('%s Campingsites restored from the Trash.', '%s Campingsites restored from the Trash.', $bulk_counts['untrashed'], 'travelmap'),
    ];

    return $bulk_messages;
}

add_filter('bulk_post_updated_messages', 'campingsites_bulk_updated_messages', 10, 2);
