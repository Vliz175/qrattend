<?php
require_once __DIR__ . '/../model/EventModel.php';
require_once __DIR__ . '/../model/TicketModel.php';
require_once __DIR__ . '/../model/CommitteeModel.php';
require_once __DIR__ . '/../model/TransactionModel.php';
require_once __DIR__ . '/../model/PaymentModel.php';
require_once __DIR__ . '/../model/UserModel.php';

class Event extends Controller
{
    private $event;
    private $ticket;
    private $committee;
    private $transaction;
    private $payment;
    private $user;

    public function __construct()
    {
        //* session check
        session_start();
        if (!$_SESSION['logged_in']) {
            header("Location: " . BASEURL . "/Auth/login");
            exit();
        }

        //* initialize model data
        $this->event = new EventModel();
        $this->ticket = new TicketModel();
        $this->committee = new CommitteeModel();
        $this->transaction = new TransactionModel();
        $this->payment = new PaymentModel();
        $this->user = new UserModel();
    }

    //* detail event page
    public function detail($id_event = null)
    {
        if ($id_event === null) {
            header("Location: " . BASEURL . "/Ticket/myticket");
            exit();
        }

        if (isset($_POST['buyTicket'])) {
            $this->transaction->createTransaction($_SESSION['user_id'], $_POST['price']);
            $transaction = $this->transaction->getTransaction($_SESSION['user_id']);
            $payment = $this->payment->createPayment($transaction['transaction_id']);

            if ($payment) {
                $this->transaction->updateTransaction($transaction['transaction_id']);
                $this->ticket->createTicketAttendee($transaction['transaction_id'], $_SESSION['user_id'], $_POST['ticket_id']);
                $attendee = $this->ticket->getTicketAttendeebyTransactionId($transaction['transaction_id']);
                $this->ticket->generate($attendee['ticket_attendee_id']);
                header('location:' . BASEURL . '/Transaction/success');
                exit();
            } else {
                header('location:' . BASEURL . '/Event/detail/' . $_POST['ticket_id']);
                exit();
            }
        }

        $events = $this->event->getEventById($id_event);
        $tickets = $this->ticket->getTicketByEventId($id_event);

        $data = [
            'events' => $events,
            'tickets' => $tickets,
            'title' => 'Detail Ticket',
            'css' => 'event/detail',
            'js' => 'index',
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/event/detailEvent', $data);
        $this->view('templates/footer', $data);
    }

    //* create event page
    public function create()
    {
        $events = $this->event->getEventsBycommittee($_SESSION['user_id']);

        $data = [
            'events' => $events,
            'title' => 'Create Ticket',
            'css' => 'event/create',
            'js' => 'index'
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/event/createEvent', $data);
        $this->view('templates/footer', $data);
    }

    //* edit event page
    public function editEvent($eventId = null)
    {
        $eventData = null;

        //* cek apakah ini untuk edit atau buat event
        if ($eventId != null) {
            // ambil data event dan tiket untuk diedit
            $eventData = $this->event->getEventById($eventId);

            if (!$eventData) {
                header("Location: " . BASEURL . "/Ticket/create");
                exit();
            }
        }

        //* proses penyimpanan data event
        if ($eventId == null && isset($_POST['save_event'])) {
            // proses penyimpanan data tiket
            $data = [
                'created_by' => $_SESSION['user_id'],
                'event_name'   => $_POST['event_name'] ?? null,
                'description'  => $_POST['description'] ?? null,
                'start_date'   => $_POST['start_date'] ?? null,
                'end_date'     => $_POST['end_date'] ?? null,
                'banner_color' => $_POST['banner_color'] ?? null,
                'location'   => $_POST['location'] ?? null,
                'status_event' => "pending"
            ];

            // Gunakan $_FILES untuk file upload
            $files = $_FILES ?? [];

            // Panggil model untuk membuat event dan dapatkan ID baru
            if ($eventId != null) {
                $newEventId = $this->event->updateEvent($eventId, $data, $files);
            } else {
                $newEventId = $this->event->createEvent($data, $files);
            }

            // Jika berhasil, redirect ke halaman detail event
            if ($newEventId !== false && !empty($newEventId)) {
                $this->committee->addCommittee($newEventId, $_SESSION['user_id'], 'creator');
                header("Location: " . BASEURL . "/Event/detail/" . $newEventId);
                exit();
            } else {
                // Kalau gagal, redirect kembali ke form create dengan pesan atau log (sederhana: kembali ke create)
                header("Location: " . BASEURL . "/Event/create");
                exit();
            }
        }

        //* delete ticket
        if (isset($_POST['delete_ticket'])) {
            $this->ticket->deleteTicket($_POST['ticket_id']);
            header('location:' . BASEURL . '/Ticket/edit/' . $eventId);
            exit();
        }

        $data = [
            'eventId' => $eventId,
            'eventData' => $eventData,
            'tickets' => $this->ticket->getTicketByEventId($eventId),
            'title' => 'Edit Ticket',
            'css' => 'event/edit',
            'js' => 'index'
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/event/editEvent', $data);
        $this->view('templates/footer', $data);
    }

    //* event statistic page
    public function statistic($eventId = null)
    {
        $data = [
            'eventId' => $eventId,
            'title' => 'Ticket Statistic',
            'css' => 'event/statistic',
            'js' => 'index'
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/event/statisticEvent', $data);
        $this->view('templates/footer', $data);
    }

    public function committee($eventId)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kick'])) {
            $this->committee->kickCommittee($_POST['committee_id']);
            header('location:' . BASEURL . '/Event/committee/' . $eventId);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {

            $user = $this->user->findUserByEmail($_POST['email']);
            $this->committee->addCommittee($eventId, $user['user_id'], "staff");
            header('location:' . BASEURL . '/Event/committee/' . $eventId);
            exit();
        }

        $list = $this->committee->geteventCommittee($eventId);

        $data = [
            'eventId' => $eventId,
            'list' => $list,
            'title' => 'Committee',
            'css' => 'event/committee',
            'js' => 'index'
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/event/committee', $data);
        $this->view('templates/footer', $data);
    }
}
