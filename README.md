# IP Info

**IP Info** is a PHP library that parses WHOIS responses from all Regional Internet Registries (RIRs) to provide detailed information about networks, routes, ASNs (Autonomous System Numbers), organizations, and abuse contact details. This tool is essential for developers, network engineers, and security professionals who need precise IP information for various network analysis and management tasks.


## Features

- **Supports all RIRs**: Compatible with WHOIS responses from all major RIRs (ARIN, RIPE, APNIC, LACNIC, and AFRINIC).
- **Comprehensive Data Parsing**: Extracts details about the network, route, ASN, organization, and abuse contact information.
- **Structured Output**: Provides a structured JSON response, making it easy to work with and integrate into other applications.
- **Easy Integration**: Available as a Composer package for quick and easy integration into PHP projects.


## Installation

Install the package via Composer:

```sh
composer require codespace/ip-info
```


## Usage

Here's a basic example of how to use IP Info in your project:

```php
<?php

require 'vendor/autoload.php';

use CodeSpace\IpInfo\IpInfo;

// Initialize IP Info
$ipInfo = new IpInfo();

// Parse WHOIS data
$response = $ipInfo->getIpInfo('2a03:3b40::');

// Display the parsed data
print_r(json_encode($response));
```


## Sample Response

Here's an example of the JSON response provided by IP Info:

```json
{
  "network": {
    "range": "2a03:3b40::/40",
    "name": "VPSFREE-IPV6-PRG2",
    "description": "vpsFree.cz - IPv6 - Prague 2",
    "country": "CZ"
  },
  "route": {
    "range": "2a03:3b40::/40",
    "description": "VPSFREE-IPV6-PRG2",
    "asn": "AS24971"
  },
  "asn": {
    "asn": "AS24971",
    "name": "MASTER-AS",
    "source": "RIPE"
  },
  "org": {
    "name": "Master Internet s.r.o.",
    "address": [
      "Jiraskova 21",
      "602 00",
      "Brno",
      "CZECH REPUBLIC"
    ],
    "phone": "+420 777919700",
    "fax": null,
    "email": "lir-req@master.cz"
  },
  "abuse": {
    "name": "Master Internet contact",
    "address": [
      "Master Internet s.r.o",
      "Jiraskova 21",
      "602 00 Brno",
      "Czech Republic"
    ],
    "phone": "+420777919484",
    "fax": null,
    "email": "abuse@master.cz"
  }
}
```


## License

This project is licensed under the LGPLv3 License.


## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue to discuss improvements or report bugs.
