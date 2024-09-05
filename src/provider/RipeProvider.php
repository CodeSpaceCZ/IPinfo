<?php namespace CodeSpace\IpInfo\Provider;

use CodeSpace\IpInfo\WhoisParser;

class RipeProvider extends AbstractProvider {

	public function getAsnSection(): ?WhoisParser {
		return $this->whois->getSectionWithKey("aut-num");
	}

	public function parseAsn(): ?array {
		$as = $this->getAsnSection();
		if (!$as) return null;
		return [
			"asn" => $as->getKeyValue("aut-num"),
			"name" => $as->getKeyValue("as-name"),
			"source" => $as->getKeyValue("source")
		];
	}

	public function getAbuseSection() {
		return $this->whois->getSectionWithKey("abuse-mailbox");
	}

	public function parseAbuse(): ?array {
		$abuse = $this->getAbuseSection();
		if (!$abuse) return null;
		return [
			"name" => $abuse->getKeyValue("role"),
			"address" => $abuse->getKeyValues("address"),
			"phone" => $abuse->getKeyValue("phone"),
			"fax" => $abuse->getKeyValue("fax-no"),
			"email" => $abuse->getKeyValue("abuse-mailbox")
		];
	}

	public function getOrgSection() {
		return $this->whois->getSectionWithKey("organisation");
	}

	public function parseOrg(): ?array {
		$org = $this->getOrgSection();
		if (!$org) return null;
		return [
			"name" => $org->getKeyValue("org-name"),
			"address" => $org->getKeyValues("address"),
			"phone" => $org->getKeyValue("phone"),
			"fax" => $org->getKeyValue("fax-no"),
			"email" => $org->getKeyValue("e-mail")
		];
	}

	public function getRouteSection() {
		return $this->whois->getSectionWithKeys(["route", "route6"]);
	}

	public function parseRoute(): ?array {
		$section = $this->getRouteSection();
		if (!$section) return null;
		return [
			"range" => $section->getKeysValue(["route", "route6"]),
			"description" => $section->getKeyValue("descr"),
			"asn" => $section->getKeyValue("origin")
		];
	}

	public function getInetSection() {
		return $this->whois->getSectionWithKeys(["inetnum", "inet6num"]);
	}

	public function parseInet(): ?array {
		$section = $this->getInetSection();
		if (!$section) return null;
		return [
			"range" => $section->getKeysValue(["inetnum", "inet6num"]),
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
