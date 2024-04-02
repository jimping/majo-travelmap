<?php


add_filter('rest_prepare_post', 'add_tags_meta_to_rest_posts', 10, 3);

function add_tags_meta_to_rest_posts($response, $post, $request)
{
    // Prüfen, ob die Anfrage die Tags mit Meta-Feldern einschließen soll.
    if (!empty($request['include_tags_meta'])) {
        $tags = wp_get_post_tags($post->ID);

        $tags_data = array_map(function ($tag) {
            // Hier wird für jedes Schlagwort ein Array von Meta-Daten abgerufen.
            $meta = get_term_meta($tag->term_id);
            // Optional: Bereinigen der Meta-Daten für die Ausgabe
            $meta_cleaned = [];
            foreach ($meta as $key => $value) {
                $meta_cleaned[$key] = maybe_unserialize($value[0]);
            }
            // Hinzufügen der Meta-Daten zum Schlagwort-Objekt
            $tag->meta = $meta_cleaned;
            return $tag;
        }, $tags);

        // Hinzufügen der Schlagworte mit Meta-Daten zur REST-Antwort
        $response->data['tags_with_meta'] = $tags_data;
    }

    return $response;
}



add_filter('rest_prepare_post', 'add_categories_meta_to_rest_posts', 10, 3);

function add_categories_meta_to_rest_posts($response, $post, $request)
{
    // Prüfen, ob die Anfrage die Kategorien mit Meta-Feldern einschließen soll.
    if (!empty($request['include_categories_meta'])) {
        $categories = wp_get_post_categories($post->ID, array('fields' => 'all'));

        $categories_data = array_map(function ($category) {
            // Hier wird für jede Kategorie ein Array von Meta-Daten abgerufen.
            $meta = get_term_meta($category->term_id);
            // Optional: Bereinigen der Meta-Daten für die Ausgabe
            $meta_cleaned = [];
            foreach ($meta as $key => $value) {
                $meta_cleaned[$key] = maybe_unserialize($value[0]);
            }
            // Hinzufügen der Meta-Daten zum Kategorie-Objekt
            $category->meta = $meta_cleaned;
            return $category;
        }, $categories);

        // Hinzufügen der Kategorien mit Meta-Daten zur REST-Antwort
        $response->data['categories_with_meta'] = $categories_data;
    }

    return $response;
}


add_filter('rest_prepare_post', 'add_locations_meta_to_rest_posts', 10, 3);

function add_locations_meta_to_rest_posts($response, $post, $request)
{
    // Prüfen, ob die Anfrage die Orte mit Meta-Feldern einschließen soll.
    // Dieser Schritt ist optional und dient nur dazu, die Performance zu optimieren,
    // falls nicht jede Anfrage diese Daten benötigt.
    if (!empty($request['include_locations_meta'])) {
        // Hole alle Terme der Taxonomie 'ort' für den aktuellen Post
        $locations = wp_get_post_terms($post->ID, 'location', array('fields' => 'all'));

        $locations_data = array_map(function ($location) {
            // Für jede Kategorie wird hier ein Array von Meta-Daten abgerufen.
            $meta = get_term_meta($location->term_id);
            // Optional: Bereinigen der Meta-Daten für die Ausgabe
            $meta_cleaned = [];
            foreach ($meta as $key => $value) {
                $meta_cleaned[$key] = maybe_unserialize($value[0]);
            }
            // Hinzufügen der Meta-Daten zum Orts-Objekt
            $location->meta = $meta_cleaned;
            return $location;
        }, $locations);

        // Hinzufügen der Orte mit Meta-Daten zur REST-Antwort
        $response->data['locations_with_meta'] = $locations_data;
    }

    return $response;
}
