<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1" />

<link rel="stylesheet" href="<?php echo e(asset('/assets/css/vendor.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('/assets/css/style.css')); ?>">

<title><?php echo $__env->yieldContent('title', request()->get('system_title') ); ?></title>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta name="generator" content="mbrella" />
<meta name="generator" content="mbrella 2.1.0" />

<!-- Favicon -->
<link rel="shortcut icon" href="<?php echo e(url('storage').'/'.request()->get('favicon')); ?>" type="image/x-icon">
<link rel="icon" href="<?php echo e(url('storage').'/'.request()->get('favicon')); ?>" type="image/x-icon">

<!-- fonts -->

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">

  
<!-- all css -->
<style>
    .colorbtn{
        height: 20px;width: 20px;
    }
    .colorbtn2{
        height: 30px;width: 30px;
    }
    .modalClose{
        position: relative; top: -9px; right: -13px;left: 98%; width: 18px;
        height: 18px;background: red; color: white; font-size: 14px;
    }
    :root {
        --primary-color: #00234D;
        --secondary-color: #ffa700;

        --btn-primary-border-radius: 0.25rem;
        --btn-primary-color: #fff;
        --btn-primary-background-color: #00234D;
        --btn-primary-border-color: #00234D;
        --btn-primary-hover-color: #fff;
        --btn-primary-background-hover-color: #00234D;
        --btn-primary-border-hover-color: #00234D;
        --btn-primary-font-weight: 500;

        --btn-secondary-border-radius: 0.25rem;
        --btn-secondary-color: #00234D;
        --btn-secondary-background-color: transparent;
        --btn-secondary-border-color: #00234D;
        --btn-secondary-hover-color: #fff;
        --btn-secondary-background-hover-color: #00234D;
        --btn-secondary-border-hover-color: #00234D;
        --btn-secondary-font-weight: 500;

        --heading-color: #000;
        --heading-font-family: 'Poppins', sans-serif;
        --heading-font-weight: 700;

        --title-color: #000;
        --title-font-family: 'Poppins', sans-serif;
        --title-font-weight: 400;

        --body-color: #e29d1b;
        --body-background-color: #fff;
        --body-font-family: 'Poppins', sans-serif;
        --body-font-size: 14px;
        --body-font-weight: 400;

        --section-heading-color: #000;
        --section-heading-font-family: 'Poppins', sans-serif;
        --section-heading-font-size: 48px;
        --section-heading-font-weight: 600;

        --section-subheading-color: #000;
        --section-subheading-font-family: 'Poppins', sans-serif;
        --section-subheading-font-size: 16px;
        --section-subheading-font-weight: 400;
    }
</style>

<?php if(env('APP_ENV') === 'production'): ?>
<!-- Meta Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '291657966426591');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=291657966426591&ev=PageView&noscript=1"
    /></noscript>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KTGZK8N');
</script>

<meta name="facebook-domain-verification" content="6kiab3kcz66g9ig1tjaq06gc1snl9l" />
<meta name="google-site-verification" content="QY1wjOUedvKJypz48xZz14WcOfRaS8xnbBIZFMidMOk" />

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0&appId=6384668444904674&autoLogAppEvents=1" nonce="kGbz52rl"></script>
<?php endif; ?><?php /**PATH /var/www/laravelapp/resources/views/includes/head.blade.php ENDPATH**/ ?>