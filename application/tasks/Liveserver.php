<?php
class Liveserver_Task {


	public function run($arguments) {
		// Do the awesome
		$loop = React\EventLoop\Factory::create();

		// Craate pusher server
		$pusher = new Pusher();
		$port = Config::get("live.client.port", 9098);
		$webSocket = new React\Socket\Server($loop);
		$webSocket->listen($port, '0.0.0.0');
		$webServer = new Ratchet\Server\IoServer(new Ratchet\WebSocket\WsServer($pusher), $webSocket);

		// Create commander
		$commander = new Commander($pusher);
		$port = Config::get("live.server.port", 9099);
		$internalSocket = new React\Socket\Server($loop);
		$internalSocket->listen($port, '127.0.0.1');
		$internalServer = new Ratchet\Server\IoServer($commander, $internalSocket);

		$loop->run();
	}
	public function update($value='') {
		// Call the server-side listener
		$socket = @fsockopen('127.0.0.1', Config::get("live.server.port", 9098));
		if(!$socket) {
			return; // Probably offline
		}
		fwrite($socket, "UPDATE");
	}
}

// Keep on pushing pushing pushing pushing pushing http://www.youtube.com/watch?v=Tr7XcsO12Rw
class Pusher implements Ratchet\MessageComponentInterface {
	protected $clients;

	public function __construct() {
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(Ratchet\ConnectionInterface $conn) {
		$this->clients->attach($conn);
	}

	public function onMessage(Ratchet\ConnectionInterface $from, $msg) {
		// Ignore!
	}

	public function onClose(Ratchet\ConnectionInterface $conn) {
		$this->clients->detach($conn);
	}

	public function onError(Ratchet\ConnectionInterface $conn, \Exception $e) {
		$conn->close();
	}

	public function giveaway() {
		// FUN TIMES!
		$tulemused = Controller::call('tulemused@json');
		$tosend = $tulemused->content;
		foreach ($this->clients as $client) {
			$client->send($tulemused);
		}

	}
}
class Commander implements Ratchet\MessageComponentInterface {

	public $pusher;

	public function __construct(Pusher $pusher) {
		$this->pusher = $pusher;
	}


	public function onOpen(Ratchet\ConnectionInterface $conn) {
		if($conn->remoteAddress != '127.0.0.1') {
			$conn->send("Never heard of it!")->close();
		}
	}

	public function onMessage(Ratchet\ConnectionInterface $from, $msg) {
		if($msg == "UPDATE") {
			$this->pusher->giveaway();
		}
	}

	public function onClose(Ratchet\ConnectionInterface $conn) {
	}

	public function onError(Ratchet\ConnectionInterface $conn, \Exception $e) {
	}
}