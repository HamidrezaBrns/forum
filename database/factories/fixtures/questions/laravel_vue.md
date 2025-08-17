# How to properly structure a Laravel + Vue project for a product with both SPA and traditional pages?

I’m working on a project built with **Laravel (10.x)** as the backend API and **Vue (3)** on the frontend. The product requires a mix of:

* A **Single Page Application (SPA)** for the user dashboard (after login).
* Some **traditional server-rendered pages** (e.g., marketing site, landing pages, blog, etc.).

Right now, my structure looks like this:

```
/routes
  web.php
  api.php
/resources
  /js
    /components
    /pages
    app.js
  /views
    welcome.blade.php
```

The issue I’m facing is how to best organize the Vue components and pages while still keeping Laravel’s Blade views for certain parts of the app.

Some questions I’m struggling with:

1. Should I separate the Vue SPA (e.g., `/resources/js/spa`) from smaller Vue components used in Blade templates (e.g., `/resources/js/components`)? Or is it better to keep everything under one `pages/components` folder?
2. For the dashboard SPA, is it better to serve a single `dashboard.blade.php` file that mounts Vue and handles all routing via Vue Router?
3. How do you usually deal with authentication when mixing Blade + SPA? Right now, I’m using Laravel Breeze with Inertia, but I’m not sure if that’s the best fit when some pages are not SPA-driven.
4. Are there recommended patterns for versioning assets and ensuring proper separation between marketing pages and the actual web app?

I’ve read the [Laravel + Vue guide](https://laravel.com/docs/10.x/vite#vue) but it mostly covers setting up Vue with Vite, not best practices for mixing SPA + Blade.

Has anyone managed a **hybrid Laravel + Vue project** like this? How did you structure your codebase to avoid things getting messy long-term?

Looking forward to hearing different approaches.
