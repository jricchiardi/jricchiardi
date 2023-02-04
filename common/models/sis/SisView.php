<?php

namespace common\models\sis;

use common\models\SisSearch;
use common\models\User;
use Yii;

class SisView
{
    use HasSaleMonth;

    /**
     * @var DrillLevel
     */
    public $drillLevel;
    /**
     * @var SisSearch
     */
    public $data;
    /**
     * @var SisFilters
     */
    public $filters;

    public function __construct()
    {
        $this->filters = new SisFilters();
        $this->drillLevel = new DrillLevel();
        $this->data = new SisSearch();
    }



}