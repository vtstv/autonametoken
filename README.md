# Auto Name Token - Nextcloud App

**Author:** Murr  
**GitHub:** https://github.com/vtstv

Automatically uses the file name as the share link token when creating public shares.

## Installation

1. Copy this folder to your Nextcloud apps directory: `apps/autonametoken/`
2. Enable the app: `php occ app:enable autonametoken`
3. Or enable via Nextcloud web interface: Settings → Apps → "Auto Name Token"

## How it works

When you create a public share link, the app automatically:
- Uses the file name (without extension) as the share token
- Sanitizes the name (removes special characters, keeps alphanumeric, dash, underscore)
- Limits to 15 characters
- Checks for duplicate tokens and appends random numbers (10-99) if needed
- Falls back to MD5 hash if sanitized name is empty

## Example

File: `My Document.pdf`
Share link: `https://cloud.example.com/s/MyDocument`

Instead of: `https://cloud.example.com/s/a8Kx9pQz2`

If you share another file with the same name:
Share link: `https://cloud.example.com/s/MyDocument47`
