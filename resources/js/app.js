import './bootstrap';
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
window.Echo = new Echo({
broadcaster: 'pusher',
key: import.meta.env.VITE_PUSHER_APP_KEY,
cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
forceTLS: true
});

const typeColor = { sistem: '#ef4444', teknisi: '#2bb0a6', pelanggan: '#f59e0b' };
const typeLabel = { sistem: 'Sistem', teknisi: 'Teknisi', pelanggan: 'Pelanggan' };

window.toggleNotifDropdown = function(e) {
e.preventDefault();
const dropdown = document.getElementById('notifDropdown');
if (!dropdown) return;
const isOpen = dropdown.style.display !== 'none';
dropdown.style.display = isOpen ? 'none' : 'block';
if (!isOpen) loadDropdownNotif();
};

window.markAllReadDropdown = async function() {
try {
await fetch(window.appConfig.notifications.readAll, {
method: 'POST',
headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
});
} catch (err) {}
fetchUnreadCount();
loadDropdownNotif();
};

window.markReadAndGo = async function(id, ticketId) {
try {
await fetch(`/notifications/${id}/read`, {
method: 'POST',
headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
});
} catch (err) {}
fetchUnreadCount();
window.location.href = ticketId
? `/admin/tickets/${ticketId}`
: (window.appConfig?.notifications?.list ?? '/notifications');
};

async function loadDropdownNotif() {
const list = document.getElementById('dropdownList');
if (!list) return;
list.innerHTML = '<div style="padding:24px;text-align:center;color:#94a3b8;font-size:13px;">Memuat...</div>';
try {
const res = await fetch(window.appConfig.notifications.list);
const items = await res.json();
if (!Array.isArray(items) || !items.length) {
list.innerHTML = '<div style="padding:32px;text-align:center;color:#94a3b8;font-size:13px;">Tidak ada notifikasi</div>';
return;
}

const unreadItems = items.filter(n => !n.is_read);
if (!unreadItems.length) {
list.innerHTML = '<div style="padding:32px;text-align:center;color:#94a3b8;font-size:13px;">Tidak ada notifikasi baru</div>';
return;
}

list.innerHTML = items.map(n => `
<div onclick="markReadAndGo('${n.id}','${n.ticket_id ?? ''}')"
    style="display:flex;gap:12px;padding:14px 18px;border-bottom:1px solid #f8faf9;
                        cursor:pointer;background:${n.is_read ? '#fff' : '#f0fdfb'};"
    onmouseover="this.style.background='#f8faf9'" onmouseout="this.style.background='${n.is_read ? '#fff' : '#f0fdfb'}'">
    <div
        style="width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:5px;
                            background:${n.is_read ? '#e2e8f0' : (typeColor[n.type] ?? '#94a3b8')};">
    </div>
    <div style="flex:1;min-width:0;">
        <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
            <span style="font-size:11px;font-weight:700;color:${typeColor[n.type] ?? '#64748b'};">
                ${typeLabel[n.type] ?? n.type ?? ''}
            </span>
            <span style="font-size:11px;color:#94a3b8;">${n.time ?? ''}</span>
        </div>
        <p
            style="font-size:13px;font-weight:600;color:#0f172a;margin:0 0 3px;
                              white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            ${n.title ?? ''}
        </p>
        <p
            style="font-size:12px;color:#64748b;margin:0;line-height:1.4;
                              display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
            ${n.description ?? ''}
        </p>
    </div>
</div>
`).join('');
} catch (err) {
list.innerHTML = '<div style="padding:24px;text-align:center;color:#ef4444;font-size:13px;">Gagal memuat notifikasi</div>';
}
}

async function fetchUnreadCount() {
try {
const res = await fetch(window.appConfig.notifications.unread);
const data = await res.json();
const badge = document.getElementById('notificationBadge');
if (!badge) return;
badge.textContent = data.count;
badge.style.display = data.count > 0 ? '' : 'none';
} catch (err) {}
}

document.addEventListener('click', function(e) {
const toggle = document.getElementById('notifToggle');
const dropdown = document.getElementById('notifDropdown');
if (!toggle || !dropdown) return;
const wrapper = toggle.closest('div');
if (wrapper && !wrapper.contains(e.target)) dropdown.style.display = 'none';
});

document.addEventListener('DOMContentLoaded', function() {
    if (window.appConfig?.notifications?.unread) {
        fetchUnreadCount();
        setInterval(fetchUnreadCount, 30000);
    }
    initSidebar();
    initLoginPage();
    initDashboardFilters();
    initDashboardMonitoring();
    initImageUpload();
    initNotifPage();
    initRiwayatPage();
    initTicketsIndex();
    initTeknisiPage();
    initAssignTeknisi();

    const selectPelanggan = document.getElementById('selectPelanggan');
    if (selectPelanggan) {
        selectPelanggan.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                document.querySelector('input[name="nama_pelapor"]').value = opt.dataset.nama    ?? '';
                document.querySelector('input[name="no_telepon"]').value   = opt.dataset.telepon ?? '';
                document.querySelector('textarea[name="alamat"]').value    = opt.dataset.alamat  ?? '';
                document.querySelector('input[name="email"]').value        = opt.dataset.email   ?? '';
            } else {
                document.querySelector('input[name="nama_pelapor"]').value = '';
                document.querySelector('input[name="no_telepon"]').value   = '';
                document.querySelector('textarea[name="alamat"]').value    = '';
                document.querySelector('input[name="email"]').value        = '';
            }
        });
    }
});

