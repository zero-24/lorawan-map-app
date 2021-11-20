# DRK LoRaWAN Map App

This is an LoRaWAN Map App which implements an public facing interface for the [LoRaWAN GPS Tracker](https://www.aeq-web.com/lorawan-gps-tracker-the-things-stack-tts-application-server/) using leaflet and OpenStreetMap.

## How it works

The data of the LoRaWAN Trackers get passed to the "uplink.php" file which stores them in the `gps_data.json` file, with the `text_mappings.json` additional texts for the tracker drvice IDs can be stored. From that two files the map application gets its data to be displyed on the OSM Map.

## How to setup

TBC

## Special Thanks

[Alex @ AEQ-WEB](https://www.aeq-web.com/) & René Wildemann
