<?php
// require_once __DIR__ . '/../model';
class Transaction extends Controller
{
    public function __construct()
    {
        // * session check
        session_start();
        if (!$_SESSION['logged_in']) {
            header("Location: " . BASEURL . "/Auth/login");
            exit();
        }
    }

    public function success()
    {
        $data = [
            'title' => 'Detail Ticket',
            'css' => 'transaction/success',
            'js' => 'index',
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/transaction/success', $data);
        $this->view('templates/footer', $data);
    }
}
