<?php
/**
 * TrackerGpsDataHelper class
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace zero24\Helper;

use zero24\Helper\FileHelper;

/**
 * Class for TrackerGpsDataHelper
 *
 * @since  1.0
 */
class TrackerGpsDataHelper
{
    /**
     * The filename with the data files
     *
     * @var    string
     * @since  1.0
     */
    private $fileName = 'tracker_gpsdata';

    /**
     * The FileHelper object pointing to the data folder
     *
     * @var    FileHelper
     * @since  1.0
     */
    private $fileHelper;

    /**
     * Constructor.
     *
     * @param   array  $options  Options to init the connection
     *
     * @since   1.0
     */
    public function __construct($options)
    {
        $this->fileHelper = new FileHelper([
            'dataFolder' => $options['dataFolder'],
        ]);

        $this->fileName = $options['fileName'];
    }

    /**
     * Get all GPS Data from the json file
     *
     * @return  string  Decoded JSON object with all GPS information
     *
     * @since   1.0
     */
    public function getGpsData()
    {
        return $this->fileHelper->readJsonFile($this->fileName);
    }

    /**
     * Get one specific gps datapoint from the json file
     *
     * @param   string  $deviceId  Tracker device ID
     *
     * @return  object|null  Decoded JSON object with the requested gps data information or null
     *
     * @since   1.0
     */
    public function getGpsPointById($deviceId)
    {
        $gpsData = $this->getGpsData();

        foreach ($gpsData as $gpsPoint)
        {
            if ($gpsPoint['device_id'] === $deviceId)
            {
                return $gpsPoint;
            }
        }

        return null;
    }

    /**
     * Delete an existing tracker from the json file
     *
     * @param   string  Device ID to be deleted
     *
     * @return  void
     *
     * @since   1.0
     */
    public function deleteGpsPoint($deviceId)
    {
        $gpsData = $this->getGpsData();

        foreach ($gpsData as $i => $gpsPoint)
        {
            if ($gpsPoint['device_id'] === $deviceId)
            {
                array_splice($gpsData, $i, 1);
            }
        }

        $this->fileHelper->writeJsonFile(
            $this->fileName,
            json_encode(
                $gpsData,
                JSON_PRETTY_PRINT
            )
        );
    }

    /**
     * Get one specific gps datapoint from the json file
     *
     * @param   string  $deviceId  Tracker device ID
     *
     * @return  object|null  Decoded JSON object with the requested gps data information or null
     *
     * @since   1.0
     */
    public function getStoredGpsPointFileNames()
    {
        $storedJsonFiles = $this->fileHelper->getJsonFilesWithinDataFolder('*_tracker_gpsdata.json');

        foreach ($storedJsonFiles as $storedJsonFile)
        {
            $storedJsonFile = \str_replace('_tracker_gpsdata.json', '', $storedJsonFile);
            $storedGpsPointFileNames[$storedJsonFile] = $storedJsonFile;
        }

        return $storedGpsPointFileNames ? $storedGpsPointFileNames : [];
    }
}
