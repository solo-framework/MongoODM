<?php
/**
 * TODO: Добавить здесь комментарий
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace App\Manager;

use Solo\Lib\Mongo\Connection;
use Solo\Lib\Mongo\EntityManager;

class UserManager extends EntityManager
{

	/**
	 * Должен возвращать объект MongoDB
	 *
	 * @return Connection
	 */
	public function getConnection()
	{
		return new Connection($GLOBALS["mongo.server"], $GLOBALS["mongo.dbname"], array());
	}
}
