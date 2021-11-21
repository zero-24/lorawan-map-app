# DRK LoRaWAN Map App

This is an LoRaWAN Map App which implements an public facing interface for the [LoRaWAN GPS Tracker](https://www.aeq-web.com/lorawan-gps-tracker-the-things-stack-tts-application-server/) using leaflet and OpenStreetMap.

## How it works

The data of the LoRaWAN Trackers get passed to the "uplink.php" file which stores them in the `gps_data.json` file, with the `text_mappings.json` additional texts for the tracker device IDs can be stored. From that two files the map application gets its data to be displyed on the OSM Map.

## How to setup

- ssh to your webserver
- Create an folder
- Run `git clone https://github.com/zero-24/lorawan-map-app.git .` inside the folder
- Point your domain to the `www` folder
- `cp etc/constants.dist.php etc/constants.php`
- `nano constants.dist.php` -> Setup the secret and the sitename
- `composer install --no-dev`
- Create within "The Things Network" an webhook to the uplink.php (Integrations -> Webhooks -> Add webhook -> custom webhook)
- Set the secret from the constants.php in the webhook config `uplink.php?uplink_secret=<your-secret>`
- Save the webhook and let the data come

## Special Thanks

[Alex @ AEQ-WEB](https://www.aeq-web.com/) & René Wildemann
