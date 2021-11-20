<?php
/**
 * FileHelper class
 *
 * @copyright  Copyright (C) 2021 Tobias Zulauf. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace zero24\Helper;

/**
 * Class for zway
 *
 * @since  1.0
 */
class FileHelper
{
	/**
	 * The folder with the data files
	 *
	 * @var    string
	 * @since  1.0
	 */
	private $dataFolder;

	/**
	 * Constructor.
	 *
	 * @param   array  $options  Options to init the connection
	 *
	 * @since   1.0
	 */
	public function __construct($options)
	{
		$this->dataFolder = $options['dataFolder'];
	}

	/**
	 * Retrun a json file
	 *
	 * @param   string  $fileName  The name of the json file without the extension
	 *
	 * @since   1.0
	 */
	public function readJsonFile($fileName)
	{
		$file = $this->dataFolder . '/' . $fileName . '.json';

		return json_decode(file_get_contents($file), true);
	}

	/**
	 * Write a json file
	 *
	 * @param   string  $fileName  The name of the json file without the extension
	 * @param   array   $json      The json data as array
	 *
	 * @since   1.0
	 */
	public function writeJsonFile($fileName, $json)
	{
		$file = $this->dataFolder . '/' . $fileName . '.json';

		if (\file_exists($file))
		{
			unlink($file);
		}

		return file_put_contents($file, json_encode($json));
	}
}
