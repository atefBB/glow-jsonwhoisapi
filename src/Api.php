<?php

namespace Glow\JSONWhois;

use Glow\Guzzler\Guzzler;

class Api {
	protected $guzzler = null;
	protected $customerId = null;
	protected $apiKey = null;
	protected $domain = null;

	public function getGuzzler() {
		return $this->guzzler;
	}

	public function setGuzzler($client) {
		$this->guzzler = $client;

		return $this;
	}

	public function getCustomerId() {
		return $this->customerId;
	}

	public function setCustomerId($id) {
		if (!is_numeric($id)) {
			throw new \ErrorException('The first parameter [id] must be of numeric value!');
		}

		$this->customerId = $id;

		return $this;
	}

	public function getApiKey() {
		return $this->apiKey;
	}

	public function setApiKey($key) {
		if (!is_string($key)) {
			throw new \ErrorException('The first parameter [key] of method setApiKey must be of data type string.');
		}

		$this->apiKey = $key;

		return $this;
	}

	public function getDomain() {
		return $this->domain;
	}

	public function setDomain($domain) {
		if (!is_string($domain)) {
			throw new \ErrorException('The first parameter [domain] of method setDomain must be of data type string.');
		}

		$this->domain = $domain;

		return $this;
	}

	public function query() {
		if (is_null($this->getGuzzler())) {
			$this->setGuzzler(new Guzzler());
		}

		if (is_null($this->getCustomerId())) {
			throw new \ErrorException('The customerId must be set!');
		}

		if (is_null($this->getApiKey())) {
			throw new \ErrorException('The api key must be set!');
		}

		if (is_null($this->getDomain())) {
			throw new \ErrorException('The domain must be specified!');
		}

		$this->guzzler->setUsername($this->getCustomerId());
		$this->guzzler->setPassword($this->getApiKey());
		$this->guzzler->setUrl('http://jsonwhoisapi.com/api/v1/whois?identifier='.$this->getdomain());

		$this->guzzler->get();

		if ($this->guzzler->hasErrors()===true) {
			throw $this->guzzler->getGuzzleException();
		}

		if ($this->guzzler->getStatusCode()!=200) {
			throw new \ErrorException('The api returned a non 200 status code!');
		}

		return json_decode($this->guzzler->getResponseContents(),true);
	}
}