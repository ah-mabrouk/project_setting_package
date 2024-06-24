<?php

namespace Mabrouk\ProjectSetting\Filters\Client;

use Mabrouk\Filterable\Helpers\QueryFilter;

class ProjectSettingGroupFilter extends QueryFilter
{
    public function search($search = '')
    {
        return $search ? $this->builder->search($search) : $this->builder;
    }
}
