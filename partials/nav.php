<header class="bg-white">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6">
        <div class="flex lg:flex-1">
            <a href="index.php" class="-m-1.5 p-1.5 text-xl font-bold tracking-wide uppercase">
                Notes
            </a>
        </div>
        <?php if($_SESSION["id"]) : ?>
            <div class="lg:flex lg:flex-1 lg:justify-end lg:items-center gap-x-4">
                <div class="hidden lg:flex items-center text-lg">
                    <i class="bi bi-person-fill"></i>
                    <p class="capitalize ml-1 font-medium text-slate-900"><?= $_SESSION["username"] ?></p>
                </div>
                <a href="logout.php" class="text-sm font-semibold leading-6 text-slate-200 bg-rose-500 hover:bg-rose-600 px-4 py-1.5 rounded-md transition-all">
                    Logout
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </nav>
</header>
