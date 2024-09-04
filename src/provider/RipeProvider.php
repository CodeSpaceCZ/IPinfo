<?php namespace CodeSpace\WhoisParser\Provider;

use CodeSpace\WhoisParser\ProviderInterface;
use CodeSpace\WhoisParser\WhoisParser;

class RipeProvider implements ProviderInterface {

	private $whois;

	public function __construct(WhoisParser $whois) {
		$this->whois = $whois;
	}

	public function parseAsn(): ?array {
		$as = $this->whois->getSectionWithKey("aut-num");
		if (!$as) return null;
		return [
			"asn" => $as->getKeyValue("aut-num"),
			"name" => $as->getKeyValue("as-name"),
			"source" => $as->getKeyValue("source")
		];
	}

	public function parseAbuse(): ?array {
		$abuse = $this->whois->getSectionWithKey("abuse-mailbox");
		if (!$abuse) return null;
		return [
			"role" => $abuse->getKeyValue("role"),
			"address" => $abuse->getKeyValues("address"),
			"phone" => $abuse->getKeyValue("phone"),
			"fax" => $abuse->getKeyValue("fax-no"),
			"email" => $abuse->getKeyValue("abuse-mailbox")
		];
	}

	public function parseOrg(): ?array {
		$org = $this->whois->getSectionWithKey("organisation");
		if (!$org) return null;
		return [
			"id" => $org->getKeyValue("organisation"),
			"name" => $org->getKeyValue("org-name"),
			"address" => $org->getKeyValues("address"),
			"phone" => $org->getKeyValue("phone"),
			"fax" => $org->getKeyValue("fax-no"),
			"email" => $org->getKeyValue("e-mail")
		];
	}

	public function parseRoute(): ?array {
		$section = $this->whois->getSectionWithKeys(["route", "route6"]);
		if (!$section) return null;
		$range = $section->getKeysValue(["route", "route6"]);
		return [
			"range" => $range,
			"description" => $section->getKeyValue("descr"),
			"asn" => $section->getKeyValue("origin")
		];
	}

	public function parseInet(): ?array {
		$section = $this->whois->getSectionWithKeys(["inetnum", "inet6num"]);
		if (!$section) return null;
		$range = $section->getKeysValue(["inetnum", "inet6num"]);
		return [
			"range" => $range,
			"name" => $section->getKeyValue("netname"),
			"description" => $section->getKeyValue("descr"),
			"country" => $section->getKeyValue("country")
		];
	}

	public function findAsn(): ?string {
		return $this->whois->getKeyValue("origin");
	}

	public function findAbuseContact(): ?string {
		return $this->whois->getKeyValue("abuse-c");
	}

}
