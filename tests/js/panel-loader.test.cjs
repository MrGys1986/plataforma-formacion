const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

test('the dashboard releases its loader when a Livewire 4 request finishes', () => {
    const listeners = new Map();
    const timers = new Map();
    const classes = new Set();
    let timerId = 0;
    let interceptRequest;
    class Form {
        attributes = [{ name: 'wire:submit' }];
        querySelector() { return null; }
    }
    const loader = {
        classList: { add: name => classes.add(name), remove: name => classes.delete(name) },
        querySelector: () => ({ textContent: '' }),
    };
    const document = {
        getElementById: id => id === 'pf-global-loader' ? loader : null,
        addEventListener: (name, callback) => listeners.set(name, callback),
    };
    const window = {
        addEventListener() {},
        Livewire: { interceptRequest: callback => { interceptRequest = callback; } },
    };
    const source = fs.readFileSync(path.join(__dirname, '../../resources/views/filament/partials/panel-loader-script.blade.php'), 'utf8')
        .replace(/^<script[^>]*>/, '').replace(/<\/script>\s*$/, '');
    vm.runInNewContext(source, {
        document, window, HTMLFormElement: Form, HTMLDialogElement: class {}, Element: class {},
        requestAnimationFrame: callback => callback(), queueMicrotask: callback => callback(),
        setTimeout: callback => { timers.set(++timerId, callback); return timerId; },
        clearTimeout: id => timers.delete(id),
    });
    listeners.get('livewire:init')();
    assert.equal(typeof interceptRequest, 'function');

    // Livewire calls onFinish after success, validation errors and transport errors.
    for (let request = 0; request < 2; request++) {
        listeners.get('submit')({ target: new Form() });
        const paint = timers.values().next().value;
        paint();
        assert.equal(classes.has('is-visible'), true);
        let finish;
        interceptRequest({ onFinish: callback => { finish = callback; } });
        assert.equal(typeof finish, 'function');
        finish();
        assert.equal(classes.has('is-visible'), false);
        assert.equal(timers.size, 0);
    }
});
