<?php
    header("Access-Control-Allow-Origin: *"); // Allow cross-origin requests (for testing)
    header("Content-Type: application/json"); // Set response type to JSON

    // Only process POST requests.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["cf_name"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["cf_email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["cf_message"]);

        // Check that data was sent to the mailer.
        if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Validation error, please try again!"]);
            exit;
        }

        // ⚠️ Update this with your real email address
        $recipient = "maha.akl12@gmail.com";  

        // Email subject
        $subject = "New Contact Form Submission from $name";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";

        // Build the email headers.
        $email_headers = "From: $name <$email>\r\n";
        $email_headers .= "Reply-To: $email\r\n";
        $email_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Thank you! Your message has been sent."]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Oops! Something went wrong and we couldn't send your message."]);
        }
    } else {
        // Not a POST request, return 403 (Forbidden)
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Forbidden request."]);
    }
?>
