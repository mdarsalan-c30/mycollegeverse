<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'title' => 'About MyCollegeVerse',
                'slug' => 'about-us',
                'content' => "<h1>Welcome to the Multiverse.</h1><p>MyCollegeVerse is the ultimate Academic Identity Platform (College OS) designed for every student seeking structure in their academic journey. We provide a centralized hub for verified notes, community discussions, professor reviews, and career opportunities.</p><p>Our mission is to build the most comprehensive academic networking node in the world, starting with your campus.</p>",
                'meta_description' => 'Learn more about MyCollegeVerse, the ultimate College OS and Academic Identity Platform.',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => "<h1>Security & Privacy Protocol.</h1><p>Your data is your academic property. At MyCollegeVerse, we prioritize the encryption and protection of your student node. We do not sell your personal data to third parties.</p><p>By using the Multiverse, you agree to the collection of necessary data metrics to improve your academic experience.</p>",
                'meta_description' => 'Read the MyCollegeVerse Privacy Policy to understand how we protect your academic data.',
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => "<h1>The Multiverse Agreement.</h1><p>By entering MyCollegeVerse, you agree to abide by our Community Guidelines. Spamming, harassment, and the distribution of unauthorized intellectual property are strictly prohibited.</p><p>Violation of these terms may result in your identity being terminated from the Multiverse system.</p>",
                'meta_description' => 'Review the official Terms of Service for using the MyCollegeVerse platform.',
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => "<h1>Reach the Command Center.</h1><p>Have a query or need to report a node malfunction? Our Master Authority is ready to assist you.</p><p><strong>Email:</strong> admin@mycollegeverse.in<br><strong>Location:</strong> Digital Multiverse HQ</p>",
                'meta_description' => 'Get in touch with the MyCollegeVerse support team and Master Authority.',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
