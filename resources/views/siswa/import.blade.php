@extends('layouts.app')

@section('title', 'Import Siswa')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════
   DESIGN TOKENS
   Scene: admin sekolah, siang hari, layar laptop/HP
   Theme: light — ruang administrasi, terang, fungsional
   Strategy: Restrained — tinted neutrals + green accent
═══════════════════════════════════════════════════════ */
:root {
  --ink-900:  oklch(14% 0.008 255);
  --ink-700:  oklch(38% 0.010 255);
  --ink-500:  oklch(58% 0.009 255);
  --ink-300:  oklch(76% 0.007 255);
  --ink-100:  oklch(94% 0.005 255);
  --ink-50:   oklch(97% 0.003 255);

  --page-bg:  oklch(97.5% 0.004 255);

  --green-600: oklch(54% 0.155 163);
  --green-500: oklch(62% 0.148 163);
  --green-100: oklch(95% 0.040 163);
  --green-50:  oklch(97.5% 0.020 163);

  --blue-600:  oklch(52% 0.195 264);
  --blue-500:  oklch(60% 0.190 264);
  --blue-100:  oklch(95% 0.040 264);
  --blue-50:   oklch(97.5% 0.018 264);

  --amber-500: oklch(76% 0.170 72);
  --amber-100: oklch(96% 0.055 72);

  --red-600:   oklch(52% 0.205 27);
  --red-100:   oklch(95% 0.040 27);
  --red-50:    oklch(97.5% 0.018 27);

  --surface:   oklch(99.5% 0.002 255);
  --surface-2: oklch(98% 0.003 255);
  --border:    oklch(90% 0.006 255);
  --border-md: oklch(86% 0.007 255);

  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-xl: 20px;

  --shadow-sm: 0 1px 3px oklch(14% 0.008 255 / 0.07), 0 1px 2px oklch(14% 0.008 255 / 0.05);
  --shadow-md: 0 4px 12px oklch(14% 0.008 255 / 0.08), 0 2px 4px oklch(14% 0.008 255 / 0.04);

  /* Timing */
  --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
}

/* ── Base ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ── Page wrapper ── */
.ip-page {
  width: 100%;
  max-width: 760px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

/* ══════════════════════════════════
   FLASH BANNERS
══════════════════════════════════ */
.flash {
  display: flex;
  align-items: flex-start;
  gap: 11px;
  padding: 13px 15px;
  border-radius: var(--radius-md);
  font-size: 13.5px;
  line-height: 1.55;
  color: var(--ink-700);
}
.flash-icon { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }
.flash-body { flex: 1; min-width: 0; }
.flash-body strong { font-weight: 600; color: var(--ink-900); }
.flash-body a { color: var(--blue-600); text-decoration: none; font-weight: 500; }
.flash-body a:hover { text-decoration: underline; }

.flash-ok   { background: var(--green-50);  border: 1px solid oklch(85% 0.060 163); }
.flash-ok   .flash-icon { color: var(--green-600); }
.flash-err  { background: var(--red-50);    border: 1px solid oklch(87% 0.040 27); }
.flash-err  .flash-icon { color: var(--red-600); }

.err-rows   { list-style: none; margin-top: 7px; display: flex; flex-direction: column; gap: 3px; }
.err-rows li { font-size: 12px; color: var(--red-600); display: flex; align-items: flex-start; gap: 6px; line-height: 1.5; }
.err-rows li::before { content: '–'; flex-shrink: 0; }

/* ══════════════════════════════════
   PAGE HEADER
══════════════════════════════════ */
.pg-head { display: flex; flex-direction: column; gap: 4px; }

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 12px;
  color: var(--ink-300);
  flex-wrap: wrap;
}
.breadcrumb a { color: var(--ink-500); text-decoration: none; transition: color .14s; }
.breadcrumb a:hover { color: var(--blue-600); }
.breadcrumb svg { width: 11px; height: 11px; flex-shrink: 0; }

.pg-title { font-size: 22px; font-weight: 700; color: var(--ink-900); letter-spacing: -0.3px; }
.pg-sub   { font-size: 13px; color: var(--ink-500); margin-top: 2px; }

/* ══════════════════════════════════
   STEP TRACK  (horizontal pill row)
══════════════════════════════════ */
.steps-track {
  display: flex;
  align-items: center;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-xl);
  padding: 10px 18px;
  box-shadow: var(--shadow-sm);
  overflow-x: auto;
  scrollbar-width: none;
  gap: 0;
}
.steps-track::-webkit-scrollbar { display: none; }

.st-item {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
  padding: 3px 0;
}
.st-dot {
  width: 28px; height: 28px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700;
  flex-shrink: 0;
  transition: all .25s var(--ease-out);
}
.st-dot.done   { background: var(--green-600); color: oklch(99% 0.002 255); }
.st-dot.done svg { width: 13px; height: 13px; }
.st-dot.active { background: var(--ink-900);   color: oklch(99% 0.002 255); box-shadow: 0 0 0 5px var(--blue-100); }
.st-dot.idle   { background: var(--ink-100);   color: var(--ink-500); }

