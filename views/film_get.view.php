<?php
require_once($template_directory . "/schemes/film.scheme.php");

function api_film_get($request)
{
    $user = wp_get_current_user();
    $user_id = $user->ID;

    if ($user_id > 0) {
        $response = film_scheme($request["slug"]);
    } else {
        $response = new WP_Error('permissao', 'Usuário não possui permissão', array("status" => 401));
    }
    return rest_ensure_response($response);
}

function register_api_film_get()
{

    register_rest_route(
        'api/v1',
        '/films/(?P<slug>[-\w]+)',
        array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => 'api_film_get',
            )
        )
    );
}

add_action('rest_api_init', 'register_api_film_get');
