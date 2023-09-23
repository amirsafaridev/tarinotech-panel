<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;

class GlobalRequestTransform
{
    public function handle($request, Closure $next)
    {
        // Transform the request here
        $input = $request->all();

        // Example: Convert all input values to uppercase
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $input[$key] = Helper::persianNumberToEnglish($value);
        }

        // Replace the request input with the transformed data
        $request->replace($input);

        return $next($request);
    }
}
