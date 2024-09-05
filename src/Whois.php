<?php namespace CodeSpace\IpInfo;

class Whois {

	public function getIpAddress(string $ip): array {
		$whois = WhoisClient::query($ip);
		$list = new WhoisList($whois);
		$this->addAsn($list);
		$this->addAbuse($list);
		return [
			"network" => $list->parseInet(),
			"route" => $list->parseRoute(),
			"asn" => $list->parseAsn(),
			"org" => $list->parseOrg(),
			"abuse" => $list->parseAbuse()
		];
	}

	private function addAsn(WhoisList $list) {
		$asn = $list->findAsn();
		if (!$asn) return;
		$asn_whois = WhoisClient::query($asn);
		$list->addProvider($asn_whois);
	}

	private function addAbuse(WhoisList $list) {
		$abuse_c = $list->findAbuseContact();
		if (!$abuse_c) return;
		$abuse_whois = WhoisClient::query($abuse_c);
		$list->addProvider($abuse_whois);
	}

}
