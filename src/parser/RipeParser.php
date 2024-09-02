<?php namespace CodeSpace\WhoisParser\Parser;

use CodeSpace\WhoisParser\ParserInterface;
use CodeSpace\WhoisParser\WhoisParser;

class RipeParser implements ParserInterface {

	public function parseAsn(WhoisParser $whois): ?array {
		$as = $whois->getSectionWithKey("aut-num");
		return [
			"asn" => $as->getKeyValue("aut-num"),
			"name" => $as->getKeyValue("as-name"),
			"source" => $as->getKeyValue("source")
		];
	}

	public function parseAbuse(WhoisParser $whois): ?array {
		$abuse = $whois->getSectionWithKey("abuse-mailbox");
		return [
			"role" => $abuse->getKeyValue("role"),
			"address" => $abuse->getKeyValues("address"),
			"phone" => $abuse->getKeyValue("phone"),
			"fax" => $abuse->getKeyValue("fax-no"),
			"email" => $abuse->getKeyValue("abuse-mailbox")
		];
	}

	public function parseOrg(WhoisParser $whois): ?array {
		$org = $whois->getSectionWithKey("organisation");
		return [
			"id" => $org->getKeyValue("organisation"),
			"name" => $org->getKeyValue("org-name"),
			"address" => $org->getKeyValues("address"),
			"phone" => $org->getKeyValue("phone"),
			"fax" => $org->getKeyValue("fax-no"),
			"email" => $org->getKeyValue("e-mail")
		];
	}

	public function parseRoute(WhoisParser $whois): ?array {
		$section = $whois->getSectionWithKeys(["route", "route6"]);
		$range = $section->getKeysValue(["route", "route6"]);
		return [
			"range" => $range,
			"description" => $section->getKeyValue("descr"),
			"asn" => $section->getKeyValue("origin")
		];
	}

	public function parseInet(WhoisParser $whois): ?array {
		$section = $whois->getSectionWithKeys(["inetnum", "inet6num"]);
		$range = $section->getKeysValue(["inetnum", "inet6num"]);
		return [
			"range" => $range,
			"name" => $section->getKeyValue("netname"),
			"description" => $section->getKeyValue("descr"),
			"country" => $section->getKeyValue("country")
		];
	}

	public function findAsn(WhoisParser $whois): ?string {
		return $whois->getKeyValue("origin");
	}

	public function findAbuseContact(WhoisParser $whois): ?string {
		return $whois->getKeyValue("abuse-c");
	}

}
