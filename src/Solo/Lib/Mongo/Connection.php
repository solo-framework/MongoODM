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
	 * Количество попыток подключения
	 *
	 * @var int
	 */
	public $attempts = 5;

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
		$mongo = $this->createConnection($server, $options, $this->attempts);
		$this->mongodb = $mongo->selectDB($dbname);
	}

	/**
	 * Создает подключение.
	 * Делает несколько попыток
	 * https://jira.mongodb.org/browse/PHP-854
	 *
	 * @param $server
	 * @param $options
	 * @param int $attempts
	 *
	 * @return \MongoClient
	 * @throws \Exception
	 */
	protected function createConnection($server, $options, $attempts = 5)
	{
		$mongo = null;
		$lastException = null;

		try
		{
			return new \MongoClient($server, $options);
		}
		catch (\Exception $e)
		{
			$lastException = $e;
		}

		if ($attempts > 0)
		{
			return $this->createConnection($server, $options, --$attempts);
		}

		throw $lastException;
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