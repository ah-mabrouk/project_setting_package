<?php

namespace Mabrouk\ProjectSetting\Filters\Admin;

use Mabrouk\Filterable\Helpers\QueryFilter;

class ProjectSettingSectionFilter extends QueryFilter
{
    public function of_group($groupId = '')
    {
        return \is_int($groupId) ? $this->builder->ofGroup($groupId) : $this->builder;
    }

    public function search($search = '')
    {
        return $search ? $this->builder->search($search) : $this->builder;
    }
}
