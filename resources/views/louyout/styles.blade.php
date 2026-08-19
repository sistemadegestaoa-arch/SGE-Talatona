<style>
/* ── SHARED PAGE STYLES ── */
.page-header-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
.page-title { font-size:20px; font-weight:700; color:#1a2e1a; margin:0; }
.page-sub   { font-size:13px; color:#6b7280; margin:3px 0 0; }

.btn-back, .btn-new {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 16px; border-radius:9px; font-size:13px; font-weight:600; text-decoration:none;
    transition:background .2s, color .2s;
}
.btn-back  { border:2px solid #1a6b2f; color:#1a6b2f; background:transparent; }
.btn-back:hover  { background:#1a6b2f; color:#fff; text-decoration:none; }
.btn-new   { background:#1a6b2f; color:#fff; border:2px solid #1a6b2f; }
.btn-new:hover   { background:#2d9e4a; border-color:#2d9e4a; color:#fff; text-decoration:none; }
.btn-danger-sm { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:9px; font-size:13px; font-weight:600; text-decoration:none; background:#ef4444; color:#fff; border:none; cursor:pointer; }
.btn-danger-sm:hover { background:#dc2626; color:#fff; text-decoration:none; }

.alert-ok  { background:#ecfdf5; border:1px solid #6ee7b7; color:#065f46; padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:16px; }
.alert-err { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:16px; }

/* TABLE */
.table-card { background:#fff; border-radius:14px; border:1px solid #e5e7eb; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.05); }
.sys-table { width:100%; border-collapse:collapse; font-size:13px; }
.sys-table thead th { padding:11px 14px; text-align:left; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#1a6b2f; background:#f0faf2; border-bottom:2px solid #d1fae5; white-space:nowrap; }
.sys-table tbody td { padding:10px 14px; color:#374151; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
.sys-table tbody tr:last-child td { border-bottom:none; }
.sys-table tbody tr:hover td { background:#f0faf2; }

.tbl-btn { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:7px; border:none; cursor:pointer; font-size:13px; text-decoration:none; transition:opacity .2s; }
.tbl-btn:hover { opacity:.8; text-decoration:none; }
.tbl-edit { background:#dbeafe; color:#1d4ed8; }
.tbl-info  { background:#d1fae5; color:#065f46; }
.tbl-del   { background:#fee2e2; color:#991b1b; }
.tbl-view  { background:#f3f4f6; color:#374151; }
.tbl-orange { background:#ffedd5; color:#c2410c; }

.qty-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:700; }
.qty-ok  { background:#d1fae5; color:#065f46; }
.qty-low { background:#fee2e2; color:#991b1b; }
.qty-warn { background:#fef3c7; color:#92400e; }

.code-badge { display:inline-block; padding:2px 8px; background:#f3f4f6; border-radius:6px; font-size:11px; font-family:monospace; color:#374151; }
.prod-link { color:#1a6b2f; font-weight:500; text-decoration:none; }
.prod-link:hover { text-decoration:underline; }

/* FORM CARD */
.form-card { background:#fff; border-radius:14px; border:1px solid #e5e7eb; padding:28px 24px; box-shadow:0 2px 8px rgba(0,0,0,.05); max-width:700px; }
.form-card .fg { margin-bottom:16px; }
.form-card .fg label { display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px; }
.form-card .fc, .form-card .form-control {
    width:100%; padding:10px 14px; border:1.5px solid #e5e7eb; border-radius:9px;
    font-size:13px; color:#1a2332; background:#f9fafb;
    transition:border-color .2s, box-shadow .2s; outline:none;
}
.form-card .fc:focus, .form-card .form-control:focus { border-color:#1a6b2f; background:#fff; box-shadow:0 0 0 3px rgba(26,107,47,.1); }
.form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.form-row-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
.btn-save { display:inline-flex; align-items:center; gap:6px; padding:11px 28px; background:#1a6b2f; color:#fff; border:none; border-radius:9px; font-size:14px; font-weight:600; cursor:pointer; margin-top:8px; transition:background .2s; }
.btn-save:hover { background:#2d9e4a; }

@media(max-width:768px) { .form-row-2,.form-row-3 { grid-template-columns:1fr; } }
</style>
