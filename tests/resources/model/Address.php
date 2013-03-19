<?php
/**
 * Сущность адрес пользователя
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

use Solo\Lib\Mongo\MongoEntity;

class Address extends MongoEntity
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
			"phones" => array("type" => self::TYPE_ARRAY_ENTITIES, "class" => "Phone")
		);
	}
}
