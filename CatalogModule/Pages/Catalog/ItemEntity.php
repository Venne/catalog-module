<?php

namespace CatalogModule\Pages\Catalog;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Pages\Catalog\ItemRepository")
 * @ORM\Table(name="catalog_product")
 */
class ItemEntity extends AbstractItemEntity
{

}
