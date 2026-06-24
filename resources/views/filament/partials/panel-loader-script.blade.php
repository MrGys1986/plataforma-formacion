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

        const getLoader = () => document.getElementById('pf-global-loader')

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
                requestAnimationFrame(() => paint())
            }
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

        document.addEventListener('livewire:navigate', () => begin(120))
        document.addEventListener('livewire:navigated', end)
        document.addEventListener('submit', (event) => {
            const form = event.target

            if (! (form instanceof HTMLFormElement)) {
                return
            }

            const isLivewireForm = [...form.attributes].some((attribute) =>
                attribute.name.startsWith('wire:submit'),
            )

            if (isLivewireForm) {
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

<script data-navigate-once>
    (() => {
        const initializeUsersRoleToggle = () => {
            document.querySelectorAll('.fi-sidebar-item').forEach((item) => {
                const directLink = item.querySelector(':scope > .fi-sidebar-item-btn')
                const directLabel = directLink?.querySelector('.fi-sidebar-item-label')
                const roleList = item.querySelector(':scope > .fi-sidebar-sub-group-items')

                if (directLabel?.textContent.trim() !== 'Usuarios' || ! roleList) {
                    return
                }

                item.classList.add('pf-collapsible-users')

                if (item.querySelector(':scope > .pf-users-toggle')) {
                    return
                }

                const toggle = document.createElement('button')
                toggle.type = 'button'
                toggle.className = 'pf-users-toggle'
                toggle.setAttribute('aria-label', 'Mostrar u ocultar roles de usuarios')
                toggle.setAttribute('aria-expanded', 'true')
                toggle.innerHTML = `
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
                    </svg>
                `

                toggle.addEventListener('click', () => {
                    const isCollapsed = item.classList.toggle('pf-users-collapsed')
                    toggle.setAttribute('aria-expanded', String(! isCollapsed))
                })

                directLink.insertAdjacentElement('afterend', toggle)
            })
        }

        document.addEventListener('DOMContentLoaded', initializeUsersRoleToggle)
        document.addEventListener('livewire:navigated', initializeUsersRoleToggle)
        initializeUsersRoleToggle()
    })()
</script>
