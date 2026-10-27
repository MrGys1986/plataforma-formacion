export const validateFileInput = (input) => {
    const maximumBytes = Number(input.dataset.maxFileSize);
    const oversized = [...(input.files ?? [])].some((file) => file.size > maximumBytes);

    input.setCustomValidity(oversized
        ? `El archivo supera el límite de ${maximumBytes / 1024 / 1024} MB. Selecciona un PDF más pequeño.`
        : '');

    return !oversized;
};

export const initializeFileUploadValidation = () => {
    document.addEventListener('change', (event) => {
        const input = event.target;

        if (input instanceof HTMLInputElement && input.matches('input[type="file"][data-max-file-size]')) {
            if (!validateFileInput(input)) {
                input.reportValidity();
            }
        }
    });

    document.addEventListener('submit', (event) => {
        const inputs = event.target.querySelectorAll('input[type="file"][data-max-file-size]');

        for (const input of inputs) {
            if (!validateFileInput(input)) {
                event.preventDefault();
                input.reportValidity();
                break;
            }
        }
    }, true);
};
