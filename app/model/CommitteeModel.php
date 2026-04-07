<?php
require_once __DIR__ . '/../config/Database.php';

class CommitteeModel
{
    private $database;
    private $db;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->connect();
    }

    //* get event committee
    public function geteventCommittee($eventId)
    {
        $sql = "SELECT 
                ec.event_committee_id AS committee_id,
                ec.committee_role AS committee_role,
                ec.assigned_at AS assigned_at,
                u.name AS name,
                u.email AS email
                FROM event_committees ec
                JOIN users u ON ec.user_id = u.user_id
                WHERE event_id = :event_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'event_id' => $eventId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* add committee event
    public function addCommittee($event_id, $user_id, $role)
    {
        $sql = "INSERT INTO event_committees (event_id, user_id, committee_role) VALUES (:event_id, :user_id, :committee_role)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':committee_role', $role);
        return $stmt->execute();
    }

    //* kick committee event
    public function kickCommittee($committeeId)
    {
        $sql = "DELETE FROM event_committees WHERE event_committee_id = :committee_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'committee_id' => $committeeId
        ]);
    }
}
