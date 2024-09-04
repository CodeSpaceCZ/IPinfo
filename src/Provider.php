<?php namespace CodeSpace\WhoisParser;

enum Provider: string {

	const APNIC_WHOIS = "whois.apnic.net";
	const RIPE_WHOIS = "whois.ripe.net";

	case APNIC = self::APNIC_WHOIS;
	case RIPE = self::RIPE_WHOIS;

}