.st-lbl { font-size: 12.5px; font-weight: 500; white-space: nowrap; }
.st-lbl.done   { color: var(--green-600); }
.st-lbl.active { color: var(--ink-900); }
.st-lbl.idle   { color: var(--ink-500); }

.st-line {
  flex: 1; height: 1.5px;
  background: var(--border);
  margin: 0 12px;
  min-width: 24px;
  border-radius: 99px;
  position: relative; overflow: hidden;
}
.st-line::after {
  content: ''; position: absolute; inset: 0; left: -100%;
  background: var(--green-600);
  transition: left .4s var(--ease-out);
}
.st-line.done::after { left: 0; }

/* On small screens hide labels, keep dots */
@media (max-width: 440px) {
  .st-lbl { display: none; }
  .st-line { min-width: 14px; margin: 0 6px; }
  .steps-track { padding: 10px 14px; }
}

/* ══════════════════════════════════
   SECTION CARD  (shared)
══════════════════════════════════ */
.sec-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  overflow: hidden;
}

/* Section header bar */
.sec-bar {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 18px;
  border-bottom: 1px solid var(--border);
  background: var(--surface-2);
}
.sec-bar-icon {
  width: 34px; height: 34px;
  border-radius: var(--radius-sm);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.sec-bar-icon svg { width: 16px; height: 16px; }
.sec-bar-txt h3 { font-size: 14px; font-weight: 600; color: var(--ink-900); }
.sec-bar-txt p  { font-size: 12px; color: var(--ink-500); margin-top: 1px; }

/* ══════════════════════════════════
   TEMPLATE DOWNLOAD STRIP
══════════════════════════════════ */
.tpl-strip {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 18px;
  background: var(--green-50);
  border-bottom: 1px solid oklch(91% 0.035 163);
  flex-wrap: wrap;
}
.tpl-file-icon {
  width: 44px; height: 44px;
  background: oklch(93% 0.060 163);
  border-radius: var(--radius-sm);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.tpl-file-icon svg { width: 22px; height: 22px; color: var(--green-600); }
.tpl-info { flex: 1; min-width: 140px; }
.tpl-info-name { font-size: 13.5px; font-weight: 600; color: var(--ink-900); }
.tpl-info-desc { font-size: 12px; color: var(--ink-700); margin-top: 2px; line-height: 1.5; }

.btn-dl {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 18px;
  background: var(--green-600);
  color: oklch(99% 0.002 255);
  font-size: 13px; font-weight: 600;
  border-radius: var(--radius-sm);
  text-decoration: none; border: none; cursor: pointer;
  transition: background .15s, transform .12s;
  white-space: nowrap; flex-shrink: 0;
  box-shadow: var(--shadow-sm);
}
.btn-dl:hover { background: oklch(49% 0.155 163); transform: translateY(-1px); }
.btn-dl svg { width: 14px; height: 14px; }

@media (max-width: 500px) {
  .tpl-strip { flex-direction: column; align-items: stretch; gap: 12px; }
  .btn-dl    { justify-content: center; }
}

/* ══════════════════════════════════
   COLUMN TABLE
══════════════════════════════════ */
.col-table-wrap { padding: 16px 18px 18px; }

.col-section-label {
  font-size: 10.5px; font-weight: 700;
  letter-spacing: .07em; text-transform: uppercase;
  padding: 8px 12px;
  border-bottom: 1px solid var(--border);
}
.csl-red  { background: oklch(97% 0.018 27);  color: var(--red-600); }
.csl-gray { background: var(--surface-2);      color: var(--ink-500); }
.csl-amb  { background: var(--amber-100);      color: oklch(45% 0.130 72); }

/* Mobile: stack table into cards */
.col-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: var(--radius-sm); border: 1px solid var(--border); }

.ctbl { width: 100%; border-collapse: collapse; min-width: 500px; }
.ctbl th {
  padding: 9px 12px;
  text-align: left;
  font-size: 10.5px; font-weight: 700;
  color: var(--ink-500); letter-spacing: .07em; text-transform: uppercase;
  background: var(--surface-2);
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
}
.ctbl td {
  padding: 9px 12px;
  font-size: 12.5px; color: var(--ink-700);
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}
.ctbl tbody tr:last-child td { border-bottom: none; }
.ctbl tbody tr:hover td { background: var(--blue-50); }

.col-chip {
  font-family: 'SF Mono','Fira Code','Consolas',monospace;
  font-size: 11.5px; font-weight: 600;
  color: var(--blue-600);
  background: var(--blue-100);
  padding: 2px 8px; border-radius: 5px;
  display: inline-block; white-space: nowrap;
}
.badge {
  display: inline-flex; align-items: center;
  padding: 2px 9px;
  border-radius: 99px;
  font-size: 10.5px; font-weight: 700;
  white-space: nowrap;
}
.badge-req  { background: var(--red-100); color: var(--red-600); border: 1px solid oklch(87% 0.040 27); }
.badge-opt  { background: var(--ink-100); color: var(--ink-700); border: 1px solid var(--border-md); }
.badge-auto { background: var(--amber-100); color: oklch(45% 0.130 72); border: 1px solid oklch(88% 0.060 72); }
.ex { font-size: 11.5px; color: var(--ink-300); font-style: italic; }

/* Mobile cards for col-table */
@media (max-width: 560px) {
  .col-scroll { overflow: visible; border: none; border-radius: 0; }
  .ctbl, .ctbl thead, .ctbl tbody, .ctbl th, .ctbl td, .ctbl tr { display: block; }
  .ctbl thead { display: none; }
  .ctbl .col-section-label { display: block; }
  .ctbl tbody tr {
    padding: 11px 14px;
    border-bottom: 1px solid var(--border);
    display: flex; flex-wrap: wrap; gap: 6px; align-items: center;
    background: var(--surface);
  }
  .ctbl tbody tr:hover { background: var(--blue-50); }
  .ctbl td { border: none; padding: 0; font-size: 12px; }
  .ctbl td:first-child { flex: 0 0 auto; }
  .ctbl td:nth-child(2) { flex: 0 0 auto; }
  .ctbl td:nth-child(3) { flex: 1 0 100%; color: var(--ink-500); font-size: 11.5px; }
  .ctbl td:nth-child(4) { display: none; }
  .ctbl tbody tr:last-child { border-bottom: none; }
  .col-scroll { border-radius: var(--radius-sm); border: 1px solid var(--border); overflow: hidden; }
}

/* ══════════════════════════════════
   DROPZONE
══════════════════════════════════ */
.dz-body { padding: 18px; }

.dropzone {
  position: relative;
  border: 2px dashed var(--border-md);
  border-radius: var(--radius-lg);
  padding: 44px 20px 36px;
  text-align: center;
  background: var(--surface-2);
  cursor: pointer;
  transition: border-color .2s, background .18s, transform .15s var(--ease-out);
  user-select: none; -webkit-user-select: none;
}
.dropzone:hover      { border-color: oklch(72% 0.150 264); background: var(--blue-50); }
.dropzone.drag-over  { border-color: var(--blue-600); background: var(--blue-50); transform: scale(1.006); }
.dropzone:focus-within { border-color: var(--blue-600); outline: 3px solid var(--blue-100); }

.dz-icon-wrap {
  width: 56px; height: 56px;
  border-radius: var(--radius-md);
  background: oklch(93% 0.030 264);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
  transition: background .18s;
}
.dropzone:hover .dz-icon-wrap,
.dropzone.drag-over .dz-icon-wrap { background: oklch(88% 0.055 264); }
.dz-icon-wrap svg { width: 26px; height: 26px; color: var(--blue-600); }

.dz-title { font-size: 15px; font-weight: 700; color: var(--ink-900); margin-bottom: 5px; }
.dz-hint  { font-size: 13px; color: var(--ink-500); margin-bottom: 18px; line-height: 1.55; max-width: 320px; margin-left: auto; margin-right: auto; }

/* Mobile: swap text */
.dz-hint-desktop { display: block; }
.dz-hint-mobile  { display: none; }
@media (max-width: 540px) {
  .dz-hint-desktop { display: none; }
  .dz-hint-mobile  { display: block; }
  .dropzone { padding: 32px 16px 28px; }
}

.btn-browse {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 22px;
  background: var(--ink-900);
  color: oklch(99% 0.002 255);
  font-size: 13.5px; font-weight: 600;
  border-radius: var(--radius-sm);
  border: none; cursor: pointer;
  transition: background .15s, transform .12s;
  box-shadow: var(--shadow-sm);
}
.btn-browse:hover { background: oklch(22% 0.010 255); transform: translateY(-1px); }
.btn-browse:active { transform: translateY(0); }
.btn-browse svg { width: 14px; height: 14px; }

.dz-meta {
  display: flex; align-items: center; justify-content: center;
  gap: 8px; flex-wrap: wrap;
  margin-top: 14px;
  font-size: 11.5px; color: var(--ink-300);
}
.dz-meta-sep { width: 3px; height: 3px; border-radius: 50%; background: var(--ink-300); flex-shrink: 0; }

/* ── File chip ── */
.file-chip {
  display: none;
  align-items: center; gap: 11px;
  padding: 12px 14px;
  background: var(--green-50);
  border: 1px solid oklch(88% 0.050 163);
  border-radius: var(--radius-sm);
  margin-top: 14px;
  animation: slideUp .2s var(--ease-out);
}
.file-chip.show { display: flex; }
.fc-icon { color: var(--green-600); flex-shrink: 0; }
.fc-icon svg { width: 22px; height: 22px; }
.fc-info { flex: 1; min-width: 0; }
.fc-name { font-size: 13.5px; font-weight: 600; color: var(--ink-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.fc-size { font-size: 11.5px; color: var(--ink-500); margin-top: 1px; }
.fc-del  {
  width: 30px; height: 30px;
  display: flex; align-items: center; justify-content: center;
  border: none; background: transparent; cursor: pointer;
  border-radius: var(--radius-sm); color: var(--ink-300);
  transition: color .15s, background .15s;
  flex-shrink: 0;
}
.fc-del:hover { color: var(--red-600); background: var(--red-100); }
.fc-del svg { width: 16px; height: 16px; }

/* ── Progress ── */
.progress-box { display: none; margin-top: 14px; }
.progress-box.show { display: block; }
.prog-track { height: 6px; border-radius: 99px; background: var(--ink-100); overflow: hidden; }
.prog-fill  {
  height: 100%; border-radius: 99px;
  background: linear-gradient(90deg, var(--blue-600), oklch(70% 0.170 264));
  width: 0%; transition: width .28s var(--ease-out);
}
.prog-meta  { display: flex; justify-content: space-between; margin-top: 6px; font-size: 11.5px; color: var(--ink-500); }

/* ── Field error ── */
.field-err {
  display: flex; align-items: center; gap: 5px;
  font-size: 12px; color: var(--red-600);
  margin-top: 8px;
}
.field-err svg { width: 13px; height: 13px; flex-shrink: 0; }

/* ══════════════════════════════════
   ACTION BAR
══════════════════════════════════ */
.action-bar {
  display: flex; align-items: center; justify-content: flex-end; gap: 9px;
  padding: 13px 18px;
  border-top: 1px solid var(--border);
  background: var(--surface-2);
  flex-wrap: wrap;
}

.btn-ghost {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 16px;
  background: transparent;
  border: 1px solid var(--border-md); color: var(--ink-700);
  font-size: 13px; font-weight: 500;
  border-radius: var(--radius-sm);
  text-decoration: none; cursor: pointer;
  transition: border-color .15s, background .15s, color .15s;
}
.btn-ghost:hover { border-color: var(--ink-300); background: var(--ink-50); color: var(--ink-900); }

.btn-primary {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 22px;
  background: var(--blue-600); color: oklch(99% 0.002 255);
  font-size: 13.5px; font-weight: 600;
  border-radius: var(--radius-sm); border: none; cursor: pointer;
  transition: background .15s, transform .12s;
  box-shadow: var(--shadow-sm);
}
.btn-primary:hover:not(:disabled) { background: oklch(48% 0.195 264); transform: translateY(-1px); }
.btn-primary:active:not(:disabled) { transform: translateY(0); }
.btn-primary:disabled { opacity: .40; cursor: not-allowed; }
.btn-primary svg { width: 15px; height: 15px; }

@media (max-width: 400px) {
  .action-bar { flex-direction: column; align-items: stretch; }
  .btn-ghost, .btn-primary { justify-content: center; width: 100%; }
}

/* ══════════════════════════════════
   FAQ ACCORDION
══════════════════════════════════ */
.faq-list { padding: 14px 18px 18px; display: flex; flex-direction: column; gap: 6px; }

.faq-item {
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  overflow: hidden;
  transition: border-color .15s;
}
.faq-item:hover { border-color: var(--border-md); }
.faq-item.open  { border-color: oklch(82% 0.030 264); }

.faq-btn {
  width: 100%; display: flex; align-items: center; gap: 10px;
  padding: 12px 14px;
  background: transparent; border: none; cursor: pointer; text-align: left;
  transition: background .12s;
}
.faq-btn:hover { background: var(--blue-50); }

.faq-num {
  width: 23px; height: 23px; border-radius: 50%;
  background: var(--ink-100); color: var(--ink-500);
  font-size: 11px; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  transition: background .15s, color .15s;
}
.faq-item.open .faq-num { background: var(--blue-100); color: var(--blue-600); }

.faq-text { flex: 1; font-size: 13.5px; font-weight: 500; color: var(--ink-900); line-height: 1.45; text-align: left; }

.faq-chev { color: var(--ink-300); flex-shrink: 0; transition: transform .22s var(--ease-out); }
.faq-chev svg { width: 14px; height: 14px; display: block; }
.faq-item.open .faq-chev { transform: rotate(180deg); color: var(--blue-600); }

.faq-answer {
  display: none;
  padding: 0 14px 13px 47px;
  font-size: 13px; color: var(--ink-700); line-height: 1.75;
}
.faq-answer.open { display: block; }
.faq-answer code {
  font-family: 'SF Mono','Fira Code','Consolas',monospace;
  font-size: 11.5px;
  background: var(--blue-100); color: var(--blue-600);
  padding: 2px 7px; border-radius: 4px;
}
@media (max-width: 500px) {
  .faq-answer { padding-left: 14px; }
}

/* ══════════════════════════════════
   ANIMATIONS
══════════════════════════════════ */
@keyframes slideUp {
  from { opacity: 0; transform: translateY(6px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ══════════════════════════════════
   TOAST
══════════════════════════════════ */
.toast-container {
  position: fixed;
  bottom: 20px; left: 50%; transform: translateX(-50%);
  z-index: 9999;
  display: flex; flex-direction: column; gap: 8px;
  pointer-events: none;
  width: calc(100vw - 32px); max-width: 380px;
}
.toast {
  display: flex; align-items: center; gap: 9px;
  padding: 11px 15px;
  border-radius: var(--radius-md);
  font-size: 13.5px; font-weight: 500; color: oklch(99% 0.002 255);
  box-shadow: var(--shadow-md);
  animation: toastIn .22s var(--ease-out);
  pointer-events: all;
}
.toast-err { background: var(--red-600); }
.toast-ok  { background: var(--green-600); }
.toast svg { width: 16px; height: 16px; flex-shrink: 0; }
.toast-out { animation: toastOut .2s var(--ease-out) forwards; }

@keyframes toastIn  { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
@keyframes toastOut { to   { opacity: 0; transform: translateY(6px); } }
</style>
@endpush

@section('content')
<div class="ip-page">

  {{-- ══ TOAST CONTAINER ══ --}}
  <div class="toast-container" id="toastContainer"></div>

  {{-- ══ FLASH: SUCCESS ══ --}}
  @if(session('success'))
  <div class="flash flash-ok">
    <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div class="flash-body">
      <strong>Import berhasil.</strong> {{ session('success') }}
      <a href="{{ route('siswa.index') }}" style="margin-left:6px">Lihat daftar siswa &rarr;</a>
    </div>
  </div>
  @endif

  {{-- ══ FLASH: IMPORT ERRORS ══ --}}
  @if(session('import_errors'))
  <div class="flash flash-err">
    <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <div class="flash-body" style="flex:1;min-width:0">
      <strong>{{ count(session('import_errors')) }} baris dilewati karena error:</strong>
      <ul class="err-rows">
        @foreach(session('import_errors') as $err)
        <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  </div>
  @endif

  @if($errors->has('file'))
  <div class="flash flash-err">
    <svg class="flash-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div class="flash-body">{{ $errors->first('file') }}</div>
  </div>
  @endif

  {{-- ══ PAGE HEADER ══ --}}
  <div class="pg-head">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('siswa.index') }}">Data Siswa</a>
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
      <span aria-current="page">Import Excel</span>
    </nav>
    <h1 class="pg-title">Import Data Siswa</h1>
    <p class="pg-sub">Upload file Excel untuk menambahkan banyak siswa sekaligus</p>
  </div>

  {{-- ══ STEP TRACK ══ --}}
  <div class="steps-track" role="list" aria-label="Langkah-langkah">
    <div class="st-item" role="listitem">
      <div class="st-dot done" aria-label="Selesai">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
      <span class="st-lbl done">Download Template</span>
    </div>
    <div class="st-line done" role="presentation"></div>

    <div class="st-item" role="listitem">
      <div class="st-dot active" aria-current="step">2</div>
      <span class="st-lbl active">Upload File</span>
    </div>
    <div class="st-line" role="presentation"></div>

    <div class="st-item" role="listitem">
      <div class="st-dot idle" aria-disabled="true">3</div>
      <span class="st-lbl idle">Selesai</span>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════
       STEP 1: Template + Keterangan Kolom
  ══════════════════════════════════════════════ --}}
  <div class="sec-card">
    <div class="sec-bar">
      <div class="sec-bar-icon" style="background:var(--green-100)">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--green-600)">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
      </div>
      <div class="sec-bar-txt">
        <h3>Langkah 1 — Download Template</h3>
        <p>Isi data ke template ini agar bisa dibaca sistem</p>
      </div>
    </div>

    {{-- Template download strip --}}
    <div class="tpl-strip">
      <div class="tpl-file-icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <div class="tpl-info">
        <div class="tpl-info-name">template_import_siswa.xlsx</div>
        <div class="tpl-info-desc">Header, baris contoh, dan catatan di tiap kolom sudah tersedia.</div>
      </div>
      <a href="{{ route('siswa.import.template') }}" class="btn-dl">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Download Template
      </a>
    </div>

    {{-- Keterangan kolom --}}
    <div class="col-table-wrap">
      <div style="font-size:11px;font-weight:700;color:var(--ink-500);letter-spacing:.07em;text-transform:uppercase;margin-bottom:10px">
        Keterangan Kolom
      </div>
      <div class="col-scroll">
        <table class="ctbl" role="table" aria-label="Keterangan kolom template">
          <thead>
            <tr>
              <th style="width:28%">Kolom</th>
              <th style="width:15%">Status</th>
              <th style="width:35%">Keterangan</th>
              <th style="width:22%">Contoh nilai</th>
            </tr>
          </thead>
          <tbody>
            {{-- WAJIB --}}
            <tr class="col-section-label csl-red"><td colspan="4">&#9733; Wajib diisi</td></tr>
            <tr>
              <td><span class="col-chip">nama_lengkap</span></td>
              <td><span class="badge badge-req">Wajib</span></td>
              <td>Nama lengkap sesuai akta lahir</td>
              <td class="ex">Ahmad Fauzi</td>
            </tr>
            <tr>
              <td><span class="col-chip">jenis_kelamin</span></td>
              <td><span class="badge badge-req">Wajib</span></td>
              <td><strong>L</strong> = Laki-laki, <strong>P</strong> = Perempuan (kapital)</td>
              <td class="ex">L atau P</td>
            </tr>
            <tr>
              <td><span class="col-chip">kelompok</span></td>
              <td><span class="badge badge-req">Wajib</span></td>
              <td>Saat ini hanya tersedia: <strong>KB</strong></td>
              <td class="ex">KB</td>
            </tr>

            {{-- OPSIONAL --}}
            <tr class="col-section-label csl-gray"><td colspan="4">Opsional — boleh dikosongkan</td></tr>
            <tr>
              <td><span class="col-chip">nis</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>No. Induk dari Dapodik, harus unik</td>
              <td class="ex">1234567890</td>
            </tr>
            <tr>
              <td><span class="col-chip">tempat_lahir</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Kota / kabupaten tempat lahir</td>
              <td class="ex">Jambi</td>
            </tr>
            <tr>
              <td><span class="col-chip">tanggal_lahir</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Format wajib: <strong>YYYY-MM-DD</strong></td>
              <td class="ex">2020-05-14</td>
            </tr>
            <tr>
              <td><span class="col-chip">agama</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Islam / Kristen / Katolik / Hindu / Buddha</td>
              <td class="ex">Islam</td>
            </tr>
            <tr>
              <td><span class="col-chip">alamat</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Alamat lengkap tempat tinggal</td>
              <td class="ex">Jl. Mawar No.5 Jambi</td>
            </tr>
            <tr>
              <td><span class="col-chip">tanggal_masuk</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Tgl masuk sekolah, format: <strong>YYYY-MM-DD</strong></td>
              <td class="ex">2024-07-15</td>
            </tr>
            <tr>
              <td><span class="col-chip">tahun_ajaran</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Format: YYYY/YYYY</td>
              <td class="ex">2025/2026</td>
            </tr>
            <tr>
              <td><span class="col-chip">nama_ayah</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Nama lengkap ayah kandung</td>
              <td class="ex">Budi Santoso</td>
            </tr>
            <tr>
              <td><span class="col-chip">no_hp_ayah</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>No HP ayah, diawali 08 atau +62</td>
              <td class="ex">081234567890</td>
            </tr>
            <tr>
              <td><span class="col-chip">pekerjaan_ayah</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Pekerjaan / profesi ayah</td>
              <td class="ex">Wiraswasta</td>
            </tr>
            <tr>
              <td><span class="col-chip">nama_ibu</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Nama lengkap ibu kandung</td>
              <td class="ex">Sari Dewi</td>
            </tr>
            <tr>
              <td><span class="col-chip">no_hp_ibu</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>No HP ibu untuk notifikasi WA</td>
              <td class="ex">082345678901</td>
            </tr>
            <tr>
              <td><span class="col-chip">pekerjaan_ibu</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Pekerjaan / profesi ibu</td>
              <td class="ex">Ibu Rumah Tangga</td>
            </tr>
            <tr>
              <td><span class="col-chip">nama_wali</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Isi jika wali bukan ayah / ibu kandung</td>
              <td class="ex">H. Suparman</td>
            </tr>
            <tr>
              <td><span class="col-chip">hubungan_wali</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>Hubungan wali dengan siswa</td>
              <td class="ex">Kakek, Paman</td>
            </tr>
            <tr>
              <td><span class="col-chip">no_hp_wali</span></td>
              <td><span class="badge badge-opt">Opsional</span></td>
              <td>No HP wali untuk notifikasi WA</td>
              <td class="ex">083456789012</td>
            </tr>

            {{-- AUTO --}}
            <tr class="col-section-label csl-amb"><td colspan="4">Diisi otomatis sistem &mdash; jangan diisi</td></tr>
            <tr>
              <td><span class="col-chip">aktif</span></td>
              <td><span class="badge badge-auto">Auto</span></td>
              <td>Otomatis <strong>true</strong> saat import</td>
              <td class="ex">&mdash;</td>
            </tr>
            <tr>
              <td><span class="col-chip">foto</span></td>
              <td><span class="badge badge-auto">Auto</span></td>
              <td>Upload foto lewat halaman Edit siswa</td>
              <td class="ex">&mdash;</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════
       STEP 2: Upload File
  ══════════════════════════════════════════════ --}}
  <div class="sec-card">
    <div class="sec-bar">
      <div class="sec-bar-icon" style="background:var(--blue-100)">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--blue-600)">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
      </div>
      <div class="sec-bar-txt">
        <h3>Langkah 2 — Upload File Excel</h3>
        <p>Seret atau pilih file yang sudah diisi</p>
      </div>
    </div>

    <form method="POST" action="{{ route('siswa.import') }}"
          enctype="multipart/form-data" id="importForm" novalidate>
      @csrf
      <div class="dz-body">

        {{-- Dropzone --}}
        <div class="dropzone" id="dropzone" role="button" tabindex="0"
             aria-label="Area upload file Excel"
             onclick="document.getElementById('fileInput').click()"
             onkeydown="if(event.key==='Enter'||event.key===' '){document.getElementById('fileInput').click()}"
             ondragover="onDragOver(event)"
             ondragleave="onDragLeave(event)"
             ondrop="onDrop(event)">

          <div class="dz-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/>
            </svg>
          </div>

          <div class="dz-title">Seret file Excel ke sini</div>
          <p class="dz-hint dz-hint-desktop">atau klik tombol di bawah untuk memilih dari komputer Anda</p>
          <p class="dz-hint dz-hint-mobile">ketuk tombol di bawah untuk memilih file</p>

          <button type="button" class="btn-browse"
                  onclick="event.stopPropagation(); document.getElementById('fileInput').click()"
                  aria-label="Pilih file Excel">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            Pilih File
          </button>

          <div class="dz-meta">
            <span>.xlsx atau .xls</span>
            <span class="dz-meta-sep"></span>
            <span>Maks 5 MB</span>
            <span class="dz-meta-sep"></span>
            <span>Maks 500 baris</span>
          </div>
        </div>

        {{-- Hidden file input --}}
        <input type="file" id="fileInput" name="file" accept=".xlsx,.xls"
               style="display:none" onchange="onFileChange(event)">

        {{-- Validation error --}}
        @error('file')
        <div class="field-err" role="alert">
          <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          {{ $message }}
        </div>
        @enderror

        {{-- File chip --}}
        <div class="file-chip" id="fileChip" role="status" aria-live="polite">
          <div class="fc-icon" aria-hidden="true">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div class="fc-info">
            <div class="fc-name" id="chipName"></div>
            <div class="fc-size" id="chipSize"></div>
          </div>
          <button type="button" class="fc-del" onclick="removeFile()" aria-label="Hapus file yang dipilih">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        {{-- Progress --}}
        <div class="progress-box" id="progressBox" aria-live="polite">
          <div class="prog-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" id="progressBar">
            <div class="prog-fill" id="progFill"></div>
          </div>
          <div class="prog-meta">
            <span id="progLabel">Memproses data...</span>
            <span id="progPct">0%</span>
          </div>
        </div>

      </div>{{-- /dz-body --}}

      {{-- Action bar --}}
      <div class="action-bar">
        <a href="{{ route('siswa.index') }}" class="btn-ghost">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Kembali
        </a>
        <button type="submit" class="btn-primary" id="btnSubmit" disabled
                aria-label="Mulai import file Excel">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
          </svg>
          <span id="btnLabel">Pilih file dulu</span>
        </button>
      </div>
    </form>
  </div>

  {{-- ══════════════════════════════════════════════
       FAQ
  ══════════════════════════════════════════════ --}}
  <div class="sec-card">
    <div class="sec-bar">
      <div class="sec-bar-icon" style="background:var(--amber-100)">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--amber-500)">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div class="sec-bar-txt">
        <h3>Panduan Singkat</h3>
        <p>Pertanyaan yang sering ditanyakan</p>
      </div>
    </div>

    <div class="faq-list">
      @php
      $faqs = [
        [
          'q' => 'Format tanggal yang benar di Excel seperti apa?',
          'a' => 'Gunakan <code>YYYY-MM-DD</code>, contoh <code>2020-05-14</code> untuk 14 Mei 2020. Di Excel, format kolom sebagai <strong>Teks</strong> agar tidak berubah otomatis saat file disimpan.',
        ],
        [
          'q' => 'Apa yang terjadi jika NIS sudah ada di sistem?',
          'a' => 'Baris dengan NIS yang sudah terdaftar akan <strong>dilewati</strong> dan muncul sebagai error. Baris lain yang valid tetap diimport. Pastikan NIS dari Dapodik sudah benar dan unik.',
        ],
        [
          'q' => 'Apakah nama kolom boleh diubah?',
          'a' => '<strong>Tidak boleh.</strong> Sistem membaca data berdasarkan nama kolom di baris pertama, bukan urutannya. Mengubah nama kolom menyebabkan data tidak terbaca.',
        ],
        [
          'q' => 'Berapa maksimal baris yang bisa diimport sekaligus?',
          'a' => 'Maksimal <strong>500 baris</strong> per file. Jika lebih, bagi menjadi beberapa file dan upload bertahap.',
        ],
        [
          'q' => 'Bagaimana cara menambahkan foto siswa?',
          'a' => 'Foto tidak bisa diimport lewat Excel. Setelah import selesai, buka halaman <strong>Edit</strong> masing-masing siswa untuk mengupload foto satu per satu.',
        ],
      ];
      @endphp

      @foreach($faqs as $i => $faq)
      <div class="faq-item" id="faqItem{{ $i }}">
        <button type="button" class="faq-btn"
                onclick="toggleFaq({{ $i }})"
                aria-expanded="false"
                aria-controls="faqAns{{ $i }}">
          <span class="faq-num">{{ $i + 1 }}</span>
          <span class="faq-text">{{ $faq['q'] }}</span>
          <span class="faq-chev" aria-hidden="true">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </span>
        </button>
        <div class="faq-answer" id="faqAns{{ $i }}" role="region" aria-labelledby="faqBtn{{ $i }}">
          {!! $faq['a'] !!}
        </div>
      </div>
      @endforeach
    </div>
  </div>

