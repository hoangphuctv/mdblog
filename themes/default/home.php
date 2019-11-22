<!DOCTYPE html>
<html>
	<?php include __DIR__ . '/parts/head.php'; ?>
	<body>
		<?php include __DIR__ . '/parts/header.php'; ?>
		<div class="container">
			<?php include __DIR__ . '/parts/list.php'; ?>

			<?php if ($page > 1) { ?>
				<a class="btn btn-primary" href="<?= get_page_link($page-1)?>"> &lt; Prev page</a>
			<?php } ?>
			<?php if ($page < $total_page) {
				if ($page > 1) {
					echo "|";
				} ?>
				<a class="btn btn-primary" href="<?= get_page_link($page+1)?>">  Next page &gt;</a>
			<?php } ?>
			<?php include __DIR__ . '/parts/footer.php'; ?>
		</div>

	<?php include __DIR__ . '/parts/foot.php'; ?>
	</body>
</html>
