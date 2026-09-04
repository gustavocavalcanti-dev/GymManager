(function () {
    const body = document.body;

    const storedTheme = localStorage.getItem('gym-theme');
    if (storedTheme === 'dark') body.classList.add('dark');
    if (storedTheme === 'light') body.classList.remove('dark');

    document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            body.classList.toggle('dark');
            localStorage.setItem('gym-theme', body.classList.contains('dark') ? 'dark' : 'light');
        });
    });

    document.querySelectorAll('[data-menu-toggle]').forEach(btn => {
        btn.addEventListener('click', () => body.classList.toggle('menu-open'));
    });

    document.addEventListener('click', e => {
        if (!body.classList.contains('menu-open')) return;
        const sidebar = document.querySelector('.sidebar');
        const menuBtn = e.target.closest('[data-menu-toggle]');
        if (menuBtn || (sidebar && sidebar.contains(e.target))) return;
        body.classList.remove('menu-open');
    });

    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.getAttribute('data-toggle-password'));
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });

    document.querySelectorAll('[data-list]').forEach(root => {
        const table = root.querySelector('tbody');
        if (!table) return;
        const rows = Array.from(table.querySelectorAll('tr')).filter(row => !row.querySelector('.empty-state'));
        const search = root.querySelector('[data-table-search]');
        const filters = Array.from(root.querySelectorAll('[data-table-filter]'));
        const footerText = root.querySelector('[data-table-count]');
        const prev = root.querySelector('[data-prev]');
        const next = root.querySelector('[data-next]');
        const current = root.querySelector('[data-page-current]');
        const pageSize = parseInt(root.getAttribute('data-page-size') || '8', 10);
        let page = 1;

        function normalize(v) {
            return (v || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }

        function visibleRows() {
            const q = normalize(search ? search.value : '');
            return rows.filter(row => {
                if (q && !normalize(row.innerText).includes(q)) return false;
                for (const filter of filters) {
                    if (!filter.value || filter.value === 'todos') continue;
                    const attr = filter.getAttribute('data-filter-attr');
                    const value = normalize(row.getAttribute(attr));
                    if (value !== normalize(filter.value)) return false;
                }
                return true;
            });
        }

        function buildPageButtons(totalPages) {
            if (!current || !current.parentElement) return;
            const pagination = current.parentElement;
            pagination.querySelectorAll('[data-generated-page]').forEach(el => el.remove());
            current.textContent = String(page);
            current.dataset.page = String(page);
            current.onclick = null;

            // O protótipo mostra o número atual e as páginas vizinhas.
            const candidates = [];
            for (let p = 1; p <= totalPages; p++) {
                if (p === page) continue;
                if (totalPages <= 4 || Math.abs(p - page) <= 2 || p === 1 || p === totalPages) candidates.push(p);
            }
            candidates.forEach(p => {
                const b = document.createElement('button');
                b.type = 'button';
                b.textContent = String(p);
                b.setAttribute('data-generated-page', '1');
                b.addEventListener('click', () => { page = p; render(); });
                if (p < page) pagination.insertBefore(b, current);
                else pagination.insertBefore(b, next || null);
            });
        }

        function render() {
            const filtered = visibleRows();
            const pages = Math.max(1, Math.ceil(filtered.length / pageSize));
            if (page > pages) page = pages;
            rows.forEach(r => r.style.display = 'none');
            const start = (page - 1) * pageSize;
            filtered.slice(start, start + pageSize).forEach(r => r.style.display = '');
            if (footerText) {
                const shown = Math.min(pageSize, Math.max(0, filtered.length - start));
                footerText.textContent = `Mostrando ${shown} de ${filtered.length} registros`;
            }
            buildPageButtons(pages);
            if (prev) prev.disabled = page <= 1;
            if (next) next.disabled = page >= pages;
        }

        if (search) search.addEventListener('input', () => { page = 1; render(); });
        filters.forEach(f => f.addEventListener('change', () => { page = 1; render(); }));
        if (prev) prev.addEventListener('click', () => { if (page > 1) { page--; render(); } });
        if (next) next.addEventListener('click', () => { const pages = Math.max(1, Math.ceil(visibleRows().length / pageSize)); if (page < pages) { page++; render(); } });
        render();
    });

    // Mantém a aparência do protótipo (só "Excluir" visível), sem perder o editar:
    // clique na linha para abrir a edição.
    document.querySelectorAll('tr[data-edit-url]').forEach(row => {
        row.addEventListener('click', e => {
            if (e.target.closest('a,button,input,select,form,label')) return;
            const url = row.getAttribute('data-edit-url');
            if (url) window.location.href = url;
        });
    });

    const global = document.querySelector('[data-global-search]');
    if (global) {
        global.addEventListener('keydown', e => {
            if (e.key !== 'Enter') return;
            const q = global.value.trim();
            if (!q) return;
            const base = global.getAttribute('data-base') || '';
            const pageSearch = document.querySelector('[data-table-search]');
            if (pageSearch) {
                pageSearch.value = q;
                pageSearch.dispatchEvent(new Event('input'));
                pageSearch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            window.location.href = base + '/alunos?busca=' + encodeURIComponent(q);
        });
    }

    document.querySelectorAll('[data-config-tab]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-config-tab]').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const target = btn.getAttribute('data-config-tab');
            document.querySelectorAll('[data-config-panel]').forEach(panel => {
                panel.hidden = panel.getAttribute('data-config-panel') !== target;
            });
        });
    });

    document.querySelectorAll('.upload-box input[type="file"]').forEach(input => {
        input.addEventListener('change', () => {
            const box = input.closest('.upload-box');
            const strong = box ? box.querySelector('strong') : null;
            if (strong && input.files && input.files[0]) {
                strong.textContent = input.files[0].name;
            }
        });
    });
})();
