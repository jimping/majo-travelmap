<?php


add_action('init', 'register_location_taxonomy');

function register_location_taxonomy()
{
    $labels = array(
        'name'              => _x('Orte', 'taxonomy general name', 'travelmap'),
        'singular_name'     => _x('Ort', 'taxonomy singular name', 'travelmap'),
        'search_items'      => __('Orte suchen', 'travelmap'),
        'all_items'         => __('Alle Orte', 'travelmap'),
        'parent_item'       => __('Übergeordneter Ort', 'travelmap'),
        'parent_item_colon' => __('Übergeordneter Ort:', 'travelmap'),
        'edit_item'         => __('Ort bearbeiten', 'travelmap'),
        'update_item'       => __('Ort aktualisieren', 'travelmap'),
        'add_new_item'      => __('Neuen Ort hinzufügen', 'travelmap'),
        'new_item_name'     => __('Neuer Ortsname', 'travelmap'),
        'menu_name'         => __('Orte', 'travelmap'),
    );

    $args = array(
        'hierarchical'      => true, // Ähnlich wie Kategorien
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'location'),
        'show_in_rest'		=> true, // Wichtig für Gutenberg-Editor
    );

    register_taxonomy('location', array('trip', 'campingsite', 'post'), $args);
}



// Feld zum Hinzufügen-Formular hinzufügen
add_action('location_add_form_fields', 'mein_custom_feld_zum_hinzufuegen_form', 10, 2);
function mein_custom_feld_zum_hinzufuegen_form($taxonomy)
{
    // add leaflet
    wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet/dist/leaflet.js');
    wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet/dist/leaflet.css');

    $plugin_dir = plugin_dir_url(__DIR__);

    // add custom css
    wp_enqueue_style('custom_css', $plugin_dir . 'assets/admin.css');
    wp_enqueue_script('custom_js', $plugin_dir . 'assets/admin.js');
    ?>
    <div class="form-field term-group">
        <label for="map"><?php _e('Map', 'travelmap'); ?></label>
		<button type="button" class="button button-secondary" style="margin-bottom: 1rem" id="locate">Aktueller Standort</button>
		<div id="map" style="height: 400px; width: 100%"></div>
		<script>
			window.onload = function () {
				window._travelmap.init()
			}
		</script>
    </div>
    <div class="form-field term-group">
        <label for="lat"><?php _e('Latitude', 'travelmap'); ?></label>
        <input type="text" id="lat" name="lat">
    </div>
    <div class="form-field term-group">
        <label for="lng"><?php _e('Longitude', 'travelmap'); ?></label>
        <input type="text" id="lng" name="lng">
    </div>


    <?php
}

// Feld zum Bearbeiten-Formular hinzufügen
add_action('location_edit_form_fields', 'mein_custom_feld_zum_bearbeiten_form', 10, 2);
function mein_custom_feld_zum_bearbeiten_form($term, $taxonomy)
{
    // add leaflet
    wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet/dist/leaflet.js');
    wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet/dist/leaflet.css');

    $plugin_dir = plugin_dir_url(__DIR__);

    // add custom css
    wp_enqueue_style('custom_css', $plugin_dir . 'assets/admin.css');
    wp_enqueue_script('custom_js', $plugin_dir . 'assets/admin.js');

    // Wert aus der Datenbank abrufen
    $lat = get_term_meta($term->term_id, 'lat', true);
    $lng = get_term_meta($term->term_id, 'lng', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="map"><?php _e('Map', 'travelmap'); ?></label></th>
        <td>
			<button type="button" class="button button-secondary" style="margin-bottom: 1rem" id="locate">Aktueller Standort</button>

			<div id="map" style="height: 400px; width: 100%"></div>

			<script>
				window.onload = function () {
					window._travelmap.init()
					<?php if ($lat && $lng) : ?>
						window._travelmap.marker = L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>]).addTo(window._travelmap.map);
						window._travelmap.map.setView([<?php echo $lat; ?>, <?php echo $lng; ?>], 10);
					<?php endif; ?>
				}
			</script>
		</td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="lat"><?php _e('Latitude', 'travelmap'); ?></label></th>
        <td><input type="text" id="lat" name="lat" value="<?php echo esc_attr($lat); ?>"></td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="lng"><?php _e('Longitude', 'travelmap'); ?></label></th>
        <td><input type="text" id="lng" name="lng" value="<?php echo esc_attr($lng); ?>"></td>
    </tr>

    <?php
}



