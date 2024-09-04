<?php namespace CodeSpace\WhoisParser;

interface ProviderInterface {

	public function parseAsn(): ?array;

	public function parseAbuse(): ?array;

	public function parseOrg(): ?array;

	public function parseRoute(): ?array;

	public function parseInet(): ?array;

	public function findAsn(): ?string;

	public function findAbuseContact(): ?string;

}
