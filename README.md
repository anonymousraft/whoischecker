# Carbon Intelligence

**Domain Intelligence Platform** — WHOIS Lookup · Bulk Analysis · DNS Tools

---

## What's New (v2.0 - Complete Redesign)

**Carbon Intelligence** is a complete reimagining of the original Bulk WHOIS Checker. The application has been rebuilt from the ground up with a modern architecture and enhanced functionality.

### Brand Transformation

| Before | After |
|--------|-------|
| Bulk WHOIS Checker | **Carbon Intelligence** |
| Multi-page navigation | Single Page Application (SPA) |
| Bootstrap UI | Custom lightweight CSS |

### Architecture Changes

The codebase has been modernized with a clean MVC-style structure:

```
whoischecker/
├── public/              # Web root (point your server here)
│   ├── index.php        # Single entry point
│   └── assets/          # CSS, JS, images
├── src/                 # Application core
│   ├── Controllers/     # Request handlers
│   ├── Services/        # Business logic (WHOIS, DNS)
│   └── Kernel/          # Router, Container
├── routes/              # Route definitions
├── templates/           # View templates
└── vendor/              # Composer dependencies
```

---

## Features

### Single Domain Lookup
Instant WHOIS intelligence for any domain with comprehensive data:
- Domain Name & Registrar
- Owner Information
- Creation, Updated & Expiry Dates
- Name Servers & WHOIS Server
- Domain Status & DNSSEC

### Bulk Analysis Engine
Process thousands of domains efficiently:
- Upload `.txt` or `.csv` files (up to 5,000 domains)
- Real-time progress tracking
- CSV Export for complete reports

### DNS Resolver
Query DNS records across 11 record types:
- A, AAAA, CNAME, MX, TXT, NS, SOA, PTR, SRV, CAA, ANY

---

## Installation

### Requirements
- PHP 8.1 or higher
- Composer
- Web server (Apache, Nginx, or IIS)
- Internet connection for WHOIS queries

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/anonymousraft/whoischecker.git
   cd whoischecker
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure your web server** to point to the `/public` directory

4. **Access the application** at `http://your-domain/`

---

## Server Configuration

Configuration files are included for all major web servers:

- **Apache**: `.htaccess` in `/public` (requires `mod_rewrite`)
- **Nginx**: See `nginx.conf.example`
- **IIS**: `web.config` in `/public` (requires URL Rewrite Module)

---


## Configuration

You can customize the application by creating a `.env` file in the project root. Copy the included `.env.example` to start:

```bash
cp .env.example .env
```

### Available Options

| Option | Default | Description |
|--------|---------|-------------|
| `APP_DEBUG` | `false` | Enable detailed error messages |
| `BULK_DOMAIN_LIMIT` | `5000` | Maximum domains allowed per bulk upload |
| `BULK_RATE_DELAY` | `250` | Delay between bulk requests in ms |
| `WHOIS_TIMEOUT` | `10` | Timeout for WHOIS queries in seconds |
| `DNS_PROVIDER` | `cloudflare` | DNS provider (`cloudflare` or `google`) |

---

## License

GNU General Public License v3.0 — free to use, modify, and distribute.

---

## Author

Built by [@anonymousraft](https://github.com/anonymousraft)

---

## Contributing

Pull requests are welcome! Create an issue for bugs or feature requests.
