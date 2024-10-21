if ("serviceWorker" in navigator) {
    // Register a service worker hosted at the root of the
    // site using the default scope.
    navigator.serviceWorker.register("/sw.js").then(
        (registration) => {

            // console.log("Service worker registration succeeded:", registration);
        },
        (error) => {
            console.error(`Service worker registration failed: ${error}`);
        },
    );
} else {
    console.error("Service workers are not supported.");
}

if ('serviceWorker' in navigator && 'BeforeInstallPromptEvent' in window) {
    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (event) => {
        // Prevent the default "Add to Home Screen" prompt
        event.preventDefault();

        window.addEventListener('DOMContentLoaded', () => {
            let displayMode = 'browser tab';
            if (window.matchMedia('(display-mode: standalone)').matches) {
                displayMode = 'standalone';
            }
            // Log launch display mode to analytics
            console.log('DISPLAY_MODE_LAUNCH:', displayMode);
        });

        // Show the "Install App" button
        let installButton = document.getElementById('appInstallModal');
        let closeInstallModal = document.getElementById('closeInstallModal');
        let pwaInstallModal;
        if (installButton) {
            pwaInstallModal = new bootstrap.Modal(installButton, {
                keyboard: true,
                backdrop: true,
            });

            pwaInstallModal.show();

            if (closeInstallModal) {
                closeInstallModal.addEventListener('click', () => {
                    pwaInstallModal.hide();
                });
            }
        }

        const installPwa = document.getElementById('installPwa');

        if (installPwa) {
            installPwa.addEventListener('click', () => {
                pwaInstallModal.hide();
            });
        }

        // Save the event for later use
        let deferredPrompt = event;

        // Add event listener to the "Install App" button
        installPwa.addEventListener('click', () => {
            // Trigger the "Add to Home Screen" prompt
            deferredPrompt.prompt();

            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice
                .then((choiceResult) => {
                    // Reset the prompt variable
                    deferredPrompt = null;
                    // Hide the "Install App" button after the prompt is shown
                    pwaInstallModal.hide();
                });
        });
    });
}
