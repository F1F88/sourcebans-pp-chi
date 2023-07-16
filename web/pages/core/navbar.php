<?php
global $userbank, $theme;

$navbar = [
     [
        'title' => '概览',
        'endpoint' => 'home',
        'description' => 'This page shows an overview of your bans and servers.',
        'permission' => true
    ],
    [
        'title' => '服务器列表',
        'endpoint' => 'servers',
        'description' => 'All of your servers and their status can be viewed here.',
        'permission' => true
    ],
    [
        'title' => '封禁名单',
        'endpoint' => 'banlist',
        'description' => 'All of the bans in the database can be viewed from here.',
        'permission' => true
    ],
    [
        'title' => '禁言名单',
        'endpoint' => 'commslist',
        'description' => 'All of the communication bans (such as chat gags and voice mutes) in the database can be viewed from here.',
        'permission' => Config::getBool('config.enablecomms')
    ],
    [
        'title' => '请求封禁',
        'endpoint' => 'submit',
        'description' => 'You can submit a demo or screenshot of a suspected cheater here. It will then be up for review by one of the admins.',
        'permission' => Config::getBool('config.enablesubmit')
    ],
    [
        'title' => '抗议封禁',
        'endpoint' => 'protest',
        'description' => 'Here you can appeal your ban. And prove your case as to why you should be unbanned.',
        'permission' => Config::getBool('config.enableprotest')
    ],
    [
        'title' => '管理面板',
        'endpoint' => 'admin',
        'description' => 'This is the control panel for SourceBans where you can setup new admins, add new server, etc.',
        'permission' => $userbank->is_admin()
    ]
];

$admin = [
    [
        'title' => '管理员列表',
        'endpoint' => 'admins',
        'permission' => ADMIN_OWNER|ADMIN_LIST_ADMINS|ADMIN_ADD_ADMINS|ADMIN_EDIT_ADMINS|ADMIN_DELETE_ADMINS
    ],
    [
        'title' => '服务器列表',
        'endpoint' => 'servers',
        'permission' => ADMIN_OWNER|ADMIN_LIST_SERVERS|ADMIN_ADD_SERVER|ADMIN_EDIT_SERVERS|ADMIN_DELETE_SERVERS
    ],
    [
        'title' => '封禁名单',
        'endpoint' => 'bans',
        'permission' => ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|ADMIN_EDIT_ALL_BANS|ADMIN_BAN_PROTESTS|ADMIN_BAN_SUBMISSIONS
    ],
    [
        'title' => '禁言名单',
        'endpoint' => 'comms',
        'permission' => ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_ALL_BANS
    ],
    [
        'title' => '组',
        'endpoint' => 'groups',
        'permission' => ADMIN_OWNER|ADMIN_LIST_GROUPS|ADMIN_ADD_GROUP|ADMIN_EDIT_GROUPS|ADMIN_DELETE_GROUPS
    ],
    [
        'title' => '设置',
        'endpoint' => 'settings',
        'permission' => ADMIN_OWNER|ADMIN_WEB_SETTINGS
    ],
    [
        'title' => '模组/游戏',
        'endpoint' => 'mods',
        'permission' => ADMIN_OWNER|ADMIN_LIST_MODS|ADMIN_ADD_MODS|ADMIN_EDIT_MODS|ADMIN_DELETE_MODS
    ]
];

$active = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_SPECIAL_CHARS);
foreach ($navbar as $key => $tab) {
    $navbar[$key]['state'] = ($active === $tab['endpoint']) ? 'active' : 'nonactive';

    if (!$tab['permission']) {
        unset($navbar[$key]);
    }
}

if ($userbank->is_admin()) {
    $cat = filter_input(INPUT_GET, 'c', FILTER_SANITIZE_SPECIAL_CHARS);
    foreach ($admin as $key => $tab) {
        $admin[$key]['state'] = ($cat === $tab['endpoint']) ? 'active' : '';

        if (!$userbank->HasAccess($tab['permission'])) {
            unset($admin[$key]);
        }
    }
}

$theme->assign('navbar', array_values($navbar));
$theme->assign('adminbar', array_values($admin));
$theme->assign('isAdmin', $userbank->is_admin());
$theme->assign('login', $userbank->is_logged_in());
$theme->assign('username', $userbank->GetProperty("user"));
$theme->display('core/navbar.tpl');
