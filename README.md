# MDM Derma

**Live site:** [mdmderma.com](https://mdmderma.com/)

Website and product catalogue for MDM Derma, a dermatology and medical-aesthetics brand. The content team manages products, brands, categories and every page from a Filament admin panel, and the public site is a Blade theme with an animated, React-powered redesign on top.

## Features

- **Product catalogue:** categories with two levels of subcategories, brands, brand filtering and product pages.
- **Editable pages:** home, about, contact and legal pages are stored in the database and edited in the admin panel, plus a blog.
- **Contact form:** messages are saved in the admin panel, emailed to the team over SMTP, and the visitor gets an automatic reply.
- **Motion redesign:** React "islands" mounted into the Blade pages (Framer Motion, GSAP ScrollTrigger, Lenis smooth scrolling). The header, footer and category pages use plain CSS/JS, so they keep working without the JS build.
- **Self-healing errors:** production exceptions are handled by [laravel-lazarus](https://github.com/mohammed-a-ashqar/laravel-lazarus), which turns them into a failing test and a proposed fix.

## Stack

| Layer | Tools |
|---|---|
| Backend | PHP 8.2, Laravel 11, Filament 3, MySQL |
| Frontend | Blade, Bootstrap theme, React 18, Framer Motion, GSAP, Lenis |
| Build | Vite 6 |
| Hosting | Hostinger (deployed from GitHub) |

## Running locally

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev        # or: npm run build
php artisan serve
```

Create an admin user with `php artisan make:filament-user`, then sign in at `/admin`.

## Deployment

The server has no Node.js, so the compiled assets in `public/build` are committed. Run `npm run build` before every push, then pull on the server.

## Author

Built by [Mohammed Alashqar](https://github.com/mohammed-a-ashqar).
