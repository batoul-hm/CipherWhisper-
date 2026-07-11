# CipherWhisper

A secure and simple real-time chat application with End-to-End Encryption. All messages are encrypted and decrypted directly on your device, so no one — not even the server — can access your messages.

## Features

- **End-to-End Encryption**: Messages are encrypted on your device and can only be decrypted by the intended recipient.
- **Your Keys, Your Control**: Private keys never leave your device. Public keys are shared securely for communication.
- **Server is Blind**: The server only stores encrypted messages and public keys — no plaintext messages or private keys are ever saved.
- **Dual Encryption**: Messages are encrypted for both the recipient and the sender, allowing both parties to securely access the conversation.
- **Brute-Force Protection**: Login includes account lockout after multiple failed attempts.

## How It Works

1. **Register**  
   Generate your RSA key pair directly on your device. The public key is sent to the server, while your private key is downloaded locally.

2. **Send Messages**  
   Messages are encrypted on your device using your friend's public key before being sent to the server.

3. **Read Messages**  
   Decrypt incoming messages on your device using your private key.


