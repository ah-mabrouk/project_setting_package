<?php

namespace Mabrouk\ProjectSetting\Filters\Backend;

use Mabrouk\Filterable\Helpers\QueryFilter;

class ProjectSettingGroupFilter extends QueryFilter
{
    public function search($search = '')
    {
        return $search ? $this->builder->search($search) : $this->builder;
    }

    public function visible($visible = 'yes')
    {
        return \in_array($visible, \array_keys($this->availableBooleanValues)) ? $this->builder->visible($this->availableBooleanValues[$visible]) : $this->builder;
    }
}
