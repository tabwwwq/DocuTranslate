# DocuTranslate — GitHub Safe Setup Guide

## What was removed

This archive was cleaned for GitHub upload:

- Gemini API key removed
- Google OAuth client ID removed
- Google OAuth client secret removed
- Uploaded files removed
- No personal tokens or private credentials included

## Config file

Open `config.php` and replace the masked values:

- `GEMINI_API_KEY = '********'`
- `GOOGLE_CLIENT_ID = '********'`
- `GOOGLE_CLIENT_SECRET = '********'`

Key format examples are already included in the same file:

- `GEMINI_API_KEY_FORMAT`
- `GOOGLE_CLIENT_ID_FORMAT`
- `GOOGLE_CLIENT_SECRET_FORMAT`

## Local setup

1. Put the project in:

```text
C:\xampp\htdocs\docutranslate\
```

2. Start Apache and MySQL in XAMPP.

3. Create the database `docutranslate` and run `database.sql`.

4. Add your own keys in `config.php`.

5. Open:

```text
http://localhost/docutranslate/
```

## Important

Do not upload real keys to GitHub.
Before pushing again, check `config.php` so it still contains only masked values.
