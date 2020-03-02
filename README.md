![CityTube Logo](docs_assets/citytube_logo.svg) 

A Drupal module for displaying videos from YouTube which are related to a specific area.

What does this module do?

- Finds all the videos based on geolocation settings (latitude and longitude)
- Finds all the videos based on location name (basically just a search query)
- Stores the location as a taxonomy (`cities`) term
- Creates a node from the video, and stores:
	- Video ID in `field_video_id`
	- Video Title in `title`
	- Video description in `body`
	- Video publishing time in `field_published`
	- Video thumbnail URL (high version) in `field_thumbnail_url`
	- Channel ID in `field_channel_id`
	- Channel Name in `field_channel`
	- Connection with the taxonomy term in `field_city`

## Instructions

- Go to `/admin/config/citytube/config` and fill the required fields.
- In case your API key is invalid or you have exceeded your daily quota, an error message will inform you about the API error.
- Every location should go to a new line. Location name, coordinates and search radius should be separated by the pipe character (|). Latitude and longitude should be separated by comma (,). Example:

```
Szeged|46.2327035,20.0003864|30
Pécs|46.0777116,18.180542|30
Debrecen|47.5308291,21.5201,11|30
```

![CityTube Settings](docs_assets/settings.png) 

- To manually run the API call, go to this URL while you are logged in: `/citytube/manual_api_call`

  In case of errors, you will hopefully see a message here about what went wrong.

- Of course, every imported video has it's own content node, list them by visiting this URL: `/admin/content?title=&type=citytube_video`

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
- Tests

## Known issues

- In case CURL can't resolve an URL, try restarting the container (assuming you are using a Docker container for development).
