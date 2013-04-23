<?php
/**
 * Картинка для статьи
 *
 * PHP version 5
 *
 * @package
 * @author  Eugene Kurbatov <ekur@i-loto.ru>
 */

namespace App\Entity;

use Solo\Lib\Mongo\Entity;

class Photo extends Entity
{
	/**
	 * Url фотографии
	 *
	 * @var string
	 */
	public $url = null;

	/**
	 * Описание фотограции
	 *
	 * @var string
	 */
	public $desc = null;

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
