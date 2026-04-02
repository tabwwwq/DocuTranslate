# Contributing to DocuTranslate

Thank you for considering contributing to **DocuTranslate**! Contributions of all kinds are welcome — bug reports, feature requests, documentation improvements, and code changes.

[English](#english) · [Русский](#русский)

---

## English

### Getting Started

1. **Fork** the repository and clone your fork locally.
2. Create a new branch for your change:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes, test them, and commit.
4. Push to your fork and open a **Pull Request** against the `main` branch.

### Code Style

- Follow **PSR-12** coding standards for PHP.
- Use 4-space indentation (no tabs).
- Keep functions small and focused on a single responsibility.
- All public functions should have a clear, descriptive name.
- Avoid committing debug output (`var_dump`, `print_r`, etc.).

### Commit Messages

Use clear, descriptive commit messages in imperative mood:

```
Add Gmail attachment size validation
Fix session not destroyed on logout
Update README with Docker instructions
```

### Reporting Bugs

When filing a bug report, please include:
- PHP and MySQL version
- Browser and OS (if UI-related)
- Steps to reproduce the issue
- Expected vs actual behaviour
- Any relevant error messages or logs

### Feature Requests

Open a GitHub Issue with the label `enhancement` and describe:
- The problem you are trying to solve
- Your proposed solution
- Any alternatives you considered

### Security Issues

**Do not** open a public issue for security vulnerabilities. Instead, contact the maintainer directly via GitHub.

### Pull Request Checklist

Before submitting a PR, please verify:

- [ ] Code follows PSR-12 style
- [ ] No real API keys, tokens, or personal data are included
- [ ] `config.php` still contains only masked values (`'********'`)
- [ ] New features are documented in `README.md`
- [ ] Existing functionality is not broken

---

## Русский

### С чего начать

1. **Сделайте форк** репозитория и склонируйте его локально.
2. Создайте новую ветку для своих изменений:
   ```bash
   git checkout -b feature/название-вашей-функции
   ```
3. Внесите изменения, протестируйте их и сделайте коммит.
4. Запушьте в свой форк и откройте **Pull Request** в ветку `main`.

### Стиль кода

- Придерживайтесь стандарта **PSR-12** для PHP.
- Используйте отступы в 4 пробела (без табуляции).
- Функции должны быть небольшими и выполнять одну задачу.
- Не коммитьте отладочный вывод (`var_dump`, `print_r` и т.д.).

### Сообщения коммитов

Пишите чёткие и понятные сообщения коммитов в повелительном наклонении (на русском или английском):

```
Добавить валидацию размера вложений Gmail
Исправить сессию, не уничтожаемую при выходе
Обновить README с инструкциями для Docker
```

### Сообщения об ошибках

При сообщении об ошибке укажите:
- Версию PHP и MySQL
- Браузер и ОС (если проблема в UI)
- Шаги для воспроизведения
- Ожидаемое и фактическое поведение
- Сообщения об ошибках или логи

### Запросы на новые функции

Откройте Issue с меткой `enhancement` и опишите:
- Проблему, которую вы хотите решить
- Предлагаемое решение
- Альтернативы, которые вы рассматривали

### Проблемы безопасности

**Не** открывайте публичный Issue для уязвимостей безопасности. Свяжитесь с мейнтейнером напрямую через GitHub.

### Чек-лист перед PR

Перед отправкой PR убедитесь:

- [ ] Код соответствует стандарту PSR-12
- [ ] В коде нет реальных API-ключей, токенов или личных данных
- [ ] В `config.php` по-прежнему только замаскированные значения (`'********'`)
- [ ] Новая функциональность задокументирована в `README.md`
- [ ] Существующая функциональность не сломана
