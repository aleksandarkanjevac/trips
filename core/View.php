<?php

namespace core;

trait View
{
    /**
     * composing page based on view file & data
     */
    public static function render_page($view, array $data = [])
    {
        if (!file_exists("../view/{$view}.php")) {
            $view = 'error';
            $data = ['code'=>404,'message'=>'Page not found'];
        }
        extract($data);
        include '../view/blocks/header.php';
        include '../view/blocks/navbar.php';
        include "../view/{$view}.php";
        include '../view/blocks/footer.php';
    }
}
