<?php

namespace Mabrouk\ProjectSetting\Filters\Admin;

use Mabrouk\Filterable\Helpers\QueryFilter;

class ProjectSettingTypeFilter extends QueryFilter
{
    public function translatable($translatable = 'yes')
    {
        return \in_array($translatable, \array_keys($this->availableBooleanValues)) ? $this->builder->translatable($this->availableBooleanValues[$translatable]) : $this->builder;
    }

    public function search($search = null)
    {
        return $search ? $this->builder->where(function ($query) use ($search) {
            $query->where('name', $search);
        }) : $this->builder;
    }
}
