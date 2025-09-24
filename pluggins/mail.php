<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username from the form
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
    
    // Get email from the form
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Database connection
    $servername = "localhost";
    $dbusername = "root";
    $password = "1234";
    $dbname = "mailapp";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Store user in database
    $stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $email);
    
    if ($stmt->execute()) {
        // Create an instance of PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'austin.maina@strathmore.edu';
            $mail->Password   = 'ulrv mrjr oztd fxvw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('austin.maina@strathmore.edu', 'ICS 2.2 Admin');
            $mail->addAddress($email, $username);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Welcome to ICS 2.2!';
            $mail->Body    = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                <h2>Hello ' . htmlspecialchars($username) . ',</h2>
                <p>You requested an Account on ICS 2.2.</p>
                <p>Your registration is now complete!</p>
                <br>
                <p>Regards,<br>
                Systems Admin<br>
                ICS 2.2</p>
            </div>';

            $mail->send();
            echo "<div style='text-align: center; margin-top: 50px;'>
                    <h2>Registration Successful!</h2>
                    <p>Welcome email has been sent to your address.</p>
                    <a href='users.php'>View all users</a>
                  </div>";
        } catch (Exception $e) {
            echo "<div style='text-align: center; margin-top: 50px; color: red;'>
                    <h2>Registration Successful but Email Failed</h2>
                    <p>Could not send welcome email: {$mail->ErrorInfo}</p>
                    <a href='users.php'>View all users</a>
                  </div>";
        }
    } else {
        echo "Error registering user: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Redirect back to the form if accessed directly
    header("Location: index.html");
    exit();
}