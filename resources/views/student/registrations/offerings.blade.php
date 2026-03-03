@extends('student.layout')

@section('title','Đăng ký học phần')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    .container-main {
        display: grid;
        grid-template-columns: 310px 1fr;
        gap: 16px;
        margin-top: 16px;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .user-card {
        background: white;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #e0e0e0;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #999;
    }

    .user-name {
        font-weight: 700;
        color: #333;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .user-id {
        font-size: 12px;
        color: #999;
    }

    .menu-box {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .menu-item {
        padding: 12px 14px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #333;
        transition: background 0.2s;
    }

    .menu-item.clickable {
        cursor: pointer;
    }

    .menu-item.clickable:hover {
        background: #f8f9ff;
    }

    .menu-item:last-child {
        border-bottom: none;
    }

    .menu-item:hover {
        background: #f8f9ff;
    }

    .menu-icon {
        font-size: 16px;
    }

    .menu-text {
        flex: 1;
    }

    .menu-count {
        background: #f0f0f0;
        color: #666;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .filter-section {
        background: white;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .filter-title {
        font-weight: 600;
        font-size: 13px;
        color: #333;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-group {
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group:last-child {
        margin-bottom: 0;
    }

    .filter-label {
        font-size: 11px;
        color: #666;
        font-weight: 500;
    }

    .filter-input {
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 12px;
        font-family: inherit;
    }

    .filter-input:focus {
        outline: none;
        border-color: #6B4B9D;
        box-shadow: 0 0 0 2px rgba(107, 75, 157, 0.1);
    }

    .search-btn {
        width: 100%;
        padding: 10px;
        background: #6B4B9D;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        margin-top: 8px;
    }

    .search-btn:hover {
        background: #5a3d7e;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .breadcrumb {
        padding: 8px 0;
        font-size: 12px;
        color: #666;
    }

    .breadcrumb a {
        color: #6B4B9D;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .info-row-box {
        background: white;
        border-radius: 6px;
        padding: 12px 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .info-row-label {
        width: 160px;
        font-weight: 600;
        color: #333;
        font-size: 13px;
        padding-top: 8px;
        flex-shrink: 0;
    }

    .info-row-content {
        flex: 1;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .info-badge-blue {
        background: #3a5a9c;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
    }

    .info-badge-orange {
        background: #ff8c42;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
    }

    .info-text-right {
        font-size: 12px;
        color: #666;
        white-space: nowrap;
    }

    .info-text-right strong {
        color: #333;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
        font-family: inherit;
    }

    .search-input::placeholder {
        color: #bbb;
    }

    .search-input:focus {
        outline: none;
        border-color: #6B4B9D;
        box-shadow: 0 0 0 2px rgba(107, 75, 157, 0.1);
    }

    .search-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
        font-family: inherit;
    }

    .search-input::placeholder {
        color: #bbb;
    }

    .search-input:focus {
        outline: none;
        border-color: #6B4B9D;
        box-shadow: 0 0 0 2px rgba(107, 75, 157, 0.1);
    }

    .courses-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
        width: 100%;
    }

    .course-badge-green {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #4caf50;
        color: white;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .course-badge-green:hover {
        background: #43a047;
    }

    .course-arrow {
        font-size: 16px;
        font-weight: bold;
    }

    .sections-title {
        font-size: 15px;
        font-weight: 700;
        color: #333;
        margin: 12px 0 12px 0;
    }

    .sections-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .section-card {
        background: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: all 0.2s;
    }

    .section-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .card-header {
        background: linear-gradient(135deg, #6B4B9D 0%, #5a3d7e 100%);
        color: white;
        padding: 12px;
    }

    .card-title {
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .card-subtitle {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
        line-height: 1.3;
    }

    .card-body {
        padding: 12px;
    }

    .card-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 12px;
    }

    .card-row:last-child {
        margin-bottom: 0;
    }

    .card-label {
        color: #666;
        font-weight: 500;
    }

    .card-value {
        font-weight: 600;
        color: #333;
        text-align: right;
    }

    .card-price {
        font-size: 14px;
        color: #f57c00;
        font-weight: 700;
    }

    .card-capacity {
        background: #f8f9ff;
        padding: 8px;
        border-radius: 4px;
        margin: 8px 0;
        font-size: 12px;
    }

    .capacity-bar {
        background: #e0e0e0;
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .capacity-fill {
        height: 100%;
        background: #6B4B9D;
        transition: width 0.3s;
    }

    .capacity-text {
        display: flex;
        justify-content: space-between;
        color: #666;
        font-weight: 500;
    }

    .card-actions {
        display: flex;
        gap: 6px;
    }

    .card-btn {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-info {
        background: #f0f0f0;
        color: #333;
    }

    .btn-info:hover {
        background: #e0e0e0;
    }

    .btn-register {
        background: #4caf50;
        color: white;
    }

    .btn-register:hover {
        background: #43a047;
    }

    .btn-registered {
        background: #d4edda;
        color: #155724;
        cursor: default;
    }

    .btn-full {
        background: #f8d7da;
        color: #721c24;
        cursor: default;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
        background: white;
        border-radius: 6px;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-weight: 700;
        font-size: 16px;
        color: #333;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .modal-close:hover {
        background: #f0f0f0;
        color: #333;
    }

    .modal-body {
        padding: 16px 20px;
    }

    .registered-class {
        background: #f8f9ff;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 12px;
        border: 1px solid #e0e0e0;
    }

    .registered-class:last-child {
        margin-bottom: 0;
    }

    .registered-class-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .registered-class-code {
        font-weight: 700;
        color: #6B4B9D;
        font-size: 14px;
    }

    .registered-class-name {
        font-size: 12px;
        color: #666;
        margin-top: 2px;
    }

    .registered-class-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        font-size: 12px;
        color: #666;
    }

    .registered-class-detail {
        display: flex;
        gap: 4px;
    }

    .detail-label-small {
        color: #999;
    }

    .btn-cancel-class {
        background: #dc3545;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-cancel-class:hover {
        background: #c82333;
    }

    /* ===== CONFIRM MODAL ===== */
    .confirm-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .confirm-modal.active {
        display: flex;
    }

    .confirm-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 520px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .confirm-modal-header {
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
    }

    .confirm-modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .confirm-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .confirm-modal-close:hover {
        background: #f0f0f0;
        color: #333;
    }

    .confirm-modal-body {
        padding: 24px 20px;
        text-align: center;
    }

    .confirm-illustration {
        font-size: 80px;
        margin-bottom: 16px;
    }

    .confirm-text {
        font-size: 15px;
        color: #555;
        margin-bottom: 8px;
    }

    .confirm-course-name {
        font-size: 17px;
        font-weight: 700;
        color: #6B4B9D;
        margin-bottom: 24px;
    }

    .confirm-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .confirm-btn {
        padding: 12px 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .confirm-btn-back {
        background: #f0f0f0;
        color: #555;
        border: 1px solid #ddd;
    }

    .confirm-btn-back:hover {
        background: #e0e0e0;
    }

    .confirm-btn-ok {
        background: #6B4B9D;
        color: white;
    }

    .confirm-btn-ok:hover {
        background: #5a3d7e;
    }

    /* ===== SUCCESS / ERROR MODAL ===== */
    .result-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .result-modal.active {
        display: flex;
    }

    .result-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 460px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
        text-align: center;
        padding: 32px 24px;
    }

    .result-icon {
        font-size: 72px;
        margin-bottom: 16px;
    }

    .result-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .result-title.success {
        color: #28a745;
    }

    .result-title.error {
        color: #dc3545;
    }

    .result-message {
        font-size: 14px;
        color: #555;
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .result-btn-ok {
        padding: 12px 40px;
        background: #6B4B9D;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }

    .result-btn-ok:hover {
        background: #5a3d7e;
    }

    /* ===== CANCEL CONFIRM MODAL ===== */
    .cancel-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .cancel-modal.active {
        display: flex;
    }

    .cancel-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 520px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }

    .cancel-modal-header {
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
    }

    .cancel-modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #dc3545;
        margin: 0;
    }

    .cancel-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .cancel-modal-close:hover {
        background: #f0f0f0;
        color: #333;
    }

    .cancel-modal-body {
        padding: 24px 20px;
        text-align: center;
    }

    .cancel-illustration {
        font-size: 80px;
        margin-bottom: 16px;
    }

    .cancel-text {
        font-size: 15px;
        color: #555;
        margin-bottom: 8px;
    }

    .cancel-course-name {
        font-size: 17px;
        font-weight: 700;
        color: #dc3545;
        margin-bottom: 24px;
    }

    .cancel-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .cancel-btn {
        padding: 12px 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .cancel-btn-back {
        background: #f0f0f0;
        color: #555;
        border: 1px solid #ddd;
    }

    .cancel-btn-back:hover {
        background: #e0e0e0;
    }

    .cancel-btn-confirm {
        background: #dc3545;
        color: white;
    }

    .cancel-btn-confirm:hover {
        background: #c82333;
    }

    /* ===== DETAIL MODAL ===== */
    .detail-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .detail-modal.active {
        display: flex;
    }

    .detail-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 560px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }

    .detail-modal-header {
        background: linear-gradient(135deg, #6B4B9D 0%, #5a3d7e 100%);
        color: white;
        padding: 20px;
    }

    .detail-modal-header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .detail-modal-title {
        font-size: 18px;
        font-weight: 700;
    }

    .detail-modal-subtitle {
        font-size: 13px;
        color: rgba(255,255,255,0.85);
        margin-top: 4px;
    }

    .detail-modal-close {
        background: rgba(255,255,255,0.2);
        border: none;
        font-size: 20px;
        color: white;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .detail-modal-close:hover {
        background: rgba(255,255,255,0.35);
    }

    .detail-modal-body {
        padding: 20px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-item-label {
        font-size: 11px;
        color: #999;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-item-value {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .detail-capacity-section {
        grid-column: 1 / -1;
        background: #f8f9ff;
        padding: 12px;
        border-radius: 6px;
        margin-top: 4px;
    }

    .detail-capacity-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .detail-capacity-bar {
        background: #e0e0e0;
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
    }

    .detail-capacity-fill {
        height: 100%;
        border-radius: 5px;
        transition: width 0.3s;
    }

    .detail-capacity-fill.low { background: #4caf50; }
    .detail-capacity-fill.medium { background: #ff9800; }
    .detail-capacity-fill.high { background: #f44336; }

    .detail-status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .detail-status-badge.available { background: #d4edda; color: #155724; }
    .detail-status-badge.full { background: #f8d7da; color: #721c24; }
    .detail-status-badge.registered { background: #cce5ff; color: #004085; }

    @media (max-width: 480px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 1200px) {
        .container-main {
            grid-template-columns: 1fr;
        }

        .info-row-box {
            flex-direction: column;
            gap: 8px;
        }

        .info-row-label {
            width: 100%;
            padding-top: 0;
        }

        .info-row-content {
            flex-direction: column;
        }

        .info-text-right {
            white-space: normal;
        }

        .sections-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .container-main {
            grid-template-columns: 1fr;
        }

        .info-row-box {
            flex-direction: column;
        }

        .info-row-label {
            width: 100%;
        }

        .sections-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-main">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <!-- User Card -->
        <div class="user-card">
            <div class="user-avatar">👤</div>
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-id">Mã SV: {{ Auth::user()->student_code ?? Auth::user()->id }}</div>
        </div>

        <!-- Menu Items -->
        <div class="menu-box">
            <div class="menu-item">
                <span class="menu-icon">💳</span>
                <span class="menu-text">Số dự tài khoản hiện tại:</span>
            </div>
            <div class="menu-item">
                <span class="menu-icon">➕</span>
                <span class="menu-text">Số phát sinh thêm trong đợt</span>
            </div>
            <div class="menu-item clickable" onclick="openRegisteredModal()" style="cursor: pointer;">
                <span class="menu-icon">📋</span>
                <span class="menu-text">Tổng lớp đã đăng ký:</span>
                <span class="menu-count">{{ count($currentRegs) }}</span>
            </div>
            <div class="menu-item">
                <span class="menu-icon">💰</span>
                <span class="menu-text">Số tín chỉ đã đăng ký:</span>
                <span class="menu-count">{{ $currentRegs->sum(fn($r) => $r->classSection->course->credits ?? 0) }}</span>
            </div>
            <div class="menu-item">
                <span class="menu-icon">📚</span>
                <span class="menu-text">Số tín chỉ tối thiểu</span>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">🔍 Bộ lọc tìm kiếm</div>
            <form method="GET" action="{{ route('student.offerings') }}" style="display: flex; flex-direction: column; gap: 10px;">
                <div class="filter-group">
                    <label class="filter-label">Giảng viên</label>
                    <input type="text" name="lecturer" class="filter-input" placeholder="Tên giảng viên" value="{{ request('lecturer') }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Thứ học</label>
                    <select name="day" class="filter-input">
                        <option value="">-- Chọn thứ --</option>
                        <option value="2" {{ request('day')=='2'?'selected':'' }}>Thứ 2</option>
                        <option value="3" {{ request('day')=='3'?'selected':'' }}>Thứ 3</option>
                        <option value="4" {{ request('day')=='4'?'selected':'' }}>Thứ 4</option>
                        <option value="5" {{ request('day')=='5'?'selected':'' }}>Thứ 5</option>
                        <option value="6" {{ request('day')=='6'?'selected':'' }}>Thứ 6</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Phương án đăng ký</label>
                    <select name="plan" class="filter-input">
                        <option value="">-- Tất cả --</option>
                        @foreach(['K17_CNTT 01' => 'K17_CNTT 01', 'K17_CNTT 02' => 'K17_CNTT 02', 'K17_CNTT 03' => 'K17_CNTT 03'] as $k => $v)
                        <option value="{{ $k }}" {{ request('plan')==$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="search-btn">Tìm kiếm</button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Breadcrumb -->
        {{-- <div class="breadcrumb">
            <a href="{{ route('student.dashboard') }}">Đăng ký trực tuyến</a> > <strong>Đăng ký học</strong>
        </div> --}}

        <!-- Header Info -->
        <div class="info-row-box">
            <div class="info-row-label">Chương trình đào tạo</div>
            <div class="info-row-content">
                <div class="info-badge-blue">
                    Đại học Chính quy Khoá 17_4 năm - Công nghệ thông tin
                </div>
            </div>
        </div>

        <!-- Kế hoạch info -->
        <div class="info-row-box">
            <div class="info-row-label">Kế hoạch</div>
            <div class="info-row-content" style="flex: 1; display: flex; justify-content: space-between; align-items: center;">
                @if($wave)
                <div class="info-badge-orange">
                    {{ $year }}, {{ $term }} | Đợt đăng ký: {{ $wave->name ?? 'Chưa đặt tên' }}
                </div>
                <div class="info-text-right">
                    Thời gian đăng ký học phần: <strong>{{ \Carbon\Carbon::parse($wave->starts_at)->format('Y-m-d H:i') }} - {{ \Carbon\Carbon::parse($wave->ends_at)->format('Y-m-d H:i') }}</strong>
                </div>
                @else
                <div class="info-badge-orange" style="background: #999;">
                    {{ $year }}, {{ $term }} | Chưa có đợt đăng ký
                </div>
                <div class="info-text-right" style="color: #999;">
                    Chưa mở đăng ký cho kỳ này
                </div>
                @endif
            </div>
        </div>

        <!-- Học phần -->
        <div class="info-row-box">
            <div class="info-row-label">Học phần</div>
            <div class="info-row-content" style="flex-direction: column; align-items: stretch;">
                <input type="text" class="search-input" id="courseSearch" placeholder="Tìm kiếm học phần">
                <div class="courses-list">
                    @forelse($courses ?? [] as $course)
                    <div class="course-badge-green" data-course-id="{{ $course->id }}">
                        <span class="course-arrow">›</span>
                        <span>{{ $course->code }} - {{ $course->name }}</span>
                    </div>
                    @empty
                    <span style="color: #999; font-size: 12px;">Không có học phần</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sections -->
        <div class="sections-title">Lớp học phần</div>

        @if(!$openForUser || !$wave)
        <div class="empty-state" style="background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">
            <div style="font-size: 48px; margin-bottom: 12px;">⏰</div>
            <div style="font-weight: 600; margin-bottom: 8px;">Chưa đến thời gian đăng ký</div>
            <div style="font-size: 13px;">Hiện tại không trong đợt đăng ký học phần. Vui lòng quay lại khi đợt đăng ký được mở.</div>
        </div>
        @elseif($sections->count() > 0)
        <div class="sections-grid">
            @foreach($sections as $s)
            @php
            $enrolled = $s->registrations()->count();
            $full = $enrolled >= $s->max_capacity;
            $registered = in_array($s->id, $registeredSectionIds);
            $percentage = ($enrolled / $s->max_capacity) * 100;
            @endphp
            <div class="section-card">
                <div class="card-header">
                    <div class="card-title">{{ $s->course->code }}</div>
                    <div class="card-subtitle">{{ $s->course->name }}</div>
                </div>

                <div class="card-body">
                    <div class="card-row">
                        <span class="card-label">Lý thuyết</span>
                        <span class="card-value">{{ $s->section_code }}</span>
                    </div>

                    <div class="card-row">
                        <span class="card-label">Thứ</span>
                        <span class="card-value">{{ $s->shift->day_name ?? 'Thứ '.$s->shift->day_of_week }}</span>
                    </div>

                    <div class="card-row">
                        <span class="card-label">Đã đăng ký</span>
                        <span class="card-value">{{ $enrolled }}/{{ $s->max_capacity }}</span>
                    </div>

                    <div class="card-capacity">
                        <div class="capacity-bar">
                            <div class="capacity-fill" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                        <div class="capacity-text">
                            <span>{{ $enrolled }} chỗ</span>
                            <span>{{ number_format($percentage, 0) }}%</span>
                        </div>
                    </div>

                    <div class="card-row">
                        <span class="card-label">Tín chỉ</span>
                        <span class="card-value">{{ $s->course->credits }} TC</span>
                    </div>

                    <div class="card-actions">
                        <button class="card-btn btn-info" onclick="openDetailModal({
                            code: '{{ $s->course->code }}',
                            name: '{{ $s->course->name }}',
                            sectionCode: '{{ $s->section_code }}',
                            credits: '{{ $s->course->credits }}',
                            day: '{{ $s->shift->day_name ?? 'Thứ '.$s->shift->day_of_week }}',
                            period: 'T{{ $s->shift->start_period ?? "?" }}-{{ $s->shift->end_period ?? "?" }}',
                            room: '{{ $s->room->code ?? "TBD" }}',
                            lecturer: '{{ $s->lecturer->name ?? "TBD" }}',
                            enrolled: {{ $enrolled }},
                            maxCapacity: {{ $s->max_capacity }},
                            percentage: {{ number_format($percentage, 0) }},
                            registered: {{ $registered ? 'true' : 'false' }},
                            full: {{ $full ? 'true' : 'false' }},
                            year: '{{ $s->academic_year }}',
                            term: '{{ $s->term }}'
                        })">Xem chi tiết</button>
                        @if($registered)
                        <button class="card-btn btn-registered" disabled>Đã đăng ký</button>
                        @elseif($full)
                        <button class="card-btn btn-full" disabled>Hết chỗ</button>
                        @elseif(!$openForUser)
                        <button class="card-btn btn-full" disabled>Chưa mở</button>
                        @else
                        <form id="register-form-{{ $s->id }}" action="{{ route('student.register', $s) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="button" class="card-btn btn-register" style="width: 100%;" onclick="openConfirmModal('{{ $s->course->name }}({{ $s->section_code }})', '{{ $s->id }}')">Đăng ký</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 16px;">
            {{ $sections->links() }}
        </div>
        @else
        <div class="empty-state">
            <div style="font-size: 48px; margin-bottom: 12px;">📭</div>
            <div>Không tìm thấy lớp học phần</div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Lớp đã đăng ký -->
<div id="registeredModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">📚 Lớp đã đăng ký ({{ count($currentRegs) }})</div>
            <button class="modal-close" onclick="closeRegisteredModal()">&times;</button>
        </div>
        <div class="modal-body">
            @if(count($currentRegs) > 0)
                @foreach($currentRegs as $reg)
                @php($cs = $reg->classSection)
                <div class="registered-class">
                    <div class="registered-class-header">
                        <div>
                            <div class="registered-class-code">{{ $cs->course->code }} - {{ $cs->section_code }}</div>
                            <div class="registered-class-name">{{ $cs->course->name }}</div>
                        </div>
                        <form id="cancel-form-{{ $reg->id }}" action="{{ route('student.cancel', $reg) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-cancel-class" onclick="openCancelModal('{{ $cs->course->code }} - {{ $cs->course->name }}({{ $cs->section_code }})', '{{ $reg->id }}')">Hủy lớp</button>
                        </form>
                    </div>
                    <div class="registered-class-details">
                        <div class="registered-class-detail">
                            <span class="detail-label-small">Tín chỉ:</span>
                            <span>{{ $cs->course->credits }} TC</span>
                        </div>
                        <div class="registered-class-detail">
                            <span class="detail-label-small">Thứ/Tiết:</span>
                            <span>{{ $cs->shift->day_name ?? 'Thứ '.$cs->shift->day_of_week ?? 'TBD' }} / T{{ $cs->shift->start_period ?? '?' }}-{{ $cs->shift->end_period ?? '?' }}</span>
                        </div>
                        <div class="registered-class-detail">
                            <span class="detail-label-small">Phòng:</span>
                            <span>{{ $cs->room->code ?? 'TBD' }}</span>
                        </div>
                        <div class="registered-class-detail">
                            <span class="detail-label-small">GV:</span>
                            <span>{{ $cs->lecturer->name ?? 'TBD' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 40px 20px; color: #999;">
                    <div style="font-size: 48px; margin-bottom: 12px;">📭</div>
                    <div>Chưa đăng ký lớp nào</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Xem chi tiết lớp học phần -->
<div id="detailModal" class="detail-modal">
    <div class="detail-modal-content">
        <div class="detail-modal-header">
            <div class="detail-modal-header-top">
                <div>
                    <div class="detail-modal-title" id="detailTitle"></div>
                    <div class="detail-modal-subtitle" id="detailSubtitle"></div>
                </div>
                <button class="detail-modal-close" onclick="closeDetailModal()">&times;</button>
            </div>
        </div>
        <div class="detail-modal-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-item-label">Mã lớp</span>
                    <span class="detail-item-value" id="detailSectionCode"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item-label">Số tín chỉ</span>
                    <span class="detail-item-value" id="detailCredits"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item-label">Thứ học</span>
                    <span class="detail-item-value" id="detailDay"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item-label">Tiết học</span>
                    <span class="detail-item-value" id="detailPeriod"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item-label">Phòng học</span>
                    <span class="detail-item-value" id="detailRoom"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item-label">Giảng viên</span>
                    <span class="detail-item-value" id="detailLecturer"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item-label">Năm học</span>
                    <span class="detail-item-value" id="detailYear"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item-label">Trạng thái</span>
                    <span id="detailStatus"></span>
                </div>
                <div class="detail-capacity-section">
                    <div class="detail-capacity-header">
                        <span>Đã đăng ký: <strong id="detailEnrolled"></strong></span>
                        <span id="detailPercentage"></span>
                    </div>
                    <div class="detail-capacity-bar">
                        <div class="detail-capacity-fill" id="detailCapacityFill"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận hủy lớp -->
<div id="cancelModal" class="cancel-modal">
    <div class="cancel-modal-content">
        <div class="cancel-modal-header">
            <h3>⚠️ Xác nhận hủy lớp</h3>
            <button class="cancel-modal-close" onclick="closeCancelModal()">&times;</button>
        </div>
        <div class="cancel-modal-body">
            <div class="cancel-illustration">🗑️</div>
            <div class="cancel-text">Bạn có chắc chắn muốn hủy lớp học phần:</div>
            <div class="cancel-course-name" id="cancelCourseName"></div>
            <div class="cancel-actions">
                <button class="cancel-btn cancel-btn-back" onclick="closeCancelModal()">← Quay lại</button>
                <button class="cancel-btn cancel-btn-confirm" onclick="submitCancellation()">Xác nhận hủy ✗</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận đăng ký -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-header">
            <h3>Xác nhận đăng ký</h3>
            <button class="confirm-modal-close" onclick="closeConfirmModal()">&times;</button>
        </div>
        <div class="confirm-modal-body">
            <div class="confirm-illustration">📚</div>
            <div class="confirm-text">Bạn có chắc chắn muốn đăng ký lớp học phần:</div>
            <div class="confirm-course-name" id="confirmCourseName"></div>
            <div class="confirm-actions">
                <button class="confirm-btn confirm-btn-back" onclick="closeConfirmModal()">← Quay lại</button>
                <button class="confirm-btn confirm-btn-ok" id="confirmOkBtn" onclick="submitRegistration()">Đồng ý ✓</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kết quả đăng ký -->
<div id="resultModal" class="result-modal">
    <div class="result-modal-content">
        <div class="result-icon" id="resultIcon"></div>
        <div class="result-title" id="resultTitle"></div>
        <div class="result-message" id="resultMessage"></div>
        <button class="result-btn-ok" onclick="closeResultModal()">Đã hiểu</button>
    </div>
</div>

<script>
let pendingSectionId = null;
let pendingCancelId = null;

// === Detail Modal ===
function openDetailModal(data) {
    document.getElementById('detailTitle').textContent = data.code + ' - ' + data.name;
    document.getElementById('detailSubtitle').textContent = 'Lớp ' + data.sectionCode;
    document.getElementById('detailSectionCode').textContent = data.sectionCode;
    document.getElementById('detailCredits').textContent = data.credits + ' TC';
    document.getElementById('detailDay').textContent = data.day;
    document.getElementById('detailPeriod').textContent = data.period;
    document.getElementById('detailRoom').textContent = data.room;
    document.getElementById('detailLecturer').textContent = data.lecturer;
    document.getElementById('detailYear').textContent = data.year + ' - ' + data.term;
    document.getElementById('detailEnrolled').textContent = data.enrolled + '/' + data.maxCapacity;
    document.getElementById('detailPercentage').textContent = data.percentage + '%';

    // Status badge
    const statusEl = document.getElementById('detailStatus');
    if (data.registered) {
        statusEl.innerHTML = '<span class="detail-status-badge registered">Đã đăng ký</span>';
    } else if (data.full) {
        statusEl.innerHTML = '<span class="detail-status-badge full">Hết chỗ</span>';
    } else {
        statusEl.innerHTML = '<span class="detail-status-badge available">Còn chỗ</span>';
    }

    // Capacity bar
    const fill = document.getElementById('detailCapacityFill');
    fill.style.width = Math.min(data.percentage, 100) + '%';
    fill.className = 'detail-capacity-fill';
    if (data.percentage >= 90) fill.classList.add('high');
    else if (data.percentage >= 60) fill.classList.add('medium');
    else fill.classList.add('low');

    document.getElementById('detailModal').classList.add('active');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.remove('active');
}

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) closeDetailModal();
});

// === Cancel Modal ===
function openCancelModal(courseName, regId) {
    pendingCancelId = regId;
    document.getElementById('cancelCourseName').textContent = courseName;
    document.getElementById('cancelModal').classList.add('active');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.remove('active');
    pendingCancelId = null;
}

function submitCancellation() {
    if (pendingCancelId) {
        const form = document.getElementById('cancel-form-' + pendingCancelId);
        if (form) {
            closeCancelModal();
            form.submit();
        }
    }
}

// Close cancel modal when clicking outside
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});

// === Confirm Modal ===
function openConfirmModal(courseName, sectionId) {
    pendingSectionId = sectionId;
    document.getElementById('confirmCourseName').textContent = courseName;
    document.getElementById('confirmModal').classList.add('active');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('active');
    pendingSectionId = null;
}

function submitRegistration() {
    if (pendingSectionId) {
        const form = document.getElementById('register-form-' + pendingSectionId);
        if (form) {
            closeConfirmModal();
            form.submit();
        }
    }
}

// Close confirm modal when clicking outside
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirmModal();
});

// === Result Modal (success / error) ===
function showResultModal(type, message, customTitle) {
    document.getElementById('resultIcon').textContent = type === 'success' ? '✅' : '❌';
    const titleEl = document.getElementById('resultTitle');
    if (customTitle) {
        titleEl.textContent = customTitle;
    } else {
        titleEl.textContent = type === 'success' ? 'Đăng ký thành công!' : 'Đăng ký thất bại!';
    }
    titleEl.className = 'result-title ' + type;
    document.getElementById('resultMessage').textContent = message;
    document.getElementById('resultModal').classList.add('active');
}

function closeResultModal() {
    document.getElementById('resultModal').classList.remove('active');
}

// Close result modal when clicking outside
document.getElementById('resultModal').addEventListener('click', function(e) {
    if (e.target === this) closeResultModal();
});

// Auto-show result modal from flash session
@if(session('success'))
    @if(str_contains(session('success'), 'Hủy'))
        showResultModal('success', @json(session('success')), 'Hủy đăng ký thành công!');
    @else
        showResultModal('success', @json(session('success')));
    @endif
@endif
@if(session('error'))
    showResultModal('error', @json(session('error')));
@endif

// === Registered Modal ===
function openRegisteredModal() {
    document.getElementById('registeredModal').classList.add('active');
}

function closeRegisteredModal() {
    document.getElementById('registeredModal').classList.remove('active');
}

// Close registered modal when clicking outside
document.getElementById('registeredModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRegisteredModal();
    }
});
</script>

@endsection
