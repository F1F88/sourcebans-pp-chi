<?php

/**
 * @param $fallback
 * @return array
 * @throws ErrorException
 */
function route($fallback)
{
    $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_SPECIAL_CHARS);
    $categorie = filter_input(INPUT_GET, 'c', FILTER_SANITIZE_SPECIAL_CHARS);
    $option = filter_input(INPUT_GET, 'o', FILTER_SANITIZE_SPECIAL_CHARS);

    switch ($page) {
        case 'login':
            switch ($option) {
                case 'steam':
                    require_once 'includes/auth/openid.php';
                    new SteamAuthHandler(new LightOpenID(Host::complete()), $GLOBALS['PDO']);
                    exit();
                default:
                    return ['Login', '/page.login.php'];
            }
        case 'logout':
            Auth::logout();
            header('Location: index.php?p=home');
            exit();
        case 'submit':
            return ['请求封禁', '/page.submit.php'];
        case 'banlist':
            return ['封禁名单', '/page.banlist.php'];
        case 'commslist':
            return ['禁言名单', '/page.commslist.php'];
        case 'servers':
            return ['服务器列表', '/page.servers.php'];
        case 'protest':
            return ['请求解封', '/page.protest.php'];
        case 'account':
            return ['你的账户', '/page.youraccount.php'];
        case 'lostpassword':
            return ['找回密码', '/page.lostpassword.php'];
        case 'home':
            return ['概览', '/page.home.php'];
        case 'admin':
            switch ($categorie) {
                case 'groups':
                    CheckAdminAccess(ADMIN_OWNER|ADMIN_LIST_GROUPS|ADMIN_ADD_GROUP|ADMIN_EDIT_GROUPS|ADMIN_DELETE_GROUPS);
                    switch ($option) {
                        case 'edit':
                            return ['编辑用户组', '/admin.edit.group.php'];
                        default:
                            return ['组管理员', '/admin.groups.php'];
                    }
                case 'admins':
                    CheckAdminAccess(ADMIN_OWNER|ADMIN_LIST_ADMINS|ADMIN_ADD_ADMINS|ADMIN_EDIT_ADMINS|ADMIN_DELETE_ADMINS);
                    switch ($option) {
                        case 'editgroup':
                            return ['编辑管理员组', '/admin.edit.admingroup.php'];
                        case 'editdetails':
                            return ['编辑管理员资料', '/admin.edit.admindetails.php'];
                        case 'editpermissions':
                            return ['编辑管理员权限', '/admin.edit.adminperms.php'];
                        case 'editservers':
                            return ['编辑服务器权限', '/admin.edit.adminservers.php'];
                        default:
                            return ['管理管理员', '/admin.admins.php'];
                    }
                case 'servers':
                    CheckAdminAccess(ADMIN_OWNER|ADMIN_LIST_SERVERS|ADMIN_ADD_SERVER|ADMIN_EDIT_SERVERS|ADMIN_DELETE_SERVERS);
                    switch ($option) {
                        case 'edit':
                            return ['编辑服务器', '/admin.edit.server.php'];
                        case 'rcon':
                            return ['服务器 RCON', '/admin.rcon.php'];
                        case 'admincheck':
                            return ['服务器管理员', '/admin.srvadmins.php'];
                        default:
                            return ['服务器管理', '/admin.servers.php'];
                    }
                case 'bans':
                    CheckAdminAccess(ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|ADMIN_EDIT_ALL_BANS|ADMIN_BAN_PROTESTS|ADMIN_BAN_SUBMISSIONS);
                    switch ($option) {
                        case 'edit':
                            return ['编辑封禁资料', '/admin.edit.ban.php'];
                        case 'email':
                            return ['邮箱', '/admin.email.php'];
                        default:
                            return ['封禁列表', '/admin.bans.php'];
                    }
                case 'comms':
                    CheckAdminAccess(ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_ALL_BANS);
                    switch ($option) {
                        case 'edit':
                            return ['编辑禁言资料', '/admin.edit.comms.php'];
                        default:
                            return ['禁言列表', '/admin.comms.php'];
                    }
                case 'mods':
                    CheckAdminAccess(ADMIN_OWNER|ADMIN_LIST_MODS|ADMIN_ADD_MODS|ADMIN_EDIT_MODS|ADMIN_DELETE_MODS);
                    switch ($option) {
                        case 'edit':
                            return ['编辑模组资料', '/admin.edit.mod.php'];
                        default:
                            return ['管理模组列表', '/admin.mods.php'];
                    }
                case 'settings':
                    CheckAdminAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS);
                    return ['SourceBans++ 设置', '/admin.settings.php'];
                default:
                    CheckAdminAccess(ALL_WEB);
                    return ['管理员', '/page.admin.php'];
        }
        default:
            switch ($fallback) {
                case 1:
                    $_GET['p'] = 'banlist';
                    return ['封禁名单', '/page.banlist.php'];
                case 2:
                    $_GET['p'] = 'servers';
                    return ['服务器信息', '/page.servers.php'];
                case 3:
                    $_GET['p'] = 'submit';
                    return ['请求封禁', '/page.submit.php'];
                case 4:
                    $_GET['p'] = 'protest';
                    return ['抗议封禁', '/page.protest.php'];
                default:
                    $_GET['p'] = 'home';
                    return ['概览', '/page.home.php'];
            }
    }
}

/**
 * @param null $title Unused
 * @param string $page
 */
function build(string $title, string $page)
{
    require_once(TEMPLATES_PATH.'/core/header.php');
    require_once(TEMPLATES_PATH.'/core/navbar.php');
    require_once(TEMPLATES_PATH.'/core/title.php');
    require_once(TEMPLATES_PATH.$page);
    require_once(TEMPLATES_PATH.'/core/footer.php');
}
