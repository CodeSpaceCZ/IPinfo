<?php namespace CodeSpace\IpInfo;

class WhoisList implements ProviderInterface {

	private array $providers = [];

	public function __construct(?ProviderInterface $provider = null) {
		$this->addProvider($provider);
	}

	public function addProvider(?ProviderInterface $provider) {
		if (!$provider) return;
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

	public function findRedirect(): ?string {
		return $this->providerIter("findRedirect");
	}

}