function initSidebar() {
const appShell = document.getElementById('appShell');
const sidebar = document.getElementById('sidebar');
const btnClose = document.getElementById('sidebarToggle');
const btnOpen = document.getElementById('sidebarOpen');
const logoBtn = document.getElementById('sidebarLogoBtn');

if (!appShell || !sidebar) return;

function collapseSidebar() {
sidebar.style.transition = 'width 0.25s ease, min-width 0.25s ease';
appShell.classList.add('sidebar-collapsed');
sidebar.style.width = '56px';
sidebar.style.minWidth = '56px';
sidebar.style.overflow = 'hidden';
localStorage.setItem('sidebarCollapsed', '1');
}

function expandSidebar() {
document.documentElement.classList.remove('sidebar-pre-collapsed');
sidebar.style.transition = 'width 0.25s ease, min-width 0.25s ease';
appShell.classList.remove('sidebar-collapsed');
sidebar.style.width = '230px';
sidebar.style.minWidth = '230px';
sidebar.style.overflow = '';
localStorage.setItem('sidebarCollapsed', '0');
setTimeout(() => {
sidebar.style.transition = '';
sidebar.style.width = '';
sidebar.style.minWidth = '';
}, 250);
}

if (localStorage.getItem('sidebarCollapsed') === '1') {
appShell.classList.add('sidebar-collapsed');
sidebar.style.transition = 'none';
sidebar.style.width = '56px';
sidebar.style.minWidth = '56px';
sidebar.style.overflow = 'hidden';
requestAnimationFrame(() => {
sidebar.style.transition = '';
});
}
btnOpen?.addEventListener('click', expandSidebar);
btnClose?.addEventListener('click', collapseSidebar);
logoBtn?.addEventListener('click', function(e) {
e.preventDefault();
e.stopPropagation();
e.stopImmediatePropagation();

if (appShell.classList.contains('sidebar-collapsed')) {
expandSidebar();
} else {
const href = this.dataset.href;
if (href) window.location.href = href;
}
});

document.querySelectorAll('#sidebar .sidebar-nav-item').forEach(function(item) {
item.addEventListener('click', function(e) {
e.preventDefault();
e.stopPropagation();
e.stopImmediatePropagation();

if (e.target.closest('#sidebarLogoBtn')) return;

const href = this.getAttribute('href');
if (!href || href === '#') return;

if (appShell.classList.contains('sidebar-collapsed')) {
localStorage.setItem('sidebarCollapsed', '1');
window.location.href = href;
return;
}

window.location.href = href;
});
});
}

function initLoginPage() {
if (!document.querySelector('.login-card')) return;
autoHideAlerts();
initPasswordToggle();
initRoleTabs();
}

function autoHideAlerts() {
document.querySelectorAll('.alert').forEach(alert => {
setTimeout(() => {
alert.style.transition = 'opacity 0.3s ease';
alert.style.opacity = '0';
setTimeout(() => alert.remove(), 300);
}, 5000);
});
}

function initPasswordToggle() {
const passwordInput = document.getElementById('password');
if (!passwordInput) return;
const toggleButton = passwordInput.parentElement.querySelector('.password-toggle');
if (!toggleButton) return;
let visible = false;
toggleButton.addEventListener('click', function() {
visible = !visible;
passwordInput.type = visible ? 'text' : 'password';
});
}

function initRoleTabs() {
const roleTabs = document.querySelectorAll('.role-tab');
const roleInput = document.getElementById('roleInput');
if (!roleTabs.length || !roleInput) return;
roleTabs.forEach(tab => {
tab.addEventListener('click', function() {
roleTabs.forEach(t => { t.classList.remove('active'); t.classList.add('inactive'); });
this.classList.remove('inactive');
this.classList.add('active');
roleInput.value = this.id === 'tabAdmin' ? 'admin' : 'teknisi';
});
});
}

function initDashboardFilters() {
const issuesList = document.getElementById('issuesList');
if (!issuesList) return;
if (!issuesList.querySelector('.issue-item')) return;

issuesList.addEventListener('click', function(e) {
const item = e.target.closest('.issue-item[data-url]');
if (item) window.location.href = item.dataset.url;
});

const statusFilter = document.getElementById('statusFilter');
const priorityFilter = document.getElementById('priorityFilter');
const teknisiFilter = document.getElementById('teknisiFilter');

function filterIssues() {
const status = statusFilter?.value || '';
const priority = priorityFilter?.value || '';
const teknisi = teknisiFilter?.value || '';
let visible = 0;

issuesList.querySelectorAll('.issue-item').forEach(issue => {
const show = (!status || issue.dataset.status === status)
&& (!priority || issue.dataset.priority === priority)
&& (!teknisi || issue.dataset.teknisi === teknisi);
issue.style.display = show ? 'block' : 'none';
if (show) visible++;
});

const title = document.querySelector('.issues-title');
if (title) title.textContent = `Daftar Masalah (${visible})`;
}

statusFilter?.addEventListener('change', filterIssues);
priorityFilter?.addEventListener('change', filterIssues);
teknisiFilter?.addEventListener('change', filterIssues);
}

function initDashboardMonitoring() {
const issuesList = document.getElementById('issuesList');
console.log('issuesList:', issuesList);
console.log('has issue-card:', issuesList?.querySelector('.issue-card'));
if (!issuesList || !issuesList.querySelector('.issue-card')) return;

let selectedTechId = null;
let selectedTechName = null;
let currentIssueId = null;

function openAssignModal(issueId, issueTitle) {
currentIssueId = issueId;
selectedTechId = null;
selectedTechName = null;
document.getElementById('modalIssueName').textContent = issueTitle;
document.getElementById('assignModal').classList.add('open');
document.querySelectorAll('.tech-select-item').forEach(function(el) {
el.classList.remove('selected');
});
const confirmBtn = document.getElementById('confirmAssignBtn');
if (confirmBtn) { confirmBtn.disabled = true; confirmBtn.style.opacity = '0.5'; }
}

function closeAssignModal() {
const modal = document.getElementById('assignModal');
if (modal) modal.classList.remove('open');
}

function updateAssignButton(issueId, techName) {
document.querySelectorAll('.btn-assign').forEach(function(btn) {
if (btn.dataset.id == issueId) {
btn.classList.remove('unassigned');
btn.classList.add('assigned');
btn.textContent = techName;
}
});
}

function showDashboardToast(msg, isError) {
const toast = document.getElementById('toast');
const toastMsg = document.getElementById('toastMsg');
if (!toast || !toastMsg) return;
toastMsg.textContent = msg;
toast.style.borderColor = isError ? 'var(--critical)' : 'var(--resolved)';
toast.classList.add('show');
setTimeout(function() { toast.classList.remove('show'); }, 3500);
}

function confirmAssign() {
    if (!selectedTechId || !currentIssueId) return;

    const confirmBtn = document.getElementById('confirmAssignBtn');
    if (confirmBtn) {
        confirmBtn.textContent = 'Memproses...';
        confirmBtn.disabled = true;
        confirmBtn.style.opacity = '0.7';
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`${window.assignRouteBase}/${currentIssueId}/assign`, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ teknisi_id: selectedTechId })
    })
    .then(function(res) {
        if (!res.ok) {
            throw new Error('HTTP status ' + res.status);
        }
        return res.json();
    })
    .then(function(data) {
        if (data.success) {
            closeAssignModal();
            updateAssignButton(currentIssueId, selectedTechName);
            showDashboardToast(selectedTechName + ' berhasil di-assign.');
            location.reload(); // supaya langsung update ke teknisi
        } else {
            showDashboardToast('Gagal: ' + (data.message || 'Coba lagi.'), true);
        }
    })
    .catch(function(err) {
        console.log(err);
        showDashboardToast('Terjadi kesalahan. Coba lagi.', true);
    })
    .finally(function() {
        if (confirmBtn) {
            confirmBtn.textContent = 'Assign Teknisi';
            confirmBtn.disabled = false;
            confirmBtn.style.opacity = '1';
        }
    });
}

