<?php
/**
 * MongoConnection
 *
 * PHP version 5
 *
 * @package MongoODM
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace Solo\Lib\Mongo;

class Connection
{
	/**
	 * Строка подключения
	 *
	 * @var string
	 */
	public $server = null;

	/**
	 * Имя базы
	 *
	 * @var string
	 */
	public $dbname = null;

	/**
	 * Опции подключения
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * MongoClient
	 *
	 * @var \MongoClient
	 */
	private $mongodb = null;

	/**
	 * Инициализация подключения
	 *
	 * @param string $server  Строка подключения
	 * @param string $dbname  Имя базы
	 * @param string $options Опции подключения
	 *
	 * @return Connection
	 */
	public function __construct($server, $dbname, $options)
	{
		$mongo = new \MongoClient($server, $options);
		$this->mongodb = $mongo->selectDB($dbname);
	}

	/**
	 * Возвращает экземпляр Mongo
	 *
	 * @return \MongoDB|null
	 */
	public function getMongoDB()
	{
		return $this->mongodb;
	}
}
