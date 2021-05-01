<?php

function registrar_cpt_filmes()
{
    register_post_type('filme', array(
        'label' => 'Filme',
        'description' => 'Filme',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'filme', 'with_front' => true),
        'query_war' => true,
        'supports' => array('custom-fields', 'author', 'title'),
        'publicly_queryable' => true
    ));
}
add_action('init', 'registrar_cpt_filmes');
