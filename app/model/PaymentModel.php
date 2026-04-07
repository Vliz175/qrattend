<?php
require_once __DIR__ . '/../config/Database.php';

class PaymentModel
{
    private $database;
    private $db;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->connect();
    }

    public function createPayment($transactionId)
    {
        $sql = "INSERT INTO payments (transaction_id, payment_method, payment_status)
            VALUES (:transaction_id, :payment_method, :payment_status)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'transaction_id' => $transactionId,
            'payment_method' => 'non_cash',
            'payment_status' => 'success'
        ]);
    }
}
