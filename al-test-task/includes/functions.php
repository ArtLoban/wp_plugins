<?php

/*** Hooks ***/
add_action('wp_enqueue_scripts', 'al_add_scripts');

// Additional input fields to form `Users -> Add New` in admin panel
add_action( 'user_new_form', 'al_admin_add_new_user_fields' );
// Save additional input fields when create a new user (admin panel)
add_action( 'edit_user_created_user', 'al_admin_save_new_user_fields' );
// Render additional input fields at User Profile in admin panel
add_action( 'show_user_profile', 'al_show_user_additional_fields' );
add_action( 'edit_user_profile', 'al_show_user_additional_fields' );
// Update additional input fields in User Profile in admin panel
add_action( 'personal_options_update', 'al_update_user_additional_profile_fields' );
add_action( 'edit_user_profile_update', 'al_update_user_additional_profile_fields' );

// Assign a template for user information output
add_filter('template_include', 'al_user_template');

// Register a new short-code for the users output
add_shortcode( 'users_list', 'al_render_users_list' );


/*** FUNCTIONS ***/

function al_add_scripts() {
    wp_enqueue_style( 'al_main_style', plugins_url( 'assets/css/al_main.css', __DIR__), array(), time() );
}

// Additional input fields to form `Users -> Add New` in admin panel
function al_admin_add_new_user_fields() {
    ?>
        <h3>Additional Information</h3>
        <table class="form-table">
            <tr class="form-field">
                <th>
                    <label for="user_address">Address</label>
                </th>
                <td>
                    <input name="address" type="text" id="user_address" value="">
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <label for="user_phone_number">Phone number</label>
                </th>
                <td>
                    <input name="phone_number" type="text" id="user_phone_number" value="">
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <label for="user_gender">Gender</label>
                </th>
                <td>
                    <select name="gender" id="user_gender">
                        <option selected="selected" disabled>- select -</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <label for="user_marital_status">Marital Status</label>
                </th>
                <td>
                    <select name="marital_status" id="user_marital_status">
                        <option selected="selected" disabled>- select -</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="widowed">Widowed</option>
                        <option value="divorced">Divorced</option>
                        <option value="separated ">Separated</option>
                        <option value="registered_partnership ">Registered partnership</option>
                    </select>
                </td>
            </tr>
        </table>
    <?php
}

// Save additional input fields when create a new user (admin panel)
function al_admin_save_new_user_fields( $user_id ) {
    $encoder = getEncoder();

    // User Address
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    if ( ! empty( $address ) ) {
        $address = encryptData( $address, $encoder);
        update_user_meta( $user_id, 'address', $address );
    }
    // Phone number
    if ( ! empty( $_POST['phone_number']) ) {
        $phone_number = encryptData( $_POST['phone_number'], $encoder);
        update_user_meta( $user_id, 'phone_number', $phone_number );
    }
    // Gender
    if ( ! empty( $_POST['gender']) ) {
        $gender = encryptData( $_POST['gender'], $encoder);
        update_user_meta( $user_id, 'gender', $gender );
    }
    // Marital status
    if ( ! empty( $_POST['marital_status']) ) {
        $marital_status = encryptData( $_POST['marital_status'], $encoder);
        update_user_meta( $user_id, 'marital_status', $marital_status );
    }
}

