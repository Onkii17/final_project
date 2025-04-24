<?php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/auth.php';

$auth = new Auth();
if(!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ".BASE_URL."login.php");
    exit();
}
?>
<div class="bg-gray-800 text-white w-64 flex-shrink-0">
    <div class="p-4 border-b border-gray-700">
        <h1 class="text-xl font-semibold">Admin Panel</h1>
    </div>
    <nav class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="<?= BASE_URL ?>admin/dashboard.php" class="flex items-center space-x-2 text-blue-400 hover:bg-gray-700 p-2 rounded">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/analysis.php" class="flex items-center space-x-2 text-white hover:bg-gray-700 p-2 rounded">
                    <i class="fas fa-chart-bar"></i>
                    <span>Feedback Analysis</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/manage.php" class="flex items-center space-x-2 text-white hover:bg-gray-700 p-2 rounded">
                    <i class="fas fa-users-cog"></i>
                    <span>Manage Users</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/teachers.php" class="flex items-center space-x-2 text-white hover:bg-gray-700 p-2 rounded">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Manage Teachers</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>logout.php" class="flex items-center space-x-2 text-white hover:bg-gray-700 p-2 rounded">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</div>