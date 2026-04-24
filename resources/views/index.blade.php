@php
    $editing = $editingEntry ?? null;
    $isEditing = filled($editing);
    $formAction = $isEditing ? cp_route('ip-whitelist.update') : cp_route('ip-whitelist.store');
    $formIpValue = old('ip', $editing['ip'] ?? $currentIp);
    $formNameValue = old('name', $editing['name'] ?? '');
@endphp

@extends('statamic::layout')

@section('title', 'IP Whitelist')

@push('head')
    <style>
        .ip-whitelist-page {
            padding-top: 18px;
        }

        @media (min-width: 768px) {
            .ip-whitelist-page {
                padding-top: 28px;
            }
        }

        .ip-whitelist-page .ip-whitelist-header {
            margin-bottom: 34px;
        }

        .ip-whitelist-page .ip-whitelist-title {
            margin: 0;
            color: var(--gray-900, #111827);
            font-size: 30px;
            font-weight: 700;
            line-height: 1.15;
        }

        .ip-whitelist-page .ip-whitelist-subtitle {
            margin-top: 7px;
            color: var(--gray-600, #4b5563);
            font-size: 15px;
            line-height: 1.5;
        }

        .ip-whitelist-page .ip-whitelist-shell {
            display: grid;
            gap: 24px;
        }

        @media (min-width: 1280px) {
            .ip-whitelist-page .ip-whitelist-shell {
                grid-template-columns: minmax(0, 1fr) 24rem;
                align-items: start;
            }
        }

        .ip-whitelist-page .ip-whitelist-form-card {
            border: 1px solid rgba(115, 130, 140, 0.24);
            border-radius: 6px;
            background: var(--white, #fff);
            box-shadow: 0 1px 2px rgba(17, 24, 39, 0.04);
        }

        .ip-whitelist-page .form-group {
            margin-bottom: 18px;
        }

        .ip-whitelist-page .form-group label {
            display: block;
            margin-bottom: 7px;
            color: var(--gray-800, #1f2937);
            font-size: 14px;
            font-weight: 600;
        }

        .ip-whitelist-page .input-text {
            display: block;
            width: 100%;
            min-height: 42px;
            padding: 9px 12px;
            border: 1px solid rgba(115, 130, 140, 0.42);
            border-radius: 4px;
            background: var(--white, #fff);
            color: var(--gray-900, #111827);
            font-size: 14px;
            line-height: 1.4;
            box-shadow: inset 0 1px 1px rgba(17, 24, 39, 0.03);
            transition: border-color 120ms ease, box-shadow 120ms ease;
        }

        .ip-whitelist-page .input-text::placeholder {
            color: var(--gray-500, #6b7280);
        }

        .ip-whitelist-page .input-text:focus {
            outline: 0;
            border-color: var(--blue, #4c8bf5);
            box-shadow: 0 0 0 3px rgba(76, 139, 245, 0.18), inset 0 1px 1px rgba(17, 24, 39, 0.03);
        }

        .ip-whitelist-page .help-block {
            margin-top: 7px;
            color: var(--gray-600, #4b5563);
            font-size: 13px;
            line-height: 1.4;
        }

        .ip-whitelist-page .ip-whitelist-form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 20px;
        }

        .ip-whitelist-page .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 7px 13px;
            border: 1px solid rgba(115, 130, 140, 0.34);
            border-radius: 4px;
            background: var(--white, #fff);
            color: var(--gray-800, #1f2937);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.2;
            text-decoration: none;
            box-shadow: 0 1px 1px rgba(17, 24, 39, 0.04);
            cursor: pointer;
        }

        .ip-whitelist-page .btn:hover,
        .ip-whitelist-page .btn:focus {
            border-color: rgba(76, 139, 245, 0.58);
            color: var(--gray-900, #111827);
            text-decoration: none;
        }

        .ip-whitelist-page .btn-primary {
            border-color: var(--blue, #4c8bf5);
            background: var(--blue, #4c8bf5);
            color: #fff;
        }

        .ip-whitelist-page .btn-primary:hover,
        .ip-whitelist-page .btn-primary:focus {
            background: #3f7be0;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="ip-whitelist-page">
        <header class="ip-whitelist-header flex items-start justify-between gap-4">
            <div>
                <h1 class="ip-whitelist-title">IP Whitelist</h1>
                <p class="ip-whitelist-subtitle">Manage which IPs may access the Statamic control panel.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ cp_route('ip-whitelist.index') }}" class="btn {{ $isEditing ? '' : 'btn-primary' }}">
                    {{ $isEditing ? 'Back to list' : 'Refresh' }}
                </a>
                <a href="{{ cp_route('ip-whitelist.settings') }}" class="btn">Settings</a>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-6">
                <h2 class="mb-2 text-base font-bold">There is a problem with this request</h2>
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="ip-whitelist-shell">
        <div class="space-y-6">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="card p-4">
                    <p class="mb-1 text-xs uppercase text-gray-600">Entries</p>
                    <p class="text-2xl font-bold">{{ count($ips) }}</p>
                </div>

                <div class="card p-4">
                    <p class="mb-1 text-xs uppercase text-gray-600">Current IP</p>
                    <p class="font-mono text-sm">{{ $currentIp }}</p>
                </div>

                <div class="card p-4">
                    <p class="mb-1 text-xs uppercase text-gray-600">Storage</p>
                    <p class="font-medium">{{ ucfirst(config('ip-whitelist.storage', 'file')) }}</p>
                    <p class="mt-1 text-xs text-gray-600">
                        {{ config('ip-whitelist.bypass_local') ? 'Local bypass on' : 'Local bypass off' }}
                    </p>
                </div>
            </div>

            <div class="card p-0">
                <div class="border-b p-4">
                    <h2 class="mb-1 text-lg font-bold">Whitelisted addresses</h2>
                    <p class="text-sm text-gray-700">Exact IPs, CIDR ranges, and wildcard patterns are supported.</p>
                </div>

                @if (empty($ips))
                    <div class="p-8 text-center">
                        <h3 class="mb-2 text-base font-bold">No addresses added yet</h3>
                        <p class="text-sm text-gray-700">
                            Add your current IP first so you can safely enable stricter access control.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="data-table w-full">
                            <thead>
                                <tr>
                                    <th>IP address</th>
                                    <th>Name</th>
                                    <th>Added by</th>
                                    <th>Added</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ips as $ip)
                                    <tr>
                                        <td>
                                            <code>{{ $ip['ip'] }}</code>
                                            @if ($ip['ip'] === $currentIp)
                                                <span class="badge ml-1">Current</span>
                                            @endif
                                        </td>
                                        <td>{{ $ip['name'] ?: '-' }}</td>
                                        <td>{{ $ip['added_by'] ?: '-' }}</td>
                                        <td>
                                            {{ $ip['created_at'] ? \Carbon\Carbon::parse($ip['created_at'])->format('M j, Y g:i A') : '-' }}
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ cp_route('ip-whitelist.index', ['edit' => $ip['ip']]) }}" class="btn btn-sm">
                                                    Edit
                                                </a>

                                                <form method="POST" action="{{ cp_route('ip-whitelist.destroy') }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="ip" value="{{ $ip['ip'] }}">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="ip-whitelist-form-card p-4">
            <div class="mb-4 flex items-start justify-between gap-3 border-b pb-4">
                <div>
                    <h2 class="mb-1 text-lg font-bold">{{ $isEditing ? 'Edit address' : 'Add address' }}</h2>
                    <p class="text-sm text-gray-700">
                        {{ $isEditing ? 'Update the selected whitelist entry.' : 'Add an address or range to the whitelist.' }}
                    </p>
                </div>
                <span class="badge">{{ $isEditing ? 'Editing' : 'Ready' }}</span>
            </div>

            <form method="POST" action="{{ $formAction }}">
                @csrf
                @if ($isEditing)
                    @method('PUT')
                    <input type="hidden" name="original_ip" value="{{ $editing['ip'] }}">
                @endif

                <div class="form-group">
                    <label for="ip">IP address or pattern</label>
                    <input
                        id="ip"
                        class="input-text"
                        name="ip"
                        value="{{ $formIpValue }}"
                        placeholder="192.168.1.1, 192.168.1.0/24, or 192.168.1.*"
                    >
                    <p class="help-block">Supports exact IPs, CIDR ranges, and wildcard patterns.</p>
                </div>

                <div class="form-group">
                    <label for="name">Label</label>
                    <input
                        id="name"
                        class="input-text"
                        name="name"
                        value="{{ $formNameValue }}"
                        placeholder="Office, VPN, home network"
                    >
                </div>

                <div class="ip-whitelist-form-actions">
                    <a href="{{ cp_route('ip-whitelist.index') }}" class="btn">
                        {{ $isEditing ? 'Cancel' : 'Clear' }}
                    </a>

                    <button type="submit" class="btn btn-primary">
                        {{ $isEditing ? 'Save changes' : 'Add to whitelist' }}
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
@endsection
