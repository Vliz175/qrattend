<?php
require_once __DIR__ . '/../config/Database.php';
class EventModel
{
    private $database;
    private $db;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->connect();
    }

    //* get all events
    public function getAllEvents()
    {
        $sql = "SELECT 
                events.*, 
                (
                    SELECT MIN(tickets.price)  
                    FROM tickets 
                    WHERE tickets.event_id = events.event_id
                ) AS min_price, 
                (
                    SELECT name 
                    FROM users 
                    WHERE users.user_id = events.created_by
                ) AS creator_name,
                (
                    SELECT user_image 
                    FROM users 
                    WHERE users.user_id = events.created_by
                ) AS creator_image
            FROM 
                events WHERE status_event = 'approved'
            ORDER BY events.start_date DESC
            LIMIT 10;
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* get event by name
    public function getEventByName($eventName)
    {
        $sql = "SELECT 
                events.*, 
                (
                    SELECT MIN(tickets.price)  
                    FROM tickets 
                    WHERE tickets.event_id = events.event_id
                ) AS min_price, 
                (
                    SELECT name 
                    FROM users 
                    WHERE users.user_id = events.created_by
                ) AS creator_name,
                (
                    SELECT user_image 
                    FROM users 
                    WHERE users.user_id = events.created_by
                ) AS creator_image
                FROM events WHERE events.event_name LIKE :name;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', "%$eventName%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* get event by id
    public function getEventById($id)
    {
        $sql = "SELECT * FROM events WHERE event_id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //* get events by committee
    public function getEventsBycommittee($user_id)
    {
        $sql = "SELECT events.*, uc.event_id, uc.user_id, users.name
                FROM events
                JOIN event_committees uc ON uc.event_id = events.event_id
                join users ON users.user_id = uc.user_id
                WHERE uc.user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* get event by status
    public function getEventByStatus()
    {
        $sql = "SELECT * FROM events WHERE status_event = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* create new event
    public function createEvent($data, $files)
    {
        // Handle file upload
        $eventImagePath = null;
        if (empty($files['event_image'])) {
            header('location:' . BASEURL . '/Event/editEvent');
            exit();
        }

        // cek pemindahan gambar ke database
        if (isset($files['event_image']) && $files['event_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileTmpPath = $files['event_image']['tmp_name'];
            $fileName = basename($files['event_image']['name']);
            $eventImagePath = uniqid() . '_' . $fileName;
            $fullPath = $uploadDir . $eventImagePath;

            if (!move_uploaded_file($fileTmpPath, $fullPath)) {
                die("Gagal upload file. Cek path: $fullPath & permission folder!");
            }
        }

        // Insert event data into database
        $sql = "INSERT INTO events 
                (created_by, event_name, description, start_date, end_date, event_image, banner_color, location, status_event, approved_by) 
                VALUES 
                (:created_by, :event_name, :description, :event_date, :event_end, :event_image, :banner_color, :location, :status_event, :approved_by)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':created_by', $data['created_by']);
        $stmt->bindParam(':event_name', $data['event_name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':event_date', $data['start_date']);
        $stmt->bindParam(':event_end', $data['end_date']);
        $stmt->bindParam(':banner_color', $data['banner_color']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':status_event', $data['status_event']);
        $stmt->bindParam(':approved_by', $data['approved_by']);
        $stmt->bindParam(':event_image', $eventImagePath);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    //* update event
    public function updateEvent($eventId, $data, $files)
    {
        // Handle file upload
        $eventImagePath = $data['existing_image'] ?? null;
        $oldImage = $eventImagePath;
        if (isset($files['event_image']) && $files['event_image']['error'] === UPLOAD_ERR_OK) {
            // Use absolute path to public/uploads/events/
            $uploadDir = __DIR__ . '/../../public/uploads/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileTmpPath = $files['event_image']['tmp_name'];
            $fileName = basename($files['event_image']['name']);
            $newImageName = uniqid() . '_' . $fileName;
            $destination = $uploadDir . $newImageName;

            // Move uploaded file first, then delete old image to avoid data loss on failure
            if (move_uploaded_file($fileTmpPath, $destination)) {
                // remove old image file if it exists and is not a placeholder
                if (!empty($oldImage) && $oldImage !== 'event.png') {
                    $oldFile = $uploadDir . $oldImage;
                    if (file_exists($oldFile) && is_file($oldFile)) {
                        @unlink($oldFile);
                    }
                }

                $eventImagePath = $newImageName;
            }
        }

        // Update event data in database
        $sql = "UPDATE events SET 
                event_name = :event_name, 
                description = :description, 
                event_date = :event_date, 
                event_end = :event_end, 
                event_image = :event_image, 
                banner_color = :banner_color, 
                location = :location 
                WHERE event_id = :event_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':event_name', $data['event_name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':event_date', $data['start_date']);
        $stmt->bindParam(':event_end', $data['end_date']);
        $stmt->bindParam(':banner_color', $data['banner_color']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':event_image', $eventImagePath);
        $stmt->bindParam(':event_id', $eventId);

        return $stmt->execute();
    }

    //* update status event
    public function updateStatusEvent($eventId, $status)
    {
        $sql = "UPDATE events 
                SET status_event = :status
                WHERE event_id = :event_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':event_id' => $eventId
        ]);
    }
}
