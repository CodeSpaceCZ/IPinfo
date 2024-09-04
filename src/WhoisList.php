<?php namespace CodeSpace\WhoisParser;

class WhoisList implements ProviderInterface {

	private array $responses = [];

	private array $providers = [];

	public function __construct(?WhoisParser $response = null) {
		$this->addResponse($response);
	}

	public function addResponse(?WhoisParser $response) {
		if (!$response) return;
		$provider = $response->getProvider();
		if (!$provider) return;
		$this->responses[] = $response;
		$this->providers[] = $provider;
	}

	private function providerIter(string $fn) {
		foreach ($this->providers as $provider) {
			$res = $provider->$fn();
			if ($res) return $res;
		}
		return null;
	}

	public function parseAsn(): ?array {
		return $this->providerIter("parseAsn");
	}

	public function parseAbuse(): ?array {
		return $this->providerIter("parseAbuse");
	}

	public function parseOrg(): ?array {
		return $this->providerIter("parseOrg");
	}

	public function parseRoute(): ?array {
		return $this->providerIter("parseRoute");
	}

	public function parseInet(): ?array {
		return $this->providerIter("parseInet");
	}

	public function findAsn(): ?string {
		return $this->providerIter("findAsn");
	}

	public function findAbuseContact(): ?string {
		return $this->providerIter("findAbuseContact");
	}

}
