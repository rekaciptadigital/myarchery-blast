<?php
// Test wa_generate_instance_id function

// Mock functions
function get_option($key, $default = '') {
    return $default;  // Return empty to test fallback
}

// Load helper
require_once 'inc/core/Whatsapp/Helpers/Whatsapp_helper.php';

echo "Testing improved wa_generate_instance_id() with fallback:\n\n";

// Test 1: Generate 5 IDs
for ($i = 1; $i <= 5; $i++) {
    $id = wa_generate_instance_id();
    echo "Instance ID #$i: $id (length: " . strlen($id) . ")\n";
    usleep(10000); // 10ms delay
}

// Test 2: Uniqueness test
echo "\nUniqueness test (100 IDs):\n";
$ids = [];
for ($i = 1; $i <= 100; $i++) {
    $ids[] = wa_generate_instance_id();
    usleep(1000);
}
$unique = count(array_unique($ids));
echo "Unique: $unique / 100\n";
if ($unique === 100) {
    echo "✅ SUCCESS: All IDs are unique!\n";
} else {
    $duplicates = 100 - $unique;
    echo "❌ FAIL: Found $duplicates duplicates\n";
}
