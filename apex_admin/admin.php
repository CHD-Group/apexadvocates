<?php
session_start();
include '../connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $input = trim($_POST['login_user'] ?? '');
        $password = $_POST['login_pass'] ?? '';

        if (empty($input) || empty($password)) {
            $error = "Please fill in all fields.";
        } else {
            $stmt = $conn->prepare("SELECT id, username,email, password FROM admins WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $input, $input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "User not found.";
            }
            $stmt->close();
        }

    } elseif ($action === 'forgot') {
        $email = trim($_POST['forgot_email'] ?? '');

        if (empty($email)) {
            $error = "Please enter your email.";
        } else {
            $stmt = $conn->prepare("SELECT username FROM admins WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $username = $user['username'];
                $token = bin2hex(random_bytes(16));

                // Optional: store token and expiration in DB here

                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                $resetLink = "$protocol://" . $_SERVER['HTTP_HOST'] . "/Edmond_admin/reset-password.php?token=$token";
                $update = $conn->prepare("UPDATE admins SET reset_token = ? WHERE email = ?");
$update->bind_param("ss", $token, $email);
$update->execute();
$update->close();


                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'harshith@eciph.in'; // 🔁 Your email
                    $mail->Password = 'fggp uwer xyrm wrbm'; // 🔁 Your email app password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('harshith@eciph.in', 'Admin');
                    $mail->addAddress($email, $username);
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body = "<p>Hi <strong>$username</strong>,</p>
                                   <p>Click the link below to reset your password:</p>
                                   <a href='$resetLink'>$resetLink</a>";

                    $mail->send();
                    $success = "Reset link sent to your email.";
                } catch (Exception $e) {
                    $error = "Email Error: " . $mail->ErrorInfo;
                }
            } else {
                $error = "Email not found.";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea, #764ba2);
      margin: 0; padding: 0; height: 100vh;
      display: flex; justify-content: center; align-items: center;
    }
    .container {
      background: white;
      border-radius: 15px;
      width: 400px;
      padding: 30px;
      box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    h2 {
      text-align: center;
      color: #5a2a83;
      margin-bottom: 25px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      box-sizing: border-box;
    }
    button {
      width: 100%;
      background-color: #5a2a83;
      color: white;
      border: none;
      padding: 14px;
      font-size: 18px;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #3d1d5a;
    }
    .toggle-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #5a2a83;
      cursor: pointer;
      user-select: none;
    }
    .error {
      background: #f8d7da;
      color: #842029;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      text-align: center;
    }
    .success {
      background: #d1e7dd;
      color: #0f5132;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      text-align: center;
    }
  </style>
  <script>
    function toggleForm(formName) {
      document.getElementById('login-form').style.display = formName === 'login' ? 'block' : 'none';
      document.getElementById('forgot-form').style.display = formName === 'forgot' ? 'block' : 'none';
    }
    window.onload = function () {
      <?php if (isset($_POST['action']) && $_POST['action'] === 'forgot' && !$success): ?>
        toggleForm('forgot');
      <?php else: ?>
        toggleForm('login');
      <?php endif; ?>
    };
  </script>
</head>
<body>
  <div class="container">

    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- LOGIN FORM -->
    <form id="login-form" method="post" action="">
      <h2>Admin Login</h2>
      <input type="hidden" name="action" value="login" />
      <div class="form-group">
        <input type="text" name="login_user" placeholder="Username or Email" required />
      </div>
      <div class="form-group">
        <input type="password" name="login_pass" placeholder="Password" required />
      </div>
      <button type="submit">Login</button>
      <div class="toggle-link" onclick="toggleForm('forgot')">Forgot Password?</div>
    </form>

    <!-- FORGOT PASSWORD FORM -->
    <form id="forgot-form" method="post" action="" style="display:none;">
      <h2>Forgot Password</h2>
      <input type="hidden" name="action" value="forgot" />
      <div class="form-group">
        <input type="email" name="forgot_email" placeholder="Enter your email" required />
      </div>
      <button type="submit">Send Reset Link</button>
      <div class="toggle-link" onclick="toggleForm('login')">Back to Login</div>
    </form>

  </div>
</body>
</html>
