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

1. Download and unzip source.
- Create a database on your web server, skip if use SQLite.
- Make sure cache folder is writable.
- Rename or copy config.sample.php to config.php.
- Change DATABASE_DSN to matches your choice.
- Change ADMIN_USER and ADMIN_PASS to something more secure.
- Open ilyrics-cloud/admin/ in browser, click install database.
- Click Search from navigation, have a search test see if anything works.

### Plugins

Plugin is the fetcher for lyrics or artworks, it does access data from provider by their api if provided, or grab web page from data source then parse lyrics or artwork from it.

#### current available plugins:

google - Google images for artwork  
jpopasia - Japanese and Korean lyrics  
kkbox - Artworks
lyricscom - English lyrics  
lyricswiki - English lyrics  
metrolyrics - English lyrics  
mojim -  Chinese lyrics  
utamap - Japanese lyrics  
yahoojp - Japanese lyrics


### Plugin guideline

Only two function would be called from iLyrics Class, with special function name.

{pluginName}\_lyrics_hook()  
{pluginName}\_artwork_hook()  

