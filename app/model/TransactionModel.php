<?php
require_once __DIR__ . '/../config/Database.php';

class TransactionModel
{
    private $database;
    private $db;

    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->connect();
    }

    public function getTransaction($userId)
    {
        $sql = "SELECT * FROM transactions 
                WHERE user_id = :user_id && status_transaction = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTransaction($userId, $totalAmount)
    {
        $sql = "INSERT INTO transactions (user_id, total_amount) 
                VALUES (:user_id, :total_amount)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':total_amount', $totalAmount);
        return $stmt->execute();
    }

    public function updateTransaction($transactionId)
    {
        $sql = "UPDATE transactions SET status_transaction = 'paid' WHERE transaction_id = :transaction_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':transaction_id', $transactionId);
        return $stmt->execute();
    }
}
