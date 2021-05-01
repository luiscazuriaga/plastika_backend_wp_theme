<?php
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

function api_film_post($request)
{
    $user = wp_get_current_user();
    $user_id = $user->ID;

    if ($user_id > 0) {
        $title = sanitize_text_field($request["title"]);
        $description = sanitize_text_field($request["description"]);
        $rate = sanitize_text_field($request["rate"]);
        $categories = sanitize_text_field($request["categories"]);
        $redirect_link = sanitize_text_field($request["redirect_link"]);
        $user_film_id = $user->user_login;


        $response = array(
            'post_author' => $user_id,
            'post_type' => 'filme',
            'post_title' => $title,
            'post_status' => 'publish',
            'meta_input' => array(
                "title" => $title,
                "description" => $description,
                "rate" => $rate,
                "categories" => $categories,
                "redirect_link" => $redirect_link,
                "user_film_id" => $user_film_id
            )
        );

        $film_id = wp_insert_post($response);
        $response['id'] = get_post_field('post_name', $film_id);

        $files = $request->get_file_params();

        if ($files) {

            foreach ($files as $file => $array) {
                media_handle_upload($file, $film_id);
            }
        }
    } else {
        $response = new WP_Error('permissao', 'Usuário não possui permissão', array("status" => 401));
    }
    return rest_ensure_response($response);
}

function register_api_film_post()
{

    register_rest_route('api/v1', '/films', array(
        array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => 'api_film_post',
        )
    ));
}

add_action('rest_api_init', 'register_api_film_post');
