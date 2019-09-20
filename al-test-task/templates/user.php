<?php
/**
 * The template for displaying user info
 */

get_header();

include_once plugin_dir_path( __DIR__  ) . 'includes/Encoder.php';
$encoder = new Encoder();

$user_id        = $_GET['id'];
$user_data      = get_userdata( $user_id );
$address        = get_the_author_meta( 'address', $user_id );
$address        = decryptData($address, $encoder);
$phone_number   = get_the_author_meta( 'phone_number', $user_id );
$phone_number   = decryptData($phone_number, $encoder);
$gender         = get_the_author_meta( 'gender', $user_id );
$gender         = decryptData($gender, $encoder);
$marital_status = get_the_author_meta( 'marital_status', $user_id );
$marital_status = decryptData($marital_status, $encoder);
?>

    <a href="<?php echo home_url(); ?>">« Back</a>

    <h2 class="user_header">User Information</h2>
    <table class="user_info_table">
        <tr>
            <th>Id</th>
            <td><?php echo $user_id; ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?php echo $user_data->display_name; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $user_data->user_email; ?></td>
        </tr>
        <tr>
            <th>Address</th>
            <td><?php echo $address; ?></td>
        </tr>
        <tr>
            <th>Phone number</th>
            <td><?php echo $phone_number; ?></td>
        </tr>
        <tr>
            <th>Gender</th>
            <td><?php echo $gender; ?></td>
        </tr>
        <tr>
            <th>Marital Status</th>
            <td><?php echo $marital_status; ?></td>
        </tr>
    </table>
<?php

get_footer();
