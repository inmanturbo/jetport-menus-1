<?php

namespace App\Http\Livewire\Frontend;

use Rappasoft\LaravelLivewireTables\TableComponent;

abstract class BaseTable extends TableComponent
{
    /**
     * @var bool
     */
    public $withChildren = false;

    /**
     * @var bool
     */
    public $withManualStriping = false;

    /**
     * @var string
     */
    public $uniqueSeed;

    /**
     * @var string
     */
    public $rowColorOne = '';

    /**
     * @var string
     */
    public $rowColorTwo = '';

    /**
     * @var int
     */
    public $parentId;

    /**
     * @var array
     */
    public $groupMeta;
}