document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const status = document.getElementById('status');

    status.textContent = 'Generating keys and registering...';
    status.style.color = 'orange';

    try {
        // Generate keys
        const keyPair = await window.crypto.subtle.generateKey(
            {
                name: "RSA-OAEP",
                modulusLength: 2048,
                publicExponent: new Uint8Array([1, 0, 1]),
                hash: "SHA-256",
            },
            true,
            ["encrypt", "decrypt"]
        );

        const publicKey = await window.crypto.subtle.exportKey("spki", keyPair.publicKey);
        const publicKeyBase64 = btoa(String.fromCharCode(...new Uint8Array(publicKey)));

        // Send to server
        const response = await fetch('register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                email: email,
                password: password,
                public_key: publicKeyBase64
            })
        });

        const result = await response.json();

        if (result.message.includes('successful')) {
            // Only download private key if registration succeeded
            const privateKey = await window.crypto.subtle.exportKey("pkcs8", keyPair.privateKey);
            const privateKeyBase64 = btoa(String.fromCharCode(...new Uint8Array(privateKey)));
            
            const blob = new Blob([privateKeyBase64], { type: "text/plain" });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${email}_private_key.pem`;
            link.click();

            status.textContent = 'Registration successful! Private key downloaded.';
            status.style.color = '#C2185B';
        } else {
            status.textContent = result.message;
            status.style.color = 'red';
        }
    } catch (error) {
        status.textContent = 'Error: ' + error.message;
        status.style.color = 'red';
    }
});
