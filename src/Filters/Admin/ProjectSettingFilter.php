<?php

namespace Mabrouk\ProjectSetting\Filters\Admin;

use Mabrouk\Filterable\Helpers\QueryFilter;

class ProjectSettingFilter extends QueryFilter
{
    public function of_key($key = '')
    {
        return $key ? $this->builder->ofKey($key) : $this->builder;
    }

    public function of_type($typeId = '')
    {
        return \is_int($typeId) ? $this->builder->ofType($typeId) : $this->builder;
    }

    public function of_section($sectionId = '')
    {
        return \is_int($sectionId) ? $this->builder->ofType($sectionId) : $this->builder;
    }

    public function search($search = '')
    {
        return $search ? $this->builder->search($search) : $this->builder;
    }
}
