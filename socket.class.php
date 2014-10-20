<?php

class Socket
{
    private $socket;
    private $connected = false;

    //Reçoit en paramètre l'adresse (127.0.0.1:4444 , par exemple)
    public function __construct($adresse)
    {
        try {
            $this->socket = stream_socket_client('tcp://'. $adresse);
            $this->connected = true;
        } catch (Exception $e) {
            $this->connected = false;
            die();
        }
    }

    function isConnected() {
        return $this->connected;
    }

    function sendMessageToSocket($message)
    {
        $resultat = false;

        if ($this->socket) {
            $sent = stream_socket_sendto($this->socket, $message);
            if ($sent > 0) {
                $resultat = true;
            }
            $this->connected = false;
        } else {
            $resultat = false;
        }
        stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);

        return $resultat;
    }

}
