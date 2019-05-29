<?php

    if (isset($_POST['name'], $_POST['mail'], $_POST['subject'], $_POST['message']))  // prüft ob Variable existiert und nicht null ist
    {
        $name = $_POST['name'];
        $mailFrom = $_POST['mail'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
       
        $mailTo = "Vero@localhost";
        $headers = 'From: ' . $mailFrom;
        $body = "Du hast eine Nachricht von " . $name . " erhalten" . "\n\n" . $message;

        
        mail($mailTo, $subject, $body, $headers);  
        echo "Vielen Dank für deine Nachricht!"; 
    }

?>