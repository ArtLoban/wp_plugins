<?php
/*
 * Plugin Name: Admin Notes Widget
 * Description: Add custom notes at the admin dashboard
 * Author:      ArtLoban
 */

/** HOOKS **/
// Register new widget
add_action('wp_dashboard_setup', 'al_notes_dashboard_widget');
// Save note by AJAX
add_action('wp_ajax_al_notes', 'al_notes_ajax_save');
add_action('admin_enqueue_scripts', 'al_notes_widget_scripts');


/** FUNCTIONS **/

function al_notes_dashboard_widget() {
    // Register new widget only for admins of the site
    if (current_user_can( 'activate_plugins')) {
        wp_add_dashboard_widget('al_notes', 'The Admin Notes', 'al_notes_form');
    }
}

// Widget rendering
function al_notes_form() {
    ?>
        <form>
            <textarea rows="7"><?php echo esc_textarea( get_option('al_note_content') ); ?></textarea>
            <button type="reset" class="clear button button-secondary">Reset</button>
            <input
                name="nonce_security"
                id="jq-nonce-security"
                type="hidden"
                value="<?php echo wp_create_nonce("al_notes_widget_nonce"); ?>"
            >
            <?php submit_button('Save note', 'primary', null, false); ?>
        </form>
    <?php
}

// Save note by AJAX
function al_notes_ajax_save() {
    check_ajax_referer('al_notes_widget_nonce', 'nonce_security');

    if ( ! isset($_POST['al_note_content']) || !current_user_can('activate_plugins')) {
        return;
    }
    // Get and clean up the data
    $note_content = sanitize_textarea_field(wp_unslash($_POST['al_note_content']));

    // Update data
    $status = update_option('al_note_content', $note_content,  false);

    if ( $status ) {
        wp_send_json_success([
            'message' => 'Note is saved',
        ]);
    } else {
        wp_send_json_error([
            'message' =>  'The note hadn\'t been changed'
        ]);
    }

}

// Enqueue scripts
function  al_notes_widget_scripts() {
    global $screen;

    // If not main admin panel - abort script execution
    if ('dashboard' != $screen->base) {
        return;
    }

    wp_enqueue_style( 'al-notes-widget-style',
        plugins_url( 'assets/css/al-notes-widget.css', __FILE__),
        array(),
        time()
    );

    wp_enqueue_script(
        'al-notes-widget-script',
        plugins_url( 'assets/js/al-notes-widget.js', __FILE__),
        array('jquery'),
        time(),
        true
    );
}
