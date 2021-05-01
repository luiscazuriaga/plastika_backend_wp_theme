<?php
require_once($template_directory . "/schemes/film.scheme.php");

function api_film_all_get($request)
{
    $user = wp_get_current_user();
    $user_id = $user->ID;

    if ($user_id > 0) {

        $q = sanitize_text_field($request["q"]) ?: "";
        $_page = sanitize_text_field($request["_page"]) ?: "";
        $_limit = sanitize_text_field($request["_limit"]) ?: 8;

        $query = array(
            'post_type' => "filme",
            'posts_per_page' => $_limit,
            'paged' => $_page,
            's' => $q
        );

        $loop = new WP_Query($query);
        $films = $loop->posts;

        $films_array = array();
        foreach ($films as $key => $value) {
            $films_array[] = film_scheme($value->post_name);
        }

        $response = film_scheme($request["slug"]);
    } else {
        $response = new WP_Error('permissao', 'Usuário não possui permissão', array("status" => 401));
    }
    return rest_ensure_response($films_array);
}

function register_api_film_all_get()
{

    register_rest_route(
        'api/v1',
        '/films',
        array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => 'api_film_all_get',
            )
        )
    );
}

add_action('rest_api_init', 'register_api_film_all_get');
