<?php

class Config extends Sample{
	public $site_name     = "Lập trình";
	public $author        = "Phuc Tran Hoang";
	public $post_per_page = 10;
	public $post_dir      = ROOT."/posts";
	public $permalink     = "/{YEAR}/{MONTH}/{DAY}/{TITLE}/";
	public $theme         = 'default';
	public $ga_id         = '';
	public $fb_app_id     = '';
	public $debug         = true;
}