function filterIssues() {
const status = document.getElementById('statusFilter')?.value || '';
const priority = document.getElementById('priorityFilter')?.value || '';
const teknisi = document.getElementById('teknisiFilter')?.value || '';
const search = document.getElementById('searchInput')?.value.toLowerCase() || '';
let visible = 0;
document.querySelectorAll('.issue-card').forEach(function(card) {
const ok = (!status || card.dataset.status === status)
&& (!priority || card.dataset.priority === priority)
&& (!teknisi || card.dataset.teknisi === teknisi)
&& (!search || card.textContent.toLowerCase().includes(search));
card.style.display = ok ? '' : 'none';
if (ok) visible++;
});
const countEl = document.getElementById('issueCount');
if (countEl) countEl.textContent = visible + ' issues';
}

document.querySelectorAll('.activity-dot').forEach(function(dot) {
dot.style.background = dot.dataset.color || 'var(--text-dim)';
});

issuesList.addEventListener('click', function(e) {
const btn = e.target.closest('.btn-assign');
if (!btn) return;
console.log('btn.dataset.id:', btn.dataset.id);
openAssignModal(btn.dataset.id, btn.dataset.title);
});

const techSelectList = document.getElementById('techSelectList');
if (techSelectList) {
techSelectList.addEventListener('click', function(e) {
const item = e.target.closest('.tech-select-item');
if (!item) return;
document.querySelectorAll('.tech-select-item').forEach(function(el) { el.classList.remove('selected'); });
item.classList.add('selected');
selectedTechId = item.dataset.techId;
selectedTechName = item.dataset.techName;
console.log('selectedTechId:', selectedTechId);
console.log('selectedTechName:', selectedTechName);
const confirmBtn = document.getElementById('confirmAssignBtn');
if (confirmBtn) { confirmBtn.disabled = false; confirmBtn.style.opacity = '1'; }
});
}

const confirmBtn2 = document.getElementById('confirmAssignBtn');
confirmBtn2?.addEventListener('click', confirmAssign);
document.getElementById('btnBatal')?.addEventListener('click', closeAssignModal);
document.getElementById('btnCloseModal')?.addEventListener('click', closeAssignModal);
document.getElementById('assignModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeAssignModal();
});

document.addEventListener('keydown', function(e) {
if (e.key === 'Escape') closeAssignModal();
});
document.getElementById('searchInput')?.addEventListener('input', filterIssues);
['statusFilter', 'priorityFilter', 'teknisiFilter'].forEach(function(id) {
document.getElementById(id)?.addEventListener('change', filterIssues);
});
}

function initImageUpload() {
const uploadArea = document.getElementById('uploadArea');
const fotoInput = document.getElementById('foto');
const placeholder = document.getElementById('uploadPlaceholder');
const imagePreview = document.getElementById('imagePreview');
const removeBtn = document.getElementById('removeImage');
if (!uploadArea || !fotoInput) return;
const previewImg = imagePreview ? imagePreview.querySelector('img') : null;
uploadArea.addEventListener('click', function (e) {
if (removeBtn && (e.target === removeBtn || removeBtn.contains(e.target))) return;
fotoInput.click();
});
uploadArea.addEventListener('dragover', function (e) {
e.preventDefault();
uploadArea.style.borderColor = '#2bb0a6';
});
uploadArea.addEventListener('dragleave', function () {
uploadArea.style.borderColor = '';
});
uploadArea.addEventListener('drop', function (e) {
e.preventDefault();
uploadArea.style.borderColor = '';
const file = e.dataTransfer.files[0];
if (!file) return;
if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
alert('Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
return;
}
if (file.size > 2 * 1024 * 1024) {
alert('Ukuran file terlalu besar. Maksimal 2MB.');
return;
}
const dataTransfer = new DataTransfer();
dataTransfer.items.add(file);
fotoInput.files = dataTransfer.files;
tampilkanPreview(file);
});
fotoInput.addEventListener('change', function () {
if (this.files && this.files[0]) tampilkanPreview(this.files[0]);
});
removeBtn?.addEventListener('click', function (e) {
e.stopPropagation();
fotoInput.value = '';
if (previewImg) previewImg.src             = '';
if (imagePreview) imagePreview.style.display = 'none';
if (placeholder) placeholder.style.display  = 'block';
});
function tampilkanPreview(file) {
const reader = new FileReader();
reader.onload = function (e) {
if (previewImg) previewImg.src = e.target.result;
if (placeholder) placeholder.style.display  = 'none';
if (imagePreview) imagePreview.style.display = 'block';
};
reader.readAsDataURL(file);
}
}

