<?php namespace CodeSpace\IpInfo\Provider;

class LacNicProvider extends RipeProvider {

	public function parseAsn(): ?array {
		$as = $this->getAsnSection();
		if (!$as) return null;
		return [
			"asn" => $as->getKeyValue("aut-num"),
			"name" => $as->getKeyValue("owner"),
			"source" => "LACNIC"
		];
	}

	public function parseInet(): ?array {
		$section = $this->getInetSection();
		if (!$section) return null;
		return [
			"range" => $section->getKeysValue(["inetnum", "inet6num"]),
			"name" => $section->getKeyValue("owner"),
			"description" => null,
			"country" => $section->getKeyValue("country")
		];
	}

	public function parseRoute(): ?array {
		$section = $this->getInetSection();
		if (!$section) return null;
		return [
			"range" => $section->getKeysValue(["inetnum", "inet6num"]),
			"description" => null,
			"asn" => $section->getKeyValue("aut-num")
		];
	}

	public function getAbuseSection() {
		return $this->whois->getSectionWithKey("e-mail");
	}

	public function parseAbuse(): ?array {
		$org = $this->getAbuseSection();
		if (!$org) return null;
		return [
			"name" => $org->getKeyValue("person"),
			"address" => $org->getKeyValues("address"),
			"phone" => $org->getKeyValue("phone"),
			"fax" => $org->getKeyValue("fax-no"),
			"email" => $org->getKeyValue("e-mail")
		];
	}

}
