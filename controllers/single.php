<?php

$post = parse_post($mdfile);

$parsedown = new Parsedown();
$post['content'] = $parsedown->text($post['content']);

$posts = get_next_posts($md_origin, 2);

$ps = [];
foreach($posts as $p) {
	$ps[] = (object) parse_post($p);
}
$posts = $ps;

include VIEW."/single.php";
