<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo url('assets/css/normalize.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('assets/css/skeleton.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('assets/css/app.min.css'); ?>">

    <!-- Favicon  -->
    <link rel="icon" type="image/png" href="<?php echo url('images/favicon.png'); ?>">

</head>
<body>
<div class="container">
    <div class="header">
        <nav class="header__menu">
            <a class="button" href="<?php echo url(); ?>">home</a>
            <?php if (auth_guest()) : ?>
                <a class="button" href="<?php echo url('dashboard'); ?>">Dashboard</a>
                <a class="button" href="<?php echo url('logout'); ?>">Logout</a>
            <?php else: ?>
            <a class="button" href="<?php echo url('login'); ?>"> login</a>
            <?php endif; ?>
        </nav>
    </div>