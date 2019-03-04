<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand mr-auto mr-lg-0" href="/"><?php echo $config->site_name ?></a>
  <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
    </ul>
    <form class="form-inline my-2 my-lg-0" id="frmsearch" action="/">
      <input class="form-control mr-sm-2" name="q" type="text" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<script>
	$(function(){
		$('#frmsearch').submit(function(){
			location.href='https://google.com/search?q='+ document.all('q').value + " sitename:" + location.hostname; return false;
		});
	})
</script>

<p class="separator"></p>