<?php
    add_action('wp_ajax_ajax_animals_create_single', 'ajax_animals_create_single_callback');
    add_action('wp_ajax_nopriv_ajax_animals_create_single', 'ajax_animals_create_single_callback');
    function ajax_animals_create_single_callback()
    {
        // set to false and go though all checks below
        $response['bSucces'] = false;
        // sMessage is 'failed' until all checks have cleared
        $response['sMessage'] = __('failed', 'layback');

        global $wpdb;
        $table_name = "animals";
        // find prefix in phpMyadmin db name
        $table_name = $wpdb->prefix .$table_name;

        // all fields to insert in db
        $species = $_POST['species'];
        $race = $_POST['race'];
        $color = $_POST['color'];
        $gender = $_POST['gender'];
        $birthdate = $_POST['birthdate'];
        $updated_at = $_POST['updated_at'];
        $created_at = $_POST['created_at'];
        $minDate = '1980-01-01';

        // SECURITY CHECKS
        if(!is_user_logged_in()) {
            $response['sMessage'] = __('OBS! <br> User not logged in', 'layback');
            echo json_encode($response);
            wp_die();
        }
        if(!current_user_can('administrator')) {
            $response['sMessage'] = __('OBS! <br> Only administrators can do this', 'layback');
            echo json_encode($response);
            wp_die();
        }
        // if not filled out
        if(!$species || !$race || !$birthdate) {
            $response['sMessage'] = __('OBS! <br> Please fill out all the required* fields', 'layback');
            echo json_encode($response);
            wp_die();
        }
        if($birthdate < $minDate){
            $response['sMessage'] = __('OBS! <br> Please choose a valid birthdate!', 'layback');
            echo json_encode($response);
            wp_die();
		}
        // if name in $species or $race includes numbers
        if(preg_match("/\d/", $species) || preg_match("/\d/", $race)) {
            $response['sMessage'] = __('OBS! <br> "Species" or "Race" can not include numbers', 'layback');
            echo json_encode($response);
            wp_die();
        }

        // set $_POST fields to columns in db
        $insert = $wpdb->insert(
            $table_name,
            array(
                'id' => null,
                'species' => $species,
                'race' => $race,
                'color' => $color,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'updated_at' => $updated_at,
                'created_at' => $created_at
            ),
            array(
                null,
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        // if insert fails
        if(!$insert) {
            $response['sMessage'] = __('Failed to create', 'layback');
            echo json_encode($response);
            wp_die();
        }

        // return everything inside ob as string
        ob_start(); ?>
            <table></table>
        <?php $sHTML = ob_get_clean();

        // all checks have cleared and bSuccess is true
        $response['bSucces'] = true;
        // sMessage is changed to success
        $response['sMessage'] = __('Animal created!', 'layback');
        $response['row_ID'] = $insert;
        $response['sHTML'] = $sHTML;
        echo json_encode($response);
        wp_die();
    }


    add_action('wp_ajax_ajax_animals_update_single', 'ajax_animals_update_single_callback');
    add_action('wp_ajax_nopriv_ajax_animals_update_single', 'ajax_animals_update_single_callback');
    function ajax_animals_update_single_callback()
    {

        global $wpdb;
        $table_name = "animals";
        // find prefix in phpMyadmin db name
        $table_name = $wpdb->prefix .$table_name;

        $response['bSucces'] = false;
        $response['sMessage'] = __('failed', 'layback');

        $_ID = $_POST['id'];
        $species = $_POST['species'];
        $race = $_POST['race'];
        $color = $_POST['color'];
        $gender = $_POST['gender'];
        $birthdate = $_POST['birthdate'];
        $updated_at = $_POST['updated_at'];
        $minDate = '1980-01-01';

        // SECURITY CHECKS
        if(!is_user_logged_in()) {
            $response['sMessage'] = __('OBS! <br> User not logged in', 'layback');
            echo json_encode($response);
            wp_die();
        }
        if(!current_user_can('administrator')) {
            $response['sMessage'] = __('OBS! <br> Only administrators can do this', 'layback');
            echo json_encode($response);
            wp_die();
        }
        // if not filled out
        if(!$species || !$race || !$birthdate) {
            $response['sMessage'] = __('OBS! <br> Please fill out all the required* fields', 'layback');
            echo json_encode($response);
            wp_die();
        }
        if($birthdate < $minDate){
            $response['sMessage'] = __('OBS! <br> Please choose a valid birthdate!', 'layback');
            echo json_encode($response);
            wp_die();
		}
        // if name in $species or $race includes numbers
        if(preg_match("/\d/", $species) || preg_match("/\d/", $race)) {
            $response['sMessage'] = __('OBS! <br> "Species" or "Race" can not include numbers', 'layback');
            echo json_encode($response);
            wp_die();
        }

        $update = $wpdb->update('wpwc_animals', array(
                'species' => $species,
                'race' => $race,
                'color' => $color,
                'gender' => $gender,
                'birthdate' => $birthdate,
                'updated_at' => $updated_at
            ),
            array(
                'id' => $_ID
            )
        );

        if(!$update) {
            echo json_encode($response);
            wp_die();
        }

        // return everything inside ob as string
        ob_start(); ?>
            <table></table>
        <?php $sHTML = ob_get_clean();

        // all checks have cleared and bSuccess is true
        $response['bSucces'] = true;
        // sMessage is changed to success
        $response['sMessage'] = __('Update complete!', 'layback');
        $response['row_ID'] = $update;
        $response['sHTML'] = $sHTML;
        echo json_encode($response);
        wp_die();
    }


    add_action('wp_ajax_ajax_animals_delete_single', 'ajax_animals_delete_single_callback');
    add_action('wp_ajax_nopriv_ajax_animals_delete_single', 'ajax_animals_delete_single_callback');
    function ajax_animals_delete_single_callback()
    {
        $response['bSucces'] = false;
        $response['sMessage'] = __('failed', 'layback');

        $_ID = $_POST['id'];

        if(!is_user_logged_in()) {
            echo json_encode($response);
            wp_die();
        }
        if(!current_user_can('administrator')) {
            echo json_encode($response);
            wp_die();
        }

        global $wpdb;

        $delete = $wpdb->delete(
            'wpwc_animals',
            array(
                'id' => $_ID
            ),
            array(
                '%d'
            )
        );

        if(!$delete) {
            $response['sMessage'] = __('Oops! Something went wrong. Try again later!', 'layback');
            echo json_encode($response);
            wp_die();
        }

        // return everything inside ob as string
        ob_start(); ?>
            <table></table>
        <?php $sHTML = ob_get_clean();

        // all checks have cleared and bSuccess is true
        $response['bSucces'] = true;
        // sMessage is changed to success
        $response['sMessage'] = __('Animal deleted!', 'layback');
        $response['row_ID'] = $delete;
        $response['sHTML'] = $sHTML;
        echo json_encode($response);
        wp_die();
    }

?>