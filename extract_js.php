<?php
$content = file_get_contents('C:/xampp/htdocs/green/app/views/admin/dashboard.php');
preg_match_all('/<script(?![^>]*src)[^>]*>(.*?)<\/script>/s', $content, $m);
foreach($m[1] as $i => $js) {
    $js = preg_replace('/<\?(?:php|=).*?\?>/s', '"PHP_VAL"', $js);
    file_put_contents('C:/xampp/htdocs/green/blk_'.$i.'.js', $js);
}
echo count($m[1]).' blocks written'.PHP_EOL;
