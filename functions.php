<?php
header('Access-Control-Allow-Origin: *');
$template_directory = get_template_directory();

require_once($template_directory . "/custom_post_type/filmes.custom.php");

//users views
require_once($template_directory . "/views/user_post.view.php");
require_once($template_directory . "/views/user_get.view.php");
// films
require_once($template_directory . "/views/film_post.view.php");
require_once($template_directory . "/views/film_get.view.php");
require_once($template_directory . "/views/film_all_get.view.php");


function get_film_id_by_slug($slug)
{
    $query = new WP_Query(array(
        'name' => $slug,
        'post_type' => 'filme',
        'numberposts' => 1,
        'fields' => 'ids'
    ));

    $films = $query->get_posts();
    return array_shift($films);
}

function expire_token()
{
    return time() + (60 * 60 * 24);
}

add_action('jwt_auth_expire', 'expire_token');
