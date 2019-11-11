<h2><a class="navbar-brand mr-auto mr-lg-0" href="/"><?php echo $config->site_name ?></a></h2>

<form class="form-inline my-2 my-lg-0" id="frmsearch" action="/">
    <input class="form-control mr-sm-2" name="q" type="text" placeholder="Search" aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
  </form>
<script>
	$(function(){
		$('#frmsearch').submit(function(){
			location.href='https://google.com/search?q='+ document.all('q').value + " sitename:" + location.hostname; return false;
		});
	})
</script>

<p class="separator"></p>