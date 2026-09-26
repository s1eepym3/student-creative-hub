<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Software Development
            'Software Development', 'Web Development', 'Mobile Application', 'Desktop Application',
            'Enterprise Application', 'Software Engineering', 'Full Stack Development',
            'Backend Development', 'Frontend Development', 'API Development', 'Microservices',
            'Software Testing', 'Quality Assurance', 'Open Source Project',

            // Artificial Intelligence
            'Artificial Intelligence', 'Machine Learning', 'Deep Learning', 'Data Science',
            'Data Analytics', 'Data Mining', 'Business Intelligence', 'Big Data',
            'Natural Language Processing', 'Computer Vision', 'Recommendation System',
            'Predictive Analytics', 'Generative AI', 'Chatbot Development', 'Expert System',

            // Cyber Security
            'Cyber Security', 'Ethical Hacking', 'Penetration Testing', 'Information Security',
            'Network Security', 'Cloud Security', 'Application Security', 'Digital Forensics',
            'Incident Response', 'Vulnerability Assessment',

            // Computer Networking
            'Computer Networking', 'Cloud Computing', 'DevOps', 'System Administration',
            'Infrastructure Engineering', 'Server Management', 'Virtualization',
            'Network Monitoring', 'Data Center Technology',

            // Internet of Things (IoT)
            'Internet of Things (IoT)', 'Embedded Systems', 'Arduino Project', 'ESP32 Project',
            'Raspberry Pi Project', 'Smart Home', 'Smart Agriculture', 'Smart Energy',
            'Industrial IoT',

            // Multimedia
            'Multimedia', 'Graphic Design', 'UI/UX Design', 'Animation', '3D Modeling',
            'Motion Graphics', 'Video Production', 'Photography', 'Digital Content Creation',
            'Interactive Media',

            // Game Development
            'Game Development', 'Game Design', 'Serious Game', 'Educational Game',
            'Augmented Reality (AR)', 'Virtual Reality (VR)', 'Mixed Reality (MR)', 'Metaverse Project',

            // Information System
            'Information System', 'Management Information System', 'Decision Support System',
            'Executive Information System', 'Accounting Information System',
            'Human Resource Information System', 'Academic Information System',
            'Inventory Information System', 'Healthcare Information System',

            // E-Commerce
            'E-Commerce', 'E-Business', 'Digital Marketing', 'Customer Relationship Management (CRM)',
            'Enterprise Resource Planning (ERP)', 'Supply Chain Management', 'Financial Technology (FinTech)',
            'Business Process Automation', 'Digital Transformation',

            // Research Project
            'Research Project', 'Final Project', 'Capstone Project', 'Kerja Praktek',
            'Thesis Project', 'Scientific Publication', 'Community Service Project', 'Educational Technology',

            // Robotics
            'Robotics', 'Automation System', 'Industrial Automation', 'Autonomous Vehicle',
            'Drone Technology', 'Computer Control System',

            // Blockchain
            'Blockchain', 'Web3', 'Smart Contract', 'Cryptography', 'Digital Twin',
            'Industry 4.0', 'Quantum Computing', 'Edge Computing', 'Green Computing',

            // Mobile Apps
            'Android Development', 'iOS Development', 'Cross Platform Development',
            'Wearable Technology', 'Smart Device Application',

            // Cloud Tech
            'AWS Project', 'Google Cloud Project', 'Microsoft Azure Project', 'Firebase Project',
            'Supabase Project', 'Cloud Native Application', 'Serverless Computing',

            // Data Visualization
            'Data Visualization', 'Dashboard Development', 'Geographic Information System (GIS)',
            'Location Intelligence', 'Spatial Data Analysis',

            // Audio & Video
            'Audio Processing', 'Video Editing', 'Broadcast Technology', 'Digital Broadcasting',
            'Streaming Platform', 'Virtual Production', 'Computer Graphics', '3D Animation',
            'Visual Effects (VFX)',

            // Specific Projects
            'Competition Project', 'Hackathon Project', 'Startup Project', 'Student Innovation',
            'Research Innovation', 'Community Impact Project', 'Portfolio Project', 'Freelance Project',
            'Internship Project'
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'nama_kategori' => trim($category),
            ]);
        }
    }
}
