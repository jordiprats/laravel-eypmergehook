# eypmergehook

## input data

## notes

```
[:error] [pid 11933] [client 91.121.142.67:37256] PHP Warning:  require(/home/jprats/git/laravel-mergehook/eypmergehook/public/../vendor/autoload.php): failed to open stream: No such file or directory in /home/jprats/git/laravel-mergehook/eypmergehook/public/index.php on line 24
[:error] [pid 11933] [client 91.121.142.67:37256] PHP Fatal error:  require(): Failed opening required '/home/jprats/git/laravel-mergehook/eypmergehook/public/../vendor/autoload.php' (include_path='.:/usr/share/php') in /home/jprats/git/laravel-mergehook/eypmergehook/public/index.php on line 24
```

solució:

```
composer install
```
