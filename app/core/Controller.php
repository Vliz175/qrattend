<?php

class Controller
{
    public function view(string $view, $data = [])
    {
        require_once "../app/views/$view.php";
    }
}