function initNotifPage() {
const notifList = document.getElementById('notifList');
const notifEmpty = document.getElementById('notifEmpty');
const assignModal = document.getElementById('assignModal');
const toast = document.getElementById('toast');
const btnMarkAll = document.getElementById('btnMarkAll');
const btnClearAll = document.getElementById('btnClearAll');
if (!notifList) return;
let currentFilter  = 'all';
let assignTargetId = null;
function showToast(msg) {
if (!toast) return;
toast.textContent = msg;
toast.classList.add('show');
setTimeout(() => toast.classList.remove('show'), 3000);
}
function getItems() {
return Array.from(notifList.querySelectorAll('.notif-item'));
}
function updateCounts() {
const items = getItems();
const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
set('countAll', items.length);
set('countSystem', items.filter(i => i.dataset.type === 'sistem').length);
set('countTeknisi', items.filter(i => i.dataset.type === 'teknisi').length);
set('countPelanggan', items.filter(i => i.dataset.type === 'pelanggan').length);
set('countUnread', items.filter(i => i.classList.contains('unread')).length);
}
function applyFilter(filter) {
currentFilter = filter;
let visible = 0;
getItems().forEach(item => {
const show = filter === 'all'
|| (filter === 'unread' && item.classList.contains('unread'))
|| item.dataset.type === filter;
item.style.display = show ? '' : 'none';
if (show) visible++;
});
if (notifEmpty) notifEmpty.style.display = visible === 0 ? '' : 'none';
}
function markRead(id) {
const item = notifList.querySelector(`.notif-item[data-id="${id}"]`);
if (!item) return;
fetch(`/notifications/${id}/read`, {
method: 'POST',
headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
}).catch(() => {});

item.classList.replace('unread', 'read');
const dot = item.querySelector('.notif-unread-dot');
if (dot) dot.style.display = 'none';
const btn = item.querySelector('.btn-mark-read');
if (btn) { btn.textContent = 'Sudah Dibaca'; btn.disabled = true; btn.style.opacity = '0.5'; }
updateCounts();
if (currentFilter === 'unread') applyFilter(currentFilter);
showToast('Ditandai sudah dibaca');
}
function deleteItem(id) {
const item = notifList.querySelector(`.notif-item[data-id="${id}"]`);
if (!item) return;
item.style.transition = 'all 0.3s ease';
item.style.opacity    = '0';
item.style.transform  = 'translateX(20px)';
setTimeout(() => { item.remove(); updateCounts(); applyFilter(currentFilter); showToast('Dihapus'); }, 300);
}
notifList.addEventListener('click', function(e) {
const btn = e.target.closest('button');
if (!btn) return;
const item = btn.closest('.notif-item');
const id = item?.dataset.id;
if (btn.classList.contains('btn-assign')) { assignTargetId = id; if (assignModal) assignModal.style.display = 'flex'; }
else if (btn.classList.contains('btn-mark-read')) markRead(id);
else if (btn.classList.contains('btn-delete')) deleteItem(id);
});
document.querySelectorAll('.notif-tab').forEach(tab => {
tab.addEventListener('click', function() {
document.querySelectorAll('.notif-tab').forEach(t => t.classList.remove('active'));
this.classList.add('active');
applyFilter(this.dataset.filter);
});
});
btnMarkAll?.addEventListener('click', function() {
fetch(window.appConfig.notifications.readAll, {
method: 'POST',
headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
}).catch(() => {});

notifList.querySelectorAll('.notif-item.unread').forEach(item => markRead(item.dataset.id));
showToast('Semua ditandai sudah dibaca');
});

btnClearAll?.addEventListener('click', function() {
fetch(window.appConfig.notifications.destroyAll, {
method: 'DELETE',
headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
})
.then(res => res.json())
.then(data => {
if (data.success) {
const visible = getItems().filter(i => i.style.display !== 'none');
visible.forEach((item, idx) => {
setTimeout(() => {
item.style.transition = 'all 0.25s ease';
item.style.opacity    = '0';
item.style.transform  = 'translateX(20px)';
setTimeout(() => item.remove(), 280);
}, idx * 60);
});
setTimeout(() => {
updateCounts();
applyFilter(currentFilter);
showToast('Semua dihapus');
const badge = document.getElementById('notificationBadge');
if (badge) { badge.textContent = '0'; badge.style.display = 'none'; }
}, visible.length * 60 + 300);
}
})
.catch(() => showToast('Gagal menghapus'));
});

if (assignModal) {
document.getElementById('closeModal')?.addEventListener('click', () => { assignModal.style.display = 'none'; });
document.getElementById('cancelAssign')?.addEventListener('click', () => { assignModal.style.display = 'none'; });
assignModal.addEventListener('click', e => { if (e.target === assignModal) assignModal.style.display = 'none'; });
document.getElementById('confirmAssign')?.addEventListener('click', function() {
const selected = document.querySelector('input[name="teknisi"]:checked');
if (!selected) { showToast('Pilih teknisi terlebih dahulu'); return; }
const teknisiName = selected.closest('.teknisi-option')?.querySelector('.teknisi-name')?.textContent;
if (assignTargetId) {
const item = notifList.querySelector(`.notif-item[data-id="${assignTargetId}"]`);
const btnAssign = item?.querySelector('.btn-assign');
if (btnAssign) {
const label = document.createElement('span');
label.className   = 'meta-chip status-progress';
label.textContent = teknisiName;
btnAssign.replaceWith(label);
}
markRead(assignTargetId);
}
assignModal.style.display = 'none';
showToast('Berhasil ditugaskan ke ' + teknisiName);
});
}
updateCounts();
}

