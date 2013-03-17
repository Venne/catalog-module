<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use DoctrineModule\Entities\NamedEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Repositories\LabelRepository")
 * @ORM\Table(name="catalogLabel")
 */
class LabelEntity extends NamedEntity implements Translatable
{
	/**
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @Gedmo\Locale
	 */
	protected $locale;


	public function setLocale($locale)
	{
		$this->locale = $locale;
	}


	public function getLocale()
	{
		return $this->locale;
	}
}

