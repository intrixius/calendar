<?php
/**
 * ownCloud - Calendar App
 *
 * @author Georg Ehrke
 * @copyright 2014 Georg Ehrke <oc.list@georgehrke.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Calendar\Db;

use OCP\Calendar\ISubscription;

class Subscription extends Entity implements ISubscription {

	/**
	 * @var string
	 */
	public $type;


	/**
	 * @var string
	 */
	public $url;


	/**
	 * @var string
	 */
	public $userId;


	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type) {
		return $this->setter('type', array($type));
	}


	/**
	 * @return string
	 */
	public function getType() {
		return $this->getter('type');
	}


	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url) {
		return $this->setter('url', array($url));
	}


	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->getter('url');
	}


	/**
	 * @param string $userId
	 * @return $this
	 */
	public function setUserId($userId) {
		return $this->setter('userId', array($userId));
	}


	/**
	 * @return string
	 */
	public function getUserId() {
		return $this->getter('userId');
	}


	/**
	 * check if object is valid
	 * @return boolean
	 */
	public function isValid() {
		$isValid = parent::isValid();
		if (!$isValid) {
			return false;
		}

		$parsedURL = parse_url($this->getUrl());
		if (!$parsedURL) {
			return false;
		}
		if (!array_key_exists('host', $parsedURL)) {
			return false;
		}

		return true;
	}


	/**
	 * register field types
	 */
	protected function registerTypes() {
		$this->addType('type', 'string');
		$this->addType('url', 'string');
		$this->addType('userId', 'string');
	}


	/**
	 * register mandatory fields
	 */
	protected function registerMandatory() {
		$this->addMandatory('type');
		$this->addMandatory('url');
		$this->addMandatory('userId');
	}


	/**
	 * create string representation of object
	 * @return string
	 */
	public function __toString() {
		return $this->userId . '::' . $this->type . '::' . $this->url;
	}
}