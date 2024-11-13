<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the config.php file to get the secret key
    $config = include('config.php');
    $secret_key = $config['recaptcha_secret_key']; // Load secret key from the config file

    // reCAPTCHA response
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verify the reCAPTCHA response
    $verify_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha_response}");
    $response_data = json_decode($verify_response);

    if ($response_data->success) {
        // reCAPTCHA is successful, now process the form data

        // Sanitize form data
        $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
        $subject = htmlspecialchars($_POST['subject']);
        $message = htmlspecialchars($_POST['message']);

        // Define the recipient email address
        $to = "gelrielkshrine@proton.me"; // Replace with your email address

        // Set the email headers
        $headers = "From: no-reply@your-website-domain.com\r\n"; // Use a fixed email address here
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        // Construct the email content
        $email_subject = "New Message with subject of: $subject";
        $email_body = "<p>You have received a new message from your website contact form.</p>";
        $email_body .= "<p><strong>Email:</strong> $email</p>";
        $email_body .= "<p><strong>Subject:</strong> $subject</p>";
        $email_body .= "<p><strong>Message:</strong></p><p>$message</p>";

        // Send the email and handle errors
        if (mail($to, $email_subject, $email_body, $headers)) {
            echo "Message sent successfully!";
        } else {
            error_log("Email sending failed", 3, "/path/to/error_log.log");
            echo "Failed to send message. Please try again.";
        }
    } else {
        echo "reCAPTCHA verification failed. Please try again.";
    }
}
?>
