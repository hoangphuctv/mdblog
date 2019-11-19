<?php

// $post = parse_post($mdfile);

$parsedown = new Parsedown();
$post->content = $parsedown->text($post->content);

$posts = get_next_posts($md_origin, 5);

$ps = [];
foreach($posts as $p) {
	$ps[] = parse_post($p);
}
unset($_post);
$posts = $ps;

include VIEW."/single.php";
