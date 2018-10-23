<?php

namespace App\Classes\Socket;

use App\Classes\Socket\Base\BaseSocket;
use App\Message;
use Ratchet\ConnectionInterface;

class ChatSocket extends BaseSocket
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg);
        $numRecv = count($this->clients) - 1;

        echo sprintf(
            'Conn %d sending message "%s" to %d other con' . "\n",
            $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's'
            );

        $message = new Message();
        $message->user_id = $data->authId;
        $message->message = $data->message;
        $message->save();

        foreach ($this->clients as $client)
        {
            if ($from !== $client) {
                $client->send($data->message);
            }
        }
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        echo  "Con {$conn->resourceId} has disconected\n";
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
