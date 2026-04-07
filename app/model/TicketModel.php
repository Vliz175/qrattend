<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/Database.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class TicketModel
{
    private $database;
    private $db;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->connect();
    }

    //* generate qr code
    public function generate(int $ticketAttendeeId): string
    {
        try {
            // 1. Generate token
            $token = bin2hex(random_bytes(16));

            // 2. Simpan token
            $stmt = $this->db->prepare("
            UPDATE ticket_attendees 
            SET qr_token = :token 
            WHERE ticket_attendee_id = :id
        ");

            if (!$stmt->execute([
                'id'    => $ticketAttendeeId,
                'token' => $token
            ])) {
                throw new Exception('DB execute failed');
            }

            if ($stmt->rowCount() === 0) {
                throw new Exception('Ticket not found');
            }

            // 3. Data QR
            $data = json_encode([
                'ticket_id' => $ticketAttendeeId,
                'token'     => $token
            ]);

            if ($data === false) {
                throw new Exception('JSON encode failed');
            }

            // 4. Folder
            $dir = __DIR__ . '/../../public/uploads/qrcodes';

            if (!is_dir($dir)) {
                if (!mkdir($dir, 0755, true)) {
                    throw new Exception('Failed to create directory');
                }
            }

            if (!is_writable($dir)) {
                throw new Exception('Directory not writable');
            }

            // 5. Builder
            $builder = new Builder(
                writer: new PngWriter(),
                data: $data,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin
            );

            // 6. Save file
            $result = $builder->build();

            $path = $dir . '/ticket_' . $ticketAttendeeId . '.png';

            $result->saveToFile($path);

            return 'qrcodes/ticket_' . $ticketAttendeeId . '.png';
        } catch (Exception $e) {
            // Debug (sementara)
            die('QR ERROR: ' . $e->getMessage());
        }
    }


    //* get ticket by event id
    public function getTicketByEventId($eventId)
    {
        $query = "SELECT * FROM tickets WHERE event_id = :eventId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':eventId', $eventId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* get ticket by ticket id
    public function getTicketByTicketId($ticketId)
    {
        $sql = "SELECT * FROM tickets WHERE ticket_id = :ticket_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindparam(':ticket_id', $ticketId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //*get ticket attendee by ticket attendee id
    public function getTicketAttendeeByTransactionId($ticketAttendeeId)
    {
        $sql = 'SELECT * FROM ticket_attendees WHERE transaction_id = :transaction_id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':transaction_id', $ticketAttendeeId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //* get ticket for my ticket only
    public function getMyTicket($userId)
    {
        $sql = "SELECT
                ta.ticket_attendee_id AS attendee_id,
                ta.user_id AS user_id,
                ta.ticket_id AS ticket_id,
                ta.attendance_status AS status,
                ta.created_at AS created_at,
                t.ticket_name AS ticket_name,

                e.event_id AS event_id,
                e.event_name AS event_name,
                e.start_date AS start_date,
                e.location AS location,
                e.event_image AS event_image,
                e.banner_color AS banner_color,

                u.name AS organizer_name,
                DATEDIFF(e.start_date, CURDATE()) AS days_left

            FROM ticket_attendees ta
            JOIN tickets t 
                ON ta.ticket_id = t.ticket_id
            JOIN events e 
                ON t.event_id = e.event_id
            JOIN users u 
                ON e.created_by = u.user_id
            WHERE ta.user_id = :user_id
            ORDER BY e.start_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* get ticket by id and token
    public function getTicketByIdAndToken($id, $token)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM ticket_attendees 
         WHERE ticket_attendee_id = :id 
         AND qr_token = :token
         LIMIT 1"
        );

        $stmt->execute([
            'id' => $id,
            'token' => $token
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    //* update ticket attendee status
    public function updateTicketAttendeeStatus($ticketAttendeeId)
    {
        $sql = "UPDATE ticket_attendees 
            SET attendance_status = 'checked_in'
            WHERE ticket_attendee_id = :ticketAttendeeId
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'ticketAttendeeId' => $ticketAttendeeId
        ]);
    }


    //* create ticket
    public function createTicket($eventId, $ticketName, $price, $quota, $sold, $startSale, $endSale)
    {
        if ($price < 0 || $quota < 1) {
            return false;
        }

        $sql = "INSERT INTO tickets 
                (event_id, ticket_name, price, quota, sold, start_sale, end_sale)    
                VALUES 
                (:event_id, :ticket_name, :price, :quota, :sold, :start_sale, :end_sale)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindparam(':event_id', $eventId);
        $stmt->bindparam(':ticket_name', $ticketName);
        $stmt->bindparam(':price', $price);
        $stmt->bindparam(':quota', $quota);
        $stmt->bindparam(':sold', $sold);
        $stmt->bindparam(':start_sale', $startSale);
        $stmt->bindparam(':end_sale', $endSale);
        return $stmt->execute();
    }

    //* create ticket attendee
    public function createTicketAttendee($transactionId, $userId, $ticketId)
    {
        $sql = "INSERT INTO ticket_attendees (transaction_id, user_id, ticket_id)
                VALUES (:transaction_id, :user_id, :ticket_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':transaction_id', $transactionId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':ticket_id', $ticketId);
        return $stmt->execute();
    }

    //* update ticket by ticket id
    public function updateTicket($ticketId, $ticketName, $price, $quota, $startSale, $endSale)
    {
        $sql = "UPDATE tickets 
            SET ticket_name = :ticket_name,
                price = :price,
                quota = :quota,
                start_sale = :start_sale,
                end_sale = :end_sale
            WHERE ticket_id = :ticket_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ticket_id', $ticketId, PDO::PARAM_INT);
        $stmt->bindParam(':ticket_name', $ticketName);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quota', $quota, PDO::PARAM_INT);
        $stmt->bindParam(':start_sale', $startSale);
        $stmt->bindParam(':end_sale', $endSale);
        return $stmt->execute();
    }

    //* delete ticket by ticket id
    public function deleteTicket($ticketId)
    {
        $sql = "DELETE FROM tickets WHERE ticket_id = :ticket_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ticket_id', $ticketId);
        return $stmt->execute();
    }
}
