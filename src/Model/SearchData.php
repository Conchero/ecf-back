<?php
namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;

class SearchData
{
    /** @var array|null */
    public $equipments = [];

    /** @var int|null */
    public $capacity;

    /** @var array|null */
    public $softwares = [];

    /** @var array|null */
    public $advantages = [];
}
