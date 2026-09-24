@auth
    <div class="pf-topbar-user-name" aria-label="Usuario actual">
        <span>{{ filament()->getUserName(filament()->auth()->user()) }}</span>
    </div>
@endauth
