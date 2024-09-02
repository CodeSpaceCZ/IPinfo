<?php namespace CodeSpace\WhoisParser;

interface ParserInterface {

	public function parseAsn(WhoisParser $whois): ?array;

	public function parseAbuse(WhoisParser $whois): ?array;

	public function parseOrg(WhoisParser $whois): ?array;

	public function parseRoute(WhoisParser $whois): ?array;

	public function parseInet(WhoisParser $whois): ?array;

	public function findAsn(WhoisParser $whois): ?string;

	public function findAbuseContact(WhoisParser $whois): ?string;

}
