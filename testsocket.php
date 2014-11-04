<?php

$socket = stream_socket_server("tcp://0.0.0.0:1500", $errno, $errstr);
stream_set_timeout($socket, -1);
if (!$socket) {
    echo "$errstr ($errno)<br />\n";
} else {
    while(true) {
        while ($conn = stream_socket_accept($socket)) {
            $command = "TES";
            $couleur = pack("CCC", 255, 255, 0);
            $result = $command . $couleur;
            fputs ($conn, $result);
            fclose ($conn);
        }
    }
    fclose($socket);
}
