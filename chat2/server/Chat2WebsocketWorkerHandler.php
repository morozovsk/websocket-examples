<?php

namespace morozovsk\websocket\examples\chat2\server;

class Chat2WebsocketWorkerHandler extends \morozovsk\websocket\Daemon
{
    protected $flud;

    protected $logins = array();

    protected function onOpen($connectionId, $info) {//it is called when the connection is open
        if ($this->logins) {
            $this->sendPacketToClient($connectionId, 'logins', array_keys($this->logins));
        }
    }

    protected function onClose($connectionId) {//it is called when the connection is closed
        if ($login = array_search($connectionId, $this->logins)) {
            unset($this->logins[$login]);
            $this->sendPacketToMaster('logout', array('login' => $login, 'clientId' => $connectionId));
            $this->sendPacketToClients('logout', $login);
        }
    }

    protected function onMessage($connectionId, $data, $type) {//it is called when received a message from client
        if (!strlen($data)) {
            return;
        }

        //anti-flood:
        $time = time();
        if (isset($this->flud[$connectionId]) && $this->flud[$connectionId] == $time) {
            return;
        } else {
            $this->flud[$connectionId] = $time;
        }

        if ($login = array_search($connectionId, $this->logins)) {
            $message = $login . ': ' . strip_tags($data);
            $this->sendPacketToMaster('message', $message);
            $this->sendPacketToClients('message', $message);
        } else {
            if (preg_match('/^[a-zA-Z0-9]{1,10}$/', $data, $match)) {
                if (isset($this->logins[$match[0]])) {
                    $this->sendPacketToClient($connectionId, 'message', 'system: selected name already exists, try another.');
                } else {
                    $this->logins[$match[0]] = -1;
                    $this->sendPacketToMaster('login', array('login' => $match[0], 'clientId' => $connectionId));
                }
            } else {
                $this->sendPacketToClient($connectionId, 'message', 'system: wrong name. Please enter valid name that will be displayed. The name can be used English letters and numbers. The name must not exceed 10 characters..');
            }
        }
    }

    protected function onMasterMessage($packet) {//it is called when received a message from master
        $packet = $this->unpack($packet);
        if ($packet['cmd'] == 'message') {
            $this->sendPacketToClients('message', $packet['data']);
        } elseif ($packet['cmd'] == 'login') {
            if ($packet['data']['result']) {
                $this->logins[ $packet['data']['login'] ] = $packet['data']['clientId'];
                $this->sendPacketToClients('login', $packet['data']['login']);
                if (isset($this->clients[ $packet['data']['clientId'] ])) {
                    $this->sendPacketToClient($this->clients[ $packet['data']['clientId'] ], 'message', 'system: you are logged in as ' . $packet['data']['login']);
                }
            } else {
                $this->sendPacketToClient($this->clients[ $packet['data']['clientId'] ], 'message', 'system: selected name already exists, try another.');
            }
        } elseif ($packet['cmd'] == 'logout') {
            unset($this->logins[$packet['data']['login']]);
            $this->sendPacketToClients('logout', $packet['data']['login']);
        }
    }

    protected function sendPacketToMaster($cmd, $data) {//send message to master, so he sent it to all the workers
        $this->sendToMaster($this->pack($cmd, $data));
    }

    private function sendPacketToClients($cmd, $data) {
        $data = $this->pack($cmd, $data);
        foreach ($this->clients as $clientId => $client) {
            $this->sendToClient($clientId, $data);
        }
    }

    protected function sendPacketToClient($connectionId, $cmd, $data) {
        $this->sendToClient($connectionId, $this->pack($cmd, $data));
    }

    public function pack($cmd, $data) {
        return json_encode(array('cmd' => $cmd, 'data' => $data));
    }

    public function unpack($data) {
        return json_decode($data, true);
    }
}
