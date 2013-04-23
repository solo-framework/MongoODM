<?php
/**
 * Сущность статьи
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace App\Entity;

use Solo\Lib\Mongo\Entity;

class Article extends Entity
{
	/**
	 * Идентификатор
	 *
	 * @var int
	 */
	public $id = null;

	/**
	 * Название статьи
	 *
	 * @var string
	 */
	public $title = null;

	/**
	 * Содержиое статьи
	 *
	 * @var string
	 */
	public $content = null;

	/**
	 * Фотография (вложенный объект)
	 *
	 * @var Photo
	 */
	public $photo = null;

	/**
	 * Ссылка на объект
	 *
	 * @var Author
	 */
	public $author = null;

	/**
	 * Список комментариев (вложенный массив объектов)
	 *
	 * @var Comment[]
	 */
	public $comments = array();

	/**
	 * Список тегов (простой массив)
	 *
	 * @var array
	 */
	public $tags = array();

	/**
	 * Список оценок по разным критериям (массив ключ-значение)
	 *
	 * @var array
	 */
	public $grades = array();

	/**
	 * Врeмя создания
	 *
	 * @var int
	 */
	public $createTime = null;

	public static function getEntityRelations()
	{
		return array(
			"author" => array("type" => self::TYPE_ENTITY, "class" => __NAMESPACE__ . "\\Author"),
			"comments" => array("type" => self::TYPE_ARRAY_ENTITIES, "class" => __NAMESPACE__ . "\\Comment")
		);
	}

	/**
	 * Возвращает имя коллекции, где хранятся сущности этого типа
	 *
	 * @return string
	 */
	public function getCollectionName()
	{
		return "article";
	}
}
