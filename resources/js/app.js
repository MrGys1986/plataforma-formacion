import { DataTable } from 'simple-datatables';

document.documentElement.classList.add('js');

const debounce = (callback, delay = 450) => {
    let timer;

    const debounced = (...args) => {
        window.clearTimeout(timer);
        timer = window.setTimeout(() => callback(...args), delay);
    };

    debounced.cancel = () => window.clearTimeout(timer);

    return debounced;
};

const initializeAutomaticFilters = () => {
    document.querySelectorAll('.portal-content form[data-auto-filter]').forEach((form) => {
        if (form.dataset.autoFilterReady) {
            return;
        }

        form.dataset.autoFilterReady = 'true';
        form.classList.add('portal-auto-filter');

        const statusLabel = form.querySelector('[data-auto-filter-label]');
        const indicator = form.querySelector('[data-auto-filter-indicator]');
        const setLoading = () => {
            form.classList.add('is-loading');
            form.setAttribute('aria-busy', 'true');

            if (statusLabel) {
                statusLabel.textContent = 'Actualizando resultados…';
            }

            indicator?.classList.add('animate-pulse', 'bg-amber-500');
            indicator?.classList.remove('bg-emerald-500');
        };
        const submit = () => {
            form.requestSubmit();
        };
        const submitAfterTyping = debounce(submit);

        form.addEventListener('submit', setLoading);

        form.querySelectorAll('input[type="search"], input[type="text"], input[type="email"]').forEach((input) => {
            input.addEventListener('input', submitAfterTyping);
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    submitAfterTyping.cancel();
                    submit();
                }

                if (event.key === 'Escape' && input.value) {
                    input.value = '';
                    submitAfterTyping.cancel();
                    submit();
                }
            });
        });

        form.querySelectorAll('select, input[type="date"]').forEach((control) => {
            control.addEventListener('change', () => {
                submitAfterTyping.cancel();
                submit();
            });
        });

    });
};

const initializeDataTables = (root = document) => {
    const scope = root === document ? document.querySelector('.portal-content') : root;

    scope?.querySelectorAll('table:not([data-datatable="false"])').forEach((table) => {
        if (table.dataset.datatableReady || !table.tHead || !table.tBodies.length) {
            return;
        }

        const headers = [...table.tHead.rows[0].cells];
        const rows = [...table.tBodies[0].rows];

        if (!headers.length || (rows.length === 1 && rows[0].cells[0]?.colSpan > 1)) {
            return;
        }

        table.dataset.datatableReady = 'true';
        table.classList.add('portal-data-table');
        table.parentElement?.classList.add('portal-datatable-host');

        const searchable = table.dataset.datatableSearch !== 'false';
        const paging = table.dataset.datatablePaging !== 'false';
        const perPage = Number.parseInt(table.dataset.datatablePerPage || '10', 10);
        let filterDefinitions = [];

        try {
            filterDefinitions = JSON.parse(table.dataset.datatableFilters || '[]');
        } catch {
            filterDefinitions = [];
        }

        headers.forEach((header) => {
            if (/acciones|control/i.test(header.textContent.trim())) {
                header.dataset.sortable = 'false';
                header.dataset.searchable = 'false';
            }
        });

        const filterColumns = filterDefinitions
            .map((filter) => Number.parseInt(filter.column, 10))
            .filter((column) => Number.isInteger(column) && column >= 0 && column < headers.length);
        const normalizeValue = (value) => String(value ?? '')
            .trim()
            .toLocaleLowerCase('es')
            .normalize('NFD')
            .replace(/\p{Diacritic}/gu, '');
        const exactColumnSearch = (terms, cell, row, column, source) => {
            const value = normalizeValue(cell.text ?? cell.data ?? '');
            const normalizedTerms = terms.map(normalizeValue);

            if (source === 'portal-column-filters') {
                return normalizedTerms.some((term) => value === term);
            }

            return normalizedTerms.some((term) => value.includes(term));
        };
        const dataTable = new DataTable(table, {
            searchable,
            sortable: true,
            paging,
            perPage,
            perPageSelect: [10, 25, 50, 100],
            firstLast: true,
            columns: filterColumns.map((column) => ({
                select: column,
                searchMethod: exactColumnSearch,
            })),
            labels: {
                placeholder: 'Buscar en la tabla...',
                searchTitle: 'Buscar autom\u00e1ticamente en la tabla',
                perPage: 'registros por p\u00e1gina',
                noRows: 'No se encontraron registros',
                noResults: 'No hay resultados para tu b\u00fasqueda',
                info: 'Mostrando {start} a {end} de {rows} registros',
            },
        });

        const toolbar = dataTable.wrapperDOM?.querySelector('.datatable-top');

        if (!toolbar || !filterColumns.length) {
            return;
        }

        const filters = document.createElement('div');
        filters.className = 'portal-datatable-filters';
        filters.setAttribute('aria-label', 'Filtros de la tabla');
        const activeFilters = new Map();
        const applyFilters = () => {
            const queries = [...activeFilters.entries()].map(([column, value]) => ({
                terms: [value],
                columns: [column],
            }));

            dataTable.multiSearch(queries, 'portal-column-filters');
        };

        filterDefinitions.forEach((filter) => {
            const column = Number.parseInt(filter.column, 10);

            if (!filterColumns.includes(column)) {
                return;
            }

            const values = [...new Set(rows
                .map((row) => row.cells[column]?.textContent.trim())
                .filter(Boolean))]
                .sort((left, right) => left.localeCompare(right, 'es', { sensitivity: 'base' }));

            if (!values.length) {
                return;
            }

            const label = document.createElement('label');
            label.className = 'portal-datatable-filter';
            const caption = document.createElement('span');
            caption.textContent = filter.label;
            const select = document.createElement('select');
            select.setAttribute('aria-label', `Filtrar por ${filter.label}`);
            select.innerHTML = '<option value="">Todos</option>';

            values.forEach((value) => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                select.append(option);
            });

            select.addEventListener('change', () => {
                if (select.value) {
                    activeFilters.set(column, select.value);
                } else {
                    activeFilters.delete(column);
                }

                applyFilters();
            });

            label.append(caption, select);
            filters.append(label);
        });

        if (filters.childElementCount) {
            toolbar.append(filters);
        }
    });
};

const initializePortalInteractions = () => {
    initializeAutomaticFilters();
    initializeDataTables();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePortalInteractions);
} else {
    initializePortalInteractions();
}

document.addEventListener('livewire:navigated', initializePortalInteractions);

window.addEventListener('pageshow', () => {
    document.querySelectorAll('form[data-auto-filter].is-loading').forEach((form) => {
        form.classList.remove('is-loading');
        form.removeAttribute('aria-busy');
        const label = form.querySelector('[data-auto-filter-label]');
        const indicator = form.querySelector('[data-auto-filter-indicator]');

        if (label) {
            label.textContent = 'Filtros automáticos';
        }

        indicator?.classList.remove('animate-pulse', 'bg-amber-500');
        indicator?.classList.add('bg-emerald-500');
    });
});
