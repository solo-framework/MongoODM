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

class Author extends Entity
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
			"address" => array("type" => self::TYPE_ENTITY, "class" => __NAMESPACE__ . "\\Address")
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
