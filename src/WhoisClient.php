<?php namespace CodeSpace\WhoisParser;

use MallardDuck\Whois\Client;

class WhoisClient {

	public static function query(string $q): ?WhoisParser {
		$server = self::getServer($q);
		if (!$server) return null;
		return self::queryServer("$q -B", $server);
	}

	public static function queryServer(string $q, string $server): WhoisParser {
		$client = new Client($server);
		$response = $client->makeRequest($q);
		return WhoisParser::fromString($response, Provider::tryFrom($server));
	}

	public static function getServer(string $q): ?string {
		if (str_ends_with($q, "-RIPE")) return Provider::RIPE_WHOIS;
		if (str_ends_with($q, "-AP")) return Provider::APNIC_WHOIS;
		$res = self::queryServer($q, "whois.iana.org");
		return $res->getKeysValue(["refer", "whois"]);
	}

}
