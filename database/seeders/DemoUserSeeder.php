<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\CorporateProfile;
use App\Models\Industry;
use App\Models\Milestone;
use App\Models\StartupProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@scp.test'],
            ['name' => 'Platform Admin', 'password' => Hash::make('password'), 'role' => 'admin', 'status' => 'approved']
        );

        $industries = Industry::all()->keyBy('slug');

        // Startups
        $startups = [
            [
                'name' => 'Priya Sharma', 'email' => 'priya@neuralcart.test',
                'company_name' => 'NeuralCart AI', 'industry' => 'artificial-intelligence',
                'stage' => 'mvp', 'team_size' => 8, 'founded_year' => 2023,
                'pitch' => 'AI-powered personalized shopping assistant that increases e-commerce conversion by 45%. Our deep learning models analyze user behavior in real-time to deliver hyper-relevant product recommendations.',
                'tech_tags' => ['Machine Learning', 'Python', 'TensorFlow', 'NLP', 'AWS'],
                'seeking' => ['investment', 'pilot'],
                'funding_status' => 'seed', 'funding_amount' => 5000000,
                'budget_min' => 200000, 'budget_max' => 1000000,
                'city' => 'Bangalore', 'state' => 'Karnataka',
            ],
            [
                'name' => 'Arjun Mehta', 'email' => 'arjun@medisync.test',
                'company_name' => 'MediSync', 'industry' => 'healthtech',
                'stage' => 'growth', 'team_size' => 15, 'founded_year' => 2022,
                'pitch' => 'Cloud-based hospital management platform serving 50+ hospitals across India. Our software reduces administrative overhead by 60% and improves patient flow significantly.',
                'tech_tags' => ['Cloud Computing', 'React', 'Node.js', 'HIPAA', 'Healthcare'],
                'seeking' => ['investment', 'pilot', 'mentorship'],
                'funding_status' => 'seed', 'funding_amount' => 12000000,
                'budget_min' => 500000, 'budget_max' => 3000000,
                'city' => 'Mumbai', 'state' => 'Maharashtra',
            ],
            [
                'name' => 'Sneha Reddy', 'email' => 'sneha@finflow.test',
                'company_name' => 'FinFlow', 'industry' => 'fintech',
                'stage' => 'mvp', 'team_size' => 6, 'founded_year' => 2024,
                'pitch' => 'Embedded finance API platform that lets any business launch banking products in days, not months. Building the financial infrastructure layer for the next 10,000 Indian startups.',
                'tech_tags' => ['API', 'FinTech', 'Banking', 'Python', 'Postgres'],
                'seeking' => ['investment', 'pilot'],
                'funding_status' => 'pre_seed', 'funding_amount' => 2500000,
                'budget_min' => 300000, 'budget_max' => 1500000,
                'city' => 'Bangalore', 'state' => 'Karnataka',
            ],
            [
                'name' => 'Vikram Singh', 'email' => 'vikram@greenharvest.test',
                'company_name' => 'GreenHarvest', 'industry' => 'agritech',
                'stage' => 'growth', 'team_size' => 25, 'founded_year' => 2021,
                'pitch' => 'IoT-powered precision farming platform helping 10,000+ farmers boost yield by 35% using soil sensors, weather AI, and crop advisory. Direct-to-consumer marketplace included.',
                'tech_tags' => ['IoT', 'Machine Learning', 'Agriculture', 'Mobile App', 'Sensors'],
                'seeking' => ['investment', 'mentorship'],
                'funding_status' => 'series_a', 'funding_amount' => 35000000,
                'budget_min' => 1000000, 'budget_max' => 5000000,
                'city' => 'Pune', 'state' => 'Maharashtra',
            ],
            [
                'name' => 'Anjali Kapoor', 'email' => 'anjali@edusphere.test',
                'company_name' => 'EduSphere', 'industry' => 'edtech',
                'stage' => 'mvp', 'team_size' => 10, 'founded_year' => 2023,
                'pitch' => 'AI-driven personalized learning platform for K-12 students. Adaptive curriculum that adjusts to each student\'s pace and learning style. Currently serving 50,000+ students.',
                'tech_tags' => ['AI', 'EdTech', 'Mobile', 'React Native', 'Personalization'],
                'seeking' => ['investment', 'pilot', 'mentorship'],
                'funding_status' => 'seed', 'funding_amount' => 8000000,
                'budget_min' => 400000, 'budget_max' => 2000000,
                'city' => 'Delhi', 'state' => 'Delhi',
            ],
            [
                'name' => 'Rohan Verma', 'email' => 'rohan@securenet.test',
                'company_name' => 'SecureNet', 'industry' => 'cybersecurity',
                'stage' => 'growth', 'team_size' => 18, 'founded_year' => 2022,
                'pitch' => 'Zero-trust cybersecurity platform for enterprises. AI-powered threat detection that catches 99.7% of attacks before they cause damage. Trusted by 30+ Fortune 500 companies.',
                'tech_tags' => ['Cybersecurity', 'AI', 'Zero Trust', 'Cloud Security', 'Enterprise'],
                'seeking' => ['investment', 'pilot'],
                'funding_status' => 'series_a', 'funding_amount' => 45000000,
                'budget_min' => 1500000, 'budget_max' => 8000000,
                'city' => 'Hyderabad', 'state' => 'Telangana',
            ],
        ];

        foreach ($startups as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password'), 'role' => 'startup', 'status' => 'approved']
            );

            $profile = StartupProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $data['company_name'],
                    'industry_id' => $industries[$data['industry']]?->id,
                    'stage' => $data['stage'],
                    'team_size' => $data['team_size'],
                    'founded_year' => $data['founded_year'],
                    'elevator_pitch' => $data['pitch'],
                    'tech_tags' => $data['tech_tags'],
                    'seeking' => $data['seeking'],
                    'funding_status' => $data['funding_status'],
                    'funding_amount' => $data['funding_amount'],
                    'budget_min' => $data['budget_min'],
                    'budget_max' => $data['budget_max'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                ]
            );

            // Add some milestones
            $milestones = [
                ['title' => 'Company Founded', 'description' => 'Incorporated and assembled founding team', 'date' => Carbon::create($data['founded_year'], 1, 1), 'icon' => 'rocket'],
                ['title' => 'First MVP Launch', 'description' => 'Released initial version to beta users', 'date' => Carbon::create($data['founded_year'], 6, 15), 'icon' => 'flag'],
                ['title' => 'First 1000 Users', 'description' => 'Crossed 1000 active users milestone', 'date' => Carbon::create($data['founded_year'] + 1, 2, 10), 'icon' => 'users'],
            ];
            foreach ($milestones as $i => $m) {
                Milestone::firstOrCreate([
                    'startup_profile_id' => $profile->id,
                    'title' => $m['title'],
                ], [
                    'description' => $m['description'],
                    'milestone_date' => $m['date'],
                    'icon' => $m['icon'],
                    'sort_order' => $i,
                ]);
            }
        }

        // Corporates
        $corporates = [
            [
                'name' => 'Rajesh Kumar', 'email' => 'rajesh@tatadigital.test',
                'company_name' => 'TATA Digital Innovations', 'industry' => 'e-commerce',
                'size' => 'enterprise',
                'about' => 'India\'s largest digital conglomerate, building the super-app ecosystem for 100 million users.',
                'problem' => 'We are actively seeking AI/ML startups that can help us personalize customer experience across our retail, finance, and entertainment verticals. Looking for solutions in recommendation engines, churn prediction, and conversational AI.',
                'partnership_types' => ['investment', 'pilot'],
                'seeking_tech' => ['Machine Learning', 'AI', 'NLP', 'Personalization'],
                'seeking_stages' => ['mvp', 'growth'],
                'budget_min' => 500000, 'budget_max' => 5000000,
                'city' => 'Mumbai', 'state' => 'Maharashtra',
            ],
            [
                'name' => 'Sunita Iyer', 'email' => 'sunita@apollohealthcare.test',
                'company_name' => 'Apollo Healthcare Ventures', 'industry' => 'healthtech',
                'size' => 'large',
                'about' => 'Leading healthcare provider with 70+ hospitals. Innovation arm focused on transforming patient care through technology.',
                'problem' => 'Looking for HealthTech startups to partner on hospital management software, telemedicine platforms, and AI-based diagnostic tools. Especially interested in solutions that can scale across our hospital network.',
                'partnership_types' => ['pilot', 'investment', 'acquisition'],
                'seeking_tech' => ['Cloud Computing', 'AI', 'Healthcare', 'Mobile App'],
                'seeking_stages' => ['mvp', 'growth', 'scale'],
                'budget_min' => 1000000, 'budget_max' => 10000000,
                'city' => 'Chennai', 'state' => 'Tamil Nadu',
            ],
            [
                'name' => 'Karan Malhotra', 'email' => 'karan@hdfclabs.test',
                'company_name' => 'HDFC Innovation Labs', 'industry' => 'fintech',
                'size' => 'enterprise',
                'about' => 'Innovation division of HDFC, India\'s largest private bank. We invest in and partner with FinTech startups disrupting banking.',
                'problem' => 'Seeking FinTech startups working on embedded finance, BNPL, financial inclusion, and AI-powered credit scoring. Looking for pilot-ready solutions to deploy across our customer base.',
                'partnership_types' => ['investment', 'pilot'],
                'seeking_tech' => ['FinTech', 'API', 'AI', 'Banking', 'Machine Learning'],
                'seeking_stages' => ['mvp', 'growth'],
                'budget_min' => 1000000, 'budget_max' => 15000000,
                'city' => 'Mumbai', 'state' => 'Maharashtra',
            ],
            [
                'name' => 'Meera Joshi', 'email' => 'meera@ril-newenergy.test',
                'company_name' => 'Reliance New Energy', 'industry' => 'cleantech',
                'size' => 'enterprise',
                'about' => 'Reliance\'s clean energy initiative investing $10B+ in renewable energy, batteries, and green hydrogen.',
                'problem' => 'Looking for CleanTech and AgriTech startups working on sustainability solutions, IoT-based farming, smart grid technology, and battery innovations.',
                'partnership_types' => ['investment', 'pilot', 'mentorship'],
                'seeking_tech' => ['IoT', 'Sensors', 'Agriculture', 'Renewable Energy'],
                'seeking_stages' => ['growth', 'scale'],
                'budget_min' => 2000000, 'budget_max' => 20000000,
                'city' => 'Mumbai', 'state' => 'Maharashtra',
            ],
        ];

        foreach ($corporates as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password'), 'role' => 'corporate', 'status' => 'approved']
            );

            CorporateProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $data['company_name'],
                    'industry_id' => $industries[$data['industry']]?->id,
                    'company_size' => $data['size'],
                    'about' => $data['about'],
                    'problem_statement' => $data['problem'],
                    'partnership_types' => $data['partnership_types'],
                    'seeking_technologies' => $data['seeking_tech'],
                    'seeking_stages' => $data['seeking_stages'],
                    'budget_min' => $data['budget_min'],
                    'budget_max' => $data['budget_max'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                ]
            );
        }

        // Sample challenges
        $tata = User::where('email', 'rajesh@tatadigital.test')->first();
        $apollo = User::where('email', 'sunita@apollohealthcare.test')->first();
        $hdfc = User::where('email', 'karan@hdfclabs.test')->first();

        if ($tata) {
            Challenge::firstOrCreate(
                ['corporate_id' => $tata->id, 'title' => 'AI-Powered Customer Churn Prediction'],
                [
                    'industry_id' => $industries['artificial-intelligence']?->id,
                    'description' => 'We need an AI/ML solution that can predict customer churn across our retail vertical with 85%+ accuracy. The solution should integrate with our existing CRM and provide actionable insights for retention teams.',
                    'requirements' => 'Experience with large-scale data, Python/TensorFlow expertise, ability to deploy on AWS, real-time prediction capability.',
                    'budget_min' => 500000, 'budget_max' => 2000000,
                    'deadline' => Carbon::now()->addDays(30),
                    'required_tags' => ['Machine Learning', 'AI', 'Python', 'Cloud'],
                    'status' => 'open',
                ]
            );
        }

        if ($apollo) {
            Challenge::firstOrCreate(
                ['corporate_id' => $apollo->id, 'title' => 'Telemedicine Platform for Rural Areas'],
                [
                    'industry_id' => $industries['healthtech']?->id,
                    'description' => 'Build a mobile-first telemedicine platform optimized for low-bandwidth rural areas in India. Should support 12+ regional languages and integrate with our hospital network.',
                    'requirements' => 'Mobile-first design, offline capability, multi-language support, HIPAA compliance, integration with existing systems.',
                    'budget_min' => 2000000, 'budget_max' => 8000000,
                    'deadline' => Carbon::now()->addDays(45),
                    'required_tags' => ['Healthcare', 'Mobile App', 'Telemedicine'],
                    'status' => 'open',
                ]
            );
        }

        if ($hdfc) {
            Challenge::firstOrCreate(
                ['corporate_id' => $hdfc->id, 'title' => 'Alternative Credit Scoring for Gig Workers'],
                [
                    'industry_id' => $industries['fintech']?->id,
                    'description' => 'Develop an alternative credit scoring system specifically for India\'s 300M+ gig economy workers who lack traditional credit history. Use alternative data sources (UPI, GST, mobile, etc).',
                    'requirements' => 'FinTech experience, regulatory understanding, ML expertise, data partnerships preferred.',
                    'budget_min' => 1500000, 'budget_max' => 6000000,
                    'deadline' => Carbon::now()->addDays(60),
                    'required_tags' => ['FinTech', 'Machine Learning', 'Credit Scoring'],
                    'status' => 'open',
                ]
            );
        }
    }
}
