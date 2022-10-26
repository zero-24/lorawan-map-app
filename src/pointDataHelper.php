<?php
/**
 * PointDataHelper class
 *
 * @copyright  Copyright (C) 2022 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace zero24\Helper;

use zero24\Helper\FileHelper;

/**
 * Class for PointDataHelper
 *
 * @since  1.0
 */
class PointDataHelper
{
    /**
     * The filename with the data files
     *
     * @var    string
     * @since  1.0
     */
    private $fileName = 'tracker_pointdata';

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
    public function getPoints()
    {
        return $this->fileHelper->readJsonFile($this->fileName);
    }

    /**
     * Get already used Groups
     *
     * @return  array  List with all groups that have been setup already
     *
     * @since   1.0
     */
    public function getPointGroups()
    {
        $points      = $this->getPoints();
        $pointGroups = [];

        foreach ($points as $point)
        {
            $pointGroups[] = $point['group'];
        }

        return array_unique($pointGroups);
    }

    /**
     * Get one specific tracker datapoint from the text mapping file
     *
     * @param   string  $pointId  Tracker device ID
     *
     * @return  object|false  Decoded JSON object with the requested tracker information or false
     *
     * @since   1.0
     */
    public function getPointById($pointId)
    {
        $points = $this->getPoints();

        foreach ($points as $point)
        {
            if ($point['point_id'] === $pointId)
            {
                return $point;
            }
        }

        return false;
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
    public function createPoint($data)
    {
        $points   = $this->getPoints();
        $points[] = $data;

        $this->fileHelper->writeJsonFile(
            $this->fileName,
            json_encode(
                $points,
                JSON_PRETTY_PRINT
            )
        );

        return $data;
    }

    /**
     * Edit an existing tracker from the json file
     *
     * @param   array   $data     New Tracker data posted to the app
     * @param   string  $pointId  Device ID that should be edited
     *
     * @return  array  Tracker data posted to the app
     *
     * @since   1.0
     */
    public function editPoint($data, $pointId)
    {
        $editPoint = [];
        $points = $this->getPoints();

        foreach ($points as $i => $point)
        {
            if ($point['point_id'] === $pointId)
            {
                $points[$i] = $editPoint = array_merge($point, $data);
            }
        }

        $this->fileHelper->writeJsonFile(
            $this->fileName,
            json_encode(
                $points,
                JSON_PRETTY_PRINT
            )
        );

        return $editPoint;
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
    public function deletePoint($pointId)
    {
        $points = $this->getPoints();

        foreach ($points as $i => $point)
        {
            if ($point['point_id'] === $pointId)
            {
                array_splice($points, $i, 1);
            }
        }

        $this->fileHelper->writeJsonFile(
            $this->fileName,
            json_encode(
                $points,
                JSON_PRETTY_PRINT
            )
        );
    }

    /**
     * Validate the data passed to the app
     *
     * @param   array  &$point   The data to be validated (referenced)
     * @param   array  &$errors  The errors collected while validating (referenced)
     *
     * @return  bool
     *
     * @since   1.0
     */
    public function validatePoint(&$point, &$errors)
    {
        $isValid = true;

        $point['latitude']   = (int) $point['latitude'];
        $point['longitude']  = (int) $point['longitude'];
        $point['visibility'] = (int) $point['visibility'];

        // Start of validation
        if (!$point['point_id'] || $this->getPointById($point['point_id']))
        {
            //$isValid = false;
            //$errors['point_id'] = 'Point ID is mandatory and has to be distinct';
        }

        if (!$point['title'] || !is_string($point['title']))
        {
            $isValid = false;
            $errors['title'] = 'Title is mandatory and has to be a string';
        }

        if (!$point['longtext'] || !is_string($point['longtext']))
        {
            $isValid = false;
            $errors['longtext'] = 'Longtext is mandatory and has to be a string';
        }

        if (!$point['latitude'])
        {
            $isValid = false;
            $errors['latitude'] = 'Latitude is mandatory';
        }

        if (!$point['longitude'])
        {
            $isValid = false;
            $errors['longitude'] = 'Longitude is mandatory';
        }

        if (!$point['visibility'] || !is_int($point['visibility']))
        {
            //$isValid = false;
            //$errors['visibility'] = 'Visibility is mandatory and has to be an integer';
        }

        if (!$point['group'] || !is_string($point['group']))
        {
            $isValid = false;
            $errors['group'] = 'Group is mandatory and has to be a string';
        }
        // End Of validation

        return $isValid;
    }
}
