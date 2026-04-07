<?php
require_once __DIR__ . '/../model/EventModel.php';
class Home extends Controller
{
    private $event;

    public function __construct()
    {
        //* session check
        session_start();
        if (!$_SESSION['logged_in']) {
            header("Location: " . BASEURL . "/Auth/login");
            exit();
        }

        $this->event = new EventModel();
    }

    public function index()
    {


        if (isset($_GET['search-btn']) && !empty($_GET['search'])) {
            $keyword = trim($_GET['search']);
            $events = $this->event->getEventByName($keyword);
        } else {
            $events = $this->event->getAllEvents();
        }

        $data = [
            'events' => $events,
            'search' => $_GET['search'] ?? '',
            'title' => 'Home Page',
            'css' => 'home',
            'js' => 'test'
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/home/index', $data);
        $this->view('templates/footer', $data);
    }
}
