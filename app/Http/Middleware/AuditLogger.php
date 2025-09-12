<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            if ($request->is('admin/*')) {
                AuditLog::create([
                    'user_id' => $request->user()?->id,
                    'event' => strtoupper($request->method()) . ' ' . $request->path(),
                    'meta' => $request->only(['query','body']),
                    'ip' => $request->ip()
                ]);
            }
        } catch (\Exception $e) {
            logger()->warning('Audit log failed: ' . $e->getMessage());
        }

        return $response;
    }
}
