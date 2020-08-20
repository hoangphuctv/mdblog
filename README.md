# mdblog

## Install
1. Install dependencies
```
$ git clone git@github.com:hoangphuctv/mdblog.git
$ cd mdblog
$ composer install
```

2. Copy config sample
```
$ cp config.json.sample config.json
```

## Build

```
$ ./mdb
```

## Server
Just use for local enviroment
```
$ ./mdb server
```

* Put your post in `mdblog/posts` dir.
* Setup vhost point to `mdblog/public` dir for live mode
* Setup vhost point to `mdblog/public` dir for static mode.

^_^! Enjoy blogging!

## Live demo

https://hoangphuctv.github.io/
