<?php

function api_user_post($request)
{
    $username = sanitize_text_field($request['username']);
    $email = sanitize_email($request['email']);
    $password = sanitize_text_field($request['password']);

    if (!$username || !$email || !$password) {
        $error = new WP_Error('values_required', 'Username, Email e Password são obrigatorios', array("status" => 403));
        return rest_ensure_response($error);
    }

    // validate data
    $username_exists = username_exists($username);
    $email_exists = email_exists($email);

    if ($email_exists) {
        $error = new WP_Error('email', 'Email já cadastrado', array("status" => 403));
        return rest_ensure_response($error);
    }

    if ($username_exists) {
        $error = new WP_Error('username', 'Username já cadastrado', array("status" => 403));
        return rest_ensure_response($error);
    }

    $user_id = wp_create_user($username, $password, $email);
    $response = array(
        "ID" => $user_id,
        "display_name" => $username,
        "first_name" => $username,
        "role" => "subscriber",
        "username" => $username,
        "email" => $email,
    );

    return rest_ensure_response($response);
}

function register_api_user_post()
{

    register_rest_route('api/v1', '/user', array(
        array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => 'api_user_post',
        )
    ));
}

add_action('rest_api_init', 'register_api_user_post');
