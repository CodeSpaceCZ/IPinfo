<?php namespace CodeSpace\IpInfo;

use CodeSpace\IpInfo\Provider;
use MallardDuck\Whois\Client;

class WhoisClient {

	const B_FLAG_SERVERS = ["whois.ripe.net"];

	public static function query(string $q): ?ProviderInterface {
		$server = self::getServer($q);
		$provider = null;
		while ($server) {
			$response = self::queryServer($q, $server);
			$provider = self::getProvider($response, Provider::tryFrom($server));
			$server = $provider->findRedirect();
		}
		return $provider;
	}

	private static function getProvider(WhoisParser $parser, Provider $provider) {
		return match ($provider) {
			Provider::RIPE => new Provider\RipeProvider($parser),
			Provider::APNIC => new Provider\ApNicProvider($parser),
			Provider::AFRINIC => new Provider\AfriNicProvider($parser),
			Provider::LACNIC => new Provider\LacNicProvider($parser),
			Provider::ARIN => new Provider\ArinProvider($parser)
		};
	}

	private static function queryServer(string $q, string $server): WhoisParser {
		if (in_array($server, self::B_FLAG_SERVERS)) $q = "$q -B";
		$client = new Client($server);
		$response = $client->makeRequest($q);
		return WhoisParser::fromString($response);
	}

	private static function getServer(string $q): ?string {
		$server = self::getServerBySuffix($q);
		if ($server) return $server;
		if (self::isValidIpv4($q) || self::isValidIpv6($q) || self::isValidAsn($q)) {
			$res = self::queryServer($q, "whois.iana.org");
			return $res->getKeysValue(["refer", "whois"]);
		}
		return null;
	}

	private static function isValidIpv4(string $ip) {
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}

	private static function isValidIpv6(string $ip) {
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
	}

	private static function isValidAsn(string $asn) {
		return preg_match('/^AS\d+$/i', $asn);
	}

	private static function getServerBySuffix(string $q) {
		$suffixToProvider = [
			'-RIPE' => Provider::RIPE_WHOIS,
			'-AP' => Provider::APNIC_WHOIS,
			'-ARIN' => Provider::ARIN_WHOIS,
			'-LACNIC' => Provider::LACNIC_WHOIS,
			'-AFRINIC' => Provider::AFRINIC_WHOIS,
		];
		foreach ($suffixToProvider as $suffix => $provider) {
			if (str_ends_with($q, $suffix)) return $provider;
		}
		return null;
	}

}
