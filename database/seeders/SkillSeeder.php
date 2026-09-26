<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Programming Languages
            'PHP', 'Java', 'Python', 'JavaScript', 'TypeScript', 'C', 'C++', 'C#', 'Go', 'Dart', 'Kotlin', 'Swift', 'Ruby', 'Rust', 'Scala', 'R', 'Objective-C', 'Assembly', 'Perl', 'Lua', 'Haskell', 'Julia', 'Elixir', 'Clojure', 'F#', 'Erlang',
            
            // Web Development (Frontend)
            'HTML', 'CSS', 'React', 'Next.js', 'Vue.js', 'Nuxt.js', 'Angular', 'Svelte', 'SvelteKit', 'Bootstrap', 'Tailwind CSS', 'Material UI', 'Chakra UI', 'Ant Design', 'Bulma', 'Foundation', 'Sass', 'Less', 'Webpack', 'Vite', 'Babel', 'jQuery', 'Alpine.js', 'HTMX',
            
            // Web Development (Backend)
            'Laravel', 'CodeIgniter', 'Symfony', 'Yii', 'CakePHP', 'Spring Boot', 'Django', 'Flask', 'FastAPI', 'Node.js', 'Express.js', 'NestJS', 'Koa', 'Ruby on Rails', 'ASP.NET Core', 'Gin', 'Echo', 'Fiber',
            
            // Mobile Development
            'Flutter', 'React Native', 'Android Studio', 'SwiftUI', 'Ionic', 'Xamarin', 'Cordova', 'Capacitor', 'NativeScript', 'Appcelerator',
            
            // Database Management
            'MySQL', 'PostgreSQL', 'MongoDB', 'SQLite', 'Redis', 'MariaDB', 'Oracle', 'SQL Server', 'Cassandra', 'CouchDB', 'DynamoDB', 'Firebase Realtime Database', 'Firestore', 'Supabase', 'Neo4j', 'Elasticsearch', 'Mongoose', 'Prisma', 'TypeORM', 'Sequelize', 'Doctrine',
            
            // Cloud & Serverless
            'AWS', 'Azure', 'Google Cloud Platform (GCP)', 'DigitalOcean', 'Heroku', 'Vercel', 'Netlify', 'Firebase', 'AWS Lambda', 'Google Cloud Functions', 'Azure Functions', 'Cloudflare Workers',
            
            // AI, Machine Learning & Data Science
            'Machine Learning', 'Deep Learning', 'Data Mining', 'Natural Language Processing (NLP)', 'Computer Vision', 'Pandas', 'NumPy', 'Scikit-learn', 'TensorFlow', 'Keras', 'PyTorch', 'Jupyter', 'Apache Spark', 'Hadoop', 'Data Analysis', 'Data Visualization', 'Tableau', 'Power BI',
            
            // DevOps & CI/CD
            'Docker', 'Kubernetes', 'Git', 'GitHub Actions', 'GitLab CI', 'Jenkins', 'Travis CI', 'CircleCI', 'Ansible', 'Terraform', 'Puppet', 'Chef', 'Linux Administration', 'Nginx', 'Apache HTTP Server', 'Prometheus', 'Grafana',
            
            // Cyber Security
            'Penetration Testing', 'Network Security', 'Digital Forensics', 'Cryptography', 'Ethical Hacking', 'OWASP', 'Burp Suite', 'Metasploit', 'Wireshark', 'Nmap',
            
            // UI/UX & Graphic Design
            'Figma', 'Adobe XD', 'Adobe Photoshop', 'Adobe Illustrator', 'Sketch', 'InVision', 'Zeplin', 'Framer', 'Prototyping', 'Wireframing', 'User Research', 'Usability Testing', 'Typography', 'Color Theory',
            
            // Multimedia & Game Development
            'Unity', 'Unreal Engine', 'Godot', 'Blender', 'Maya', '3ds Max', 'Cinema 4D', 'Adobe Premiere Pro', 'Adobe After Effects', 'Final Cut Pro', 'DaVinci Resolve', 'Audio Editing', 'Video Editing', '3D Modeling', 'Animation',
            
            // Testing
            'Unit Testing', 'Integration Testing', 'E2E Testing', 'PHPUnit', 'Pest', 'Jest', 'Mocha', 'Chai', 'Cypress', 'Selenium', 'Playwright', 'Puppeteer',
            
            // Architecture & Principles
            'RESTful APIs', 'GraphQL', 'gRPC', 'Microservices', 'Monolithic Architecture', 'MVC', 'SOLID Principles', 'Design Patterns', 'TDD', 'BDD', 'Clean Code',
            
            // Tools & Others
            'Postman', 'Insomnia', 'Swagger / OpenAPI', 'Jira', 'Trello', 'Asana', 'Notion', 'Agile / Scrum', 'Kanban', 'WordPress', 'SEO', 'Google Analytics'
        ];

        foreach ($skills as $skillName) {
            Skill::firstOrCreate([
                'nama_skill' => $skillName
            ]);
        }
    }
}
