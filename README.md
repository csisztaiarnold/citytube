![CityTube Logo](citytube_logo.svg) 

A Drupal module for displaying videos from YouTube which are related to a specific area.

## Instructions

- Go to `/admin/config/citytube/config` and fill the required fields.
- In case your API key is invalid or you have exceeded your daily quota, an error message will inform you about the API error.
- Every location should go to a new line. Location name, coordinates and search radius should be separated by the pipe character (|). Latitude and longitude should be separated by comma (,). Example:

```
Szeged|46.2327035,20.0003864|30
Pécs|46.0777116,18.180542|30
Debrecen|47.5308291,21.5201,11|30
```

## TODO
 
- ~~Populating the nodes~~
- Retrieving older results (next page token)
- ~~Configuration link on the module list~~
- Frontend
  - Page layout
  - Menu
  - Post preview layout
  - Post page layout
- Log error messages

## Known issues

- In case CURL can't resolve an URL, try restarting the container (assuming you are using a container for development).
