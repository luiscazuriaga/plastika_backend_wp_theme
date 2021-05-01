<?php

function film_scheme($slug)
{
    $film_id = get_film_id_by_slug($slug);
    if ($film_id) {
        $film_meta = get_post_meta($film_id);

        $images = get_attached_media('image', $film_id);

        if ($images) {
            $images_array = array();
            foreach ($images as $key => $value) {
                $images_array[] = array(
                    'title' => $value->post_name,
                    'src' => $value->guid
                );
            }
        }

        $response = array(
            "id" => $slug,
            "images" => $images_array,
            "title" => $film_meta["title"][0],
            "description" => $film_meta["description"][0],
            "rate" => $film_meta["rate"][0],
            "categories" => $film_meta["categories"][0],
            "redirect_link" => $film_meta["redirect_link"][0],
            "user_film_id" => $film_meta["user_film_id"][0],
        );
    } else {
        $response = new WP_Error('naoexiste', 'Filme não encontrado', array("status" => 404));
    }
    return  $response;
}
