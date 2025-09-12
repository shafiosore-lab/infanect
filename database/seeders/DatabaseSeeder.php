<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Comprehensive database seeding that handles all table creation and data insertion.
     * Enhanced with additional development features and sample data.
     */
    public function run(): void
    {
        // Disable foreign key checks for clean setup
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Step 1: Clean up any problematic migration entries
            $this->cleanupMigrationTable();

            // Step 2: Create all required tables
            $this->createAllTables();

            // Step 3: Insert roles data
            $this->insertRoles();

            // Step 4: Insert test users
            $this->insertUsers();

            // Step 5: Create provider profiles
            $this->createProviderProfiles();

            // Step 6: Create additional tables if needed
            $this->createOptionalTables();

            // Step 7: Create sample development data
            $this->createDevelopmentSampleData();

        } catch (\Exception $e) {
            $this->command->error('Database seeding failed: ' . $e->getMessage());
            throw $e;
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('✅ Complete database setup finished successfully!');
        $this->displayTestAccounts();
    }

    /**
     * Clean up problematic migration entries - ENHANCED
     */
    private function cleanupMigrationTable(): void
    {
        if (Schema::hasTable('migrations')) {
            // Remove ALL custom migration entries that could cause conflicts
            $problematicPatterns = [
                '%2025%', '%2024_01_01%', '%add_role%', '%ensure_users%',
                '%fix_users%', '%create_roles%', '%update_users%',
                '%modify_users%', '%providers%', '%role_id%',
                '%doctrine%', '%schema_manager%'
            ];

            foreach ($problematicPatterns as $pattern) {
                $deleted = DB::table('migrations')->where('migration', 'like', $pattern)->delete();
                if ($deleted > 0) {
                    $this->command->info("Removed {$deleted} problematic migration entries matching pattern: {$pattern}");
                }
            }
        }
    }

    /**
     * Create all required tables with proper structure
     */
    private function createAllTables(): void
    {
        // Create roles table
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function ($table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 50)->unique();
                $table->text('description')->nullable();
                $table->json('permissions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['slug', 'is_active']);
                $table->index('name');
            });
        }

        // Ensure users table has all required columns
        $this->ensureUsersTableStructure();

        // Create providers table
        if (!Schema::hasTable('providers')) {
            Schema::create('providers', function ($table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('provider_type', 50)->default('provider');
                $table->string('business_name')->nullable();
                $table->text('business_description')->nullable();
                $table->string('website')->nullable();
                $table->text('address')->nullable();
                $table->string('city', 100)->nullable();
                $table->string('state', 100)->nullable();
                $table->string('country', 100)->nullable();
                $table->string('postal_code', 20)->nullable();
                $table->json('specializations')->nullable();
                $table->json('availability')->nullable();
                $table->decimal('hourly_rate', 8, 2)->nullable();
                $table->integer('years_experience')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
                $table->enum('kyc_status', ['not_started', 'pending', 'approved', 'rejected'])->default('not_started');
                $table->json('kyc_documents')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['status', 'kyc_status']);
                $table->index('provider_type');
            });
        }

        // Add foreign key constraint to users table
        $this->addUsersForeignKeys();
    }

    /**
     * Ensure users table has all required columns without conflicts
     */
    private function ensureUsersTableStructure(): void
    {
        if (Schema::hasTable('users')) {
            $columns = Schema::getColumnListing('users');

            // Add missing columns one by one
            if (!in_array('phone', $columns)) {
                try {
                    Schema::table('users', function ($table) {
                        $table->string('phone')->nullable()->after('email');
                    });
                } catch (\Exception $e) {
                    // Column might already exist or have different constraints
                }
            }

            if (!in_array('department', $columns)) {
                try {
                    Schema::table('users', function ($table) {
                        $table->string('department')->nullable()->after('phone');
                    });
                } catch (\Exception $e) {
                    // Column might already exist
                }
            }

            if (!in_array('role_id', $columns)) {
                try {
                    Schema::table('users', function ($table) {
                        $table->unsignedBigInteger('role_id')->nullable()->after('password');
                    });
                } catch (\Exception $e) {
                    // Column might already exist
                }
            }

            if (!in_array('provider_data', $columns)) {
                try {
                    Schema::table('users', function ($table) {
                        $table->json('provider_data')->nullable()->after('remember_token');
                    });
                } catch (\Exception $e) {
                    // Column might already exist
                }
            }
        }
    }

    /**
     * Add foreign key constraints safely
     */
    private function addUsersForeignKeys(): void
    {
        try {
            if (Schema::hasTable('roles') && Schema::hasTable('users')) {
                // Check if foreign key already exists
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = 'users'
                    AND COLUMN_NAME = 'role_id'
                    AND REFERENCED_TABLE_NAME = 'roles'
                ");

                if (empty($foreignKeys)) {
                    Schema::table('users', function ($table) {
                        $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
                    });
                }
            }
        } catch (\Exception $e) {
            // Foreign key might already exist or there might be data conflicts
            $this->command->warn('Could not add foreign key constraint: ' . $e->getMessage());
        }
    }

    /**
     * Insert roles data
     */
    private function insertRoles(): void
    {
        // Clear existing roles
        if (Schema::hasTable('roles')) {
            DB::table('roles')->truncate();
        }

        $roles = [
            ['id' => 1, 'name' => 'Client', 'slug' => 'client', 'description' => 'Family members and individuals seeking services', 'permissions' => '["view_activities", "book_services", "submit_mood"]', 'is_active' => 1],
            ['id' => 2, 'name' => 'Employee', 'slug' => 'employee', 'description' => 'General staff members', 'permissions' => '["view_activities", "book_services"]', 'is_active' => 1],
            ['id' => 3, 'name' => 'Provider', 'slug' => 'provider', 'description' => 'General service providers', 'permissions' => '["manage_services", "view_bookings", "manage_clients"]', 'is_active' => 1],
            ['id' => 4, 'name' => 'Professional Provider', 'slug' => 'provider-professional', 'description' => 'Healthcare professionals, therapists, medical services', 'permissions' => '["manage_services", "view_bookings", "manage_clients", "access_ai_tools", "view_mood_insights"]', 'is_active' => 1],
            ['id' => 5, 'name' => 'Bonding Provider', 'slug' => 'provider-bonding', 'description' => 'Community organizers, family activity coordinators', 'permissions' => '["manage_activities", "create_events", "manage_community"]', 'is_active' => 1],
            ['id' => 6, 'name' => 'Manager', 'slug' => 'manager', 'description' => 'Department managers with limited admin access', 'permissions' => '["view_reports", "manage_team", "approve_content"]', 'is_active' => 1],
            ['id' => 7, 'name' => 'Administrator', 'slug' => 'admin', 'description' => 'System administrators', 'permissions' => '["manage_users", "manage_system", "view_analytics", "manage_content"]', 'is_active' => 1],
            ['id' => 8, 'name' => 'Super Administrator', 'slug' => 'super-admin', 'description' => 'Full system access and control', 'permissions' => '["*"]', 'is_active' => 1]
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert(array_merge($role, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }

    /**
     * Insert test users
     */
    private function insertUsers(): void
    {
        // Clear existing users
        if (Schema::hasTable('users')) {
            DB::table('users')->truncate();
        }

        $users = [
            ['id' => 1, 'name' => 'Super Admin', 'email' => 'admin@infanect.com', 'phone' => '+1234567890', 'department' => 'Administration', 'role_id' => 8, 'password' => Hash::make('password'), 'email_verified_at' => now(), 'provider_data' => null],
            ['id' => 2, 'name' => 'Professional Provider', 'email' => 'provider@infanect.com', 'phone' => '+1234567891', 'department' => 'Healthcare', 'role_id' => 4, 'password' => Hash::make('password'), 'email_verified_at' => now(), 'provider_data' => '{"provider_type":"provider-professional","registration_stage":"approved","kyc_status":"approved","onboarding_completed":true}'],
            ['id' => 3, 'name' => 'Bonding Provider', 'email' => 'bonding@infanect.com', 'phone' => '+1234567892', 'department' => 'Community', 'role_id' => 5, 'password' => Hash::make('password'), 'email_verified_at' => now(), 'provider_data' => '{"provider_type":"provider-bonding","registration_stage":"approved","kyc_status":"approved","onboarding_completed":true}'],
            ['id' => 4, 'name' => 'Test Client', 'email' => 'client@infanect.com', 'phone' => '+1234567893', 'department' => 'Family', 'role_id' => 1, 'password' => Hash::make('password'), 'email_verified_at' => now(), 'provider_data' => null],
            ['id' => 5, 'name' => 'Test Manager', 'email' => 'manager@infanect.com', 'phone' => '+1234567894', 'department' => 'Operations', 'role_id' => 6, 'password' => Hash::make('password'), 'email_verified_at' => now(), 'provider_data' => null]
        ];

        foreach ($users as $user) {
            DB::table('users')->insert(array_merge($user, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }

    /**
     * Create provider profiles
     */
    private function createProviderProfiles(): void
    {
        // Clear existing providers
        if (Schema::hasTable('providers')) {
            DB::table('providers')->truncate();
        }

        $providers = [
            ['user_id' => 2, 'provider_type' => 'provider-professional', 'business_name' => 'Professional Provider\'s Practice', 'business_description' => 'Professional healthcare services for families', 'status' => 'approved', 'kyc_status' => 'approved', 'kyc_documents' => '{}', 'specializations' => '["Family Therapy", "Child Psychology", "Parenting Support"]', 'availability' => '["morning", "afternoon"]', 'hourly_rate' => 75.00, 'years_experience' => 5],
            ['user_id' => 3, 'provider_type' => 'provider-bonding', 'business_name' => 'Bonding Provider\'s Practice', 'business_description' => 'Community bonding and family activities', 'status' => 'approved', 'kyc_status' => 'approved', 'kyc_documents' => '{}', 'specializations' => '["Family Activities", "Community Events", "Team Building"]', 'availability' => '["morning", "afternoon", "weekend"]', 'hourly_rate' => 50.00, 'years_experience' => 3]
        ];

        foreach ($providers as $provider) {
            DB::table('providers')->insert(array_merge($provider, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }

    /**
     * Create additional tables that might be needed
     */
    private function createOptionalTables(): void
    {
        // Create mood_submissions table if it doesn't exist
        if (!Schema::hasTable('mood_submissions')) {
            try {
                Schema::create('mood_submissions', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->string('mood', 50);
                    $table->integer('mood_score')->default(5);
                    $table->json('availability')->nullable();
                    $table->json('location')->nullable();
                    $table->string('timezone', 50)->nullable();
                    $table->string('language', 10)->default('en');
                    $table->string('age_group', 50)->nullable();
                    $table->text('notes')->nullable();
                    $table->string('ip_address')->nullable();
                    $table->text('user_agent')->nullable();
                    $table->timestamps();

                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->index('mood');
                    $table->index('created_at');
                });
            } catch (\Exception $e) {
                $this->command->warn('Could not create mood_submissions table: ' . $e->getMessage());
            }
        }

        // Create bookings table if it doesn't exist
        if (!Schema::hasTable('bookings')) {
            try {
                Schema::create('bookings', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->unsignedBigInteger('provider_id')->nullable();
                    $table->unsignedBigInteger('service_id')->nullable();
                    $table->unsignedBigInteger('activity_id')->nullable();
                    $table->date('booking_date');
                    $table->time('booking_time')->nullable();
                    $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
                    $table->decimal('amount', 10, 2)->default(0);
                    $table->decimal('amount_paid', 10, 2)->default(0);
                    $table->text('notes')->nullable();
                    $table->timestamps();

                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->index(['booking_date', 'status']);
                    $table->index('provider_id');
                });
            } catch (\Exception $e) {
                $this->command->warn('Could not create bookings table: ' . $e->getMessage());
            }
        }

        // Create services table if it doesn't exist
        if (!Schema::hasTable('services')) {
            try {
                Schema::create('services', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('provider_id')->nullable();
                    $table->string('name');
                    $table->text('description')->nullable();
                    $table->decimal('price', 8, 2)->default(0);
                    $table->integer('duration_minutes')->default(60);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();

                    $table->index(['is_active', 'provider_id']);
                });
            } catch (\Exception $e) {
                $this->command->warn('Could not create services table: ' . $e->getMessage());
            }
        }

        // Create activities table if it doesn't exist
        if (!Schema::hasTable('activities')) {
            try {
                Schema::create('activities', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('provider_profile_id')->nullable();
                    $table->string('title');
                    $table->text('description')->nullable();
                    $table->datetime('start_date');
                    $table->datetime('end_date')->nullable();
                    $table->decimal('price', 8, 2)->default(0);
                    $table->integer('max_participants')->nullable();
                    $table->boolean('is_approved')->default(false);
                    $table->timestamps();

                    $table->index(['is_approved', 'start_date']);
                });
            } catch (\Exception $e) {
                $this->command->warn('Could not create activities table: ' . $e->getMessage());
            }
        }
    }

    /**
     * Create additional sample data for development testing
     */
    private function createDevelopmentSampleData(): void
    {
        // Create sample services for providers
        if (Schema::hasTable('services')) {
            DB::table('services')->truncate();

            $sampleServices = [
                ['provider_id' => 1, 'name' => 'Family Therapy Session', 'description' => 'Professional family counseling and therapy services', 'price' => 75.00, 'duration_minutes' => 60, 'is_active' => 1],
                ['provider_id' => 1, 'name' => 'Child Psychology Consultation', 'description' => 'Specialized psychological services for children', 'price' => 85.00, 'duration_minutes' => 45, 'is_active' => 1],
                ['provider_id' => 2, 'name' => 'Family Bonding Workshop', 'description' => 'Interactive workshop to strengthen family bonds', 'price' => 45.00, 'duration_minutes' => 120, 'is_active' => 1],
                ['provider_id' => 2, 'name' => 'Community Family Event', 'description' => 'Large community gathering for families', 'price' => 25.00, 'duration_minutes' => 180, 'is_active' => 1],
            ];

            foreach ($sampleServices as $service) {
                DB::table('services')->insert(array_merge($service, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }

        // Create sample activities for bonding provider
        if (Schema::hasTable('activities')) {
            DB::table('activities')->truncate();

            $sampleActivities = [
                ['provider_profile_id' => 2, 'title' => 'Weekend Family Picnic', 'description' => 'Join us for a fun family picnic in the park with games and activities', 'start_date' => now()->addDays(7), 'end_date' => now()->addDays(7)->addHours(4), 'price' => 15.00, 'max_participants' => 50, 'is_approved' => 1],
                ['provider_profile_id' => 2, 'title' => 'Parent-Child Cooking Class', 'description' => 'Learn to cook healthy meals together as a family', 'start_date' => now()->addDays(14), 'end_date' => now()->addDays(14)->addHours(3), 'price' => 35.00, 'max_participants' => 20, 'is_approved' => 1],
                ['provider_profile_id' => 2, 'title' => 'Family Art Workshop', 'description' => 'Creative art activities for the whole family', 'start_date' => now()->addDays(21), 'end_date' => now()->addDays(21)->addHours(2), 'price' => 20.00, 'max_participants' => 25, 'is_approved' => 1],
            ];

            foreach ($sampleActivities as $activity) {
                DB::table('activities')->insert(array_merge($activity, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }

        // Create sample mood submissions for testing
        if (Schema::hasTable('mood_submissions')) {
            DB::table('mood_submissions')->truncate();

            $sampleMoods = [
                ['user_id' => 4, 'mood' => 'happy', 'mood_score' => 8, 'availability' => '["morning", "afternoon"]', 'timezone' => 'UTC', 'language' => 'en'],
                ['user_id' => 4, 'mood' => 'calm', 'mood_score' => 7, 'availability' => '["evening"]', 'timezone' => 'UTC', 'language' => 'en'],
                ['user_id' => 5, 'mood' => 'stressed', 'mood_score' => 3, 'availability' => '["morning"]', 'timezone' => 'UTC', 'language' => 'en'],
            ];

            foreach ($sampleMoods as $mood) {
                DB::table('mood_submissions')->insert(array_merge($mood, [
                    'created_at' => now()->subDays(rand(1, 7)),
                    'updated_at' => now()->subDays(rand(1, 7))
                ]));
            }
        }

        // Create sample bookings
        if (Schema::hasTable('bookings')) {
            DB::table('bookings')->truncate();

            $sampleBookings = [
                ['user_id' => 4, 'provider_id' => 1, 'service_id' => 1, 'booking_date' => now()->addDays(3)->format('Y-m-d'), 'booking_time' => '10:00:00', 'status' => 'confirmed', 'amount' => 75.00, 'amount_paid' => 75.00],
                ['user_id' => 5, 'provider_id' => 2, 'activity_id' => 1, 'booking_date' => now()->addDays(7)->format('Y-m-d'), 'booking_time' => '14:00:00', 'status' => 'pending', 'amount' => 15.00, 'amount_paid' => 0.00],
            ];

            foreach ($sampleBookings as $booking) {
                DB::table('bookings')->insert(array_merge($booking, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }

        $this->command->info('📊 Sample development data created successfully!');
    }

    /**
     * Enhanced display with development information
     */
    private function displayTestAccounts(): void
    {
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('🔑 Test accounts created:');
        $this->command->info('   - Super Admin: admin@infanect.com / password');
        $this->command->info('   - Professional Provider: provider@infanect.com / password');
        $this->command->info('   - Bonding Provider: bonding@infanect.com / password');
        $this->command->info('   - Test Client: client@infanect.com / password');
        $this->command->info('   - Test Manager: manager@infanect.com / password');
        $this->command->info('');
        $this->command->info('🚀 All database tables created with proper relationships!');
        $this->command->info('📊 Provider profiles automatically set up for testing!');

        $this->command->info('🛠️ Development Features Ready:');
        $this->command->info('   - Sample services and activities created');
        $this->command->info('   - Test mood submissions added');
        $this->command->info('   - Sample bookings for testing');
        $this->command->info('   - KYC document upload system configured');
        $this->command->info('   - Role-based dashboard routing ready');
        $this->command->info('');
        $this->command->info('🔗 Key URLs:');
        $this->command->info('   - Home: /');
        $this->command->info('   - Login: /login');
        $this->command->info('   - Register: /register');
        $this->command->info('   - Provider Registration: /provider/register');
        $this->command->info('   - Professional Dashboard: /dashboard/provider-professional');
        $this->command->info('   - Bonding Dashboard: /dashboard/provider-bonding');
        $this->command->info('   - Admin Dashboard: /dashboard/super-admin');
    }
}
