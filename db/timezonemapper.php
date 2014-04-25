<?php
/**
 * Copyright (c) 2014 Georg Ehrke <oc.list@georgehrke.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */
namespace OCA\Calendar\Db;

use \OCP\AppFramework\IAppContainer;

use \OCA\Calendar\Db\Timezone;
use \OCA\Calendar\Db\TimezoneCollection;

class TimezoneMapper extends Mapper {

	private $folderName;
	private $fileBlacklist;

	/**
	 * @param API $api: Instance of the API abstraction layer
	 * @param string $folderName
	 */
	public function __construct(IAppContainer $app, $folderName='/../timezones/'){
		$this->app = $app;
		$this->folderName = __DIR__ . $folderName;

		$this->fileBlacklist = array('.', '..', 'INFO.md');
	}


	/**
	 * @brief find a timezone
	 * @param string $tzId
	 * @return \OCA\Calendar\Db\Timezone
	 */
	public function find($tzId) {
		$tzIdName = strtoupper(str_replace('/', '-', $tzId));
		$path = $this->folderName . $tzIdName . '.ics';

		/**throw DoesNotExistException when:
		 * - [x] $tzId is info.md (info.md shouldn't be packaged in the first place) or
		 * - [x] $tzId is not a valid filename or
		 * - [x] file inside /../timezones/ does not exist
		 */ 
		if (strtolower($tzId) === 'info.md' ||
		   !\OCP\Util::isValidFileName($tzIdName) ||
		   !file_exists($path)) {
			throw new DoesNotExistException();
		}

		$data = file_get_contents($path);

		return new Timezone($tzId, $data);
	}

	/**
	 * @brief find all timezones 
	 * @param integer $limit
	 * @param integer $offset
	 * @return \OCA\Calendar\Db\TimezoneCollection
	 */
	public function findAll($limit, $offset) {
		$tzFiles = scandir($this->folderName);
		$files = array_values(array_diff($tzFiles, $this->fileBlacklist));

		if (is_null($limit)) {
			$limit = (count($files) - 1);
		}
		if (is_null($offset)) {
			$offset = 0;
		}

		$collection = new TimezoneCollection();
		for($i = $offset; $i < ($offset + $limit); $i++) {
			$tzId = str_replace('-', '/', str_replace('.ics', '', $files[$i]));
			$data = file_get_contents($this->folderName . $files[$i]);
			$timezone = new Timezone($tzId, $data);
			$collection->add($timezone);
		}

		return $collection;
	}


	public function delete(Entity $entity){
		return null;
	}


	public function insert(Entity $entity){
		return null;
	}


	public function update(Entity $entity){
		return null;
	}
}