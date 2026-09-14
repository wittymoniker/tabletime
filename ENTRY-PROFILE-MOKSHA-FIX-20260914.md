# Tabletime entry/profile fix — 2026-09-14

- Instructional guidance is displayed on the entry, login, and registration pages.
- Selected profile pages now resolve the requested user directly instead of depending on the first 100 People results.
- Moksha controls therefore appear on profile views for any valid non-self user when the account_ratings schema is present.
- Profile picture fallback recognizes profile post types case-insensitively and uses the newest image attachment.
- Karma remains post-only; Moksha remains profile-only; Message remains a post type; replies use Comment.
