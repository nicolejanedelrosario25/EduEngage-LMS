<?php
// Bcrypt is one-way — you cannot decrypt the hash, only verify a guess against it.
// This script tries a list of candidate passwords and reports the match (if any).

$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

$candidates = [
    'rasmuslerdorf',
    'password',
    'secret',
    '123456',
    'admin',
    'eduengage',
    'student',
];

echo "<pre>";
echo "Hash: " . $hash . "\n\n";

$found = null;

foreach ($candidates as $guess) {
    if (password_verify($guess, $hash)) {
        $found = $guess;
        echo "MATCH: '" . $guess . "'\n";
        break;
    } else {
        echo "no match: '" . $guess . "'\n";
    }
}

echo "\n";

if ($found) {
    echo "Result: the plaintext password is '" . $found . "'.\n";
} else {
    echo "Result: no candidate matched. Add more guesses to \$candidates.\n";
}

echo "</pre>";
?>
