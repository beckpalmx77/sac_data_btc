<?php
class HashMap {
    private $map;

    public function __construct() {
        $this->map = [];
    }

    public function put($key, $value) {
        $hashCode = $this->hashCode($key);
        $this->map[$hashCode] = $value;
    }

    public function get($key) {
        $hashCode = $this->hashCode($key);
        return isset($this->map[$hashCode]) ? $this->map[$hashCode] : null;
    }

    private function hashCode($key) {
        // Custom hashing algorithm implementation
        $hash = 0;
        for ($i = 0; $i < strlen($key); $i++) {
            $hash += ord($key[$i]);
        }
        return $hash;
    }
}

// Example usage
$map = new HashMap();
$map->put("A", 25);
$map->put("B", 32);
$map->put("C", 45);

echo $map->get("A") . "\n\r";  // Output: 25
echo $map->get("B") . "\n\r";  // Output: 32
echo $map->get("C") . "\n\r"; // Output: null

