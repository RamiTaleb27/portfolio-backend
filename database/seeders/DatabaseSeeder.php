<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // // Create admin user
        // User::create([
        //     'name' => 'Rami',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('password'),
        // ]);

        // Seed projects
        Project::create([
            'name' => 'MarketHub',
            'slug' => 'markethub',
            'tagline' => 'Multi-vendor E-commerce Platform',
            'description' => 'A full-featured marketplace with vendor onboarding, product management, Stripe payments, and real-time order tracking.',
            'featured' => true,
            'status' => 'published',
            'tags' => ['Laravel', 'React', 'MySQL', 'Stripe', 'Redis'],
            'live_url' => '#',
            'github_url' => '#',
            'sort_order' => 1,
        ]);

        Project::create([
            'name' => 'TaskFlow',
            'slug' => 'taskflow',
            'tagline' => 'Project Management SaaS',
            'description' => 'Kanban-style task manager with real-time collaboration, team roles, and drag-and-drop boards.',
            'featured' => false,
            'status' => 'published',
            'tags' => ['React', 'Laravel', 'Pusher', 'Tailwind'],
            'live_url' => '#',
            'github_url' => '#',
            'sort_order' => 2,
        ]);

        Project::create([
            'name' => 'DevBlog',
            'slug' => 'devblog',
            'tagline' => 'Headless CMS + Blog Engine',
            'description' => 'Custom content management system with markdown editor, image uploads, and SEO optimization.',
            'featured' => false,
            'status' => 'published',
            'tags' => ['Laravel', 'React', 'AWS S3', 'MySQL'],
            'github_url' => '#',
            'sort_order' => 3,
        ]);

        // Seed skills
        $skills = [
            ['name' => 'React.js', 'category' => 'Frontend', 'color' => 'cyan'],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'color' => 'cyan'],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'color' => 'cyan'],
            ['name' => 'HTML5 & CSS3', 'category' => 'Frontend', 'color' => 'cyan'],
            ['name' => 'Framer Motion', 'category' => 'Frontend', 'color' => 'amber'],
            ['name' => 'Laravel', 'category' => 'Backend', 'color' => 'amber'],
            ['name' => 'PHP', 'category' => 'Backend', 'color' => 'amber'],
            ['name' => 'REST APIs', 'category' => 'Backend', 'color' => 'amber'],
            ['name' => 'MySQL', 'category' => 'Database', 'color' => 'green'],
            ['name' => 'Database Design', 'category' => 'Database', 'color' => 'green'],
            ['name' => 'Git & GitHub', 'category' => 'DevOps', 'color' => 'slate'],
            ['name' => 'Docker', 'category' => 'DevOps', 'color' => 'blue'],
        ];

        foreach ($skills as $index => $skill) {
            Skill::create([...$skill, 'sort_order' => $index + 1]);
        }
    }
}