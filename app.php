<?php

require_once __DIR__ . '/vendor/autoload.php';

use a3330\pro_php_v2\src\Article;
use a3330\pro_php_v2\src\Comment;
use a3330\pro_php_v2\src\User;

$post = new Article(1, 1, 'текст', 'текст');
$user = new User(1, 'first', 'last');
$comment = new Comment(1, 1, 1, 'text text');

print_r($post);
print_r($user);
print_r($comment);



