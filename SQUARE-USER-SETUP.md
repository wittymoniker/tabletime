# Connect Square to Tabletime

Tabletime uses a standard Square authorization screen; you should never paste your Square password, Application Secret, or access token into Tabletime.

1. Create or sign in to your Square seller account.
2. Complete the business/profile verification Square asks for.
3. In Square, link the bank account or eligible transfer destination where you want funds to settle.
4. In Tabletime, open **Earnings → Square setup**.
5. Choose **Connect Square**.
6. Sign in on Square and approve the requested permissions.
7. Square returns you to Tabletime. Your Earnings page will show the connected merchant ID.
8. When you have an available cash balance, enter an amount under **Request payout**. Tabletime reserves that amount and shows its status in **Payout history**.

Natural ad credits are separate from cash earnings. One natural ad credit equals one counted impression. Square-funded advertising is SFW-only.

Tabletime stores Square OAuth tokens encrypted on the server. Square's Payouts API is used for payout/account visibility; an actual Tabletime transfer is only marked paid when the configured payout workflow confirms it.
