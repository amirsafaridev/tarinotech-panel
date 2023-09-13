<?php

/** @noinspection PhpSameParameterValueInspection */

namespace App\Service;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use ReflectionClass;
use ReflectionException;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    private array $langKeys = [];

    private array $customPermissions = [

    ];

    public function sync()
    {
        $this->langKeys = [];

        $this->scan('app/Http/Controllers/Admin');
        //$this->scan('app/Http/Controllers/Admin/Website', 'website_');

        $this->writeLangFile($this->langKeys);
    }

    private function scan($path, $prefix = ''): void
    {
        $files = File::files(base_path($path));
        $permissions = [];
        $removeMethods = ['data', 'store', 'update', 'setData'];
        foreach ($files as $item) {
            $file = basename($item, '.php');
            $classPath = str_replace(['/', 'app'], ['\\', 'App'], $path.'/'.$file);
            try {
                $class = new $classPath;
                $fireClass = new ReflectionClass($class);
                foreach ($fireClass->getMethods() as $method) {
                    if ($method->class == $classPath && ! in_array($method->name, $removeMethods) && $method->isPublic()) {
                        $controllerName = str($file)
                            ->ucfirst()
                            ->replace('Controller', '')
                            ->split('/(?=[A-Z])/')
                            ->reject(function ($item) {
                                return $item === '';
                            })
                            ->join('_');

                        $methodName = str($method->name)
                            ->ucfirst()
                            ->split('/(?=[A-Z])/')
                            ->reject(function ($item) {
                                return $item === '';
                            })
                            ->join('_');

                        $permissions[] = strtolower(
                            $prefix.$controllerName.'_'.$methodName
                        );
                    }
                }
            } catch (ReflectionException $e) {
                report($e);
            }
        }

        if (count($this->customPermissions)) {
            foreach ($this->customPermissions as $customPermission) {
                $permissions[] = strtolower(
                    $customPermission
                );
            }
            $this->customPermissions = [];
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin',
            ], [
                'name' => $permission,
                'guard_name' => 'admin',
            ]);

            if (! Lang::has('permission.'.$permission)) {
                $this->langKeys[] = [
                    'key' => $permission,
                    'value' => $this->langPermissionValue($permission),
                ];
            } else {
                $keyTrans = 'permission.'.$permission;
                $this->langKeys[] = [
                    'key' => $permission,
                    'value' => trans($keyTrans),
                ];
            }
        }
    }

    private function langPermissionValue(string $input): array|bool|string|null
    {
        $output = str_replace('_', ' ', $input);

        return mb_convert_case($output, MB_CASE_TITLE, 'UTF-8');
    }

    private function writeLangFile(array $trans)
    {
        $content = '<?php'.PHP_EOL;
        $content .= 'return ['.PHP_EOL;
        foreach ($trans as $item) {
            $content .= sprintf("\t'%s'=>'%s',", $item['key'], $item['value']).PHP_EOL;
        }
        $content .= '];';
        File::put(lang_path('en/permission.php'), $content);
        File::put(lang_path('fa/permission.php'), $content);
    }
}
