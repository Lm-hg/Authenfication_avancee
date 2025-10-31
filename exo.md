# Authentification 2FA

Objectif :
- L'utilisateur doit pouvoir s1identifier et rester reconnu par l'application avec des jetons JWT

1. L'utilisateur s'identifie ave son mot de passe
2. On demande à l'utilisateur de confirmer son identité
    - Il peut choisir entre :
        - recevoir un mail
        - recevoir un SMS (Twilio)
        - scanner un QR Code (TOTP)
            - Google Authenticator
            - TOTP Authenticator
3. Autoriser l'utilisateur à accéder à certaines ressources.

## Ressources

### OTP
- robthree/twofactorauth
- spomky-labs/otphp
- scheb/2fa-totp
- scheb/2fa-google-authenticator (via Google Authenticator)

### Vérification par mail
- scheb/2fa-email
