<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CatalogModule\Pages\Catalog;

use Doctrine\ORM\Mapping as ORM;
use GalleryModule\Pages\Gallery\AbstractRouteEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CatalogModule\Pages\Catalog\RouteRepository")
 * @ORM\Table(name="catalog_route")
 */
class RouteEntity extends AbstractRouteEntity
{

}
