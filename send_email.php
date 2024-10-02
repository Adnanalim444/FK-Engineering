<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $education = $_POST['education'];
    $position = $_POST['position'];
    $coverLetter = $_POST['coverLetter'];

    $to = 'careers@fkengineerings.com'; // Replace with your email address
    $subject = 'Career Application from ' . $name;
    
    $message = "Name: $name\n Email: $email\n phone: $phone\n education: $education\n position: $position\n Cover Letter:\n$coverLetter";
    
    // To send an email with an attachment, we need to use the MIME format
    $boundary = md5(time());
    
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= "$message\r\n";
    
    // File attachment
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['resume']['tmp_name'];
        $fileName = $_FILES['resume']['name'];
        $fileSize = $_FILES['resume']['size'];
        $fileType = $_FILES['resume']['type'];
        $fileContent = file_get_contents($fileTmpPath);
        
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $fileType; name=\"$fileName\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n\r\n";
        $body .= chunk_split(base64_encode($fileContent)) . "\r\n";
        $body .= "--$boundary--";
    } else {
        $body .= "--$boundary--";
    }
    
    if (mail($to, $subject, $body, $headers)) {
        echo "Your application has been sent successfully.";
    } else {
        echo "There was an error sending your application.";
    }
}
?>
