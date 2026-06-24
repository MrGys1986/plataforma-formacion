<style>
    .pf-panel-footer {
        margin-top: 1.5rem;
        border-top: 1px solid rgba(148, 163, 184, 0.18);
        padding-top: 1rem;
    }

    .pf-panel-footer-card {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        border-radius: 1rem;
        border: 1px solid rgba(148, 163, 184, 0.18);
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(30, 41, 59, 0.92));
        color: #e2e8f0;
        padding: 1rem 1.25rem;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
    }

    .pf-panel-footer-title {
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0 0 0.2rem;
    }

    .pf-panel-footer-copy,
    .pf-panel-footer-meta {
        margin: 0;
        font-size: 0.85rem;
        color: #cbd5e1;
    }

    .pf-dashboard-hero,
    .pf-edition-summary {
        border-radius: 1.25rem;
        background:
            radial-gradient(circle at top right, rgba(96, 165, 250, 0.24), transparent 28%),
            linear-gradient(135deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.96));
        color: #f8fafc;
        padding: 1.5rem;
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.14);
        display: grid;
        gap: 1.25rem;
    }

    .pf-dashboard-eyebrow {
        margin: 0 0 0.35rem;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #93c5fd;
    }

    .pf-dashboard-title,
    .pf-edition-summary-title {
        margin: 0;
        font-size: clamp(1.6rem, 2vw, 2.2rem);
        line-height: 1.1;
        font-weight: 800;
    }

    .pf-dashboard-copy,
    .pf-edition-summary-copy {
        margin: 0.6rem 0 0;
        max-width: 56rem;
        color: #cbd5e1;
        font-size: 0.98rem;
        line-height: 1.65;
    }

    .pf-dashboard-metrics,
    .pf-edition-grid,
    .pf-dashboard-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(13rem, 1fr));
        gap: 1rem;
    }

    .pf-dashboard-metric-card,
    .pf-dashboard-link-card,
    .pf-edition-card,
    .pf-dashboard-panel {
        border-radius: 1rem;
        border: 1px solid rgba(148, 163, 184, 0.18);
        background: #fff;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.08);
    }

    .dark .pf-dashboard-link-card,
    .dark .pf-edition-card,
    .dark .pf-dashboard-panel {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.18);
    }

    .pf-dashboard-metric-card {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        padding: 1rem;
        border-color: rgba(255, 255, 255, 0.08);
        box-shadow: none;
    }

    .pf-dashboard-metric-label {
        display: block;
        font-size: 0.84rem;
        color: #bfdbfe;
    }

    .pf-dashboard-metric-value {
        display: block;
        margin-top: 0.35rem;
        font-size: 1.8rem;
        line-height: 1;
        font-weight: 800;
    }

    .pf-dashboard-columns {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }

    .pf-dashboard-panel {
        padding: 1.25rem;
    }

    .pf-dashboard-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .pf-dashboard-panel-kicker {
        margin: 0 0 0.15rem;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
    }

    .dark .pf-dashboard-panel-kicker {
        color: #94a3b8;
    }

    .pf-dashboard-panel-title,
    .pf-edition-card-title,
    .pf-dashboard-link-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .dark .pf-dashboard-panel-title,
    .dark .pf-edition-card-title,
    .dark .pf-dashboard-link-title {
        color: #f8fafc;
    }

    .pf-dashboard-link-card,
    .pf-edition-card {
        padding: 1rem;
        text-decoration: none;
        display: grid;
        gap: 0.9rem;
        transition: transform 120ms ease, box-shadow 120ms ease, border-color 120ms ease;
    }

    .pf-dashboard-link-card:hover,
    .pf-edition-card:hover,
    .pf-dashboard-attention-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        border-color: rgba(59, 130, 246, 0.34);
    }

    .pf-dashboard-link-card {
        grid-template-columns: auto 1fr;
        align-items: start;
    }

    .pf-dashboard-link-icon-wrap,
    .pf-edition-card-icon-wrap {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.95rem;
        background: rgba(59, 130, 246, 0.12);
        color: #2563eb;
    }

    .pf-dashboard-link-icon,
    .pf-edition-card-icon {
        width: 1.4rem;
        height: 1.4rem;
    }

    .pf-dashboard-link-copy,
    .pf-edition-card-copy {
        margin: 0.25rem 0 0;
        font-size: 0.9rem;
        line-height: 1.55;
        color: #64748b;
    }

    .dark .pf-dashboard-link-copy,
    .dark .pf-edition-card-copy {
        color: #94a3b8;
    }

    .pf-dashboard-attention-list {
        display: grid;
        gap: 0.75rem;
    }

    .pf-dashboard-attention-item {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: center;
        padding: 0.95rem 1rem;
        border-radius: 0.95rem;
        border: 1px solid rgba(148, 163, 184, 0.16);
        text-decoration: none;
        color: inherit;
        transition: transform 120ms ease, box-shadow 120ms ease, border-color 120ms ease;
    }

    .pf-dashboard-attention-label {
        color: #0f172a;
        font-weight: 600;
        line-height: 1.45;
    }

    .dark .pf-dashboard-attention-label {
        color: #f8fafc;
    }

    .pf-dashboard-attention-badge,
    .pf-edition-card-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
        font-weight: 700;
        background: rgba(37, 99, 235, 0.12);
        color: #1d4ed8;
    }

    .pf-dashboard-widgets {
        margin-top: 1rem;
    }

    .pf-edition-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(13rem, 1fr));
        gap: 0.9rem;
        margin: 0;
    }

    .pf-edition-meta div {
        padding: 0.9rem 1rem;
        border-radius: 0.95rem;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .pf-edition-meta dt {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #93c5fd;
        margin-bottom: 0.2rem;
    }

    .pf-edition-meta dd {
        margin: 0;
        font-size: 0.96rem;
        font-weight: 600;
        color: #f8fafc;
    }

    .pf-edition-grid {
        margin-top: 1rem;
    }

    .pf-edition-card-head {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: center;
    }

    .pf-global-loader {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background: rgba(15, 23, 42, 0.28);
        backdrop-filter: blur(8px);
        opacity: 0;
        pointer-events: none;
        transition: opacity 140ms ease;
    }

    .pf-global-loader.is-visible {
        opacity: 1;
        pointer-events: auto;
    }

    .pf-global-loader-card {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        border-radius: 1rem;
        border: 1px solid rgba(148, 163, 184, 0.16);
        background: rgba(255, 255, 255, 0.96);
        padding: 1rem 1.1rem;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.16);
    }

    .dark .pf-global-loader-card {
        background: rgba(15, 23, 42, 0.96);
    }

    .pf-global-loader-spinner {
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 999px;
        border: 3px solid rgba(59, 130, 246, 0.18);
        border-top-color: #2563eb;
        animation: pf-loader-spin 0.7s linear infinite;
    }

    .pf-global-loader-title {
        margin: 0;
        font-size: 0.96rem;
        font-weight: 700;
        color: #0f172a;
    }

    .dark .pf-global-loader-title {
        color: #f8fafc;
    }

    .pf-global-loader-copy {
        margin: 0.15rem 0 0;
        font-size: 0.84rem;
        color: #64748b;
    }

    .dark .pf-global-loader-copy {
        color: #94a3b8;
    }

    .fi-sidebar-item.pf-collapsible-users {
        position: relative;
    }

    .pf-users-toggle {
        position: absolute;
        top: 0.55rem;
        right: 0.65rem;
        z-index: 2;
        display: inline-flex;
        width: 1.75rem;
        height: 1.75rem;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        color: #94a3b8;
        transition: color 120ms ease, background-color 120ms ease;
    }

    .pf-users-toggle:hover,
    .pf-users-toggle:focus-visible {
        background: rgba(148, 163, 184, 0.12);
        color: #3b82f6;
        outline: none;
    }

    .pf-users-toggle svg {
        width: 1rem;
        height: 1rem;
        transition: transform 160ms ease;
    }

    .pf-users-toggle[aria-expanded='true'] svg {
        transform: rotate(180deg);
    }

    .pf-collapsible-users > .fi-sidebar-item-btn {
        padding-right: 3rem;
    }

    .pf-collapsible-users.pf-users-collapsed > .fi-sidebar-sub-group-items {
        display: none;
    }

    .pf-management-table.fi-ta-ctn {
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, 0.16);
        border-radius: 1.15rem;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    }

    .dark .pf-management-table.fi-ta-ctn {
        border-color: rgba(148, 163, 184, 0.18);
        box-shadow: 0 22px 55px rgba(0, 0, 0, 0.24);
    }

    .pf-management-table .fi-ta-header-toolbar {
        padding: 1rem 1.15rem;
        background:
            radial-gradient(circle at top right, rgba(59, 130, 246, 0.1), transparent 36%),
            rgba(248, 250, 252, 0.8);
    }

    .dark .pf-management-table .fi-ta-header-toolbar {
        background:
            radial-gradient(circle at top right, rgba(59, 130, 246, 0.14), transparent 36%),
            rgba(24, 24, 27, 0.92);
    }

    .pf-management-table .fi-ta-table thead {
        background: rgba(241, 245, 249, 0.9);
    }

    .dark .pf-management-table .fi-ta-table thead {
        background: rgba(39, 39, 42, 0.92);
    }

    .pf-management-table .fi-ta-header-cell {
        padding-top: 0.9rem;
        padding-bottom: 0.9rem;
    }

    .pf-management-table .fi-ta-row {
        transition: background-color 140ms ease, transform 140ms ease;
    }

    .pf-management-table .fi-ta-row:hover {
        background: rgba(59, 130, 246, 0.055);
    }

    .dark .pf-management-table .fi-ta-row:hover {
        background: rgba(59, 130, 246, 0.09);
    }

    .pf-management-table .fi-ta-text:not(.fi-inline) {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .pf-management-table .fi-badge {
        border-radius: 999px;
        padding-inline: 0.65rem;
        font-weight: 600;
    }

    .pf-management-table .fi-ta-actions {
        padding-inline: 0.75rem;
    }

    .pf-management-table .fi-pagination {
        padding: 0.9rem 1.15rem;
        background: rgba(248, 250, 252, 0.72);
    }

    .dark .pf-management-table .fi-pagination {
        background: rgba(24, 24, 27, 0.72);
    }

    @keyframes pf-loader-spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 900px) {
        .pf-dashboard-columns {
            grid-template-columns: 1fr;
        }

        .pf-panel-footer-card {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
