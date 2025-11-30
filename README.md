# Universal Game Bossing Timer

A Laravel Vue application for tracking boss respawn timers across different games.

## Requirements

- PHP >= 8.1
- Node.js >= 16
- Composer
- MySQL/PostgreSQL

## Installation

1. Clone the repository:
```bash
git clone https://github.com/gandhyonly/universal-game-bossing-timer.git
cd universal-game-bossing-timer
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file

7. Run migrations:
```bash
php artisan migrate
```

8. Build assets:
```bash
npm run dev
```

9. Start the development server:
```bash
php artisan serve
```

## Development

- Run `npm run dev` for development
- Run `npm run build` for production build

## License

MIT License


sudo chown -R gandhy:www-data /var/www/universal-game-bossing-timer


sudo chown -R www-data:www-data /var/www/universal-game-bossing-timer






