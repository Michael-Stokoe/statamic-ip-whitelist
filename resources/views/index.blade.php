@extends('statamic::layout')
@section('title', 'IP Whitelist')

@push('head')
    @include('ip-whitelist::partials.head')
@endpush

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="flex-1 text-3xl font-bold">IP Whitelist</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage IP addresses that can access the control panel</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ cp_route('ip-whitelist.settings') }}" class="btn">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Settings
        </a>
        <button id="addIpBtn" class="btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add IP Address
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total IPs</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($ips) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Your IP</p>
                <p class="text-lg font-mono text-gray-900 dark:text-white">{{ $currentIp }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Storage</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst(config('ip-whitelist.storage', 'file')) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Local Bypass</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ config('ip-whitelist.bypass_local') ? 'Enabled' : 'Disabled' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card p-0">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Whitelisted IP Addresses</h2>
            <div class="flex items-center space-x-4">
                <button id="addCurrentIpBtn" class="btn btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Current IP
                </button>
            </div>
        </div>
    </div>

    @if(empty($ips))
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No IP addresses whitelisted</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Get started by adding your first IP address to the whitelist.</p>
            <div class="mt-6">
                <button id="addIpBtnEmpty" class="btn-primary">Add IP Address</button>
            </div>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Added</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($ips as $ip)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-md font-mono text-sm">{{ $ip['ip'] }}</code>
                                @if($ip['ip'] === $currentIp)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Current
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $ip['name'] ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $ip['created_at'] ? \Carbon\Carbon::parse($ip['created_at'])->format('M j, Y g:i A') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="editIp('{{ $ip['ip'] }}', '{{ $ip['name'] }}')" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    Edit
                                </button>
                                <button onclick="deleteIp('{{ $ip['ip'] }}')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Add/Edit Modal -->
    <div id="ipModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModals()"></div>
            
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 id="modalTitle" class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Add IP Address</h3>
                        
                        <form id="ipForm" class="mt-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">IP Address</label>
                                <input 
                                    type="text" 
                                    id="ipInput"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                    placeholder="192.168.1.1 or 192.168.1.0/24"
                                    required
                                >
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Supports CIDR notation (192.168.1.0/24) and wildcards (192.168.1.*)</p>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name (Optional)</label>
                                <input 
                                    type="text" 
                                    id="nameInput"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                    placeholder="Office network, Home IP, etc."
                                >
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button id="submitBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Add</button>
                    <button onclick="closeModals()" class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('ipModal');
    const modalTitle = document.getElementById('modalTitle');
    const ipInput = document.getElementById('ipInput');
    const nameInput = document.getElementById('nameInput');
    const submitBtn = document.getElementById('submitBtn');
    const ipForm = document.getElementById('ipForm');
    
    let isEditMode = false;
    let originalIp = '';

    // Event listeners
    document.getElementById('addIpBtn').addEventListener('click', showAddModal);
    document.getElementById('addCurrentIpBtn').addEventListener('click', addCurrentIp);
    document.getElementById('addIpBtnEmpty')?.addEventListener('click', showAddModal);
    document.getElementById('submitBtn').addEventListener('click', submitForm);
    ipForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm();
    });

    function showAddModal() {
        isEditMode = false;
        modalTitle.textContent = 'Add IP Address';
        submitBtn.textContent = 'Add';
        ipInput.value = '';
        nameInput.value = '';
        originalIp = '';
        modal.style.display = 'block';
    }

    function addCurrentIp() {
        isEditMode = false;
        modalTitle.textContent = 'Add IP Address';
        submitBtn.textContent = 'Add';
        ipInput.value = '{{ $currentIp }}';
        nameInput.value = 'Current IP';
        originalIp = '';
        modal.style.display = 'block';
    }

    window.editIp = function(ip, name) {
        isEditMode = true;
        modalTitle.textContent = 'Edit IP Address';
        submitBtn.textContent = 'Update';
        ipInput.value = ip;
        nameInput.value = name || '';
        originalIp = ip;
        modal.style.display = 'block';
    };

    window.deleteIp = function(ip) {
        if (confirm('Are you sure you want to remove this IP address from the whitelist?\n\nWarning: If this is your current IP, you may lose access to the control panel.')) {
            fetch(`/cp/ip-whitelist/${encodeURIComponent(ip)}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error removing IP address');
                }
            })
            .catch(error => {
                alert('Error removing IP address');
            });
        }
    };

    function submitForm() {
        const url = isEditMode 
            ? `/cp/ip-whitelist/${encodeURIComponent(originalIp)}`
            : '/cp/ip-whitelist';
        
        const method = isEditMode ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                ip: ipInput.value,
                name: nameInput.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error saving IP address');
            }
        })
        .catch(error => {
            alert('Error saving IP address');
        });
    }

    window.closeModals = function() {
        modal.style.display = 'none';
        ipInput.value = '';
        nameInput.value = '';
        originalIp = '';
        isEditMode = false;
    };
});
</script>
@endsection
