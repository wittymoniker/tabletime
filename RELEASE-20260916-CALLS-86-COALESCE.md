# Tabletime 2026-09-16 — Calls 86 / target coalescing

- Call rooms allow up to 86 simultaneously active participants.
- Users may remain active in up to three calls at once.
- Creating a call for an identical target reuses the existing active room by default instead of creating duplicate rooms.
- Private targets are canonicalized by sorted account IDs; Group/Event/Forum targets by stable object id; Tag targets by normalized tag; default/ad-hoc calls by creator.
- Reusing a room does not ring/re-notify the same audience again, reducing noise and signaling usage.
- Direct WebRTC remains the current media path with optional TURN fallback. An SFU/media relay is still required for Discord-scale media efficiency at very large participant counts.
