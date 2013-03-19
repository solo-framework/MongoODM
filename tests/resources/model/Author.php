<?php
/**
 * Сущность автор статьи
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

use Solo\Lib\Mongo\MongoEntity;

class Author extends MongoEntity
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
	 * Адрес
	 *
	 * @var Address
	 */
	public $address = null;

	public static function getEntityRelations()
	{
		return array(
			"address" => array("type" => self::TYPE_ENTITY, "class" => "Address")
		);
	}
}
