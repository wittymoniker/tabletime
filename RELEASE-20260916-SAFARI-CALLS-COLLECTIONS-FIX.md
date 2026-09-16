# Tabletime — Safari session + persistent Calls + Group/Event collections — 2026-09-16

- Stable v9 auth cookie: no random auth-cookie rewrite on every page; renewal occurs only near expiry.
- Calls nav updates as Calls (n calls); incoming calls ring and follow the user across Tabletime.
- Dedicated call windows keep camera/audio alive while the main Tabletime page navigates.
- Up to three concurrent active calls per account.
- Call scopes: ad-hoc/default, private, Group, Event, Forum and Tag.
- Safari/iPhone media acquisition happens only after a direct Join / enable camera tap.
- Default/ad-hoc calls work without selecting a Group or Event.
- Groups are gallery-first; Events are calendar-first with date parsing from date tags/text/filenames and post-date fallback, plus yearly recurrence projection.
- TURN remains optional fallback; direct WebRTC remains default. A Discord-style SFU still requires a separate media service.
