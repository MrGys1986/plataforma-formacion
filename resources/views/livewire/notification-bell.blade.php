<details class="pf-notification-bell {{ $admin ? 'pf-notification-bell-admin' : '' }}" wire:poll.30s wire:ignore.self x-data x-on:click.outside="$el.removeAttribute('open')" x-on:keydown.escape.window="$el.removeAttribute('open')" data-notification-bell>
    <summary class="pf-notification-trigger" aria-label="Notificaciones, {{ $unreadCount }} sin leer">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
        @if ($unreadCount > 0)
            <span class="pf-notification-count" aria-hidden="true">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </summary>
    <div class="pf-notification-panel" aria-label="Tus notificaciones">
        <div class="pf-notification-heading">
            <div><h2>Notificaciones</h2><p>{{ $unreadCount }} {{ $unreadCount === 1 ? 'aviso sin leer' : 'avisos sin leer' }}</p></div>
            @if ($unreadCount > 0)
                <button type="button" class="pf-notification-read-all" wire:click="markAllAsRead" wire:loading.attr="disabled">Marcar todas como leídas</button>
            @endif
        </div>
        <div class="pf-notification-list">
            @forelse ($notifications as $notification)
                <article class="pf-notification-item {{ $notification->read_at === null ? 'pf-notification-unread' : '' }}" wire:key="notice-{{ $notification->id }}">
                    <button type="button" class="pf-notification-open" wire:click="open({{ $notification->id }})" wire:loading.attr="disabled">
                        <span class="pf-notification-subject">{{ $notification->subject }}</span>
                        <span class="pf-notification-message">{{ $notification->message }}</span>
                        <time class="pf-notification-time" datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->diffForHumans() }}</time>
                    </button>
                    @if ($notification->read_at === null)
                        <button type="button" class="pf-notification-read" wire:click="markAsRead({{ $notification->id }})" wire:loading.attr="disabled" aria-label="Marcar como leída: {{ $notification->subject }}" title="Marcar como leída">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>
                        </button>
                    @endif
                </article>
            @empty
                <div class="pf-notification-empty"><p>Estás al día</p><span>Aquí aparecerán los avisos de tus inscripciones, calificaciones y constancias.</span></div>
            @endforelse
        </div>
        @if ($notifications->hasPages())
            <div class="pf-notification-pagination">
                <button type="button" wire:click="previousPage('notificationsPage')" @disabled($notifications->onFirstPage())>Más recientes</button>
                <span>{{ $notifications->currentPage() }} / {{ $notifications->lastPage() }}</span>
                <button type="button" wire:click="nextPage('notificationsPage')" @disabled(! $notifications->hasMorePages())>Anteriores</button>
            </div>
        @endif
    </div>
</details>
