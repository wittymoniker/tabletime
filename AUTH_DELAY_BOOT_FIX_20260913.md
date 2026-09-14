# Tabletime auth-delay boot fix — 2026-09-13

- Successful login/registration no longer creates a 10/30-minute cooldown.
- Login/register timing locks activate only after a failed attempt.
- Delay identity now combines REMOTE_ADDR with a durable per-browser token so edge/reverse-proxy deployments do not accidentally share one auth lock across visitors.
- Existing Karma/Moksha and action-delay formulas remain intact for failed attempts and non-auth actions.
