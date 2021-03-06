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

use Solo\Core\IApplicationComponent;

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
	 * @var Connection
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
		$this->mongocon = new Connection($this->server, $this->dbname, $this->options);
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