// Render additional input fields at User Profile in admin panel
function al_show_user_additional_fields( $user ) {
    $encoder = getEncoder();

    $address        = get_the_author_meta( 'address', $user->ID );
    $address        = decryptData($address, $encoder);
    $phone_number   = get_the_author_meta( 'phone_number', $user->ID );
    $phone_number   = decryptData($phone_number, $encoder);
    $gender         = get_the_author_meta( 'gender', $user->ID );
    $gender         = decryptData($gender, $encoder);
    $marital_status = get_the_author_meta( 'marital_status', $user->ID );
    $marital_status   = decryptData($marital_status, $encoder);
    ?>
        <h3>Additional Information</h3>
        <table class="form-table">
            <tr>
                <th>
                    <label for="user_address">Address</label>
                </th>
                <td>
                    <input name="address" type="text" id="user_address" value="<?php echo esc_attr( $address ); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="user_phone_number">Phone number</label>
                </th>
                <td>
                    <input name="phone_number" type="text" id="user_phone_number" value="<?php echo esc_attr( $phone_number ); ?>" class="regular-text">
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <label for="user_gender">Gender</label>
                </th>
                <td>
                    <select name="gender" id="user_gender">
                        <option selected="selected" disabled>- select -</option>
                        <option value="male" <?php echo ($gender == 'male') ? 'selected="selected"' : ''; ?> >Male</option>
                        <option value="female" <?php echo ($gender == 'female') ? 'selected="selected"' : ''; ?>>Female</option>
                    </select>
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <label for="user_marital_status">Marital Status</label>
                </th>
                <td>
                    <select name="marital_status" id="user_marital_status">
                        <option selected="selected" disabled>- select -</option>
                        <option value="single" <?php echo ($marital_status == 'single') ? 'selected="selected"' : ''; ?>>Single</option>
                        <option value="married" <?php echo ($marital_status == 'married') ? 'selected="selected"' : ''; ?>>Married</option>
                        <option value="widowed" <?php echo ($marital_status == 'widowed') ? 'selected="selected"' : ''; ?>>Widowed</option>
                        <option value="divorced" <?php echo ($marital_status == 'divorced') ? 'selected="selected"' : ''; ?>>Divorced</option>
                        <option value="separated" <?php echo ($marital_status == 'separated') ? 'selected="selected"' : ''; ?>>Separated</option>
                        <option value="registered_partnership" <?php echo ($marital_status == 'registered_partnership') ? 'selected="selected"' : ''; ?>>Registered partnership</option>
                    </select>
                </td>
            </tr>
        </table>
    <?php
}

// Update additional input fields in User Profile in admin panel
function al_update_user_additional_profile_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    $encoder = getEncoder();

    // User Address
    if ( ! empty( $_POST['address'] ) ) {
        $address = encryptData( $_POST['address'], $encoder);
        update_user_meta( $user_id, 'address', $address );
    }
    // Phone number
    if ( ! empty( $_POST['phone_number']) ) {
        $phone_number = encryptData( $_POST['phone_number'], $encoder);
        update_user_meta( $user_id, 'phone_number', $phone_number );
    }
    // Gender
    if ( ! empty( $_POST['gender']) ) {
        $gender = encryptData( $_POST['gender'], $encoder);
        update_user_meta( $user_id, 'gender', $gender );
    }
    // Marital status
    if ( ! empty( $_POST['marital_status']) ) {
        $marital_status = encryptData( $_POST['marital_status'], $encoder);
        update_user_meta( $user_id, 'marital_status', $marital_status );
    }
}

// Render the list of all Users
function al_render_users_list( $atts ) {
    $number = 5; // Items per page
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    $offset = ($paged - 1) * $number;
    $users = get_users(); // All users
    $total_users = count($users); // Total users count
    $users_per_page = get_users([
        'fields' => ['ID', 'user_email', 'display_name'],
        'offset' => $offset,
        'number' => $number
    ]);
    $total_pages = ceil($total_users / $number);

    $index = $offset + 1;
    $html = '<ul class="users_list">';
    foreach($users_per_page as $user){
        $url = add_query_arg( 'id', $user->ID, get_site_url(). '/users' );
        $url = esc_url($url);
        $html .= '<li><span>' . $index . '.</span><a href="' .$url. '">' . $user->display_name . ' (' . $user->user_email . ')'.'</a></li>';
        $index++;
    }
    $html .= '</ul>';

    // Pagination
    if ($total_users > count($users_per_page)) {
        $current_page = max(1, get_query_var('page'));
        echo paginate_links([
            'base'      => get_pagenum_link(1) . '%_%',
            'format'    => 'page/%#%/',
            'current'   => $current_page,
            'total'     => $total_pages,
//            'prev_next' => false,
            'type'      => 'plain',
        ]);
    }

    return $html;
}

// Assign a template for user information output
function al_user_template( $template ) {
    if( is_page('users') ){
        return plugin_dir_path( __DIR__  ) . 'templates/user.php';
    }

    return $template;
}

// Get Encoder class instance
function getEncoder() {
    include_once 'Encoder.php';

    return new Encoder();
}

// Encryption algorithm
function encryptData( $data, EncoderInterface $encoder ) {
    $data = $encoder->encodeData($data);

    return base64_encode($data);
}

// Decryption algorithm
function decryptData( $data, EncoderInterface $encoder ) {
    $data =  base64_decode($data);

    return $encoder->decodeData($data);
}
