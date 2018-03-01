#Overview
This is simple CLI app to get beers from brewerydb. App can be extendable by creating new Class on app folder and implements Export interface.
Add exported data will be stored at `data` folder. 
I cannot provide Docker container because I didn't work with Docker.

## Install steps

1. run `composer install`
2. provide `apiKey` in `config/main.php` (apiKey you can get from [here](http://www.brewerydb.com/developers/apps))
2. run  `chmod 777 data` to set write permissions

### Run
You can simply run app from CLI  

```php index.php```

app support following options:

-  `-l` or `--limit` - limit of beers to get. Max value is 50 (see todo)
-  `-f` or `--formal` - format to export data. Supported formats: `json`, `html`, `xml`;


### \\\TODO
- Pagination - now max limit is 50. To use more need to do pagination.


