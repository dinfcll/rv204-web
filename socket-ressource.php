<?php

include "socket.class.php";

if(isset($_POST['color']) && isset($_POST['url'])) {
    $socket = new Socket($_POST['url']);

    if ($socket->isConnected()) {
        $resultat = $socket->sendMessageToSocket($_POST['color']);

        if(!$resultat) {
            http_response_code(503);
        }
    } else {
        http_response_code(503);
    }
} else {
    http_response_code(400);
}
