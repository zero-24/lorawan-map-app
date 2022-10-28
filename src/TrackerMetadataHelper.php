<?php
/**
 * TrackerMetadataHelper class
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace zero24\Helper;

use zero24\Helper\FileHelper;

/**
 * Class for TrackerMetadataHelper
 *
 * @since  1.0
 */
class TrackerMetadataHelper
{
    /**
     * The filename with the data files
     *
     * @var    string
     * @since  1.0
     */
    private $fileName = 'tracker_metadata';

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
     * Get all Tracker Data from the text mapping file
     *
     * @return  string  Decoded JSON object with all tracker information
     *
     * @since   1.0
     */
    public function getTrackers()
    {
        return $this->fileHelper->readJsonFile($this->fileName);
    }

    /**
     * Get one specific tracker datapoint from the text mapping file
     *
     * @param   string  $deviceId  Tracker device ID
     *
     * @return  object|false  Decoded JSON object with the requested tracker information or false
     *
     * @since   1.0
     */
    public function getTrackerById($deviceId)
    {
        $trackers = $this->getTrackers();

        foreach ($trackers as $tracker)
        {
            if ($tracker['device_id'] === $deviceId)
            {
                return $tracker;
            }
        }

        return false;
    }

    /**
     * Get already used Icons
     *
     * @return  array  List with all groups that have been setup already
     *
     * @since   1.0
     */
    public function getTrackerIcons()
    {
        $trackers      = $this->getTrackers();
        $trackerIcons  = MARKER_ICON_ARRAY_SUGGESTION;

        foreach ($trackers as $tracker)
        {
            $trackerIcons[] = $tracker['icon'];
        }

        return array_unique($trackerIcons);
    }

    /**
     * Add a new tracker to the json file
     *
     * @param   array  $data  Tracker data posted to the app
     *
     * @return  array  Tracker data posted to the app
     *
     * @since   1.0
     */
    public function createTracker($data)
    {
        $trackers   = $this->getTrackers();
        $trackers[] = $data;

        $this->fileHelper->writeJsonFile(
            $this->fileName,
            json_encode(
                $trackers,
                JSON_PRETTY_PRINT
            )
        );

        return $data;
    }

    /**
     * Edit an existing tracker from the json file
     *
     * @param   array   $data      New Tracker data posted to the app
     * @param   string  $deviceId  Device ID that should be edited
     *
     * @return  array  Tracker data posted to the app
     *
     * @since   1.0
     */
    public function editTracker($data, $deviceId)
    {
        $editTracker = [];
        $trackers = $this->getTrackers();

        foreach ($trackers as $i => $tracker)
        {
            if ($tracker['device_id'] === $deviceId)
            {
                $trackers[$i] = $editTracker = array_merge($tracker, $data);
            }
        }

        $this->fileHelper->writeJsonFile(
            $this->fileName,
            json_encode(
                $trackers,
                JSON_PRETTY_PRINT
            )
        );

        return $editTracker;
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
    public function deleteTracker($deviceId)
    {
        $trackers = $this->getTrackers();

        foreach ($trackers as $i => $tracker)
        {
            if ($tracker['device_id'] === $deviceId)
            {
                array_splice($trackers, $i, 1);
            }
        }

        $this->fileHelper->writeJsonFile(
            $this->fileName,
            json_encode(
                $trackers,
                JSON_PRETTY_PRINT
            )
        );
    }

    /**
     * Validate the data passed to the app
     *
     * @param   array   &$tracker  The data to be validated (referenced)
     * @param   array   &$errors   The errors collected while validating (referenced)
     * @param   string  $type      String wether we are in 'edit' or 'create' mode
     *
     * @return  bool
     *
     * @since   1.0
     */
    public function validateTracker(&$tracker, &$errors, $type)
    {
        $isValid = true;

        // Cast strength values to integer
        $tracker['strength_leader']      = (int) $tracker['strength_leader'];
        $tracker['strength_groupleader'] = (int) $tracker['strength_groupleader'];
        $tracker['strength_helper']      = (int) $tracker['strength_helper'];

        // Start of validation
        if (!$tracker['device_id'] || ($type === 'create' && $this->getTrackerById($tracker['device_id'])))
        {
            $isValid = false;
            $errors['device_id'] = 'Tracker ID is mandatory and has to be unique';
        }

        if (!$tracker['title'])
        {
            $isValid = false;
            $errors['title'] = 'Title is mandatory';
        }

        if (!$tracker['longtext'])
        {
            $isValid = false;
            $errors['longtext'] = 'Longtext is mandatory';
        }

        if (!$tracker['callsign'])
        {
            $isValid = false;
            $errors['callsign'] = 'Callsign is mandatory';
        }

        if (!$tracker['groupleader'])
        {
            $isValid = false;
            $errors['groupleader'] = 'Groupleader is mandatory';
        }

        if (!$tracker['strength_leader']
            && is_int($tracker['strength_leader'])
            && (int) $tracker['strength_leader'] !== 0)
        {
            $isValid = false;
            $errors['strength_leader'] = 'The number of association strength_leaders and doctors are mandatory' . '<br>';
        }

        if (!$tracker['strength_groupleader']
            && is_int($tracker['strength_groupleader'])
            && $tracker['strength_groupleader'] !== 0)
        {
            $isValid = false;
            $errors['strength_groupleader'] = 'The number of group and squad strength_leaders are mandatory'. '<br>';
        }

        if (!$tracker['strength_helper']
            && is_int($tracker['strength_helper'])
            && (int) $tracker['strength_helper'] !== 0)
        {
            $isValid = false;
            $errors['strength_helper'] = 'The number of strength_helpers are mandatory' . '<br>';
        }

        if (!$tracker['icon'])
        {
            $isValid = false;
            $errors['icon'] = 'Icon is mandatory';
        }
        // End Of validation

        // Calculate the full strength
        $tracker['strength'] = $tracker['strength_leader'] + $tracker['strength_groupleader'] + $tracker['strength_helper'];

        return $isValid;
    }
}
