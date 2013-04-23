<?php
/**
 * Сущность адрес пользователя
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace App\Entity;

use Solo\Lib\Mongo\Entity;

class Address extends Entity
{
	/**
	 * Назваие страны
	 *
	 * @var string
	 */
	public $country = null;

	/**
	 * Название города
	 *
	 * @var null
	 */
	public $city = null;

	/**
	 * Список номеров телефонов
	 *
	 * @var Phone[]
	 */
	public $phones = array();

	public static function getEntityRelations()
	{
		return array(
			"phones" => array("type" => self::TYPE_ARRAY_ENTITIES, "class" => __NAMESPACE__ . "\\Phone")
		);
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
