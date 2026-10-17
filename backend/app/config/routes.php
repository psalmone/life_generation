<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/
/** @var object $router **/

$router->get('/', 'Auth::home');
$router->get('/home', 'Auth::home');
$router->get('/login', 'Auth::login');
$router->post('/login', 'Auth::login_submit');
$router->get('/register', 'Auth::register');
$router->post('/register', 'Auth::register_submit');
$router->get('/dashboard', 'Auth::dashboard');
$router->post('/dashboard/update-profile', 'Auth::update_profile');
$router->post('/dashboard/upload-avatar', 'Auth::upload_avatar');
$router->post('/dashboard/remove-avatar', 'Auth::remove_avatar');
$router->post('/admin/toggle-member-status', 'Auth::toggle_member_status');
$router->get('/logout', 'Auth::logout');

// Migration trigger route
$router->get('/migrate', 'Migrate::index');

// D-Group routes
$router->post('/dgroups/join', 'Auth::join_dgroup');
$router->post('/dgroups/leave', 'Auth::leave_dgroup');
$router->post('/admin/dgroups/create', 'Auth::create_dgroup');
$router->post('/admin/dgroups/update', 'Auth::update_dgroup');
$router->post('/admin/dgroups/delete', 'Auth::delete_dgroup');


// Prayer Wall routes
$router->get('/prayer-wall', 'Prayer_wall::index');
$router->post('/prayer-wall/submit', 'Prayer_wall::submit');
$router->post('/prayer-wall/mark-answered', 'Prayer_wall::mark_answered');
$router->post('/prayer-wall/delete', 'Prayer_wall::delete');

// Ministry Volunteer & Management routes
$router->post('/ministry/apply', 'Auth::apply_ministry');
$router->post('/ministry/cancel', 'Auth::cancel_ministry');
$router->post('/admin/ministry/update-application', 'Auth::update_ministry_application');
$router->post('/admin/ministry/delete-application', 'Auth::delete_ministry_application');
$router->get('/admin/export-volunteer-requests', 'Auth::export_ministry_apps_csv');
$router->post('/admin/ministries/create', 'Auth::create_ministry');
$router->post('/admin/ministries/update', 'Auth::update_ministry');
$router->post('/admin/ministries/delete', 'Auth::delete_ministry');

// Event & Attendance routes
$router->post('/events/rsvp', 'Auth::rsvp_event');
$router->post('/events/cancel-rsvp', 'Auth::cancel_rsvp_event');
$router->post('/admin/events/create', 'Auth::create_event');
$router->post('/admin/events/update', 'Auth::update_event');
$router->post('/admin/events/delete', 'Auth::delete_event');
$router->post('/admin/events/check-in', 'Auth::check_in_attendee');

// Member Management & Export routes
$router->post('/admin/update-member-role', 'Auth::update_member_role');
$router->get('/admin/export-members', 'Auth::export_members_csv');

// =============================================================================
//  REST API ROUTES (for React frontend)
// =============================================================================

// Public (no auth)
$router->get('/api/public/home', 'Api_public::home');

// Auth API
$router->post('/api/auth/login',    'Api_auth::login');
$router->post('/api/auth/register', 'Api_auth::register');
$router->post('/api/auth/google',   'Api_auth::google');
$router->get('/api/auth/me',        'Api_auth::me');
$router->post('/api/auth/refresh',  'Api_auth::refresh');
$router->post('/api/auth/logout',   'Api_auth::logout');

// Members API
$router->get('/api/members/dashboard',      'Api_members::dashboard');
$router->post('/api/members/update-profile','Api_members::update_profile');
$router->post('/api/members/upload-avatar', 'Api_members::upload_avatar');
$router->post('/api/members/remove-avatar', 'Api_members::remove_avatar');
$router->post('/api/members/toggle-status', 'Api_members::toggle_status');
$router->post('/api/members/update-role',   'Api_members::update_role');

// Events API
$router->get('/api/events',             'Api_events::index');
$router->post('/api/events/rsvp',       'Api_events::rsvp');
$router->post('/api/events/cancel-rsvp','Api_events::cancel_rsvp');
$router->post('/api/events/create',     'Api_events::create');
$router->post('/api/events/update',     'Api_events::update');
$router->post('/api/events/delete',     'Api_events::delete');
$router->post('/api/events/check-in',   'Api_events::check_in');

// D-Groups API
$router->get('/api/dgroups',         'Api_dgroups::index');
$router->post('/api/dgroups/join',   'Api_dgroups::join');
$router->post('/api/dgroups/leave',  'Api_dgroups::leave');
$router->post('/api/dgroups/create', 'Api_dgroups::create');
$router->post('/api/dgroups/update', 'Api_dgroups::update');
$router->post('/api/dgroups/delete', 'Api_dgroups::delete');

// Ministries API
$router->get('/api/ministries',                      'Api_ministries::index');
$router->post('/api/ministries/apply',               'Api_ministries::apply');
$router->post('/api/ministries/cancel',              'Api_ministries::cancel');
$router->post('/api/ministries/update-application',  'Api_ministries::update_application');
$router->post('/api/ministries/delete-application',  'Api_ministries::delete_application');
$router->post('/api/ministries/create',              'Api_ministries::create');
$router->post('/api/ministries/update',              'Api_ministries::update');
$router->post('/api/ministries/delete',              'Api_ministries::delete');

// Prayer Wall API
$router->get('/api/prayer',               'Api_prayer::index');
$router->post('/api/prayer/submit',        'Api_prayer::submit');
$router->post('/api/prayer/mark-answered', 'Api_prayer::mark_answered');
$router->post('/api/prayer/delete',        'Api_prayer::delete');