<?php namespace CodeSpace\IpInfo;

enum Provider: string {

	const RIPE_WHOIS = "whois.ripe.net";
	const APNIC_WHOIS = "whois.apnic.net";
	const AFRINIC_WHOIS = "whois.afrinic.net";
	const LACNIC_WHOIS = "whois.lacnic.net";
	const ARIN_WHOIS = "whois.arin.net";

	case RIPE = self::RIPE_WHOIS;
	case APNIC = self::APNIC_WHOIS;
	case AFRINIC = self::AFRINIC_WHOIS;
	case LACNIC = self::LACNIC_WHOIS;
	case ARIN = self::ARIN_WHOIS;

}
