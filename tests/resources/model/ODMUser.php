<?php
/**
 * Сущность автор статьи
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace App\Entity;

use Solo\Lib\Mongo\Entity;

class ODMUser extends Entity
{
	/**
	 * Идентификатор
	 *
	 * @var string
	 */
	public $id = null;

	/**
	 * Имя
	 *
	 * @var string
	 */
	public $name = null;

	/**
	 * Возраст
	 *
	 * @var int
	 */
	public $age = null;

	/**
	 * Время создания
	 *
	 * @var \DateTime
	 */
	public $createAt = null;

	/**
	 * Список друзей
	 *
	 * @var array
	 */
	public $friends = array();

	public static function getFieldsMeta()
	{
		return array(
			"friends" => false
		);
	}

	/**
	 * Возвращает имя коллекции, где хранятся сущности этого типа
	 *
	 * @return string
	 */
	public function getCollectionName()
	{
		return "user";
	}
}
