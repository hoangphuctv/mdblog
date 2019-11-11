<div class="my-3 p-3 bg-white rounded shadow-sm">
	<h6 class="border-bottom border-gray pb-2 mb-0"><?= isset($sub_title) ? $sub_title : "Recent updates" ?></h6>
	<?php if(isset($posts)) foreach($posts as $p){ ?>
	<div class="media text-muted pt-3">
		<p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
		<strong class="d-block text-gray-dark">
			<a href="<?= str_replace('.md', '.html', $p->path) ?>"><?= $p->title ?></a>
		</strong>
		<br>
		<small><?= $p->author ? $config->author : $config->author ?></small>
		<small><?= $p->mod_date != '1970-01-01' ? $p->mod_date : '' ?></small>
		</p>
	</div>
	<?php } ?>
</div>