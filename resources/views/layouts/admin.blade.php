<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'نظام مسار | الإدارة العامة')</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
/* ═══════════════════════════════════════════════
   MASAR ADMIN SYSTEM — v2.0
   Dark Governance Theme | Tajawal
   ═══════════════════════════════════════════════ */
:root {
  /* Palette */
  --bg-base:    #0b1220;
  --bg-surface: #111827;
  --bg-card:    #1a2332;
  --bg-hover:   #1f2d40;
  --border:     rgba(255,255,255,.07);
  --border-md:  rgba(255,255,255,.12);

  /* Brand */
  --blue:       #3b82f6;
  --blue-light: #60a5fa;
  --blue-dark:  #1d4ed8;
  --blue-glow:  rgba(59,130,246,.25);

  /* Status */
  --green:  #10b981;
  --amber:  #f59e0b;
  --red:    #ef4444;
  --purple: #8b5cf6;
  --cyan:   #06b6d4;

  /* Text */
  --text-1: #f1f5f9;
  --text-2: #94a3b8;
  --text-3: #475569;

  /* Sizing */
  --sidebar-w: 255px;
  --topbar-h:  60px;
  --radius:    12px;
  --radius-lg: 18px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }

body {
  font-family: 'Tajawal', sans-serif;
  background: var(--bg-base);
  color: var(--text-1);
  overflow-x: hidden;
  font-size: 14px;
  line-height: 1.6;
}

