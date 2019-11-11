<!DOCTYPE html>
<html>
	<?php include __DIR__ . '/parts/head.php'; ?>
	<body>
		<?php include __DIR__ . '/parts/header.php'; ?>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/3.0.1/github-markdown.min.css">
		<div class="container">
			<div class="markdown-body">
				<h1 class="page-title"><?php echo $post->title ?></h1>
				<?php if (isset($post)) { echo $post->content; } ?>
			</div>
			<?php include __DIR__ . '/parts/fb-comments.php'; ?>
		</div>
		<hr>
		<div class="container">
			<?php $sub_title = 'Các bài viết khác'; ?>
			<?php include __DIR__ . '/parts/list.php'; ?>
			<?php include __DIR__ . '/parts/footer.php'; ?>
		</div>

		<?php include __DIR__ . '/parts/foot.php'; ?>
	</body>
</html>
