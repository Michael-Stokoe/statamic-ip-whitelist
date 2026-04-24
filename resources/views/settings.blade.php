@extends('statamic::layout')

@section('title', 'IP Whitelist Settings')

@section('content')
    <header class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1>IP Whitelist Settings</h1>
            <p class="text-sm text-gray-700">
                Review how the addon is configured and how to apply protection outside the control panel.
            </p>
        </div>

        <a href="{{ cp_route('ip-whitelist.index') }}" class="btn">Back to list</a>
    </header>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="card p-4">
            <h2 class="mb-1 text-lg font-bold">Storage</h2>
            <p class="text-sm text-gray-700">Choose where whitelist entries are stored.</p>

            <div class="mt-4 space-y-4 border-t pt-4">
                <div>
                    <p class="mb-1 text-xs uppercase text-gray-600">Driver</p>
                    <p class="font-medium">{{ ucfirst($settings['storage']) }}</p>
                </div>

                @if ($settings['storage'] === 'file')
                    <div>
                        <p class="mb-1 text-xs uppercase text-gray-600">File path</p>
                        <code class="block rounded bg-gray-200 px-3 py-2 text-xs">{{ config('ip-whitelist.file_path') }}</code>
                    </div>
                @endif
            </div>
        </div>

        <div class="card p-4">
            <h2 class="mb-1 text-lg font-bold">Security</h2>
            <p class="text-sm text-gray-700">Control how strict access rules are in development and production.</p>

            <div class="mt-4 space-y-4 border-t pt-4">
                <div>
                    <p class="mb-1 text-sm font-medium">Local bypass</p>
                    <p class="text-sm text-gray-700">Skip checks when the app environment is local.</p>
                    <span class="badge mt-2">{{ $settings['bypass_local'] ? 'Enabled' : 'Disabled' }}</span>
                </div>

                <div>
                    <p class="mb-2 text-xs uppercase text-gray-600">Always allowed</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($settings['default_allowed_ips'] as $ip)
                            <span class="badge">{{ $ip }}</span>
                        @empty
                            <p class="text-sm text-gray-700">No default IPs configured.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-4 lg:col-span-2">
            <h2 class="mb-1 text-lg font-bold">Applying the middleware</h2>
            <p class="text-sm text-gray-700">
                The control panel is protected automatically. For your own routes, apply the addon middleware explicitly so it wraps the real route definitions instead of creating shadow routes.
            </p>

            <div class="mt-4 space-y-4 border-t pt-4">
                <div>
                    <p class="mb-1 text-xs uppercase text-gray-600">Registered middleware alias</p>
                    <code class="block rounded bg-gray-200 px-3 py-2 text-xs">ip-whitelist</code>
                </div>

                <div>
                    <p class="mb-1 text-xs uppercase text-gray-600">Example</p>
                    <pre class="overflow-x-auto rounded bg-gray-200 px-4 py-3 text-xs"><code>Route::middleware(['web', 'ip-whitelist'])->prefix('admin')->group(function () {
    Route::get('/reports', AdminReportsController::class);
});</code></pre>
                </div>

                @if (! empty($settings['protected_routes']))
                    <div>
                        <p class="mb-2 text-xs uppercase text-gray-600">Configured route patterns</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($settings['protected_routes'] as $route)
                                <span class="badge">{{ $route }}</span>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm text-gray-700">
                            These are now informational only. Apply the middleware alias directly to the route groups you want to protect.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
