<?php
/**
 * Сущность номер телефона
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace App\Entity;

use Solo\Lib\Mongo\Entity;

class Phone extends Entity
{
	const TYPE_HOME = "TYPE_HOME";

	const TYPE_WORK = "TYPE_WORK";

	/**
	 * Тип номера
	 *
	 * @var string
	 */
	public $type = null;

	/**
	 * Значение номера
	 *
	 * @var string
	 */
	public $value = null;

	public function __construct($type = null, $value = null)
	{
		$this->type = $type;
		$this->value = $value;
	}

	/**
	 * Возвращает имя коллекции, где хранятся сущности этого типа
	 *
	 * @return string
	 */
	public function getCollectionName()
	{
		return null;
	}
}
