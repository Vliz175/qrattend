<?php
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../model/EventModel.php';

class Admin extends Controller
{
    private $user;
    private $event;

    public function __construct()
    {
        // * session check
        session_start();
        if (!$_SESSION['logged_in'] && $_SESSION['role'] != 'admin') {
            header("Location: " . BASEURL . "/Auth/login");
            exit();
        }

        $this->user = new userModel();
        $this->event = new EventModel();
    }

    //* index page
    public function index()
    {
        $data = [
            'title' => "admin",
            'css' => "admin/index",
            'js' => "index"
        ];
        $this->view('admin/templates/header', $data);
        $this->view('admin/templates/sidebar', $data);
        $this->view('admin/index', $data);
        $this->view('admin/templates/footer', $data);
    }

    //* user verification page
    public function userVerif()
    {
        $list = $this->user->getUserVerification();

        if (isset($_POST['approve'])) {
            $this->user->updateVerificationStatus($_POST['verificationId'], 'approved');
            header('location:' . BASEURL . '/Admin/userVerif');
            exit();
        }


        if (isset($_POST['reject'])) {
            $this->user->updateVerificationStatus($_POST['verificationId'], 'rejected');
            header('location:' . BASEURL . '/Admin/userVerif');
            exit();
        }

        $data = [
            'title' => "User Verification",
            'list' => $list,
            'css' => "admin/user_verif/userVerif",
            'js' => "index"
        ];
        $this->view('admin/templates/header', $data);
        $this->view('admin/templates/sidebar', $data);
        $this->view('admin/user_verif/userVerif', $data);
        $this->view('admin/templates/header', $data);
    }

    //* detil user verification page
    public function detailUserVerif()
    {
        $data = [
            'title' => "detail user verification",
            'css' => "universal",
            'js' => "index"
        ];
        $this->view('admin/templates/header', $data);
        $this->view('admin/templates/sidebar', $data);
        $this->view('admin/user_verif/detailUserVerif', $data);
        $this->view('admin/templates/header', $data);
    }

    //* event verification page
    public function eventVerif()
    {

        $list = $this->event->getEventByStatus();

        if (isset($_POST['approve'])) {
            $this->event->updateStatusEvent($_POST['event_id'], "approved");
            header('location:' . BASEURL . '/Admin/userVerif');
            exit();
        }

        if (isset($_POST['reject'])) {
            $this->event->updateStatusEvent($_POST['event_id'], "rejected");
            header('location:' . BASEURL . '/Admin/userVerif');
            exit();
        }

        $data = [
            'list' => $list,
            'title' => "event verification",
            'css' => "admin/event_verif/eventVerif",
            'js' => "index"
        ];
        $this->view('admin/templates/header', $data);
        $this->view('admin/templates/sidebar', $data);
        $this->view('admin/event_verif/eventVerif', $data);
        $this->view('admin/templates/header', $data);
    }

    //* detail event verification page
    public function detailEventVerif()
    {
        $data = [
            'title' => "detail event verification",
            'css' => "universal",
            'js' => "index"
        ];
        $this->view('admin/templates/header', $data);
        $this->view('admin/templates/sidebar', $data);
        $this->view('admin/event_verif/detailEventVerif', $data);
        $this->view('admin/templates/header', $data);
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: " . BASEURL . "/auth/login");
        exit();
    }
}
