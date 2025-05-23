<?php include 'darkmode.php'; ?>

<footer style="
    background: <?= $darkMode ? '#1e1e1e' : '#fff3e0' ?>;
    color: <?= $darkMode ? '#f1c40f' : '#a05e00' ?>;
    padding: 24px;
    text-align: center;
    border-top: 1px solid <?= $darkMode ? '#444' : '#ffe0b2' ?>;
    font-size: 14px;
    margin-top: 40px;
">
    <div style="margin-bottom: 8px; font-size: 18px;">
        🍹 <strong>Tatua Milktea Admin</strong>
    </div>
    <div>
        &copy; <?= date('Y') ?> Tatua Milktea. All rights reserved.
    </div>
    <div style="margin-top: 4px;">
        Designed with ☀️ & 🍵 by PTIT Dev
    </div>
</footer>
