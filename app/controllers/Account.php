<?php
require_once __DIR__ . '/../model/UserModel.php';
class Account extends Controller
{
    private $user;

    public function __construct()
    {
        // * session check
        session_start();
        if (!$_SESSION['logged_in']) {
            header("Location: " . BASEURL . "/Auth/login");
            exit();
        }

        //* load user model
        $this->user = new UserModel();
    }

    public function index()
    {
        //* logout user
        if (isset($_POST['logout-yes'])) {
            session_unset();
            session_destroy();
            header("Location: " . BASEURL . "/auth/login");
            exit();
        }

        //* delete account
        if (isset($_POST['delete-yes'])) {
            $this->user->deleteUser($_SESSION['user_id']);
            session_unset();
            session_destroy();
            header("Location: " . BASEURL . "/Auth/login");
            exit();
        }

        //* update profile
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {

            $password = $_POST['password'] ?? '';
            $verifyPassword = $_POST['confirm_password'] ?? '';
            $userId = $_SESSION['user_id'];

            // Normalize name: treat an empty string as null so it won't overwrite DB
            $name = isset($_POST['name']) ? trim($_POST['name']) : null;
            if ($name === '') {
                $name = null;
            } else {
                $_SESSION['user_name'] = $name;
            }

            // Ambil data user lama (termasuk foto lama)
            $oldUser = $this->user->getUserById($userId);
            $oldImage = $oldUser['user_image'];  // misal: "abc123.jpg"

            $newPassword = null;
            $newProfileImage = null;

            // --- HANDLE PASSWORD ---

            if ($name !== null) {
                $_SESSION['user_name'] = $name;
            }

            if (!empty($password)) {

                if ($password !== $verifyPassword) {
                    echo "Password dan Confirm Password tidak sesuai.";
                    return;
                }

                $newPassword = $password;
            }

            // --- HANDLE FOTO BARU ---
            if (isset($_FILES['profile-image']) && $_FILES['profile-image']['error'] === UPLOAD_ERR_OK) {

                $uploadDir = __DIR__ . '/../../public/uploads/account/';
                $ext = pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;

                $uploadFile = $uploadDir . $newName;

                // Upload foto baru
                if (move_uploaded_file($_FILES['profile-image']['tmp_name'], $uploadFile)) {

                    $newProfileImage = $newName;

                    // --- HAPUS FOTO LAMA ---
                    if (!empty($oldImage) && $oldImage !== 'user.png') {
                        $oldFile = $uploadDir . $oldImage;

                        if (file_exists($oldFile)) {
                            unlink($oldFile);
                        }
                    }

                    $_SESSION['user_image'] = $newName;
                }
            }

            // --- UPDATE USER ---
            $this->user->updateUser(
                $userId,
                $newProfileImage,
                $newPassword,
                $name
            );

            header('location: ' . BASEURL . '/Account');
            exit();
        }

        //* menjadikan user menjadi verified user
        if (isset($_POST['be-verified'])) {

            // 1. Ambil data input
            $fullName = $_POST['full_name'] ?? '';
            $nik = $_POST['nik'] ?? '';

            // 2. Validasi sederhana
            if (empty($fullName) || empty($nik)) {
                die("Nama dan NIK wajib diisi");
            }

            // 3. File upload
            $fotoWajah = $_FILES['foto_wajah'];
            $fotoKtp   = $_FILES['foto_ktp'];


            function uploadImage($file, $targetDir)
            {
                $allowedExt = ['jpg', 'jpeg', 'png'];

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExt)) {
                    die("Format file tidak diizinkan");
                }

                if ($file['error'] !== 0) {
                    die("Gagal upload file");
                }

                // Nama file unik
                $newName = uniqid() . '.' . $ext;

                // ⚠️ GANTI PATH FOLDER DI SINI
                $destination = $targetDir . '/' . $newName;

                move_uploaded_file($file['tmp_name'], $destination);

                // return path untuk disimpan ke DB
                return $destination;
            }

            $pathFotoWajah = uploadImage($fotoWajah, __DIR__ . '/../../public/uploads/verif_file/faces');
            $pathFotoKtp   = uploadImage($fotoKtp, __DIR__ . '/../../public/uploads/verif_file/ktp');

            $this->user->createVerification($_SESSION['user_id'], $fullName, $nik, $pathFotoWajah, $pathFotoKtp);

            echo "Data berhasil dikirim untuk verifikasi";
        }


        $data = [
            "title" => "Profile Account",
            "css" => "account",
            "js" => "index",
        ];
        $this->view("templates/header", $data);
        $this->view("templates/navbar", $data);
        $this->view("frontend/account/index", $data);
        $this->view("templates/footer", $data);
    }
}
