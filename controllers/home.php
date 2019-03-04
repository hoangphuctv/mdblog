<?php
$page = isset($_GET['page']) ? $_GET['page'] : 1;
if ($page < 1) { $page = 1;}
$limit  = $config->post_per_page;
$offset = ($page - 1) * $limit;

list($posts, $total) = find_posts($offset, $limit);
$total_page = $total/$limit;
$ps = [];
foreach($posts as $p) {
	$ps[] = (object) parse_post($p);
}
$posts = $ps;

include VIEW."/home.php";

// ---