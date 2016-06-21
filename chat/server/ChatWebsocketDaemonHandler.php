<?php

namespace morozovsk\websocket\examples\chat\server;

//chat realization example
class ChatWebsocketDaemonHandler extends \morozovsk\websocket\Daemon
{
    protected function onOpen($connectionId, $info) {//it is called when new connection is open

    }

    protected function onClose($connectionId) {//it is called when existed connection is closed

    }

    protected function onMessage($connectionId, $data, $type) {//it is called when a message is received from the client
        if (!strlen($data)) {
            return;
        }

        //var_export($data);
        //send to all clients a message received from the client
        //echo $data . "\n";
        $message = 'user #' . $connectionId . ': ' . strip_tags($data);

        foreach ($this->clients as $clientId => $client) {
            $this->sendToClient($clientId, $message);
        }
    }
}
