/*
 * UI helpers (pure DOM, no framework):
 * - animate reveal and progress bars
 * - showToast helper for transient messages
 * - handle `form.ajax-form` submissions via fetch and inject created tasks
 *   to `#tasks-list` when the server returns a `task` JSON payload.
 */
// Lightweight UI enhancements: animate progress bars, show toasts, reveal-on-load
document.addEventListener('DOMContentLoaded', () => {
    // Reveal animations
    document.querySelectorAll('.animate-on-load').forEach((el, i) => {
        setTimeout(() => el.classList.add('in-view'), 80 * i);
    });

    // Progress bars
    document.querySelectorAll('.progress-inner').forEach((el, i) => {
        const pct = parseInt(el.dataset.percent || '0', 10);
        setTimeout(() => {
            el.style.width = pct + '%';
            // add gradient background for visibility
            el.style.background = 'linear-gradient(90deg, #34d399, #60a5fa)';
        }, 200 + (i * 120));
    });

    // Session toast
    const toastHolder = document.getElementById('__session_toast');
    if (toastHolder) {
        const msg = toastHolder.dataset.message || '';
        showToast(msg);
    }

    // Handle simple ajax forms: forms with class 'ajax-form' will POST via fetch and reload on success
    document.querySelectorAll('form.ajax-form').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const action = form.action;
            const data = new FormData(form);
            // Send form as XHR. If server replies with { task: { ... } }, the code will
            // inject the new task item into `#tasks-list` and update `#tasks-count`.
            fetch(action, { method: form.method || 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json().then(body => ({ status: r.status, ok: r.ok, body })).catch(() => ({ status: r.status, ok: r.ok, body: null })))
                .then(({ status, ok, body }) => {
                    // clear previous errors
                    form.querySelectorAll('.ajax-error').forEach(n=>n.remove());

                                        if (ok) {
                                                showToast((body && body.message) ? body.message : 'تمت العملية بنجاح');
                                                // If server returned a created task, inject it into the tasks list without reloading
                                                try {
                                                        if (body && body.task) {
                                                                const task = body.task;
                                                                const tasksList = document.getElementById('tasks-list');
                                                                const tasksCount = document.getElementById('tasks-count');
                                                                if (tasksList) {
                                                                        const esc = (s) => String(s || '').replace(/[&<>"'`]/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;', '`':'&#96;'}[c]));
                                                                        const fmtH = (secs) => {
                                                                                secs = Number(secs) || 0;
                                                                                const h = Math.floor(secs/3600);
                                                                                const m = Math.floor((secs%3600)/60);
                                                                                const s = secs%60;
                                                                                return String(h).padStart(2,'0')+":"+String(m).padStart(2,'0')+":"+String(s).padStart(2,'0');
                                                                        };

                                                                        const desc = task.description ? `<p class="text-sm text-slate-600 line-clamp-1">${esc(task.description)}</p>` : '';
                                                                        const statusText = task.status === 'running' ? 'جاري' : (task.status === 'stopped' ? 'موقوف' : 'معلق');
                                                                        const total = task.total_tracked_seconds ?? 0;
                                                                        const item = `\
<a href="/tasks/${task.id}" class="glass p-4 rounded-lg card-hover border border-white/20 block hover:shadow-md transition-all group">\
    <div class="flex justify-between items-start gap-3">\
        <div class="flex-1 min-w-0">\
            <h4 class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">${esc(task.title)}</h4>\
            ${desc}\
            <div class="flex gap-3 mt-2 text-xs text-slate-500">\
                <span>🕐 ${statusText}</span>\
                <span>⏱️ ${fmtH(total)}</span>\
            </div>\
        </div>\
    </div>\
</a>`;
                                                                        tasksList.insertAdjacentHTML('afterbegin', item);
                                                                }
                                                                if (tasksCount) {
                                                                        tasksCount.textContent = String((parseInt(tasksCount.textContent || '0', 10) || 0) + 1);
                                                                }
                                                                // clear the form fields
                                                                form.reset();
                                                                return;
                                                        }
                                                } catch (e) {
                                                        console.error(e);
                                                }
                                                // default behavior: reload to reflect broader changes
                                                setTimeout(()=> location.reload(), 700);
                                                return;
                                        }

                    if (status === 422 && body && body.errors) {
                        Object.entries(body.errors).forEach(([field, messages]) => {
                            const el = form.querySelector('[name="'+field+'"]') || form.querySelector('[name="'+field+'[]"]');
                            const msg = Array.isArray(messages) ? messages.join('، ') : String(messages);
                            const div = document.createElement('div');
                            div.className = 'text-sm text-red-600 mt-1 ajax-error';
                            div.textContent = msg;
                            if (el && el.parentNode) el.parentNode.appendChild(div);
                        });
                        showToast('الرجاء تصحيح الأخطاء');
                    } else {
                        showToast('حدث خطأ، حاول مرة أخرى');
                    }
                })
                .catch(() => showToast('حدث خطأ، حاول مرة أخرى'));
        });
    });
});

function showToast(message, timeout=3500){
    if(!message) return;
    const t = document.createElement('div');
    t.className = 'toast';
    t.textContent = message;
    document.body.appendChild(t);
    setTimeout(()=> t.classList.add('show'), 10);
    setTimeout(()=>{ t.classList.add('hide'); setTimeout(()=> t.remove(), 300) }, timeout);
}

export {};
