<?php

namespace Glow\JSONWhois;

use Glow\Guzzler\Guzzler;

class Api {
	/**
	 * Guzzler Object
	 * The Glow Guzzler Object
	 *
	 * @access protected
	 * @var null|Glow\Guzzler\Guzzler
	 */
	protected $guzzler = null;

	/**
	 * Customer Id
	 * The customerId assigned by the API service
	 *
	 * @access protected
	 * @var null|integer
	 */
	protected $customerId = null;

	/**
	 * Api Key
	 * The api key assigned by the API service
	 *
	 * @access protected
	 * @var null|string
	 */
	protected $apiKey = null;

	/**
	 * Domain
	 * The domain you wish to query information about
	 *
	 * @access protected
	 * @var null|string
	 */
	protected $domain = null;

	/**
	 * Get Guzzler
	 * Returns the Guzzler object
	 *
	 * @access public
	 * @return null|Glow\Guzzler\Guzzler
	 */
	public function getGuzzler() {
		return $this->guzzler;
	}

	/**
	 * Set Guzzler
	 * Sets the Guzzler Object
	 *
	 * @todo : Verify the is actually the Guzzler 
	 * client
	 *
	 * @access public
	 * @param object $client
	 * @return Glow\JSONWhois\Api
	 */
	public function setGuzzler($client) {
		$this->guzzler = $client;

		return $this;
	}

	/**
	 * Get Customer ID
	 * Returns the customer Id
	 *
	 * @access public
	 * @return null|integer
	 */
	public function getCustomerId() {
		return $this->customerId;
	}

	/**
	 * Set Customer Id
	 * Sets the customer ID
	 *
	 * @access public
	 * @throws \ErrorException - When the id parameter is not numeric
	 * @param integer - $id
	 * @return Glow\JSONWhois\Api
	 */
	public function setCustomerId($id) {
		if (!is_numeric($id)) {
			throw new \ErrorException('The first parameter [id] must be of numeric value!');
		}

		$this->customerId = $id;

		return $this;
	}

	/**
	 * Get Api Key
	 * Returns the api key
	 *
	 * @access public
	 * @return null|string
	 */
	public function getApiKey() {
		return $this->apiKey;
	}

	/**
	 * Set Api Key
	 * Sets the Api key
	 *
	 * @access public
	 * @throws \ErrorException - When the api parameter is not a string.
	 * @param null|string $key
	 * @return Glow\JSONWhois\Api
	 */
	public function setApiKey($key) {
		if (!is_string($key)) {
			throw new \ErrorException('The first parameter [key] of method setApiKey must be of data type string.');
		}

		$this->apiKey = $key;

		return $this;
	}

	/**
	 * Get Domain
	 * Returns the domain name
	 *
	 * @access public
	 * @return null|string
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * Set Domain
	 * Sets the domain to query against
	 *
	 * @access public
	 * @throws \ErrorException - When the domain parameter is not a string.
	 * @param string - $domain
	 * @return Glow\JSONWhois\Api
	 */
	public function setDomain($domain) {
		if (!is_string($domain)) {
			throw new \ErrorException('The first parameter [domain] of method setDomain must be of data type string.');
		}

		$this->domain = $domain;

		return $this;
	}

	/**
	 * Query
	 * Query the whois record from the API
	 *
	 * @access public
	 * @throws \ErrorException when the customerId is not set or is null
	 * @throws \ErrorException when the apiKey is not set or is null
	 * @throws \ErrorException when the domain is not set or is null
	 * @throws \ANYException when Guzzle encounters an exception
	 * @throws \ErrorException when Guzzle does not return a 200
	 * @return array
	 */
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