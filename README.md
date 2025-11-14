# IP Whitelist for Statamic

A comprehensive IP whitelist addon for Statamic CMS that provides robust access control for your control panel and custom routes.

## Features

- 🛡️ **Control Panel Protection** - Automatically protects Statamic CP routes
- 🎨 **Dark/Light Mode Support** - Seamless integration with Statamic's theme system
- 💾 **Flexible Storage** - Choose between file-based or database storage
- 🌐 **Advanced IP Matching** - Supports CIDR notation, wildcards, and exact matches
- ⚡ **Artisan Commands** - Manage whitelist via command line
- 🔧 **Configurable Routes** - Protect additional custom routes
- 🏠 **Local Development Bypass** - Optional bypass for local environments
- 📊 **Beautiful Interface** - Modern, responsive control panel interface

## Installation

1. `composer require stokoe/ip-whitelist`

2. Publish the configuration file:
```bash
php artisan vendor:publish --tag=ip-whitelist-config
```

3. Run migrations (if using database storage):
```bash
php artisan migrate
```

4. Configure your settings in `config/ip-whitelist.php`

## Configuration

### Storage Options

Choose between file or database storage:

```php
// File storage (default)
'storage' => 'file',

// Database storage
'storage' => 'database', // Don't forget to run the migrations!
```

### Protected Routes

Add additional routes to protect:

```php
'protected_routes' => [
    'admin/*',
    'api/admin/*',
    'custom-admin/*',
],
```

### Local Development

Bypass whitelist in local environment:

```php
'bypass_local' => true, // Set to false to enforce in local
```

## Usage

### Control Panel Interface

1. Navigate to **Tools > IP Whitelist** in the Statamic control panel
2. Add, edit, or remove IP addresses through the intuitive interface
3. View statistics and current IP information

### Artisan Commands

Add an IP address:
```bash
php artisan ip-whitelist:manage add 192.168.1.100 --name="Office Network"
```

Remove an IP address:
```bash
php artisan ip-whitelist:manage remove 192.168.1.100
```

List all whitelisted IPs:
```bash
php artisan ip-whitelist:manage list
```

### IP Address Formats

The addon supports multiple IP address formats:

- **Exact IP**: `192.168.1.100`
- **CIDR Notation**: `192.168.1.0/24`
- **Wildcards**: `192.168.1.*` (Allows 192.168.1.1, 192.168.1.2, [...], 192.168.1.255)

## Permissions

The addon creates a `manage ip whitelist` permission. Assign this to users who should be able to manage the IP whitelist.

## Security Considerations

- Always add your current IP before enabling the whitelist
- Test access from different locations before deploying
- Consider using CIDR notation for office networks

## File Storage Location

When using file storage, IP addresses are stored in:
```
storage/app/ip-whitelist.json
```

## Database Storage

When using database storage, IP addresses are stored in the `whitelisted_ips` table with the following structure:

- `id` - Primary key
- `ip` - IP address or pattern
- `name` - Optional description
- `active` - Boolean status
- `user_id` - The ID of the user who added the IP
- `created_at` / `updated_at` - Timestamps

## Troubleshooting

### Locked Out of Control Panel

If you're locked out:

1. Add your IP via Artisan command:
```bash
php artisan ip-whitelist:manage add YOUR_IP_ADDRESS
```

2. Or temporarily disable the middleware by setting `bypass_local` to `true` in local environment (Only works in local dev)

3. Or directly edit the storage file/database to add your IP

### Local Development Issues

Ensure `bypass_local` is set to `true` in your configuration for local development.

## Support

For issues or feature requests, please check the addon documentation or raise a Github issue.
