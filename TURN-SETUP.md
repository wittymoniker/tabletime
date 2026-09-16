# Tabletime TURN setup

Tabletime video calls use WebRTC. STUN is included by default. TURN should be enabled for reliable calls across restrictive cellular, NAT, corporate, hotel, and public Wi-Fi networks.

## Recommended: Twilio Network Traversal Service

Set these Wasmer secrets/environment variables:

- `TT_TURN_PROVIDER=twilio`
- `TWILIO_ACCOUNT_SID=<your Twilio Account SID>`
- `TWILIO_AUTH_TOKEN=<your Twilio Auth Token>`
- optional: `TT_TURN_TOKEN_TTL=3600`

Tabletime requests short-lived ICE credentials server-side. The Twilio account secret is never sent to the browser; only temporary TURN credentials are embedded in the Calls page.

## Generic/static TURN

If using Coturn, Metered, or another TURN provider, set:

- `TT_TURN_URLS=turn:host:3478?transport=udp,turn:host:3478?transport=tcp,turns:host:5349?transport=tcp`
- `TT_TURN_USERNAME=<username>`
- `TT_TURN_CREDENTIAL=<credential>`

The legacy single variable `TT_TURN_URL` is still accepted.

## Verification

Open **Calls** and start/join a call from two different networks. Tabletime reports `TURN relay active.` when WebRTC gathers a relay candidate. A direct/STUN connection is still valid; TURN is a fallback when direct paths fail.
