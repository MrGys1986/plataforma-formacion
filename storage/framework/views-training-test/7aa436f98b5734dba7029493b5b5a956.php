<script data-navigate-once>
    (() => {
        if (window.pfPanelLoaderInitialized) {
            return
        }

        window.pfPanelLoaderInitialized = true

        const state = {
            pending: 0,
            timer: null,
            safetyTimer: null,
            awaitingFormRequest: false,
        }
        const confirmedSaveForms = new WeakSet()
        let pendingSaveSubmission = null

        const getLoader = () => document.getElementById('pf-global-loader')

        const setLoaderContent = (mode = 'loading') => {
            const loader = getLoader()

            if (! loader) {
                return
            }

            const title = loader.querySelector('.pf-global-loader-title')
            const copy = loader.querySelector('.pf-global-loader-copy')

            if (mode === 'saving') {
                title.textContent = 'Guardando información'
                copy.textContent = 'Estamos procesando la operación. Espera un momento.'

                return
            }

            title.textContent = 'Cargando información'
            copy.textContent = 'Estamos preparando la vista para que sigas trabajando.'
        }

        const paint = () => {
            const loader = getLoader()

            if (! loader) {
                return
            }

            if (state.pending > 0) {
                loader.classList.add('is-visible')
            } else {
                loader.classList.remove('is-visible')
            }
        }

        const begin = (delay = 180) => {
            state.pending += 1
            clearTimeout(state.timer)
            state.timer = setTimeout(paint, delay)
        }

        const end = () => {
            state.pending = Math.max(0, state.pending - 1)
            clearTimeout(state.timer)
            clearTimeout(state.safetyTimer)

            if (state.pending === 0) {
                requestAnimationFrame(() => {
                    paint()
                    setLoaderContent()
                })
            }
        }

        const openSaveConfirmation = (form, submitter) => {
            const dialog = document.getElementById('pf-save-confirmation')

            if (! (dialog instanceof HTMLDialogElement) || typeof dialog.showModal !== 'function') {
                return false
            }

            pendingSaveSubmission = { form, submitter }
            dialog.returnValue = ''

            if (! dialog.open) {
                dialog.showModal()
            }

            return true
        }

        const resolveSaveConfirmation = (confirmed) => {
            const dialog = document.getElementById('pf-save-confirmation')
            const submission = pendingSaveSubmission
            pendingSaveSubmission = null

            if (dialog instanceof HTMLDialogElement && dialog.open) {
                dialog.close(confirmed ? 'confirm' : 'cancel')
            }

            if (! confirmed || ! submission?.form?.isConnected) {
                return
            }

            confirmedSaveForms.add(submission.form)

            queueMicrotask(() => {
                if (submission.submitter?.isConnected) {
                    submission.form.requestSubmit(submission.submitter)

                    return
                }

                submission.form.requestSubmit()
            })
        }

        const beginFormSubmission = () => {
            if (state.awaitingFormRequest) {
                return
            }

            state.awaitingFormRequest = true
            begin()

            clearTimeout(state.safetyTimer)
            state.safetyTimer = setTimeout(() => {
                state.awaitingFormRequest = false
                end()
            }, 15000)
        }

        document.addEventListener('livewire:navigate', () => {
            setLoaderContent()
            begin(120)
        })
        document.addEventListener('livewire:navigated', end)
        window.addEventListener('pf-confirm-save', (event) => {
            const form = event.detail?.form
            const submitter = event.detail?.submitter

            if (! (form instanceof HTMLFormElement)) {
                return
            }

            if (openSaveConfirmation(form, submitter)) {
                return
            }

            if (window.confirm('¿Estás seguro de guardar los cambios?')) {
                confirmedSaveForms.add(form)
                form.requestSubmit(submitter)
            }
        })
        document.addEventListener('click', (event) => {
            const choice = event.target instanceof Element
                ? event.target.closest('[data-pf-confirm-choice]')?.dataset.pfConfirmChoice
                : null

            if (! choice) {
                return
            }

            resolveSaveConfirmation(choice === 'confirm')
        })
        document.addEventListener('cancel', (event) => {
            if (event.target?.id === 'pf-save-confirmation') {
                pendingSaveSubmission = null
            }
        }, true)
        document.addEventListener('submit', (event) => {
            const form = event.target

            if (! (form instanceof HTMLFormElement)) {
                return
            }

            const isLivewireForm = [...form.attributes].some((attribute) =>
                attribute.name.startsWith('wire:submit'),
            )

            if (isLivewireForm) {
                const saveSubmitter = event.submitter ?? form.querySelector('[data-pf-confirm-save="true"]')
                const shouldConfirmSave = saveSubmitter?.matches('[data-pf-confirm-save="true"]')

                if (shouldConfirmSave && ! confirmedSaveForms.has(form) && openSaveConfirmation(form, saveSubmitter)) {
                    event.preventDefault()
                    event.stopImmediatePropagation()

                    return
                }

                confirmedSaveForms.delete(form)
                setLoaderContent('saving')
                beginFormSubmission()
            }
        }, true)

        window.addEventListener('load', () => {
            state.pending = 0
            state.awaitingFormRequest = false
            paint()
        })

        document.addEventListener('livewire:init', () => {
            if (! window.Livewire?.hook) {
                return
            }

            window.Livewire.hook('request', ({ succeed, fail }) => {
                if (! state.awaitingFormRequest) {
                    return
                }

                state.awaitingFormRequest = false

                const finalize = () => {
                    requestAnimationFrame(() => end())
                }

                succeed(finalize)
                fail(finalize)
            })
        })
    })()
</script>
<?php /**PATH C:\Projects\plataforma-formacion\resources\views\filament\partials\panel-loader-script.blade.php ENDPATH**/ ?>