</div>{{-- /ip-page --}}
@endsection

@push('scripts')
<script>
/* ════════════════════ DRAG & DROP ════════════════════ */
function onDragOver(e) {
  e.preventDefault();
  document.getElementById('dropzone').classList.add('drag-over');
}
function onDragLeave(e) {
  if (!document.getElementById('dropzone').contains(e.relatedTarget)) {
    document.getElementById('dropzone').classList.remove('drag-over');
  }
}
function onDrop(e) {
  e.preventDefault();
  document.getElementById('dropzone').classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (file) applyFile(file);
}

/* ════════════════════ FILE SELECT ════════════════════ */
function onFileChange(e) {
  const file = e.target.files[0];
  if (file) applyFile(file);
}

function applyFile(file) {
  const validMime = [
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-excel',
  ];
  const ext = file.name.split('.').pop().toLowerCase();

  if (!validMime.includes(file.type) && !['xlsx','xls'].includes(ext)) {
    showToast('Format tidak didukung. Gunakan file .xlsx atau .xls.', 'err');
    return;
  }
  if (file.size > 5 * 1024 * 1024) {
    showToast('File terlalu besar. Maksimal 5 MB.', 'err');
    return;
  }

  /* Assign ke input (DataTransfer untuk drag-drop) */
  try {
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('fileInput').files = dt.files;
  } catch (_) { /* Safari OK lewat input onChange */ }

  /* Tampilkan chip */
  document.getElementById('chipName').textContent = file.name;
  document.getElementById('chipSize').textContent = fmtSize(file.size);
  document.getElementById('fileChip').classList.add('show');

  /* Aktifkan submit */
  document.getElementById('btnSubmit').disabled = false;
  document.getElementById('btnLabel').textContent = 'Import Sekarang';
}

