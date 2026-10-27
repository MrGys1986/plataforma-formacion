import test from 'node:test';
import assert from 'node:assert/strict';
import { validateFileInput, initializeFileUploadValidation } from '../../resources/js/file-upload-validation.js';

const limit = 10 * 1024 * 1024;
const fileInput = (size) => ({
    dataset: { maxFileSize: String(limit) },
    files: [{ size }],
    validationMessage: '',
    setCustomValidity(message) { this.validationMessage = message; },
});

test('accepts a PDF at the 10 MB boundary', () => {
    const input = fileInput(limit);
    assert.equal(validateFileInput(input), true);
    assert.equal(input.validationMessage, '');
});

test('rejects the 16 MB replacement before upload', () => {
    const input = fileInput(16 * 1024 * 1024);
    assert.equal(validateFileInput(input), false);
    assert.match(input.validationMessage, /10 MB/);
});

test('clears the error after selecting a smaller file', () => {
    const input = fileInput(limit + 1);
    validateFileInput(input);
    input.files = [{ size: 1024 }];
    assert.equal(validateFileInput(input), true);
    assert.equal(input.validationMessage, '');
});

test('blocks form submission even if no change event was triggered', () => {
    const listeners = new Map();
    globalThis.document = { addEventListener: (name, handler) => listeners.set(name, handler) };
    initializeFileUploadValidation();
    const input = fileInput(16 * 1024 * 1024);
    let blocked = false;
    let reported = false;
    input.reportValidity = () => { reported = true; };
    listeners.get('submit')({
        target: { querySelectorAll: () => [input] },
        preventDefault: () => { blocked = true; },
    });
    assert.equal(blocked, true);
    assert.equal(reported, true);
    delete globalThis.document;
});
