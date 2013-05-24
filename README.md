## iLyricsCloud - lyrics and artworks fetcher

lyraox, leMusic

iLyricsCloud is a web service to find lyrics and artworks for a given song from various sites.

Source code: https://bitbucket.org/ethanliu/ilyrics-cloud

### Dependencies

jQuery

Bootstrap

phpQuery

### Requires

Web server: Apache, lighttpd, nginx

Database: Mysql, Postgres, SQLite

### Installation

1. Download and unzip source
2. Create a database on your web server, skip if use SQLite.
3. Rename or copy config.sample.php to config.php
4.




your secret key password.
Upload the WordPress files in the desired location on your web server:

    If you want to integrate WordPress into the root of your domain (e.g. http://example.com/), move or upload all contents of the unzipped WordPress directory (but excluding the directory itself) into the root directory of your web server.
    If you want to have your WordPress installation in its own subdirectory on your web site (e.g. http://example.com/blog/), create the blog directory on your server and upload WordPress to the directory via FTP.
    Note: If your FTP client has an option to convert file names to lower case, make sure it's disabled.

Run the WordPress installation script by accessing wp-admin/install.php in a web browser.

    If you installed WordPress in the root directory, you should visit: http://example.com/wp-admin/install.php
    If you installed WordPress in its own subdirectory called blog, for example, you should visit: http://example.com/blog/wp-admin/install.php


Due to the PDO limited, the folder which the cache.db file stored must have write permission.

## Plugins

google
jpopasia
kkbox
lyricswiki
mojim
utamap


## Plugin guildline

Only two function would be call from iLyrics Class, with special function name.

{pluginName}_lyrics_hook
{pluginName}_artwork_hook


## Demo
check demo.html for live demo


