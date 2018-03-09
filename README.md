# iLyricsCloud - lyrics and artworks fetcher

iLyricsCloud is a web service to find lyrics and artworks for a given song from various sites. Also come with a backend to help you manipulate the database.

iLyricsWidget - a popular dashboard widget for Mac OS X works with iLyricsCloud.   
https://creativecrap.com/ilyrics-widget-itunes


## Requirements

iLyricsCloud requires a web server runs PHP 5 and above, a database or sqlite is also required for storing data.

## Installation

1. Download and unzip source.  
1. Create a database on your server, skip if choose SQLite.  
1. Create a directory "`cache`" if not exists, and make sure the cache directory is writable.  
1. Rename or copy config.sample.php to config.php.  
1. Change `DATABASE_DSN` to matches your choice.  
1. Change `ADMIN_USER` and `ADMIN_PASS` to something more secure.  
1. Open `ilyrics-cloud/admin/` in browser, click install database.  
1. Click Search from navigation, have a search test see if anything works.  

## Plugins

Plugin is the fetcher for lyrics or artworks, it does access data from provider by their api if provided, or grab web page from data source then parse lyrics or artwork from it.

### current available plugins:

`google` - Google images for artwork  
`jpopasia` - Japanese and Korean lyrics  
`kkbox` - Artworks  
`lyricscom` - English lyrics  
`lyricswiki` - English lyrics  
`metrolyrics` - English lyrics  
`mojim` -  Chinese lyrics  
`utamap` - Japanese lyrics  
`yahoojp` - Japanese lyrics  


### Plugin Guideline

Only two function would be called from iLyrics Class, with special function name.

`{pluginName}\_lyrics_hook()`  
return lyrics as string.

`{pluginName}\_artwork_hook()`  
return image url as string.

To active a plugin, after put the file in plugins folder, you must add the plugin to into $plugins from config.php file.


### Usage

To make iLyricsCloud as a service, use `q` for api endpoint, i.e. `http://example.com/ilyrics-cloud/q/`

## License

iLyricsCloud is available under the MIT license. See the LICENSE file for more info.

## Legal

All lyrics and artworks are copyright of their owners.
