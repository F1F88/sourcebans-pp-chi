<?php
global $theme;

$breadcrumb = [
    [
        'title' => '首页',
        'url' => 'index.php?p=home'
    ],
    [
        'title' => $title,
        'url' => 'index.php?p='.filter_input(INPUT_GET, 'p', FILTER_SANITIZE_SPECIAL_CHARS)
    ]
];

$theme->assign('board_name', Config::get('template.title'));
$theme->assign('title', $title);
$theme->assign('breadcrumb', $breadcrumb);
$theme->display('core/title.tpl');
