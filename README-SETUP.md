# Project Setup Notes

Run these commands locally after pulling changes:

- Install composer deps (add any SDKs you need):
  composer install
  composer require barryvdh/laravel-dompdf
  composer require stripe/stripe-php

- Run migrations and seed roles:
  php artisan migrate
  php artisan db:seed --class=RoleSeeder

- Create storage symlink for avatar uploads:
  php artisan storage:link

- Clear caches if needed:
  php artisan route:clear
  php artisan view:clear
  php artisan config:clear

- Configure .env with payment gateway keys in services.php

## AI / Document PDF & Embeddings Setup

To enable PDF extraction, embeddings, and LLM-based answers, run the following locally:

- Install required PHP packages:
  composer require guzzlehttp/guzzle openai-php/client smalot/pdfparser

- Configure OpenAI key in .env:
  OPENAI_API_KEY=your_openai_api_key_here
  or set services.openai.key in config/services.php

- Run migrations to create documents/chunks tables:
  php artisan migrate

- Create storage symlink for uploaded files:
  php artisan storage:link

- Start a queue worker (recommended) for asynchronous embedding computation:
  php artisan queue:work --tries=3

- If you cannot run a queue, the embedding job will run synchronously as a fallback, but it may be slow.

- Optional: run seeder(s) if provided:
  php artisan db:seed

Admin document management UI is available at /admin/documents (requires authentication). The AI chat UI is at /ai/chat and polls document indexing status.

## Frontend: Tailwind CSS

To enable modern UI with Tailwind CSS:

1. Install Node dependencies:
   npm install

2. Install Tailwind and build tools (if not installed):
   npm install -D tailwindcss postcss autoprefixer
   npx tailwindcss init

3. Build assets:
   npm run dev

Include compiled CSS (public/css/app.css) in your layout.

## Spatie Laravel-Permission (optional)

To enable advanced role & permission management:

1. Install package:
   composer require spatie/laravel-permission

2. Publish config and migrations:
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

3. Run migrations:
   php artisan migrate

4. Seed Spatie roles from existing roles (optional):
   php artisan db:seed --class=SpatieIntegrationSeeder

Note: The project already supports a simple Role table; Spatie is optional but recommended for complex permissions.

## Realtime & Queue (Pusher) Setup

To enable real-time notifications and background job processing using Pusher and Laravel's queue system, add the following to your .env:

BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=database

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1

After setting env variables, run these commands:

# Create database table to store jobs
php artisan queue:table
php artisan migrate

# Ensure broadcasting config is set (config/broadcasting.php uses env values)
# Start a queue worker to process jobs
php artisan queue:work --tries=3

# For local development with Pusher, you may use Laravel Echo and Pusher dev credentials.
# Remember to run: php artisan config:clear and php artisan cache:clear after editing .env

