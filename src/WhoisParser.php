<?php namespace CodeSpace\WhoisParser;

class WhoisParser {

	public array $lines = [];
	public string $provider;

	public static function fromArray(array $lines, string $provider) {
		$parser = new self();
		$parser->lines = $lines;
		$parser->provider = $provider;
		return $parser;
	}

	public static function fromString(string $whois, string $provider) {
		return self::fromArray(explode("\n", $whois), $provider);
	}

	private function getOffset(array $arr, int $index): mixed {
		return count($arr) > $index ? $arr[$index] : null;
	}

	function workWithKeys(array $keys, $fn): array {
		$num = 0;
		$arr = [];
		foreach ($this->lines as &$line) {
			foreach ($keys as &$key) {
				if (str_starts_with($line, "$key: ")) {
					array_push($arr, $fn($line, $num));
				}
			}
			$num++;
		}
		return $arr;
	}

	function getKeyValues(string $key): ?array {
		return $this->getKeysValues([$key]);
	}

	function getKeysValues(array $keys): ?array {
		return $this->workWithKeys($keys, function($line, $num) {
			$start = strpos($line, ": ") + 2;
			return trim(mb_substr($line, $start));
		});
	}

	function getKeyValue(string $key, int $offset = 0): ?string {
		return $this->getKeysValue([$key], $offset);
	}

	function getKeysValue(array $keys, int $offset = 0): ?string {
		$arr = $this->workWithKeys($keys, function($line, $num) {
			$start = strpos($line, ": ") + 2;
			return trim(substr($line, $start));
		});
		return $this->getOffset($arr, $offset);
	}

	function findKey(string $key, int $offset = 0): ?int {
		return $this->findFirstKey([$key], $offset);
	}

	function findFirstKey(array $keys, int $offset = 0): ?int {
		$arr = $this->workWithKeys($keys, function($line, $num) {
			return $num;
		});
		return $this->getOffset($arr, $offset);
	}

	function getSection(int $line) {
		$start = $end = $line;
		$line = null;
		while ($line !== "") {
			$start--;
			$line = $this->lines[$start];
		}
		$line_count = count($this->lines) - 1;
		$line = null;
		while ($line !== "" && $end < $line_count) {
			$end++;
			$line = $this->lines[$end];
		}
		return $this->getSubset($start, $end);
	}

	function getSubset(int $start, int $end) {
		return self::fromArray(array_slice($this->lines, $start, $end-$start), $this->provider);
	}

	function getSectionWithKey(string $key) {
		return $this->getSectionWithKeys([$key]);
	}

	function getSectionWithKeys(array $keys): ?WhoisParser {
		$line = $this->findFirstKey($keys);
		if ($line == null) return null;
		return $this->getSection($line);
	}

}
