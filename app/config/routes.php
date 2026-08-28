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

// Prayer Wall routes
$router->get('/prayer-wall', 'Prayer_wall::index');
$router->post('/prayer-wall/submit', 'Prayer_wall::submit');
$router->post('/prayer-wall/mark-answered', 'Prayer_wall::mark_answered');
$router->post('/prayer-wall/delete', 'Prayer_wall::delete');

// Ministry Volunteer routes
$router->post('/ministry/apply', 'Auth::apply_ministry');
$router->post('/ministry/cancel', 'Auth::cancel_ministry');
$router->post('/admin/ministry/update-application', 'Auth::update_ministry_application');

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