function initRiwayatPage() {
const riwayatList = document.getElementById('riwayatList');
const riwayatEmpty = document.getElementById('riwayatEmpty');
const searchInput = document.getElementById('riwayatSearch');
const periodSelect = document.getElementById('riwayatPeriod');
if (!riwayatList) return;
function applyFilters() {
const keyword = searchInput?.value.toLowerCase().trim() || '';
const period = periodSelect?.value || '';
const today = new Date();
let visible = 0;
riwayatList.querySelectorAll('.riwayat-item').forEach(item => {
const title = (item.dataset.title || '').toLowerCase();
const itemDate = item.dataset.date ? new Date(item.dataset.date) : null;
const keywordOk = !keyword || title.includes(keyword);
let periodOk = true;
if (period && itemDate) {
const diff = (today - itemDate) / 86400000;
if (period === 'today') periodOk = diff < 1; if (period === 'week') periodOk=diff <=7; if (period === 'month')
    periodOk=diff <=30; } const show=keywordOk && periodOk; item.style.display=show ? '' : 'none' ; if (show) visible++;
    }); if (riwayatEmpty) riwayatEmpty.style.display=visible===0 ? '' : 'none' ; }
    searchInput?.addEventListener('input', applyFilters); periodSelect?.addEventListener('change', applyFilters);
    riwayatList.addEventListener('click', function(e) { const btn=e.target.closest('.riwayat-detail-btn'); if (!btn)
    return; e.preventDefault(); window.location.href=`/admin/tickets/${btn.dataset.id}`; }); applyFilters(); } function
    initTicketsIndex() { if (!document.querySelector('.filter-form')) return; const
    filterForm=document.querySelector('.filter-form'); const
    statusSelect=filterForm?.querySelector('select[name="status" ]'); const
    prioritySelect=filterForm?.querySelector('select[name="priority" ]'); [statusSelect,
    prioritySelect].forEach(function(select) { if (!select) return; select.addEventListener('change', function() {
    filterForm.submit(); }); }); const tableRows=document.querySelectorAll('.table-row');
    tableRows.forEach(function(row) { const actionLink=row.querySelector('.btn-action'); if (!actionLink) return;
    row.addEventListener('click', function(e) { if (e.target.closest('a') || e.target.closest('button')) return;
    window.location.href=actionLink.href; }); }); const searchInput=document.getElementById('searchInput'); const
    statusFilter=document.getElementById('statusFilter'); const
    priorityFilter=document.getElementById('priorityFilter'); const cards=document.querySelectorAll('.ticket-card');
    function filterCards() { const search=searchInput ? searchInput.value.toLowerCase() : '' ; const status=statusFilter
    ? statusFilter.value : '' ; const priority=priorityFilter ? priorityFilter.value : '' ; cards.forEach(card=> {
    const matchSearch = !search || card.dataset.search.includes(search);
    const matchStatus = !status || card.dataset.status === status;
    const matchPriority = !priority || card.dataset.priority === priority;
    card.style.display = (matchSearch && matchStatus && matchPriority) ? 'flex' : 'none';
    });
    }
    if (searchInput) searchInput.addEventListener('input', filterCards);
    if (statusFilter) statusFilter.addEventListener('change', filterCards);
    if (priorityFilter) priorityFilter.addEventListener('change', filterCards);
    cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
    card.style.transform   = 'translateY(-4px)';
    card.style.boxShadow   = '0 8px 24px rgba(0,0,0,0.10)';
    card.style.borderColor = '#2bb0a6';
    });
    card.addEventListener('mouseleave', () => {
    card.style.transform   = '';
    card.style.boxShadow   = '';
    card.style.borderColor = '#e2e8f0';
    });
    });
    const grid = document.getElementById('ticketGrid');
    function updateGrid() {
    if (!grid) return;
    const w = window.innerWidth;
    if (w <= 640) grid.style.gridTemplateColumns = '1fr'; else if (w <=1100)
        grid.style.gridTemplateColumns = 'repeat(2,1fr)'; else grid.style.gridTemplateColumns = 'repeat(3,1fr)'; }
        updateGrid(); window.addEventListener('resize', updateGrid); const
        ticketTitles=document.querySelectorAll('.ticket-title'); ticketTitles.forEach(function(el) { if (el.scrollWidth>
        el.clientWidth) {
        el.setAttribute('title', el.textContent.trim());
        }
        });
        }

        function initTeknisiPage() {
        const modal = document.getElementById('teknisiModal');
        if (!modal) return;
        const btnAdd = document.querySelector('.teknisi-btn-add');
        const btnClose = document.getElementById('tmClose');
        const btnBatal = document.getElementById('tmBatal');
        const form = document.getElementById('formTambahTeknisi');
        const optAktif = document.getElementById('optAktif');
        const optNonaktif = document.getElementById('optNonaktif');
        btnAdd?.addEventListener('click', function() { modal.classList.add('open'); });
        function closeTeknisiModal() {
        modal.classList.remove('open');
        if (form) form.reset();
        if (optAktif) optAktif.classList.add('active');
        if (optNonaktif) optNonaktif.classList.remove('active');
        const hiddenStatus = form?.querySelector('input[name="status"]');
        if (hiddenStatus) hiddenStatus.value = 'Aktif';
        }
        btnClose?.addEventListener('click', closeTeknisiModal);
        btnBatal?.addEventListener('click', closeTeknisiModal);
        modal.addEventListener('click', function(e) {
        if (e.target === modal) closeTeknisiModal();
        });
        document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) closeTeknisiModal();
        });
        optAktif?.addEventListener('click', function() {
        optAktif.classList.add('active');
        optNonaktif?.classList.remove('active');
        const hiddenStatus = form?.querySelector('input[name="status"]');
        if (hiddenStatus) hiddenStatus.value = 'Aktif';
        });
        optNonaktif?.addEventListener('click', function() {
        optNonaktif.classList.add('active');
        optAktif?.classList.remove('active');
        const hiddenStatus = form?.querySelector('input[name="status"]');
        if (hiddenStatus) hiddenStatus.value = 'Nonaktif';
        });
        }

        const editModal = document.getElementById('editTeknisiModal');
        if (editModal) {
        editModal.style.cssText = 'display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;';

        document.querySelectorAll('.btn-edit-teknisi').forEach(btn => {
        btn.addEventListener('click', function() {
        document.getElementById('editName').value = this.dataset.name;
        document.getElementById('editEmail').value = this.dataset.email;
        document.getElementById('editPassword').value = '';
        document.getElementById('editSpesialisasi').value = this.dataset.spesialisasi;

        document.querySelectorAll('#formEditTeknisi input[name="status"]').forEach(r => {
        r.checked = (r.value === this.dataset.status);
        r.closest('label').classList.toggle('active', r.value === this.dataset.status);
        });

        document.getElementById('formEditTeknisi').action = `/admin/teknisi/${this.dataset.id}`;
        document.querySelectorAll('.teknisi-dropdown').forEach(d => d.classList.remove('show'));
        editModal.style.display = 'flex';
        });
        });

        document.getElementById('tmEditClose')?.addEventListener('click', () => editModal.style.display = 'none');
        document.getElementById('tmEditBatal')?.addEventListener('click', () => editModal.style.display = 'none');
        editModal?.addEventListener('click', e => { if (e.target === editModal) editModal.style.display = 'none'; });
        }

        window.applyTheme = function(theme) {
        fetch('/settings/theme', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ theme: theme })
        })
        .then(r => r.json())
        .then(res => {
        if (res.success) {
        if (theme === 'dark') document.body.classList.add('dark-mode');
        else document.body.classList.remove('dark-mode');
        }
        });
        };

        function toggleSection(id) {
        const el = document.getElementById(id);
        const arrow = document.getElementById(id + 'Arrow');
        const open = el.style.display === 'none';
        el.style.display = open ? 'block' : 'none';
        if (arrow) arrow.style.transform = open ? 'rotate(90deg)' : 'rotate(0deg)';
        }

        let currentTicketId = null;
        let activeStatusFilter = 'all';

        window.filterStatus = function(status, el) {
        activeStatusFilter = status;
        document.querySelectorAll('.stat-chip').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        doSearch();
        }

        window.doSearch = function() {
        const searchEl = document.getElementById('searchInput');
        const priorityEl = document.getElementById('priorityFilter');
        const sortEl = document.getElementById('sortFilter');
        if (!searchEl) return;
        const q = searchEl.value.toLowerCase().trim();
        const priority = priorityEl ? priorityEl.value : '';
        const sort = sortEl ? sortEl.value : 'newest';
        const cards = [...document.querySelectorAll('.tugas-card')];
        const pOrder = { critical:0, high:1, medium:2, low:3 };
        let visible = cards.filter(c => {
        const matchStatus = activeStatusFilter === 'all' || matchStatusFilter(c.dataset.status,
        activeStatusFilter);
        const matchPriority = !priority || c.dataset.priority === priority;
        const matchSearch = !q || c.dataset.title.includes(q) || c.dataset.desc.includes(q);
        return matchStatus && matchPriority && matchSearch;
        });
        visible.sort((a, b) => {
        if (sort === 'oldest') return a.dataset.created - b.dataset.created;
        if (sort === 'priority') return pOrder[a.dataset.priority] - pOrder[b.dataset.priority];
        return b.dataset.created - a.dataset.created;
        });
        cards.forEach(c => c.style.display = 'none');
        const grid = document.getElementById('tugasGrid');
        visible.forEach(c => { c.style.display = ''; grid.appendChild(c); });
        const emptyEl = document.getElementById('emptySearch');
        if (emptyEl) emptyEl.style.display = visible.length === 0 ? '' : 'none';
        }

        function matchStatusFilter(status, filter) {
        if (filter === 'resolved') return ['resolved','closed'].includes(status);
        return status === filter;
        }

        window.openDetail = function(id) {
        const t = window.tugasTickets[id];
        if (!t) return;
        currentTicketId = id;
        document.getElementById('modalTicketId').textContent = '#' + String(id).padStart(5,'0');
        document.getElementById('modalTitle').textContent = t.title;
        document.getElementById('modalDesc').textContent = t.description || '—';
        document.getElementById('modalCreated').textContent = formatDate(t.created_at);
        document.getElementById('modalUpdated').textContent = formatDate(t.updated_at);
        document.getElementById('modalStatusBadge').innerHTML = badgeHtml(t.status);
        document.getElementById('modalPriority').innerHTML = badgeHtml(t.priority);
        const isClosed = ['resolved','closed'].includes(t.status);
        document.getElementById('statusSection').style.display = isClosed ? 'none' : '';
        if (!isClosed) document.getElementById('statusSelect').innerHTML = statusOptions(t.status);
        document.getElementById('selesaiBtn').innerHTML = !isClosed
        ? `<button class="btn btn-selesai btn-sm" onclick="tandaiSelesai(${id})">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg> Tandai Selesai
        </button>` : '';
        loadComments(id);
        document.getElementById('detailModal').classList.add('show');
        document.body.style.overflow = 'hidden';
        }

        window.closeDetail = function() {
        document.getElementById('detailModal').classList.remove('show');
        document.body.style.overflow = '';
        currentTicketId = null;
        }

        window.tandaiSelesai = function(id) {
        if (!confirm('Tandai tiket ini sebagai selesai?')) return;
        const token = document.querySelector('meta[name="csrf-token"]').content;
        fetch(`/teknisi/tugas/${id}/selesai-ajax`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                const card = document.querySelector(`.tugas-card[data-id="${id}"]`);
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(-10px)';
                    setTimeout(() => card.remove(), 300);
                }
                closeDetail();
                showToast('Tiket berhasil diselesaikan!', 'success');
            } else {
                showToast('Gagal menyelesaikan tiket', 'error');
            }
        })
        .catch(() => showToast('Terjadi kesalahan', 'error'));
    }

        window.tandaiSelesaiDashboard = function(id, btn) {
        if (!confirm('Tandai tiket ini sebagai selesai?')) return;
        const token = document.querySelector('meta[name="csrf-token"]').content;
        btn.disabled = true;
        btn.textContent = 'Memproses...';
        fetch(`/teknisi/tugas/${id}/selesai-ajax`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                const item = btn.closest('.ticket-item');
                if (item) {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(-8px)';
                    setTimeout(() => item.remove(), 300);
                }
            } else {
                btn.disabled = false;
                btn.textContent = 'Tandai Selesai';
                alert('Gagal: ' + (res.message || 'Coba lagi'));
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'Tandai Selesai';
            alert('Terjadi kesalahan');
        });
    }

        function statusOptions(current) {
        return [
        { value: 'open', label: 'Open' },
        { value: 'in_progress', label: 'In Progress' },
        { value: 'resolved', label: 'Resolved' },
        ].map(o => `<option value="${o.value}" ${o.value===current?'selected':''}>${o.label}</option>`).join('');
        }

        window.updateStatus = function() {
        const id = currentTicketId;
        const status = document.getElementById('statusSelect').value;
        const token = document.querySelector('meta[name="csrf-token"]').content;
        fetch(`/teknisi/tugas/${id}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify({ status })
        })
        .then(r => r.json())
        .then(res => {
        if (res.success) {
        window.tugasTickets[id].status = status;
        const card = document.querySelector(`.tugas-card[data-id="${id}"]`);
        if (card) {
        card.dataset.status = status;
        const badge =
        card.querySelector('[class*="badge-open"],[class*="badge-in_progress"],[class*="badge-resolved"],[class*="badge-closed"]');
        if (badge) {
        badge.className = `badge badge-${status}`;
        badge.textContent = status === 'in_progress' ? 'In Progress' : capitalize(status);
        }
        }
        document.getElementById('modalStatusBadge').innerHTML = badgeHtml(status);
        if (['resolved','closed'].includes(status)) {
        document.getElementById('statusSection').style.display = 'none';
        document.getElementById('selesaiBtn').innerHTML = '';
        }
        showToast('Status berhasil diperbarui', 'success');
        } else {
        showToast(res.message || 'Gagal memperbarui status', 'error');
        }
        })
        .catch(() => showToast('Terjadi kesalahan', 'error'));
        }

        function loadComments(id) {
        const list = document.getElementById('commentList');
        list.innerHTML = '<div style="font-size:13px;color:#94a3b8;text-align:center;padding:16px 0;">Memuat...</div>';
        fetch(`/teknisi/tugas/${id}/komentar`)
        .then(r => r.json())
        .then(res => {
        if (!res.data || res.data.length === 0) {
        list.innerHTML = '<div style="font-size:13px;color:#94a3b8;text-align:center;padding:16px 0;">Belum ada catatan</div>';
        return;
        }
        list.innerHTML = res.data.map(c => `
        <div class="comment-item">
            <div class="comment-meta">
                <span style="font-weight:600;color:#334155;">${c.user_name ?? 'Teknisi'}</span>
                <span>${formatDate(c.created_at)}</span>
            </div>
            <div>${escHtml(c.body)}</div>
        </div>
        `).join('');
        })
        .catch(() => {
        list.innerHTML = '<div style="font-size:13px;color:#ef4444;text-align:center;padding:16px 0;">Gagal memuat komentar</div>';
        });
        }

        window.submitComment = function() {
        const id = currentTicketId;
        const body = document.getElementById('commentInput').value.trim();
        const token = document.querySelector('meta[name="csrf-token"]').content;
        if (!body) { showToast('Komentar tidak boleh kosong', 'error'); return; }
        fetch(`/teknisi/tugas/${id}/komentar`, {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify({ body })
        })
        .then(r => r.json())
        .then(res => {
        if (res.success) {
        document.getElementById('commentInput').value = '';
        loadComments(id);
        showToast('Catatan berhasil ditambahkan', 'success');
        } else {
        showToast(res.message || 'Gagal mengirim catatan', 'error');
        }
        })
        .catch(() => showToast('Terjadi kesalahan', 'error'));
        }

        function badgeHtml(value) {
        const labels = {
        open:'Open', in_progress:'In Progress', resolved:'Resolved', closed:'Closed',
        critical:'Critical', high:'High', medium:'Medium', low:'Low'
        };
        return `<span class="badge badge-${value}">${labels[value] ?? capitalize(value)}</span>`;
        }

        function capitalize(s) { return s ? s.charAt(0).toUpperCase()+s.slice(1) : ''; }

        function formatDate(raw) {
        if (!raw) return '—';
        const d = new Date(raw);
        return d.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit',
        minute:'2-digit' });
        }

        function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace( />/g,'&gt;');
        }

        function showToast(msg, type='success') {
        const existing = document.getElementById('toastMsg');
        if (existing) existing.remove();
        const t = document.createElement('div');
        t.id = 'toastMsg';
        t.textContent = msg;
        Object.assign(t.style, {
        position:'fixed', bottom:'24px', right:'24px', zIndex:'9999',
        background: type==='success' ? '#10b981' : '#ef4444',
        color:'#fff', padding:'12px 20px', borderRadius:'10px',
        fontSize:'13.5px', fontWeight:'600', fontFamily:'DM Sans,sans-serif',
        boxShadow:'0 8px 24px rgba(0,0,0,.15)',
        });
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
        }

        document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('detailModal')?.addEventListener('click', closeDetail);
        });

        window.toggleExpand = function(id) {
        const body = document.getElementById('expand-' + id);
        const chevron = document.getElementById('chevron-' + id);
        if (!body) return;
        const isOpen = body.style.display !== 'none';
        body.style.display = isOpen ? 'none' : 'block';
        if (chevron) chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(90deg)';
        };

        window.togglePw = function(id, el) {
        const input = document.getElementById(id);
        if (!input) return;
        input.type = input.type === 'text' ? 'password' : 'text';
        };

        window.applyLang = function(lang) {
        fetch('/settings/language', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ locale: lang })
        })
        .then(r => r.json())
        .then(res => { if (res.success) window.location.reload(); })
        .catch(() => alert('Gagal mengubah bahasa.'));
        };

        window.handleNotifAll = function(checkbox) {
        ['notif-tiket-row', 'notif-reminder-row', 'notif-selesai-row'].forEach(id => {
        const row = document.getElementById(id);
        if (row) row.style.opacity = checkbox.checked ? '1' : '0.4';
        });
        ['notif-tiket', 'notif-reminder', 'notif-selesai'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.disabled = !checkbox.checked;
        });
        };

        function saveNotif() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(window.notifRoute, {
            method: 'PUT',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                notif_enabled: document.getElementById('notif-all').checked,
                notif_ticket: document.getElementById('notif-tiket').checked,
                notif_assign: document.getElementById('notif-selesai').checked,
            })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('HTTP ' + res.status);
                }
                return res.json();
            })
            .then(res => {
                if (res.success) {
                    showSettingsToast('Pengaturan notifikasi disimpan!');
                } else {
                    showSettingsToast('Gagal menyimpan pengaturan', true);
                }
            })
            .catch(err => {
                console.log(err);
                showSettingsToast('Terjadi kesalahan', true);
            });
        }

        function showSettingsToast(msg) {
        const existing = document.getElementById('settingsToast');
        if (existing) existing.remove();
        const t = document.createElement('div');
        t.id = 'settingsToast';
        t.textContent = msg;
        Object.assign(t.style, {
        position:'fixed', bottom:'24px', right:'24px', zIndex:'9999',
        background:'#10b981', color:'#fff', padding:'12px 20px',
        borderRadius:'10px', fontSize:'13px', fontWeight:'600',
        boxShadow:'0 8px 24px rgba(0,0,0,.15)',
        });
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
        }

        document.querySelectorAll('.teknisi-action-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const id = this.dataset.id;
        const dropdown = document.getElementById('dropdown-' + id);

        document.querySelectorAll('.teknisi-dropdown').forEach(function(d) {
        if (d !== dropdown) d.classList.remove('show');
        });

        dropdown.classList.toggle('show');
        });
        });

        document.addEventListener('click', function() {
        document.querySelectorAll('.teknisi-dropdown').forEach(function(d) {
        d.classList.remove('show');
        });
        });

        const roleLabels = { admin: 'Admin', teknisi: 'Teknisi', pelanggan: 'Pelanggan' };

        function selectRole(role) {
        document.getElementById('roleInput').value = role;
        const badge = document.getElementById('roleBadge');
        badge.className = 'selected-role-badge ' + role;
        document.getElementById('roleBadgeText').textContent = roleLabels[role];
        document.getElementById('formStaff').style.display = role !== 'pelanggan' ? 'block' : 'none';
        document.getElementById('formPelanggan').style.display = role === 'pelanggan' ? 'block' : 'none';
        document.getElementById('roleSelectScreen').style.display = 'none';
        document.getElementById('loginFormScreen').style.display = 'block';
        }

        function backToRole() {
        document.getElementById('loginFormScreen').style.display = 'none';
        document.getElementById('roleSelectScreen').style.display = 'flex';
        }

        function togglePassword(id) {
        const pwd = document.getElementById(id);
        pwd.type = pwd.type === 'password' ? 'text' : 'password';
        }

        window.selectRole = selectRole;
        window.backToRole = backToRole;
        window.togglePassword = togglePassword;

        document.addEventListener('DOMContentLoaded', () => {
        if (window.loginErrors) {
        selectRole(window.oldRole || 'admin');
        }
        });

        document.addEventListener('click', function(e) {
        if (e.target.closest('#btnBuatTiket')) {
        document.getElementById('tiketModal').style.display = 'flex';
        }
        if (e.target.closest('#tiketModalClose') || e.target.closest('#tiketModalBatal')) {
        document.getElementById('tiketModal').style.display = 'none';
        }
        if (e.target.id === 'tiketModal') {
        document.getElementById('tiketModal').style.display = 'none';
        }
        });

        function switchTab(tab) {
        const tiket = document.getElementById('sectionTiket');
        const laporan = document.getElementById('sectionLaporan');
        const tabT = document.getElementById('tabTiket');
        const tabL = document.getElementById('tabLaporan');

        if (tab === 'tiket') {
        tiket.style.display   = 'block';
        laporan.style.display = 'none';
        tabT.style.background = 'linear-gradient(135deg,#2bb0a6,#059669)';
        tabT.style.color      = '#fff';
        tabT.style.border     = 'none';
        tabL.style.background = '#fff';
        tabL.style.color      = '#64748b';
        tabL.style.border     = '1px solid #e2e8f0';
        } else {
        tiket.style.display   = 'none';
        laporan.style.display = 'block';
        tabL.style.background = 'linear-gradient(135deg,#2bb0a6,#059669)';
        tabL.style.color      = '#fff';
        tabL.style.border     = 'none';
        tabT.style.background = '#fff';
        tabT.style.color      = '#64748b';
        tabT.style.border     = '1px solid #e2e8f0';
        }
        }

        function openRejectModal(id) {
        document.getElementById('rejectForm').action = `/admin/laporan-pelanggan/${id}/reject`;
        document.getElementById('rejectModal').style.display = 'flex';
        }

        function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        }

        window.switchTab = switchTab;
        window.openRejectModal = openRejectModal;
        window.closeRejectModal = closeRejectModal;

        window.hapusSelected = function() {
        const checked = document.querySelectorAll('input[name="selected[]"]:checked');
        if (checked.length === 0) {
        alert('Pilih teknisi yang ingin dihapus terlebih dahulu.');
        return;
        }
        if (!confirm('Hapus ' + checked.length + ' teknisi yang dipilih?')) return;

        const ids = Array.from(checked).map(cb => cb.value);
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/teknisi/bulk-delete';

        const csrf = document.createElement('input');
        csrf.type  = 'hidden';
        csrf.name  = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrf);

        const method = document.createElement('input');
        method.type  = 'hidden';
        method.name  = '_method';
        method.value = 'DELETE';
        form.appendChild(method);

        ids.forEach(id => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'ids[]';
        input.value = id;
        form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        };

        function initAssignTeknisi() {
        const assignTekModal = document.getElementById('assignTeknisiModal');
        if (!assignTekModal) return;

        let assignTeknisiId = null;

        document.querySelectorAll('.btn-assign-teknisi').forEach(btn => {
        btn.addEventListener('click', function() {
        assignTeknisiId = this.dataset.id;
        document.getElementById('assignTeknisiName').textContent = this.dataset.name;
        document.querySelectorAll('.teknisi-dropdown').forEach(d => d.classList.remove('show'));

        const list = document.getElementById('assignTicketList');
        list.innerHTML = '<div style="text-align:center;color:#94a3b8;font-size:13px;padding:20px;">Memuat tiket...</div>';
        assignTekModal.style.display = 'flex';

        fetch('/admin/tickets?status=open&format=json')
        .then(r => r.json())
        .then(data => {
        if (!data.length) {
        list.innerHTML = '<div style="text-align:center;color:#94a3b8;font-size:13px;padding:20px;">Tidak ada tiket terbuka</div>';
        return;
        }
        list.innerHTML = data.map(t => `
        <label
            style="display:flex;align-items:center;gap:10px;padding:12px;border:1.5px solid #e2e8f0;border-radius:10px;cursor:pointer;background:#f8faf9;">
            <input type="radio" name="assign_ticket" value="${t.id}" style="accent-color:#2bb0a6;">
            <div>
                <div style="font-size:13px;font-weight:600;color:#0f172a;">${t.title}</div>
                <div style="font-size:11px;color:#94a3b8;">#${String(t.id).padStart(5,'0')} • ${t.priority}</div>
            </div>
        </label>
        `).join('');
        })
        .catch(() => {
        list.innerHTML = '<div style="text-align:center;color:#ef4444;font-size:13px;padding:20px;">Gagal memuat tiket</div>';
        });
        });
        });

        document.getElementById('closeAssignTeknisi')?.addEventListener('click', () => assignTekModal.style.display = 'none');
    document.getElementById('cancelAssignTeknisi')?.addEventListener('click', () => assignTekModal.style.display = 'none');
    assignTekModal?.addEventListener('click', e => { 
        if (e.target === assignTekModal) assignTekModal.style.display = 'none'; 
    });

    document.getElementById('confirmAssignTeknisi')?.addEventListener('click', function() {
        const selected = document.querySelector('input[name="assign_ticket"]:checked');
        if (!selected) { alert('Pilih tiket terlebih dahulu'); return; }

        const ticketId = selected.value;
        const token = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/admin/tickets/${ticketId}/assign`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ assigned_to: assignTeknisiId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                assignTekModal.style.display = 'none';
                location.reload();
            } else {
                alert('Gagal: ' + (data.message || 'Coba lagi'));
            }
        })
        .catch(() => alert('Terjadi kesalahan'));
    });}