function removeFile() {
  document.getElementById('fileInput').value = '';
  document.getElementById('fileChip').classList.remove('show');
  document.getElementById('btnSubmit').disabled = true;
  document.getElementById('btnLabel').textContent = 'Pilih file dulu';
}

function fmtSize(b) {
  if (b < 1024)    return b + ' B';
  if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
  return (b / 1048576).toFixed(1) + ' MB';
}

/* ════════════════════ SUBMIT ════════════════════ */
document.getElementById('importForm').addEventListener('submit', function (e) {
  if (!document.getElementById('fileInput').files.length) {
    e.preventDefault();
    showToast('Pilih file Excel terlebih dahulu.', 'err');
    return;
  }

  const btn = document.getElementById('btnSubmit');
  btn.disabled = true;
  document.getElementById('btnLabel').textContent = 'Mengimport...';

  const box   = document.getElementById('progressBox');
  const fill  = document.getElementById('progFill');
  const label = document.getElementById('progLabel');
  const pct   = document.getElementById('progPct');
  const bar   = document.getElementById('progressBar');
  box.classList.add('show');

  let v = 0;
  const timer = setInterval(() => {
    v += Math.random() * 10 + 4;
    if (v >= 90) { v = 90; clearInterval(timer); }
    fill.style.width = v + '%';
    bar.setAttribute('aria-valuenow', Math.round(v));
    label.textContent = 'Memproses data...';
    pct.textContent   = Math.round(v) + '%';
  }, 240);
});

