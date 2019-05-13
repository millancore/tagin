Tagin
=====
Tagin is a fork of [Xhgui](https://github.com/perftools/xhgui) project, rewritten exclusively for php 7^, 
small improvements such as dark theme and a simplified interface have been added.

This tool requires that [Tideways](https://github.com/tideways/php-profiler-extension) are installed. Tideway is a PHP Extension that records and provides profiling data. Tagin (this tool) takes that information, saves it in MongoDB, and provides a convenient GUI for working with it.

System Requirements
===================

 * PHP version 7 or later.
 * [MongoDB Extension](http://pecl.php.net/package/mongodb) MongoDB PHP driver.
 * [MongoDB](http://www.mongodb.org/) MongoDB Itself.
 * [Tideways](https://github.com/tideways/php-profiler-extension) to actually profile the data.
 
Install
=======

## Install Tideway

Clone from Github repository

    git clone https://github.com/tideways/php-xhprof-extension


Build tideways extension 
    
    phpize
    ./configure
    make
    sudo make install

Configure the extension to load with this PHP INI directive:

    extension=tideways_xhprof.so

Restart Apache or PHP-FPM.

## Install Tagin 

1. Clone or download this repository.

2. Set the permissions on the `cache` and `data` directories to allow the
   webserver to create files. If you're lazy, `0777` will work.

4. Start a MongoDB instance. Tagin uses the MongoDB instance to store
   profiling data.

5. You can check the configuration file `config/config.php`, but this one already comes with the necessary configurations.

7. Run `composer install` to install dependencies.

8. Run `composer start` and enjoy to profiling on http://localhost:8080.

How to collect profile data
===========================

The simplest way to profile an application is to use `external/header.php` is designed to be combined with PHP's
[auto_prepend_file](https://www.php.net/manual/en/ini.core.php#ini.auto-prepend-file) directive. You can enable `auto_prepend_file` system-wide
through `php.ini`. Alternatively, you can enable `auto_prepend_file` per virtual
host.

With apache this would look like:

```apache
<VirtualHost *:80>
  php_admin_value auto_prepend_file "/path/to/tagin/external/header.php"
  DocumentRoot "/var/www/awesome-thing/app/webroot/"
  ServerName site.localhost
</VirtualHost>
```
With Nginx in fastcgi mode you could use:

```nginx
server {
  listen 80;
  server_name site.localhost;
  root /var/www/awesome-thing/app/webroot/;
  fastcgi_param PHP_VALUE "auto_prepend_file=/path/to/tagin/external/header.php";
}
```

### Profile a CLI Script

The simplest way to profile a CLI is to use `external/header.php` is designed to be combined with PHP's
[auto_prepend_file](https://www.php.net/manual/en/ini.core.php#ini.auto-prepend-file) directive. You can enable `auto_prepend_file` system-wide
through `php.ini`. Alternatively, you can enable include the `header.php` at the
top of your script:

```php
<?php
require '/path/to/tagin/external/header.php';
// Rest of script.
```

You can alternatively use the `-d` flag when running php:

```bash
php -d auto_prepend_file=/path/to/tagin/external/header.php do_work.php
```



License
=======

Tagin Copyright (c) 2019 Millancore

Xhgui Copyright (c) 2013 Mark Story & Paul Reinheimer

Permission is hereby granted, free of charge, to any person obtaining a
copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.