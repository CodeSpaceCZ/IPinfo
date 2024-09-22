<?php namespace CodeSpace\IpInfo\Provider;

use CodeSpace\IpInfo\WhoisParser;

class ArinProvider extends AbstractProvider {

	public function getAsnSection(): ?WhoisParser {
		return $this->whois->getSectionWithKey("ASName");
	}

	public function parseAsn(): ?array {
		$as = $this->getAsnSection();
		if (!$as) return null;
		return [
			"asn" => $as->getKeyValue("ASHandle"),
			"name" => $as->getKeyValue("ASName"),
			"source" => "ARIN"
		];
	}

	public function getAbuseSection() {
		return $this->whois->getSectionWithKey("OrgAbuseEmail");
	}

	public function parseAbuse(): ?array {
		$abuse = $this->getAbuseSection();
		if (!$abuse) return null;
		return [
			"name" => $abuse->getKeyValue("OrgAbuseName"),
			"address" => $abuse->getKeyValues("OrgAbuseAddress"),
			"phone" => $abuse->getKeyValue("OrgAbusePhone"),
			"fax" => $abuse->getKeyValue("OrgAbuseFax"),
			"email" => $abuse->getKeyValue("OrgAbuseEmail")
		];
	}

	public function getOrgSection() {
		return $this->whois->getSectionWithKey("OrgName");
	}

	public function parseOrg(): ?array {
		$org = $this->getOrgSection();
		if (!$org) return null;
		return [
			"name" => $org->getKeyValue("OrgName"),
			"address" => $this->parseAddress($org),
			"phone" => $org->getKeyValue("OrgPhone"),
			"fax" => $org->getKeyValue("OrgFax"),
			"email" => $org->getKeyValue("OrgEmail")
		];
	}

	public function getRouteSection() {
		return $this->whois->getSectionWithKeys(["NetRange", "CIDR"]);
	}

	public function parseRoute(): ?array {
		$section = $this->getRouteSection();
		if (!$section) return null;
		return [
			"range" => $section->getKeysValue(["NetRange", "CIDR"]),
			"description" => $section->getKeyValue("NetName"),
			"asn" => $section->getKeyValue("OriginAS")
		];
	}

	public function getInetSection() {
		return $this->whois->getSectionWithKeys(["NetHandle", "NetRange"]);
	}

	public function parseInet(): ?array {
		$section = $this->getInetSection();
		if (!$section) return null;
		return [
			"range" => $section->getKeysValue(["NetRange"]),
			"name" => $section->getKeyValue("NetName"),
			"description" => $section->getKeyValue("Comment"),
			"country" => $this->whois->getKeyValue("Country")
		];
	}

	public function findAsn(): ?string {
		return $this->whois->getKeyValue("OriginAS");
	}

	public function findAbuseContact(): ?string {
		return $this->whois->getKeyValue("OrgAbuseEmail");
	}

	public function findRedirect(): ?string {
		return $this->whois->getKeyValue("ResourceLink", 1);
	}

	private function parseAddress(WhoisParser $whois): ?array {
		$arr = $whois->getKeyValues("Address");
		if (!$arr) return null;
		foreach (["City", "StateProv", "PostalCode", "Country"] as $key) {
			if ($value = $whois->getKeyValue($key)) $arr[] = $value;
		}
		return $arr;
	}

}
