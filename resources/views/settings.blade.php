@extends('statamic::layout')
@section('title', 'IP Whitelist Settings')

@push('head')
    @include('ip-whitelist::partials.head')
@endpush

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="flex-1 text-3xl font-bold">IP Whitelist Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Configure IP whitelist behavior and storage options</p>
    </div>
    <a href="{{ cp_route('ip-whitelist.index') }}" class="btn">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to IP List
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Storage Configuration -->
    <div class="card">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Storage Configuration</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Storage Type</label>
                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md">
                        <code class="text-sm">{{ ucfirst($settings['storage']) }}</code>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Configure in <code>config/ip-whitelist.php</code>
                    </p>
                </div>

                @if($settings['storage'] === 'file')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Location</label>
                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md">
                        <code class="text-sm">{{ config('ip-whitelist.file_path') }}</code>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="card">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Security Settings</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Local Bypass</label>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $settings['bypass_local'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $settings['bypass_local'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        When enabled, IP whitelist is bypassed in local environment
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Default Allowed IPs -->
    <div class="card">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Default Allowed IPs</h3>
            
            <div class="space-y-2">
                @forelse($settings['default_allowed_ips'] as $ip)
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                        <code class="text-sm">{{ $ip }}</code>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Always allowed</span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No default IPs configured</p>
                @endforelse
            </div>
            
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                These IPs are always allowed, regardless of whitelist settings
            </p>
        </div>
    </div>

    <!-- Protected Routes -->
    <div class="card">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Protected Routes</h3>
            
            <div class="space-y-2">
                <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900 p-3 rounded-md">
                    <code class="text-sm">{{ config('statamic.cp.route', 'cp') }}/*</code>
                    <span class="text-xs text-blue-600 dark:text-blue-400">Control Panel (automatic)</span>
                </div>
                
                @forelse($settings['protected_routes'] as $route)
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                        <code class="text-sm">{{ $route }}</code>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Custom</span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No additional routes configured</p>
                @endforelse
            </div>
            
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                Configure additional routes in <code>config/ip-whitelist.php</code>
            </p>
        </div>
    </div>
</div>

<!-- Configuration Help -->
<div class="card mt-6">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Configuration Help</h3>
        
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                To modify these settings, edit your <code>config/ip-whitelist.php</code> file or set the appropriate environment variables:
            </p>
            
            <div class="mt-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-md">
                <pre class="text-xs"><code># Environment Variables
IP_WHITELIST_STORAGE=file
IP_WHITELIST_BYPASS_LOCAL=true

# Or edit config/ip-whitelist.php directly
'storage' => 'database',
'bypass_local' => false,
'protected_routes' => [
    'admin/*',
    'api/admin/*',
],</code></pre>
            </div>
        </div>
    </div>
</div>
@endsection
