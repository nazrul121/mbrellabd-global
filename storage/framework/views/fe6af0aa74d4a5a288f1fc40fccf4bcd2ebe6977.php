<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', request()->get('system_title') ); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            padding: 30px;
        }
        .error-container {
            margin-top: 40px;
            position: relative;
        }
        .error-code {
            font-size: 100px;
            font-weight: 600;
            color: #e74c3c;
            animation: bounce 1s infinite;
        }
        .error-message {
            font-size: 24px;
            margin: 20px 0;
            color: #555;
        }
        .search-bar {
            max-width: 500px;
            margin: 20px auto;
        }
        .btn-custom {
            background-color: #3498db;
            color: white;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #2980b9;
        }
        .categories {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .category-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin: 10px;
            padding: 20px;
            text-align: center;
            width: 150px;
            transition: transform 0.2s;
        }
        .category-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        @keyframes  bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
    </style>
</head>
<body>


<div class="container error-container bg-light p-md-5">
    <?php echo $__env->yieldContent('content'); ?>
</div>

</body>
</html>

<?php /**PATH /var/www/laravelapp/resources/views/layouts/error.blade.php ENDPATH**/ ?>