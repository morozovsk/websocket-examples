<?php

namespace morozovsk\websocket\examples\chat3\server;

class Chat3WebsocketDaemonHandler extends \morozovsk\websocket\Daemon
{
    public $userIds = [];
    protected function onOpen($connectionId, $info) {//it is called when the connection is open
        $message = 'пользователь #' . $connectionId . ' : ' . var_export($info, true) . ' ' . stream_socket_get_name($this->clients[$connectionId], true);

        foreach ($this->clients as $clientId => $client) {
            $this->sendToClient($clientId, $message);
        }

        $info['GET'];//or use $info['Cookie'] for use PHPSESSID or $info['X-Real-IP'] if you use proxy-server like nginx
        parse_str(substr($info['GET'], 1), $_GET);//parse get-query
        //var_export($_GET['id']);
        $this->userIds[$connectionId] = $_GET['userId'];
    }

    protected function onClose($connectionId) {//it is called when existed connection is closed
        unset($this->userIds[$connectionId]);
    }

    protected function onMessage($connectionId, $data, $type) {//it is called when received a message from client
        if (!strlen($data)) {
            return;
        }

        $message = 'пользователь #' . $connectionId . ' : ' . strip_tags($data);

        foreach ($this->clients as $clientId => $client) {
            $this->sendToClient($clientId, $message);
        }
    }

    protected function onServiceMessage($connectionId, $data) {
        $data = json_decode($data);

        foreach ($this->userIds as $clientId => $userId) {
            if ($data->userId == $userId) {
                $this->sendToClient($clientId, $data->message);
            }
        }

        /*if (isset($this->clients[$data->clientId])) {
            $this->sendToClient($data->clientId, $data->message);
        }*/
    }
}
