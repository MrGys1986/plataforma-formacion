<script data-navigate-once>
    (() => {
        if (window.pfPanelLoaderInitialized) {
            return
        }

        window.pfPanelLoaderInitialized = true

        const state = {
            pending: 0,
            timer: null,
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

        const begin = () => {
            state.pending += 1
            clearTimeout(state.timer)
            state.timer = setTimeout(paint, 120)
        }

        const end = () => {
            state.pending = Math.max(0, state.pending - 1)
            clearTimeout(state.timer)

            if (state.pending === 0) {
                requestAnimationFrame(() => paint())
            }
        }

        document.addEventListener('livewire:navigate', begin)
        document.addEventListener('livewire:navigated', end)
        window.addEventListener('load', () => {
            state.pending = 0
            paint()
        })

        document.addEventListener('livewire:init', () => {
            if (! window.Livewire?.hook) {
                return
            }

            window.Livewire.hook('request', ({ succeed, fail }) => {
                begin()

                const finalize = () => {
                    requestAnimationFrame(() => end())
                }

                succeed(finalize)
                fail(finalize)
            })
        })
    })()
</script>
