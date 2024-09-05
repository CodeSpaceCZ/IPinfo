<?php namespace CodeSpace\IpInfo\Provider;

use CodeSpace\IpInfo\ProviderInterface;
use CodeSpace\IpInfo\WhoisParser;

abstract class AbstractProvider implements ProviderInterface {

	protected $whois;

	public function __construct(WhoisParser $whois) {
		$this->whois = $whois;
	}

	public function findRedirect(): ?string {
		return null;
	}

}
