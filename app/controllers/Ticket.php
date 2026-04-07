<?php
require_once __DIR__ . '/../model/TicketModel.php';
require_once __DIR__ . '/../model/EventModel.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Ticket extends Controller
{
    private $ticket;
    private $event;


    public function __construct()
    {
        //* session check
        session_start();
        if (!$_SESSION['logged_in']) {
            header("Location: " . BASEURL . "/Auth/login");
            exit();
        }

        $this->ticket  = new TicketModel();
        $this->event  = new EventModel();
    }

    //* my ticket page
    public function myticket()
    {
        $list = $this->ticket->getMyTicket($_SESSION['user_id']);

        $data = [
            'list' => $list,
            'title' => 'My Ticket',
            'css' => 'ticket/myticket',
            'js' => 'index'
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/ticket/myticket', $data);
        $this->view('templates/footer', $data);
    }

    //* edit ticket page
    public function editTicket($eventId, $ticketId = null)
    {
        $ticketData = null;

        //* cek apakah ini untuk edit atau buat tiket
        if ($ticketId != null) {
            // ambil data event dan tiket untuk diedit
            $ticketData = $this->ticket->getTicketByTicketId($ticketId);

            if (!$ticketData) {
                header("Location: " . BASEURL . "/Ticket/create");
                exit();
            }
        }

        // buat tiket saat di tekan tombolnya
        if (isset($_POST['save'])) {
            $ticketName = $_POST['ticket_name'];
            $price = $_POST['price'];
            $quota = $_POST['quota'];
            $startSale = $_POST['start_sale'];
            $endSale = $_POST['end_sale'];
            $sold = 0;

            if ($ticketId == null) {
                $this->ticket->createTicket($eventId, $ticketName, $price, $quota, $sold,  $startSale, $endSale);
            } else {
                $this->ticket->updateTicket($ticketId, $ticketName, $price, $quota, $startSale, $endSale);
            }

            header('location:' . BASEURL . '/Event/editEvent/' . $eventId);
            exit();
        }

        $data = [
            'title' => 'Edit Ticket',
            'ticketData' => $ticketData,
            'css' => 'ticket/edit',
            'js' => 'index'
        ];
        $this->view('templates/header', $data);
        $this->view('templates/navbar', $data);
        $this->view('frontend/ticket/editTicket', $data);
        $this->view('templates/footer', $data);
    }

    //* scan ticket 
    public function scanTicket()
    {
        // ==== API SCAN (AJAX) ====
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            header('Content-Type: application/json');

            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data || empty($data['ticket_id']) || empty($data['token'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid QR data'
                ]);
                exit;
            }

            $ticket = $this->ticket->getTicketByIdAndToken(
                $data['ticket_id'],
                $data['token']
            );

            if (!$ticket) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'QR CODE TIDAK VALID'
                ]);
                exit;
            }

            if ($ticket['attendance_status'] === 'checked_in') {
                echo json_encode([
                    'status' => 'warning',
                    'message' => 'TIKET SUDAH DIGUNAKAN'
                ]);
                exit;
            }

            $this->ticket->updateTicketAttendeeStatus(
                $ticket['ticket_attendee_id']
            );

            echo json_encode([
                'status' => 'success',
                'message' => 'CHECK-IN BERHASIL'
            ]);
            exit;
        }

        // ==== VIEW (GET) ====
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            echo "\u{200B}";


            $data = [
                'title' => 'Scan Ticket',
                'css'   => 'ticket/scan',
                'js'    => 'scan'
            ];

            $this->view('templates/header', $data);
            $this->view('templates/navbar', $data);
            $this->view('frontend/ticket/scanTicket', $data);
            $this->view('templates/footer', $data);
        }
    }
}
