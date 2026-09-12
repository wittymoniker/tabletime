# Tabletime — GitHub-ready source

Current hardened Tabletime PHP/MySQL source from the Eski/Wasmer site bundle (2026-09-12).

## Quick start

1. Attach or create a MySQL/MariaDB database.
2. For local/shared hosting, copy `tabletime/config/db_config.example.php` to `tabletime/config/db_config.php` and enter local credentials. Do **not** commit the real file.
3. Serve the repository root (or move `tabletime/` under your document root).
4. Visit `tabletime/setup.php` once. Setup preserves existing data, performs additive legacy-schema upgrades, and runs prepare/write/read/cleanup self-tests.

On Wasmer, Tabletime can use managed database environment variables instead of `db_config.php`.

The package intentionally includes source and schema material, but no live database credentials or generated runtime files.

## 2026-09-12 persistent sessions / calls / notifications

This build adds renewable long-lived login sessions, private/group/event WebRTC video calls, browser notifications, authenticated MySQL signaling, and group/event invite membership. Optional TURN relay configuration uses `TT_TURN_URL`, `TT_TURN_USERNAME`, and `TT_TURN_CREDENTIAL`.
