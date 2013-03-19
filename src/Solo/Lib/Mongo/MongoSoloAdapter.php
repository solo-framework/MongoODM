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

class MongoSoloAdapter implements IApplicationComponent
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
	 * MongoConnection
	 *
	 * @var MongoConnection
	 */
	private $mongocon = null;

	/**
	 * Инициализация компонента
	 *
	 * @see IApplicationComponent::initComponent()
	 *
	 * @return boolean
	 **/
	public function initComponent()
	{
		$this->mongocon = new MongoConnection($this->server, $this->dbname, $this->options);
		return true;
	}

	/**
	 * Возвращает экземпляр Mongo
	 *
	 * @return \MongoDB|null
	 */
	public function getConnection()
	{
		return $this->mongocon;
	}
}
