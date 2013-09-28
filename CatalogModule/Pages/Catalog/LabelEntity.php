<?php

namespace CatalogModule\Pages\Catalog;

use Doctrine\ORM\Mapping as ORM;
use DoctrineModule\Entities\NamedEntity;
use Venne;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Pages\Catalog\LabelRepository")
 * @ORM\Table(name="catalog_label")
 */
class LabelEntity extends NamedEntity
{

	/**
	 * @ORM\Column(type="string")
	 */
	protected $name = '';

	/** @var string */
	protected $locale;


	public function __toString()
	{
		return $this->name;
	}


	public function setLocale($locale)
	{
		$this->locale = $locale;
	}


	public function getLocale()
	{
		return $this->locale;
	}
}

