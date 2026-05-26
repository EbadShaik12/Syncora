<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syncora - Connecting Startups with Corporates</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.jsx']); ?>
</head>
<body class="bg-[#090909] text-white antialiased selection:bg-primary-500/30 selection:text-primary-200">
    <div id="react-landing-root" data-authenticated="<?php echo e(auth()->check() ? 'true' : 'false'); ?>" data-stats="<?php echo e(json_encode($stats)); ?>" data-featured-startups="<?php echo e(json_encode($featuredStartups)); ?>"></div>
</body>
</html>
<?php /**PATH C:\Users\user\Desktop\startup-corporate-platform\resources\views\home.blade.php ENDPATH**/ ?>