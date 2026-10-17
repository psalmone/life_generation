<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migrate extends Controller {

    public function __construct()
    {
        parent::__construct();
        $this->call->library('migration');
    }

    /**
     * Run all migrations
     * Access: /migrate
     */
    public function index()
    {
        $status = $this->migration->latest();

        $seed_msg = '';
        if ($this->io->get('seed') == '1' || $this->io->get('seed') == 'true') {
            require_once APP_DIR . 'seeds/DatabaseSeeder.php';
            $seeder = new DatabaseSeeder();
            $seeder->run();
            $seed_msg = '<p style="color: #4ade80;">✓ Seed data injected successfully (Admin & Demo users created).</p>';
        }

        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Database Migration & Seeding Status</title>
            <style>
                body {
                    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                    background: #0f172a;
                    color: #f8fafc;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    margin: 0;
                    padding: 20px;
                }
                .card {
                    background: rgba(30, 41, 59, 0.85);
                    backdrop-filter: blur(16px);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    border-radius: 16px;
                    padding: 32px;
                    max-width: 540px;
                    width: 100%;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                }
                .badge {
                    display: inline-block;
                    padding: 6px 14px;
                    border-radius: 9999px;
                    font-size: 0.875rem;
                    font-weight: 600;
                    background: rgba(34, 197, 94, 0.2);
                    color: #4ade80;
                    border: 1px solid rgba(74, 222, 128, 0.3);
                    margin-bottom: 16px;
                }
                h1 { margin-top: 0; font-size: 1.5rem; color: #fff; }
                p { color: #94a3b8; line-height: 1.6; }
                .btn-group { display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap; }
                .btn {
                    display: inline-block;
                    padding: 12px 20px;
                    background: linear-gradient(135deg, #6366f1, #8b5cf6);
                    color: #fff;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: opacity 0.2s;
                }
                .btn-outline {
                    background: rgba(255, 255, 255, 0.08);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    color: #fff;
                }
                .btn:hover { opacity: 0.9; }
            </style>
        </head>
        <body>
            <div class='card'>
                <span class='badge'>Database Migration</span>
                <h1>Migration Completed</h1>
                <p>Status: " . ($status ? 'Successfully processed migrations.' : 'Migrations evaluated.') . "</p>
                {$seed_msg}
                <p>The <code>users</code> table schema is ready with bcrypt password hashing support.</p>
                <div class='btn-group'>
                    <a href='" . site_url('migrate?seed=1') . "' class='btn btn-outline'>Seed Database (Default Users)</a>
                    <a href='" . site_url('login') . "' class='btn'>Go to Login Page &rarr;</a>
                </div>
            </div>
        </body>
        </html>";
    }
}
