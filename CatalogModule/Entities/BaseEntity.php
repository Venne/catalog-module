<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Nette\Utils\Strings;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\MappedSuperclass
 */
abstract class BaseEntity extends \DoctrineModule\Entities\IdentifiedEntity implements Translatable
{

	/**
	 * @var PageEntity
	 * @ORM\ManyToOne(targetEntity="PageEntity")
	 * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $page;

	/**
	 * @var \CmsModule\Content\Entities\RouteEntity
	 * @ORM\OneToOne(targetEntity="\CmsModule\Content\Entities\RouteEntity", cascade={"persist"}, orphanRemoval=true)
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $route;

	/**
	 * @var string
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @Gedmo\Translatable
	 * @ORM\Column(type="string")
	 */
	protected $description;

	/**
	 * @var \CmsModule\Content\Entities\FileEntity
	 * @ORM\OneToOne(targetEntity="\CmsModule\Content\Entities\FileEntity", cascade={"all"}, orphanRemoval=true)
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $image;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection|TypeEntity[]
	 */
	protected $types;

	/**
	 * @Gedmo\Locale
	 */
	protected $locale;


	public function __construct(PageEntity $page, $name)
	{
		$this->setPage($page);
		$this->setName($name);

		$this->types = new ArrayCollection;
	}


	public function __destruct()
	{
		$this->removeRoute();
	}


	public function setLocale($locale)
	{
		$this->locale = $locale;
	}


	public function getLocale()
	{
		return $this->locale;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;

		if ($this->route) {
			$this->route->setLocalUrl(Strings::webalize($this->name), false);
			$this->route->setTitle($this->name);
		}
	}


	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}


	/**
	 * @return PageEntity
	 */
	public function getPage()
	{
		return $this->page;
	}


	/**
	 * @param PageEntity $page
	 */
	public function setPage($page)
	{
		$this->page = $page;
	}


	public function getImage()
	{
		return $this->image;
	}


	public function setImage($image)
	{
		$this->image = $image;

		if ($this->image) {
			$this->image->setParent($this->page->getDir());
			$this->image->setInvisible(true);
		}
	}


	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|array
	 */
	public function getTypes()
	{
		return $this->types;
	}


	/**
	 * @param string $types
	 */
	public function setTypes($types)
	{
		$this->types = $types;
	}


	public function getTypesAsArray()
	{
		$ret = array();
		foreach ($this->types as $type) {
			$ret[] = (string)$type;
		}
		return $ret;
	}


	public function setTypesAsArray(array $types)
	{
		$newCollection = new ArrayCollection;

		foreach ($types as $name) {
			if ($name) {
				$ok = true;
				foreach ($this->types as $type) {
					if ($type->name === $name) {
						$newCollection[] = $type;
						$ok = false;
						break;
					}
				}

				if ($ok) {
					$newCollection[] = new TypeEntity($name, $this);
				}
			}
		}

		foreach ($this->types as $type) {
			if (array_search($type->name, $types) === false) {
				$this->types->removeElement($type);
			}
		}

		$this->types = $newCollection;
	}


	/**
	 * @return \CmsModule\Content\Entities\RouteEntity
	 */
	public function getRoute()
	{
		return $this->route;
	}


	/**
	 * @param \CmsModule\Content\Entities\RouteEntity $route
	 */
	public function setRoute($route)
	{
		$this->route = $route;
	}


	protected function createRoute($link)
	{
		$this->route = new \CmsModule\Content\Entities\RouteEntity;
		$this->route->setType($link);
		$this->route->setLocalUrl(Strings::webalize($this->name));
		$this->page->routes[] = $this->route;
		$this->route->setPage($this->page);
		$this->route->setParent($this->page->mainRoute);
	}


	protected function removeRoute()
	{
		//$this->page->routes->removeElement($this->route);
		unset($this->route);
	}
}

