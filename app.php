<?php

require_once __DIR__ . '/vendor/autoload.php';

use a3330\pro_php_v2\src\Articles;
use a3330\pro_php_v2\src\Comments;
use a3330\pro_php_v2\src\User;

$post = new Articles(1, 1, 'текст', 'текст');
$user = new User(1, 'first', 'last');
$comment = new Comments(1, 1, 1, 'text text');

print_r($post);
print_r($user);
print_r($comment);



