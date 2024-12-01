<?php
class LoginController {
    public function login() {
        require_once '../privat/Models/db.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Fetch the user from the database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the account is locked
                if ($user['failed_attempts'] >= 3 && strtotime($user['lock_time']) > time() - 300) {
                    $remainingTime = 300 - (time() - strtotime($user['lock_time']));
                    echo "Kontoen er låst. Prøv igjen om " . ceil($remainingTime / 60) . " minutter.";
                    return;
                }

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Reset failed attempts
                    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0, lock_time = NULL WHERE id = ?");
                    $stmt->execute([$user['id']]);

                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header("Location: index.php?page=admin/profile");
                    } elseif ($user['role'] === 'bruker') {
                        header("Location: index.php?page=user/profile");
                    }
                    exit();
                } else {
                    // Increment failed attempts
                    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, lock_time = IF(failed_attempts >= 2, NOW(), NULL) WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    echo "Feil e-post eller passord. Prøv igjen.";
                    return;
                }
            } else {
                echo "Bruker ikke funnet. Sjekk e-postadressen din.";
                return;
            }
        } else {
            require '../privat/Views/pages/login.php';
        }
    }
}