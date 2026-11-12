import FingerprintJS from '@fingerprintjs/fingerprintjs';

let fingerprintPromise = null;

export function getFingerprint() {
    if (!fingerprintPromise) {
        fingerprintPromise = FingerprintJS.load()
            .then(agent => agent.get())
            .then(result => result.visitorId);
    }

    return fingerprintPromise;
}
