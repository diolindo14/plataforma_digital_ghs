<?php
$lines = file('app/views/estudante/dashboard.php');
$stack = [];
$tagRegex = '/<(div|main|nav|ul|header|footer|section)(?:\s+[^>]*)?>|<\/(div|main|nav|ul|header|footer|section)>/i';

foreach ($lines as $i => $line) {
    preg_match_all($tagRegex, $line, $matches, PREG_SET_ORDER);
    foreach ($matches as $m) {
        $tag = strtolower($m[1] ?: $m[2]);
        $isClose = strpos($m[0], '</') === 0;
        
        if (!$isClose) {
            $stack[] = ['tag' => $tag, 'line' => $i + 1];
        } else {
            if (empty($stack)) {
                echo "EXTRA CLOSING TAG: </$tag> on line " . ($i + 1) . "\n";
            } else {
                $last = array_pop($stack);
                if ($last['tag'] !== $tag) {
                    echo "MISMATCH: <" . $last['tag'] . "> from line " . $last['line'] . " closed by </$tag> on line " . ($i + 1) . "\n";
                }
            }
        }
    }
}

foreach ($stack as $s) {
    echo "UNCLOSED TAG: <" . $s['tag'] . "> from line " . $s['line'] . "\n";
}
