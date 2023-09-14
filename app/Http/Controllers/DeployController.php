<?php

namespace App\Http\Controllers;

class DeployController extends Controller
{
    public function index()
    {
        $bashFile = file_get_contents(base_path('deploy.sh'));
        $commands = explode(PHP_EOL, $bashFile);
        foreach ($commands as $command) {
            echo '<pre>'.shell_exec($command).'</pre>';
        }

    }
}