/* ════════════════════ FAQ ACCORDION ════════════════════ */
function toggleFaq(i) {
  const item   = document.getElementById('faqItem' + i);
  const ans    = document.getElementById('faqAns'  + i);
  const btn    = item.querySelector('.faq-btn');
  const isOpen = item.classList.contains('open');

  /* Close all */
  document.querySelectorAll('.faq-item.open').forEach(el => {
    el.classList.remove('open');
    el.querySelector('.faq-answer').classList.remove('open');
    el.querySelector('.faq-btn').setAttribute('aria-expanded','false');
  });

  if (!isOpen) {
    item.classList.add('open');
    ans.classList.add('open');
    btn.setAttribute('aria-expanded', 'true');
  }
}

/* ════════════════════ TOAST ════════════════════ */
function showToast(msg, type = 'ok') {
  const c = document.getElementById('toastContainer');
  const t = document.createElement('div');
  t.className = 'toast toast-' + type;
  t.innerHTML = `
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
      ${ type === 'err'
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
      }
    </svg>
    <span>${msg}</span>
  `;
  t.setAttribute('role', 'alert');
  c.appendChild(t);
  setTimeout(() => {
    t.classList.add('toast-out');
    t.addEventListener('animationend', () => t.remove(), { once: true });
  }, 3400);
}
</script>
@endpush