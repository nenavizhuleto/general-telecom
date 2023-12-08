<?php

namespace App\Models;

use CodeIgniter\Model;

class AMIModel extends Model {
	protected $config = [
		'ami_host' => 'localhost',
		'ami_scheme' => 'tcp://',
		'ami_port' => 5038,
		'ami_user' => 'golos',
		'ami_password' => 'golos',
		'ami_connect_timeout' => 5000,
		'ami_read_timeout' => 3000
	];

	protected $ami = null;

	private function open() {
		$this->ami = new \PAMI\Client\Impl\ClientImpl([
			'host' => $this->config['ami_host'],
			'scheme' => $this->config['ami_scheme'],
			'port' => $this->config['ami_port'],
			'username' => $this->config['ami_user'],
			'secret' => $this->config['ami_password'],
			'connect_timeout' => $this->config['ami_connect_timeout'],
			'read_timeout' => $this->config['ami_read_timeout']
		]);

		try {
			$this->ami->open();
		} catch (\Exception $e) {
			$this->ami = null;

			throw $e;
		}
	}

	private function close() {
		if (null !== $this->ami) {
			$this->ami->close();
			$this->ami = null;
		}
	}

	public function getCoreSettings() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\CoreSettingsAction();
			$response = $this->ami->send($msg);
			$responseKeys = $response->getKeys();
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		return $responseKeys;
	}

	public function getPeers() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\SIPPeersAction();
			$response = $this->ami->send($msg);
			$responseEvents = $response->getEvents();
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$peers = [];
		foreach ($responseEvents as $event) {
			$keys = $event->getKeys();

			if ('PeerEntry' != $keys['event'])
				continue;

			$peers[] = $keys;
		}

		return $peers;
	}

	public function getQueues() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\QueueStatusAction();
			$response = $this->ami->send($msg);
			$responseEvents = $response->getEvents();
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$queues = [];
		foreach ($responseEvents as $event) {
			$keys = $event->getKeys();

			if ('QueueParams' == $keys['event']) {
				$queues[$keys['queue']] = $keys;
				$queues[$keys['queue']]['members'] = [];
				continue;
			}

			if ('QueueMember' == $keys['event']) {
				$queues[$keys['queue']]['members'][] = $keys;
				continue;
			}
		}

		return $queues;
	}

	public function getDialplan() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\ShowDialPlanAction();
			$response = $this->ami->send($msg);
			$responseEvents = $response->getEvents();
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$contexts = [];
		foreach ($responseEvents as $event) {
			$keys = $event->getKeys();

			if ('ListDialplan' != $keys['event'])
				continue;

			$contexts[] = $keys;
		}

		return $contexts;
	}

	public function getRegistrations() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\SIPShowRegistryAction();
			$response = $this->ami->send($msg);
			$responseEvents = $response->getEvents();
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$registrations = [];
		foreach ($responseEvents as $event) {
			$keys = $event->getKeys();

			if ('RegistryEntry' != $keys['event'])
				continue;

			$registrations[] = $keys;
		}

		return $registrations;
	}

	public function sipPruneRealtimeAll() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\CommandAction('sip prune realtime all');
			$response = $this->ami->send($msg);
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$keys = $response->getKeys();

		return [
			'result' => 'success' == strtolower($keys['response']),
			'message' => $keys['output']
		];
	}

	public function sipPruneRealtimePeer($peername) {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\CommandAction("sip prune realtime peer $peername");
			$response = $this->ami->send($msg);
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$keys = $response->getKeys();

		return [
			'result' => 'success' == strtolower($keys['response']),
			'message' => $keys['output']
		];
	}

	public function sipReload() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\CommandAction('sip reload');
			$response = $this->ami->send($msg);
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$keys = $response->getKeys();

		return [
			'result' => 'success' == strtolower($keys['response']),
			'message' => $keys['output']
		];
	}

	public function dialplanReload() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\CommandAction('dialplan reload');
			$response = $this->ami->send($msg);
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$keys = $response->getKeys();

		return [
			'result' => 'success' == strtolower($keys['response']),
			'message' => $keys['output']
		];
	}

	public function queueReloadAll() {
		try {
			$this->open();
		} catch (\Exception $e) {
			return null;
		}

		try {
			$msg = new \PAMI\Message\Action\CommandAction('queue reload all');
			$response = $this->ami->send($msg);
		} catch (\Exception $e) {
			$this->close();
			throw $e;
		}

		$this->close();

		//
		$keys = $response->getKeys();

		return [
			'result' => 'success' == strtolower($keys['response']),
			'message' => $keys['output']
		];
	}
}
