<?php
/**
 * TODO: Добавить здесь комментарий
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

use Solo\Lib\Mongo\MongoConnection;
use Solo\Lib\Mongo\MongoEntityManager;

class ArticleManager extends MongoEntityManager
{

	/**
	 * Должен возвращать объект MongoDB
	 *
	 * @return MongoConnection
	 */
	public function getConnection()
	{
		return new MongoConnection($_ENV["mongo.server"], $_ENV["mongo.dbname"], array());
	}
}