// Beim Erstellen einer neuen Kategorie
add_action('created_location', 'save_lat_lng_fields', 10, 2);
// Beim Bearbeiten einer existierenden Kategorie
add_action('edited_location', 'save_lat_lng_fields', 10, 2);

function save_lat_lng_fields($term_id, $tt_id)
{
    // on update
    if (isset($_POST['name']) && '' !== $_POST['name']) {
        $ort_name = sanitize_text_field($_POST['name']);
        $koordinaten = get_lat_lng_from_term($ort_name);

        if (!is_null($koordinaten)) {
            update_term_meta($term_id, 'lat', $koordinaten->geometry->location->lat);
            update_term_meta($term_id, 'lng', $koordinaten->geometry->location->lng);
            update_term_meta($term_id, 'place_id', $koordinaten->place_id);

            $place_details = get_place_details($koordinaten->place_id);

            if (!is_null($place_details)) {
                update_term_meta($term_id, 'place_details', $place_details);
            }
        }
    } elseif (isset($_POST['tag-name']) && '' !== $_POST['tag-name']) {
        // on create

        $ort_name = sanitize_text_field($_POST['tag-name']);
        $koordinaten = get_lat_lng_from_term($ort_name);

        if (!is_null($koordinaten)) {
            update_term_meta($term_id, 'lat', $koordinaten->geometry->location->lat);
            update_term_meta($term_id, 'lng', $koordinaten->geometry->location->lng);
            update_term_meta($term_id, 'place_id', $koordinaten->place_id);

            $place_details = get_place_details($koordinaten->place_id);

            if (!is_null($place_details)) {
                update_term_meta($term_id, 'place_details', $place_details);
            }
        }
    } else {
        // on create in Gutenberg
        $term = get_term($term_id);
        $ort_name = $term->name;


        $koordinaten = get_lat_lng_from_term($ort_name);

        if (!is_null($koordinaten)) {
            update_term_meta($term_id, 'lat', $koordinaten->geometry->location->lat);
            update_term_meta($term_id, 'lng', $koordinaten->geometry->location->lng);
            update_term_meta($term_id, 'place_id', $koordinaten->place_id);

            $place_details = get_place_details($koordinaten->place_id);

            if (!is_null($place_details)) {
                update_term_meta($term_id, 'place_details', $place_details);
            }
        }

    }


    if (isset($_POST['lat']) && '' !== $_POST['lat']) {
        $wert = sanitize_text_field($_POST['lat']);
        update_term_meta($term_id, 'lat', $wert);
    }

    if (isset($_POST['lng']) && '' !== $_POST['lng']) {
        $wert = sanitize_text_field($_POST['lng']);
        update_term_meta($term_id, 'lng', $wert);
    }

}


add_action('delete_location', 'delete_lat_lng_fields', 10, 2);
function delete_lat_lng_fields($term_id, $tt_id)
{
    delete_term_meta($term_id, 'lat');
    delete_term_meta($term_id, 'lng');
}

function get_lat_lng_from_term($ort_name)
{
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($ort_name) . '&key=' . get_option('travelmap_google_api_key');

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return null;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if ($data->status != 'OK') {
        return null;
    }

    $koordinaten = $data->results[0];

    return $koordinaten; // Gibt ein Objekt mit lat und lng zurück
}

function get_place_details($place_id)
{
    $url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . $place_id . '&key=' . get_option('travelmap_google_api_key');


    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return null;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if ($data->status != 'OK') {
        return null;
    }

    $details = $data->result;

    return $details; // Gibt ein Objekt mit lat und lng zurück
}
