# Tabletime forum / reply interaction refresh — 2026-09-12

This build preserves the existing inline Comments disclosure and adds the following interaction rules.

- Clicking a non-interactive area of a post card toggles an expanded reading layout. Links, buttons, form controls, media controls, details/summary controls, and text selection do not toggle the post.
- Reply chooses the source post's mode (`message`, `forum`, `event`, `group`, `media`, or ordinary `post`). Comment chooses `comment` and continues to append to the target post's existing Comments field.
- Post replies carry a canonical `ID#:<post-id>` tag. The server restores this tag even if it is removed from the reply composer before submission.
- Legacy recognized reply tags such as `reply-to-post=<id>` are backfilled to the canonical `ID#:<id>` form during the additive runtime schema upgrade when the referenced post exists.
- Post search ordering blends ordinary order with reply-thread order according to the scope slider. Private emphasizes ordinary order, public mixes both, and global most strongly groups reply chains.
- Forums use a time/content cloud. The default interval is 10 minutes. The earliest visible forum post anchors interval zero, only occupied intervals render, intervals descend vertically, and posts within each interval run left-to-right by content relevance blended with reply-chain affinity.

The Karma/Moksha, scope, timing-delay, persistent-session, notification, call, federation, and host/admin source features are retained.
