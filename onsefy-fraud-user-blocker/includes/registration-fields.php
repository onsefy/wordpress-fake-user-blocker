<?php
// Show extra fields in default registration form
function onsefy_extra_register_fields() {
    ?>
    <p>
        <label for="first_name"><?php _e('First Name', 'onsefy-fraud-blocker') ?><br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr($_POST['first_name'] ?? ''); ?>" size="25" /></label>
    </p>
    <p>
        <label for="last_name"><?php _e('Last Name', 'onsefy-fraud-blocker') ?><br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr($_POST['last_name'] ?? ''); ?>" size="25" /></label>
    </p>
    <?php
}
add_action('register_form', 'onsefy_extra_register_fields');

// Validate fields
function onsefy_validate_extra_fields($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST['first_name']) || trim($_POST['first_name']) === '') {
        $errors->add('first_name_error', __('<strong>ERROR</strong>: First name is required.', 'onsefy-fraud-blocker'));
    }
    if (empty($_POST['last_name']) || trim($_POST['last_name']) === '') {
        $errors->add('last_name_error', __('<strong>ERROR</strong>: Last name is required.', 'onsefy-fraud-blocker'));
    }
    return $errors;
}
add_filter('registration_errors', 'onsefy_validate_extra_fields', 10, 3);

// Save the values
function onsefy_save_extra_register_fields($user_id) {
    if (!empty($_POST['first_name'])) {
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (!empty($_POST['last_name'])) {
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
    }
    $full_name = sanitize_text_field($_POST['first_name']) . ' ' . sanitize_text_field($_POST['last_name']);
    wp_update_user([
        'ID' => $user_id,
        'display_name' => $full_name
    ]);
}
add_action('user_register', 'onsefy_save_extra_register_fields', 1); // Priority 1 to run before your API logic