/* ─── LIGHT MODE ─────────────────────────────── */
body.light-mode {
  --bg-base:    #f0f4f9;
  --bg-surface: #ffffff;
  --bg-card:    #ffffff;
  --bg-hover:   #f1f5fb;
  --border:     rgba(0,0,0,.07);
  --border-md:  rgba(0,0,0,.12);
  --text-1: #1e293b;
  --text-2: #475569;
  --text-3: #94a3b8;
  --blue-glow: rgba(59,130,246,.15);
}
body.light-mode .c-table thead th { background: rgba(0,0,0,.02); }
body.light-mode .c-table tbody tr:hover td { background: #f8fafc; }
body.light-mode .f-input, body.light-mode .f-select { background: #f8fafc; color: #1e293b; border-color: #e2e8f0; }
body.light-mode .f-input:focus, body.light-mode .f-select:focus { background: #fff; }
body.light-mode .f-select option { background: #fff; color: #1e293b; }
body.light-mode .form-input-pro { background: #f8fafc; color: #1e293b; }
body.light-mode .form-input-pro:focus { background: #fff; }
body.light-mode .form-input-pro option { background: #fff; color: #1e293b; }
body.light-mode .modal-pro .modal-content { background: #fff; }
body.light-mode .modal-pro .modal-header { background: #f8fafc; }
body.light-mode .modal-pro .modal-footer { background: #f8fafc; }
body.light-mode .btn-ghost { background: rgba(0,0,0,.04); color: var(--text-2); }
body.light-mode .btn-ghost:hover { background: rgba(0,0,0,.07); }
body.light-mode .sb-link { color: #475569; }
body.light-mode .sb-link:hover, body.light-mode .sb-link.active { color: var(--blue); }
body.light-mode .sb-icon { background: rgba(0,0,0,.04); }
body.light-mode .sb-badge.muted { background: rgba(0,0,0,.06); color: #475569; }
body.light-mode .notif-panel { background: #fff; }
body.light-mode .notif-item:hover { background: #f8fafc; }
body.light-mode .n-text { color: #475569; }
body.light-mode .n-strong { color: #1e293b; }
body.light-mode .filter-bar { background: #fff; }
body.light-mode .tb-menu-btn, body.light-mode .tb-bell { background: rgba(0,0,0,.04); color: #475569; }
body.light-mode .tb-clock { background: rgba(0,0,0,.04); color: #475569; }
body.light-mode .page-link { background: #fff; color: #475569; }
body.light-mode .page-item.active .page-link { background: var(--blue); color: #fff; }
body.light-mode .empty-state i { opacity: .15; }
body.light-mode .dropdown-menu { background: #fff !important; }
body.light-mode .dropdown-item { color: #475569 !important; }
body.light-mode .dropdown-item:hover { background: #f8fafc !important; }
body.light-mode #sidebar { box-shadow: 2px 0 20px rgba(0,0,0,.08); }

/* ─── SCROLLBAR ─────────────────────────────── */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: var(--text-3); }

/* ─── SIDEBAR ────────────────────────────────── */
#sidebar {
  position: fixed;
  top: 0; right: 0; bottom: 0;
  width: var(--sidebar-w);
  background: var(--bg-surface);
  border-left: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  z-index: 1050;
  transition: transform .28s cubic-bezier(.4,0,.2,1);
}

/* Logo */
.sb-brand {
  height: var(--topbar-h);
  padding: 0 18px;
  display: flex;
  align-items: center;
  gap: 10px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}
.sb-logo-mark {
  width: 34px; height: 34px;
  border-radius: 9px;
  background: linear-gradient(135deg, var(--blue), var(--blue-dark));
  display: flex; align-items: center; justify-content: center;
  font-size: .9rem; color: #fff;
  box-shadow: 0 4px 14px var(--blue-glow);
  flex-shrink: 0;
}
.sb-brand-name { font-size: .95rem; font-weight: 900; color: var(--text-1); letter-spacing: -.01em; }
.sb-brand-sub  { font-size: .65rem; color: var(--text-3); font-weight: 500; margin-top: 1px; }

/* Nav */
.sb-nav { flex: 1; overflow-y: auto; padding: 10px 10px 16px; }
.sb-group { margin-bottom: 2px; }
.sb-group-label {
  font-size: .6rem; font-weight: 800; letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--text-3);
  padding: 12px 10px 5px;
}

.sb-item { position: relative; margin-bottom: 2px; }
.sb-link {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 9px 11px;
  border-radius: 9px;
  color: var(--text-2);
  text-decoration: none;
  font-size: .835rem;
  font-weight: 600;
  transition: all .18s;
  cursor: pointer;
}
.sb-link:hover {
  background: var(--bg-hover);
  color: var(--text-1);
}
.sb-link.active {
  background: linear-gradient(90deg, rgba(59,130,246,.18), rgba(59,130,246,.08));
  color: var(--blue-light);
  border-right: 2px solid var(--blue);
}
.sb-icon {
  width: 28px; height: 28px;
  border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem;
  background: rgba(255,255,255,.05);
  flex-shrink: 0;
  transition: .18s;
}
.sb-link.active .sb-icon { background: rgba(59,130,246,.2); color: var(--blue-light); }
.sb-link:hover .sb-icon  { background: rgba(255,255,255,.08); }

.sb-badge {
  margin-right: auto;
  padding: 2px 7px;
  border-radius: 20px;
  font-size: .6rem;
  font-weight: 800;
  line-height: 1.4;
}
.sb-badge.red    { background: rgba(239,68,68,.15); color: #fca5a5; }
.sb-badge.amber  { background: rgba(245,158,11,.15); color: #fcd34d; }
.sb-badge.blue   { background: rgba(59,130,246,.15); color: var(--blue-light); }
.sb-badge.muted  { background: rgba(255,255,255,.06); color: var(--text-3); }

.sb-divider { height: 1px; background: var(--border); margin: 8px 10px; }

/* User footer */
.sb-footer {
  padding: 12px 14px;
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
}
.sb-avatar {
  width: 34px; height: 34px;
  border-radius: 9px;
  object-fit: cover;
  border: 1.5px solid var(--border-md);
  flex-shrink: 0;
}
.sb-user-name { font-size: .79rem; font-weight: 700; color: var(--text-1); }
.sb-user-role { font-size: .62rem; color: var(--text-3); margin-top: 1px; }
.sb-logout-btn {
  margin-right: auto;
  width: 28px; height: 28px;
  border-radius: 7px;
  background: rgba(239,68,68,.1);
  border: none;
  color: #fca5a5;
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem;
  cursor: pointer;
  transition: .18s;
  flex-shrink: 0;
}
.sb-logout-btn:hover { background: rgba(239,68,68,.2); }

/* ─── TOPBAR ──────────────────────────────────── */
#topbar {
  position: fixed;
  top: 0; left: 0;
  right: var(--sidebar-w);
  height: var(--topbar-h);
  background: var(--bg-surface);
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  padding: 0 22px;
  gap: 14px;
  z-index: 900;
  transition: right .28s;
}

.tb-menu-btn {
  width: 32px; height: 32px;
  border-radius: 8px;
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border-md);
  color: var(--text-2);
  display: flex; align-items: center; justify-content: center;
  font-size: .8rem;
  cursor: pointer;
  transition: .18s;
  flex-shrink: 0;
}
.tb-menu-btn:hover { background: var(--bg-hover); color: var(--text-1); }

.tb-breadcrumb {
  display: flex; align-items: center; gap: 6px;
  font-size: .75rem; font-weight: 600;
  color: var(--text-3);
  flex: 1;
}
.tb-breadcrumb .crumb-active { color: var(--text-1); font-weight: 700; }
.tb-breadcrumb .crumb-sep { color: var(--text-3); font-size: .6rem; }

.tb-clock {
  font-size: .75rem; font-weight: 700;
  color: var(--text-2);
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border-md);
  padding: 4px 11px;
  border-radius: 7px;
  font-family: 'SF Mono', monospace;
  letter-spacing: .5px;
}

/* Notification bell */
.tb-notif-wrap { position: relative; }
.tb-bell {
  width: 32px; height: 32px;
  border-radius: 8px;
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border-md);
  color: var(--text-2);
  display: flex; align-items: center; justify-content: center;
  font-size: .8rem;
  cursor: pointer;
  transition: .18s;
  position: relative;
}
.tb-bell:hover { background: var(--bg-hover); color: var(--text-1); }
.tb-bell-dot {
  position: absolute; top: 5px; right: 5px;
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--red);
  border: 1.5px solid var(--bg-surface);
}

/* Dropdown Notification */
.notif-panel {
  position: absolute;
  top: calc(100% + 10px);
  left: 0;
  width: 300px;
  background: var(--bg-card);
  border: 1px solid var(--border-md);
  border-radius: var(--radius-lg);
  box-shadow: 0 20px 50px rgba(0,0,0,.5);
  z-index: 9999;
  display: none;
  overflow: hidden;
}
.notif-panel.open { display: block; animation: fadeInDown .2s ease; }
.notif-head {
  padding: 12px 16px;
  border-bottom: 1px solid var(--border);
  font-size: .8rem; font-weight: 800;
  color: var(--text-1);
  display: flex; align-items: center; gap: 6px;
}
.notif-item {
  padding: 11px 14px;
  border-bottom: 1px solid var(--border);
  display: flex; gap: 10px; align-items: flex-start;
  transition: .15s; cursor: default;
}
.notif-item:hover { background: var(--bg-hover); }
.notif-item:last-child { border-bottom: none; }
.n-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
.n-text { font-size: .76rem; color: var(--text-2); line-height: 1.45; }
.n-strong { color: var(--text-1); font-weight: 700; }
.n-time { font-size: .65rem; color: var(--text-3); margin-top: 3px; }
.notif-footer { padding: 10px 14px; }
.notif-footer a { font-size: .73rem; font-weight: 700; color: var(--blue-light); text-decoration: none; }

/* ─── MAIN ────────────────────────────────────── */
#main {
  margin-right: var(--sidebar-w);
  margin-top: var(--topbar-h);
  padding: 22px;
  min-height: calc(100vh - var(--topbar-h));
  transition: margin-right .28s;
}

/* ─── TOAST ALERTS ───────────────────────────── */
.toast-stack {
  position: fixed;
  top: calc(var(--topbar-h) + 12px);
  left: 22px;
  z-index: 9000;
  display: flex;
  flex-direction: column;
  gap: 8px;
  pointer-events: none;
}
.toast-item {
  pointer-events: all;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 11px 16px;
  border-radius: var(--radius);
  font-size: .8rem;
  font-weight: 600;
  animation: toastIn .3s ease;
  box-shadow: 0 8px 24px rgba(0,0,0,.4);
  min-width: 260px;
  max-width: 380px;
}
.toast-success { background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.3); color: #6ee7b7; }
.toast-error   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #fca5a5; }
.toast-warn    { background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.3); color: #fcd34d; }
@keyframes toastIn { from{opacity:0;transform:translateY(-12px);}to{opacity:1;transform:translateY(0);} }

/* ─── SHARED COMPONENTS ────────────────────────── */

/* Page header */
.page-hd {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 20px;
}
.page-hd-title {
  font-size: 1.1rem;
  font-weight: 900;
  color: var(--text-1);
  letter-spacing: -.01em;
}
.page-hd-sub { font-size: .72rem; color: var(--text-3); margin-top: 2px; font-weight: 500; }

/* Cards */
.c-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
}
.c-card-head {
  padding: 14px 18px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}
.c-card-title { font-size: .87rem; font-weight: 800; color: var(--text-1); }
.c-card-sub   { font-size: .68rem; color: var(--text-3); margin-top: 2px; }
.c-card-link  { font-size: .72rem; font-weight: 700; color: var(--blue-light); text-decoration: none; transition: .15s; }
.c-card-link:hover { color: var(--blue); }

/* Stat card */
.stat-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 18px 20px;
  display: flex;
  align-items: center;
  gap: 15px;
  transition: .22s;
  cursor: default;
}
.stat-card:hover { border-color: var(--border-md); transform: translateY(-2px); }
.stat-icon {
  width: 44px; height: 44px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: .95rem;
  flex-shrink: 0;
}
.stat-val   { font-size: 1.7rem; font-weight: 900; color: var(--text-1); line-height: 1; }
.stat-label { font-size: .68rem; color: var(--text-3); font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
.stat-trend { font-size: .69rem; font-weight: 600; margin-top: 4px; display: flex; align-items: center; gap: 4px; }

/* Colors */
.ic-blue   { background: rgba(59,130,246,.12); color: var(--blue-light); }
.ic-green  { background: rgba(16,185,129,.12); color: #6ee7b7; }
.ic-amber  { background: rgba(245,158,11,.12); color: #fcd34d; }
.ic-red    { background: rgba(239,68,68,.12);  color: #fca5a5; }
.ic-purple { background: rgba(139,92,246,.12); color: #c4b5fd; }
.ic-cyan   { background: rgba(6,182,212,.12);  color: #67e8f9; }

.t-blue   { color: var(--blue-light); }
.t-green  { color: #6ee7b7; }
.t-amber  { color: #fcd34d; }
.t-red    { color: #fca5a5; }
.t-muted  { color: var(--text-3); }

/* Tables */
.c-table { width: 100%; border-collapse: collapse; }
.c-table thead th {
  background: rgba(255,255,255,.03);
  padding: 11px 16px;
  font-size: .65rem;
  font-weight: 800;
  color: var(--text-3);
  text-transform: uppercase;
  letter-spacing: .07em;
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
}
.c-table tbody td {
  padding: 13px 16px;
  font-size: .82rem;
  color: var(--text-2);
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}
.c-table tbody tr:hover td { background: var(--bg-hover); }
.c-table tbody tr:last-child td { border-bottom: none; }

/* Badges */
.badge-pro {
  padding: 3px 9px;
  border-radius: 20px;
  font-size: .66rem;
  font-weight: 800;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  white-space: nowrap;
}
.badge-green   { background: rgba(16,185,129,.12); color: #6ee7b7; }
.badge-red     { background: rgba(239,68,68,.12);  color: #fca5a5; }
.badge-amber   { background: rgba(245,158,11,.12); color: #fcd34d; }
.badge-blue    { background: rgba(59,130,246,.12); color: var(--blue-light); }
.badge-purple  { background: rgba(139,92,246,.12); color: #c4b5fd; }
.badge-muted   { background: rgba(255,255,255,.06); color: var(--text-3); }

/* Buttons */
.btn-pro {
  padding: 8px 18px;
  border-radius: var(--radius);
  border: none;
  font-family: 'Tajawal', sans-serif;
  font-size: .8rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .18s;
  display: inline-flex;
  align-items: center;
  gap: 7px;
  text-decoration: none;
  white-space: nowrap;
}
.btn-blue   { background: var(--blue); color: #fff; box-shadow: 0 4px 14px var(--blue-glow); }
.btn-blue:hover   { background: var(--blue-dark); transform: translateY(-1px); }
.btn-ghost  { background: rgba(255,255,255,.06); color: var(--text-2); border: 1px solid var(--border-md); }
.btn-ghost:hover  { background: var(--bg-hover); color: var(--text-1); }
.btn-green  { background: rgba(16,185,129,.12); color: #6ee7b7; border: 1px solid rgba(16,185,129,.2); }
.btn-green:hover  { background: rgba(16,185,129,.2); }
.btn-red    { background: rgba(239,68,68,.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,.2); }
.btn-red:hover    { background: rgba(239,68,68,.2); }
.btn-amber  { background: rgba(245,158,11,.1); color: #fcd34d; border: 1px solid rgba(245,158,11,.2); }
.btn-amber:hover  { background: rgba(245,158,11,.2); }
.btn-sm { padding: 6px 13px; font-size: .75rem; border-radius: 8px; }
.btn-icon { width: 30px; height: 30px; padding: 0; border-radius: 8px; }

/* Filter bar */
.filter-bar {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 12px 16px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
  margin-bottom: 16px;
}
.f-input, .f-select {
  padding: 7px 12px;
  border: 1px solid var(--border-md);
  border-radius: 9px;
  font-family: 'Tajawal', sans-serif;
  font-size: .8rem;
  color: var(--text-1);
  background: rgba(255,255,255,.04);
  outline: none;
  transition: .18s;
  direction: rtl;
}
.f-input:focus, .f-select:focus {
  border-color: var(--blue);
  background: var(--bg-hover);
  box-shadow: 0 0 0 2px var(--blue-glow);
}
.f-input::placeholder { color: var(--text-3); }
.f-select option { background: var(--bg-card); color: var(--text-1); }

/* Bar progress */
.bar-outer { background: rgba(255,255,255,.06); border-radius: 4px; overflow: hidden; }
.bar-inner  { height: 100%; border-radius: 4px; transition: width 1s ease; }

/* Forms & Modals */
.modal-pro .modal-content {
  background: var(--bg-card);
  border: 1px solid var(--border-md);
  border-radius: var(--radius-lg);
  overflow: hidden;
}
.modal-pro .modal-header {
  background: var(--bg-surface);
  border-bottom: 1px solid var(--border);
  padding: 16px 20px;
}
.modal-pro .modal-title { color: var(--text-1); font-weight: 800; font-size: .9rem; }
.modal-pro .modal-body  { padding: 20px; overflow-y: auto; flex: 1; }
.modal-pro .modal-footer {
  background: var(--bg-surface);
  border-top: 1px solid var(--border);
  padding: 13px 20px;
}
.modal-pro .btn-close-dark {
  background: rgba(255,255,255,.06);
  border: 1px solid var(--border-md);
  width: 28px; height: 28px;
  border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: .7rem;
  color: var(--text-2);
  cursor: pointer;
  transition: .15s;
  margin-right: auto;
}
.modal-pro .btn-close-dark:hover { background: var(--bg-hover); color: var(--text-1); }

.form-group { margin-bottom: 14px; }
.form-label-pro {
  display: block;
  font-size: .72rem;
  font-weight: 800;
  color: var(--text-3);
  margin-bottom: 5px;
  text-transform: uppercase;
  letter-spacing: .05em;
}
.form-input-pro {
  width: 100%;
  padding: 9px 13px;
  border: 1px solid var(--border-md);
  border-radius: var(--radius);
  font-family: 'Tajawal', sans-serif;
  font-size: .83rem;
  color: var(--text-1);
  background: rgba(255,255,255,.04);
  outline: none;
  transition: .18s;
  direction: rtl;
}
.form-input-pro:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px var(--blue-glow);
  background: var(--bg-hover);
}
.form-input-pro::placeholder { color: var(--text-3); }
.form-input-pro option { background: var(--bg-card); color: var(--text-1); }
.form-section {
  margin-bottom: 18px;
  padding-bottom: 16px;
  border-bottom: 1px solid var(--border);
}
.form-section-title {
  font-size: .72rem;
  font-weight: 800;
  color: var(--blue-light);
  text-transform: uppercase;
  letter-spacing: .08em;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 7px;
}
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
@media(max-width:576px) { .form-grid-2,.form-grid-3 { grid-template-columns: 1fr; } }

/* Ensure ALL modals are scrollable */
.modal-dialog .modal-content { max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; }
.modal-dialog .modal-body { overflow-y: auto; flex: 1; }

/* Pagination override */
.pagination { gap: 4px; }
.page-link {
  background: var(--bg-card);
  border: 1px solid var(--border-md);
  color: var(--text-2);
  border-radius: 8px !important;
  padding: 6px 11px;
  font-size: .75rem !important;
  font-weight: 700;
  transition: .15s;
}
.page-link svg { width: 12px; height: 12px; }
.page-link:hover { background: var(--bg-hover); color: var(--text-1); border-color: var(--border-md); }
.page-item.active .page-link { background: var(--blue); border-color: var(--blue); color: #fff; }
.page-item.disabled .page-link { opacity: .3; }

/* Empty state */
.empty-state {
  text-align: center;
  padding: 52px 20px;
  color: var(--text-3);
}
.empty-state i { font-size: 2.5rem; opacity: .2; display: block; margin-bottom: 12px; }
.empty-state strong { display: block; color: var(--text-2); font-size: .87rem; margin-bottom: 4px; }
.empty-state span { font-size: .75rem; }

/* Sidebar overlay mobile */
.sb-overlay {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,.6);
  z-index: 1040;
}
.sb-overlay.show { display: block; }

@keyframes fadeInDown {
  from { opacity:0; transform: translateY(-8px); }
  to   { opacity:1; transform: translateY(0); }
}

/* Responsive */
@media(max-width: 991px) {
  #sidebar { transform: translateX(100%); }
  #sidebar.mob-open { transform: translateX(0); }
  #topbar { right: 0; }
  #main   { margin-right: 0; }
}

@media print {
  #sidebar, #topbar, .toast-stack, .btn-pro, form[onsubmit] { display: none !important; }
  #main { margin: 0 !important; padding: 0 !important; background: #fff !important; color: #000 !important; }
  .c-card { background: #fff !important; border: 1px solid #ddd !important; }
  .c-table tbody td, .c-table thead th { color: #000 !important; }
}
</style>
@yield('styles')
</head>
<body>

{{-- ─── SIDEBAR ─────────────────────────────────── --}}
<nav id="sidebar">
  <div class="sb-brand">
    <div class="sb-logo-mark"><i class="fas fa-route"></i></div>
    <div>
      <div class="sb-brand-name">مسار</div>
      <div class="sb-brand-sub">الإدارة العامة للمرور</div>
    </div>
  </div>

  <div class="sb-nav">
    {{-- الرئيسية --}}
    <div class="sb-group">
      <div class="sb-group-label">الرئيسية</div>
      <div class="sb-item">
        <a href="{{ route('admin.dashboard') }}"
           class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <span class="sb-icon"><i class="fas fa-chart-pie"></i></span>
          لوحة التحكم
        </a>
      </div>
    </div>

    {{-- إدارة النظام --}}
    <div class="sb-group">
      <div class="sb-group-label">إدارة النظام</div>
      <div class="sb-item">
        <a href="{{ route('schools.index') }}"
           class="sb-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
          <span class="sb-icon"><i class="fas fa-school"></i></span>
          المدارس
        </a>
      </div>
      <div class="sb-item">
        <a href="{{ route('admin.subscriptions') }}"
           class="sb-link {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}">
          <span class="sb-icon"><i class="fas fa-file-invoice-dollar"></i></span>
          الاشتراكات المالية
          @php
            $expCount = \App\Models\School::where('is_active',true)
              ->whereDate('subscription_end','<=',now()->addDays(14))->count();
          @endphp
          @if($expCount > 0)
            <span class="sb-badge red">{{ $expCount }}</span>
          @endif
        </a>
      </div>
      <div class="sb-item">
        <a href="{{ route('students.index') }}"
           class="sb-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
          <span class="sb-icon"><i class="fas fa-user-graduate"></i></span>
          الطلاب
        </a>
      </div>
      <div class="sb-item">
        <a href="{{ route('questions.index') }}"
           class="sb-link {{ request()->routeIs('questions.*') ? 'active' : '' }}">
          <span class="sb-icon"><i class="fas fa-traffic-light"></i></span>
          بنك الأسئلة
        </a>
      </div>
    </div>

    {{-- التقارير --}}
    <div class="sb-group">
      <div class="sb-group-label">التقارير</div>
      <div class="sb-item">
        <a href="{{ route('admin.logs') }}"
           class="sb-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
          <span class="sb-icon"><i class="fas fa-scroll"></i></span>
          سجل العمليات
        </a>
      </div>
    </div>

    <div class="sb-divider"></div>

    {{-- النظام --}}
    <div class="sb-item">
      <a href="#" class="sb-link">
        <span class="sb-icon"><i class="fas fa-cog"></i></span>
        الإعدادات
        <span class="sb-badge muted">قريباً</span>
      </a>
    </div>
  </div>

  <div class="sb-footer">
    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=1d4ed8&color=fff&bold=true&format=png"
         class="sb-avatar" alt="">
    <div>
      <div class="sb-user-name">{{ Str::limit(Auth::user()->name ?? 'المدير العام', 18) }}</div>
      <div class="sb-user-role">مدير النظام</div>
    </div>
    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:none">@csrf</form>
    <button class="sb-logout-btn" onclick="document.getElementById('logout-form').submit()" title="تسجيل الخروج">
      <i class="fas fa-sign-out-alt"></i>
    </button>
  </div>
</nav>

{{-- Mobile overlay --}}
<div class="sb-overlay" id="sbOverlay" onclick="closeSidebar()"></div>

{{-- ─── TOPBAR ──────────────────────────────────── --}}
<header id="topbar">
  <button class="tb-menu-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <div class="tb-breadcrumb">
    <i class="fas fa-home" style="color:var(--text-3);font-size:.7rem;"></i>
    <span class="crumb-sep">›</span>
    @hasSection('page_section')
      <span>@yield('page_section')</span>
      <span class="crumb-sep">›</span>
    @endif
    <span class="crumb-active">@yield('page_title','لوحة التحكم')</span>
  </div>

  <span class="tb-clock" id="liveClock">--:--:--</span>

  {{-- Theme Toggle --}}
  <button class="tb-menu-btn" id="themeToggleBtn" onclick="toggleAdminTheme()" title="تبديل الوضع">
    <i class="fas fa-sun" id="themeIcon"></i>
  </button>

  {{-- Bell --}}
  <div class="tb-notif-wrap" id="notifWrap">
    <div class="tb-bell" onclick="toggleNotif()">
      <i class="fas fa-bell"></i>
      @if($expCount > 0)<span class="tb-bell-dot"></span>@endif
    </div>
    <div class="notif-panel" id="notifPanel">
      <div class="notif-head">
        <i class="fas fa-bell" style="color:var(--amber);"></i>
        التنبيهات
        @if($expCount > 0)
          <span class="sb-badge red" style="margin-right:4px;">{{ $expCount }}</span>
        @endif
      </div>
      @php
        $notifSchools = \App\Models\School::where('is_active',true)
          ->whereDate('subscription_end','<=',now()->addDays(30))
          ->orderBy('subscription_end')->take(6)->get();
      @endphp
      @forelse($notifSchools as $ns)
        @php $d = now()->diffInDays(\Carbon\Carbon::parse($ns->subscription_end), false); @endphp
        <div class="notif-item">
          <span class="n-dot" style="background:{{ $d<=0?'var(--red)':($d<=7?'var(--red)':'var(--amber)') }};"></span>
          <div>
            <div class="n-text">اشتراك <span class="n-strong">{{ $ns->name }}</span>
              {{ $d<=0 ? 'منتهٍ' : 'ينتهي خلال '.$d.' يوم' }}
            </div>
            <div class="n-time">{{ \Carbon\Carbon::parse($ns->subscription_end)->format('Y-m-d') }}</div>
          </div>
        </div>
      @empty
        <div class="notif-item" style="justify-content:center;">
          <div class="n-text" style="text-align:center;padding:8px 0;">
            <i class="fas fa-check-circle" style="color:var(--green);margin-left:5px;"></i>
            لا توجد اشتراكات منتهية
          </div>
        </div>
      @endforelse
      <div class="notif-footer">
        <a href="{{ route('admin.subscriptions') }}">
          <i class="fas fa-arrow-left" style="font-size:.6rem;"></i>
          إدارة الاشتراكات
        </a>
      </div>
    </div>
  </div>
</header>

{{-- ─── TOAST ALERTS ────────────────────────────── --}}
<div class="toast-stack" id="toastStack">
  @if(session('success'))
    <div class="toast-item toast-success">
      <i class="fas fa-check-circle"></i>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="toast-item toast-error">
      <i class="fas fa-times-circle"></i>
      {{ session('error') }}
    </div>
  @endif
  @if(session('warning'))
    <div class="toast-item toast-warn">
      <i class="fas fa-exclamation-triangle"></i>
      {{ session('warning') }}
    </div>
  @endif
</div>

{{-- ─── MAIN CONTENT ───────────────────────────── --}}
<main id="main">
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Theme toggle
function toggleAdminTheme() {
  const body = document.body;
  const isDark = !body.classList.contains('light-mode');
  body.classList.toggle('light-mode', isDark);
  localStorage.setItem('adminTheme', isDark ? 'light' : 'dark');
  document.getElementById('themeIcon').className = isDark ? 'fas fa-moon' : 'fas fa-sun';
}
(function initTheme() {
  const saved = localStorage.getItem('adminTheme');
  if (saved === 'light') {
    document.body.classList.add('light-mode');
    const icon = document.getElementById('themeIcon');
    if (icon) icon.className = 'fas fa-moon';
  }
})();

// Fix pagination arrows size
document.querySelectorAll('.page-link').forEach(function(el) {
  el.style.fontSize = '.75rem';
});

// Clock
(function tick() {
  const el = document.getElementById('liveClock');
  if (el) {
    const now = new Date();
    el.textContent = now.toLocaleTimeString('ar-PS',{hour12:false});
  }
  setTimeout(tick, 1000);
})();

// Sidebar
let collapsed = false;
function toggleSidebar() {
  if (window.innerWidth < 992) {
    document.getElementById('sidebar').classList.toggle('mob-open');
    document.getElementById('sbOverlay').classList.toggle('show');
  } else {
    collapsed = !collapsed;
    const sb = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const tb = document.getElementById('topbar');
    if (collapsed) {
      sb.style.transform = 'translateX(100%)';
      main.style.marginRight = '0';
      tb.style.right = '0';
    } else {
      sb.style.transform = '';
      main.style.marginRight = 'var(--sidebar-w)';
      tb.style.right = 'var(--sidebar-w)';
    }
  }
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('mob-open');
  document.getElementById('sbOverlay').classList.remove('show');
}

// Notifications
function toggleNotif() {
  document.getElementById('notifPanel').classList.toggle('open');
}
document.addEventListener('click', function(e) {
  const wrap = document.getElementById('notifWrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('notifPanel').classList.remove('open');
  }
});

// Auto-dismiss toasts
document.querySelectorAll('.toast-item').forEach(function(el) {
  setTimeout(function() {
    el.style.transition = '.4s'; el.style.opacity = '0';
    setTimeout(() => el.remove(), 400);
  }, 4500);
});
</script>
@yield('scripts')
</body>
</html>
