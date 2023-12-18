<?php

namespace App\Service;

class Permissions
{
    const PROJECT_INDEX = 'project_index';

    const PROJECT_CREATE = 'project_create';

    const PROJECT_UPDATE = 'project_update';

    const PROJECT_DELETE = 'project_delete';

    public function getAll()
    {
        $reflectionClass = new \ReflectionClass($this);
        $constants = $reflectionClass->getConstants();

        $permissions = [];
        foreach ($constants as $constantName => $constantValue) {
            $permissions[$constantName] = [
                'name' => $constantValue,
                'title' => trans('permission.'.$constantValue),
            ];
        }

        return $permissions;
    }
}
