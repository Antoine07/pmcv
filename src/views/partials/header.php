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
<div class="container u-full-width">
    <div class="header">
        <a class="header__icon" id="header__icon" href="#"></a>
        <h1 class="header__title"><?php echo trans('title'); ?></h1>
    </div>