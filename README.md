<div align="center">

# 📄 DocuTranslate

**Intelligent Document Translation & Analysis powered by Google Gemini AI**

[![Last Commit](https://img.shields.io/github/last-commit/tabwwwq/DocuTranslate)](https://github.com/tabwwwq/DocuTranslate/commits)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-orange?logo=mysql)](https://www.mysql.com/)
[![Gemini AI](https://img.shields.io/badge/Gemini-AI-4285F4?logo=google)](https://ai.google.dev/)

[English](#english) · [Русский](#русский)

</div>

---

## English

### 📖 About

**DocuTranslate** is an open-source PHP web application that allows you to upload documents (images and PDFs), translate them into any language, and get a structured AI-powered analysis — all powered by Google Gemini AI. It also integrates with Gmail to let you analyse and translate email attachments directly from your inbox.

### ✨ Features

- 📤 **Document Upload** — supports JPG, PNG and PDF files up to 20 MB
- 🌍 **AI Translation** — translate documents into any language using Google Gemini
- 🔍 **Document Analysis** — get a summary, document type and key points
- 📧 **Gmail Integration** — connect your Gmail account and analyse email attachments
- 🔐 **User Authentication** — register/login with email and password
- 🌐 **Multi-language UI** — interface adapts to user's preferred language
- 🔒 **Secure** — API keys masked in config, session-based auth

### 🛠 Tech Stack

| Layer      | Technology                          |
|------------|-------------------------------------|
| Backend    | PHP 8.0+                            |
| Database   | MySQL 8.0+ / MariaDB                |
| AI Engine  | Google Gemini API (`gemini-2.5-flash`) |
| Auth       | Google OAuth 2.0 (Gmail)            |
| Server     | Apache (XAMPP recommended for local)|

### 🚀 Installation

#### Prerequisites

- PHP 8.0 or higher with extensions: `pdo`, `pdo_mysql`, `curl`, `fileinfo`
- MySQL 8.0+ or MariaDB
- Apache with `mod_rewrite` enabled (XAMPP works out of the box)
- A [Google Cloud](https://console.cloud.google.com/) project with:
  - **Gemini API** enabled → get a key at [Google AI Studio](https://aistudio.google.com/app/apikey)
  - **Google OAuth 2.0** credentials (for Gmail integration)

#### Step-by-step

1. **Clone the repository**

   ```bash
   git clone https://github.com/tabwwwq/DocuTranslate.git
   cd DocuTranslate
   ```

2. **Place the project in your web root**

   ```text
   C:\xampp\htdocs\docutranslate\   (Windows / XAMPP)
   /var/www/html/docutranslate/     (Linux / Apache)
   ```

3. **Start Apache and MySQL** (XAMPP Control Panel or `systemctl`)

4. **Create the database and run the schema**

   ```sql
   -- In phpMyAdmin or MySQL CLI:
   source database.sql;
   ```

5. **Configure `config.php`** — replace the masked placeholders with your real keys:

   ```php
   define('GEMINI_API_KEY',      'AIzaSy...');   // From Google AI Studio
   define('GOOGLE_CLIENT_ID',    '12345....apps.googleusercontent.com');
   define('GOOGLE_CLIENT_SECRET','GOCSPX-...');
   define('GOOGLE_REDIRECT_URI', 'http://localhost/docutranslate/api/gmail_callback.php');
   ```

6. **Open in browser**

   ```
   http://localhost/docutranslate/
   ```

> ⚠️ **Never commit real API keys to Git.** Keep `config.php` with masked values (`'********'`) when pushing.

### 📡 API Reference

All API endpoints are located in the `api/` directory and return JSON.

#### Authentication — `api/auth.php`

| Method | Action            | Body / Params                          | Description              |
|--------|-------------------|----------------------------------------|--------------------------|
| POST   | `?action=register`| `{name, email, password, language}`    | Register a new user      |
| POST   | `?action=login`   | `{email, password}`                    | Login                    |
| POST   | `?action=logout`  | —                                      | Logout                   |
| GET    | `?action=check`   | —                                      | Check session status     |
| POST   | `?action=update_language` | `{language}`                 | Update preferred language|

#### Document Analysis — `api/analyze.php`

Requires authentication (active session).

| Method | Action             | Body / Params                          | Description              |
|--------|--------------------|----------------------------------------|--------------------------|
| POST   | `?action=upload`   | `multipart: file`                      | Upload a document        |
| POST   | `?action=translate`| `{filename, language}`                 | Translate document       |
| POST   | `?action=analyze`  | `{filename, language}`                 | Analyse document         |

**Translate response:**
```json
{
  "success": true,
  "result": {
    "short_description": "Invoice from Acme Corp for March 2024",
    "translation": "..."
  }
}
```

**Analyse response:**
```json
{
  "success": true,
  "result": {
    "summary": "...",
    "document_type": "Invoice",
    "key_points": ["Point 1", "Point 2"]
  }
}
```

#### Gmail Integration — `api/gmail_*.php`

| Endpoint                    | Description                          |
|-----------------------------|--------------------------------------|
| `api/gmail_connect.php`     | Start Google OAuth flow              |
| `api/gmail_callback.php`    | OAuth redirect callback              |
| `api/gmail_disconnect.php`  | Disconnect Gmail account             |
| `api/gmail_inbox.php`       | List inbox messages                  |
| `api/gmail_message.php`     | Get a single message                 |
| `api/gmail_analyze.php`     | Analyse/translate a Gmail attachment |

### 📂 Project Structure

```
DocuTranslate/
├── api/
│   ├── analyze.php          # Document upload, translate & analyse
│   ├── analyze_shared.php   # Shared Gemini API helpers
│   ├── auth.php             # User registration, login, logout
│   ├── gmail.php            # Gmail API helpers
│   ├── gmail_analyze.php    # Analyse Gmail attachments
│   ├── gmail_callback.php   # Google OAuth callback
│   ├── gmail_connect.php    # Initiate Gmail OAuth flow
│   ├── gmail_disconnect.php # Disconnect Gmail
│   ├── gmail_inbox.php      # Fetch inbox
│   └── gmail_message.php    # Fetch single message
├── uploads/                 # Uploaded files (git-ignored)
├── config.php               # App configuration (keys masked)
├── database.sql             # Database schema
├── index.php                # Main frontend application
├── .gitignore
├── CONTRIBUTING.md
├── LICENSE
└── README.md
```

### 🤝 Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

### 📄 License

This project is licensed under the [MIT License](LICENSE).

---

## Русский

### 📖 О проекте

**DocuTranslate** — веб-приложение с открытым исходным кодом на PHP, позволяющее загружать документы (изображения и PDF), переводить их на любой язык и получать структурированный AI-анализ с помощью Google Gemini AI. Также поддерживается интеграция с Gmail: можно анализировать и переводить вложения писем прямо из почтового ящика.

### ✨ Возможности

- 📤 **Загрузка документов** — поддержка JPG, PNG и PDF файлов до 20 МБ
- 🌍 **AI-перевод** — перевод документов на любой язык через Google Gemini
- 🔍 **Анализ документов** — краткое содержание, тип документа и ключевые тезисы
- 📧 **Интеграция с Gmail** — подключите аккаунт Gmail и анализируйте вложения писем
- 🔐 **Аутентификация** — регистрация и вход по email и паролю
- 🌐 **Мультиязычный интерфейс** — адаптируется под предпочитаемый язык пользователя
- 🔒 **Безопасность** — API-ключи замаскированы в конфиге, сессионная аутентификация

### 🚀 Установка

#### Требования

- PHP 8.0 или выше с расширениями: `pdo`, `pdo_mysql`, `curl`, `fileinfo`
- MySQL 8.0+ или MariaDB
- Apache с включённым `mod_rewrite` (XAMPP подходит «из коробки»)
- Проект в [Google Cloud Console](https://console.cloud.google.com/) с:
  - Включённым **Gemini API** → ключ на [Google AI Studio](https://aistudio.google.com/app/apikey)
  - Учётными данными **Google OAuth 2.0** (для Gmail)

#### Пошаговая инструкция

1. **Клонируйте репозиторий**

   ```bash
   git clone https://github.com/tabwwwq/DocuTranslate.git
   cd DocuTranslate
   ```

2. **Разместите проект в корне веб-сервера**

   ```text
   C:\xampp\htdocs\docutranslate\   (Windows / XAMPP)
   /var/www/html/docutranslate/     (Linux / Apache)
   ```

3. **Запустите Apache и MySQL** (через XAMPP Control Panel или `systemctl`)

4. **Создайте базу данных и выполните схему**

   ```sql
   -- В phpMyAdmin или MySQL CLI:
   source database.sql;
   ```

5. **Настройте `config.php`** — замените заглушки на реальные ключи:

   ```php
   define('GEMINI_API_KEY',      'AIzaSy...');   // Из Google AI Studio
   define('GOOGLE_CLIENT_ID',    '12345....apps.googleusercontent.com');
   define('GOOGLE_CLIENT_SECRET','GOCSPX-...');
   define('GOOGLE_REDIRECT_URI', 'http://localhost/docutranslate/api/gmail_callback.php');
   ```

6. **Откройте в браузере**

   ```
   http://localhost/docutranslate/
   ```

> ⚠️ **Никогда не коммитьте реальные API-ключи в Git.** В `config.php` должны оставаться только замаскированные значения (`'********'`).

### 📡 API-справочник

Все эндпоинты находятся в директории `api/` и возвращают JSON.

#### Аутентификация — `api/auth.php`

| Метод | Действие              | Тело / Параметры                        | Описание                     |
|-------|-----------------------|-----------------------------------------|------------------------------|
| POST  | `?action=register`    | `{name, email, password, language}`     | Регистрация нового пользователя |
| POST  | `?action=login`       | `{email, password}`                     | Вход в систему               |
| POST  | `?action=logout`      | —                                       | Выход из системы             |
| GET   | `?action=check`       | —                                       | Проверка сессии              |
| POST  | `?action=update_language` | `{language}`                        | Обновить язык интерфейса     |

#### Анализ документов — `api/analyze.php`

Требует активной сессии.

| Метод | Действие            | Тело / Параметры               | Описание                 |
|-------|---------------------|--------------------------------|--------------------------|
| POST  | `?action=upload`    | `multipart: file`              | Загрузить документ       |
| POST  | `?action=translate` | `{filename, language}`         | Перевести документ       |
| POST  | `?action=analyze`   | `{filename, language}`         | Проанализировать документ|

#### Gmail — `api/gmail_*.php`

| Эндпоинт                    | Описание                               |
|-----------------------------|----------------------------------------|
| `api/gmail_connect.php`     | Начать OAuth-авторизацию Google        |
| `api/gmail_callback.php`    | Обратный вызов OAuth                   |
| `api/gmail_disconnect.php`  | Отключить Gmail-аккаунт                |
| `api/gmail_inbox.php`       | Получить список писем                  |
| `api/gmail_message.php`     | Получить одно письмо                   |
| `api/gmail_analyze.php`     | Анализировать/переводить вложение Gmail|

### 🔒 Безопасность

- Реальные API-ключи в открытых файлах **отсутствуют** — в `config.php` используются заглушки `'********'`
- Загруженные файлы исключены из Git через `.gitignore`
- Перед публикацией всегда проверяйте, что в `config.php` нет реальных ключей

### 🤝 Участие в проекте

Вклад приветствуется! Пожалуйста, прочитайте [CONTRIBUTING.md](CONTRIBUTING.md).

### 📄 Лицензия

Проект распространяется под лицензией [MIT](LICENSE).
