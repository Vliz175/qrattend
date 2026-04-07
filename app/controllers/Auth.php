<?php
require_once __DIR__ . '/../model/UserModel.php';
class Auth extends Controller
{
    private $user;
    private $organizer;

    public function __construct()
    {
        //* load model user
        $this->user = new UserModel();
    }

    //* Login Page
    public function login()
    {
        session_start();

        //* cek apakah sudah login
        if ($_SESSION['logged_in'] ?? false) {
            header("Location: " . BASEURL);
            exit();
        }

        //* proses login
        if (isset($_POST['submit'])) {
            if ($this->user->findUserByEmail($_POST['email']) && $this->user->verifyPassword($_POST['email'], $_POST['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $this->user->findUserByEmail($_POST['email'])['user_id'];
                $_SESSION['user_name'] = $this->user->findUserByEmail($_POST['email'])['name'];
                $_SESSION['user_email'] = $this->user->findUserByEmail($_POST['email'])['email'];
                $_SESSION['user_noHp'] = $this->user->findUserByEmail($_POST['email'])['phone_number'];
                $_SESSION['user_birthDate'] = $this->user->findUserByEmail($_POST['email'])['birth_date'];
                $_SESSION['user_gender'] = $this->user->findUserByEmail($_POST['email'])['gender'];
                $_SESSION['user_image'] = $this->user->findUserByEmail($_POST['email'])['user_image'];
                $_SESSION['user_role'] = $this->user->findUserByEmail($_POST['email'])['role'];

                //* get info organizer
                $isVerified = $this->user->getUserById($_SESSION['user_id']);
                if ($isVerified == true && $isVerified['verification_id'] != null) {
                    $_SESSION['is_verified'] = true;
                } else {
                    $_SESSION['is_verified'] = false;
                }

                echo "Login successful";
                if ($_SESSION['user_role'] == 'admin') {
                    header('location:' . BASEURL . '/Admin');
                } else {
                    header("Location: " . BASEURL);
                }

                exit();
            } else {
                echo "User not found";
            }
        }

        $data = [
            'title' => 'Login Page',
            'css' => 'auth'
        ];
        $this->view('templates/header', $data);
        $this->view('frontend/auth/login', $data);
        $this->view('templates/footer', $data);
    }

    //* Register Page
    public function register()
    {
        //* cek apakah sudah login
        if ($_SESSION['logged_in'] ?? false) {
            header("Location: " . BASEURL . "/Home/index");
            exit();
        }

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phoneNumber = $_POST['phone_number'];
            $birthDate = $_POST['birth_date'];
            $password = $_POST['password'];
            $gender = $_POST['gender'];
            $verifyPassword = $_POST['confirm_password'];

            if ($password === $verifyPassword) {
                $this->user->createUser($name, $email, $phoneNumber, $birthDate, $password, $gender);
                header("Location: " . BASEURL . "/Auth/login");
                exit();
            } else {
                echo "Password dan Confirm Password tidak sesuai.";
            }
        }

        $data = [
            'css' => 'auth'
        ];
        $this->view('templates/header', $data);
        $this->view('frontend/auth/register', $data);
        $this->view('templates/footer', $data);
    }
}
