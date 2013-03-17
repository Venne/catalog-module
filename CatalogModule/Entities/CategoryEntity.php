<?php

namespace CatalogModule\Entities;

use Venne;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Repositories\CategoryRepository")
 * @ORM\Table(name="catalogCategory")
 */
class CategoryEntity extends BaseCategoryEntity
{


}

