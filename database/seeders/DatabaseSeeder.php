<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sample rich posts.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Mastering Laravel 12 & Client-Side Frameworks',
                'content' => '<h2>The Power of Modern Full-Stack Development</h2><p>Combining the robust backend structure of Laravel 12 with lightweight reactive client-side libraries enables high-performance web applications.</p><blockquote>"Simplicity is prerequisite for reliability." - Edsger W. Dijkstra</blockquote><ul><li>Clean REST API endpoints</li><li>Instant Two-Way Data Binding</li><li>Safe, decoupled architectures</li></ul>',
                'status' => 'Published',
                'created_at' => now()->subDays(3),
            ],
            [
                'title' => 'Product Release Notes: Version 2.0.0 Live Content Studio',
                'content' => '<h2>🚀 Major Update - Content Studio v2.0</h2><p>We are delighted to release our new editor suite featuring <strong>Split-Screen Live Preview</strong>, <strong>LocalStorage Crash Recovery</strong>, and <strong>1-Click Templates</strong>.</p><h3>What is New?</h3><ul><li>Real-time word and reading time synchronization</li><li>Auto-recovery of unsaved drafts</li><li>Mobile-responsive reading layouts</li></ul>',
                'status' => 'Published',
                'created_at' => now()->subDays(1),
            ],
            [
                'title' => 'Draft: Future of AI in Automated Code Generation',
                'content' => '<p>Preliminary brainstorming notes on how agentic coding assistants integrate with pair programming workflows. Testing draft auto-save and content formatting.</p>',
                'status' => 'Draft',
                'created_at' => now(),
            ],
        ];

        foreach ($posts as $post) {
            Post::firstOrCreate(
                ['title' => $post['title']],
                $post
            );
        }
    }
}
