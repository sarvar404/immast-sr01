
<?php
$dir = 'C:/moodledata';

echo "Dataroot exists: " . (is_dir($dir) ? 'YES' : 'NO') . "<br>";
echo "Dataroot writable: " . (is_writable($dir) ? 'YES' : 'NO') . "<br>";

$file = $dir . '/write-test.txt';

$result = @file_put_contents($file, 'Moodle write test');

echo "Write test: " . ($result !== false ? 'PASS' : 'FAIL');
?>
