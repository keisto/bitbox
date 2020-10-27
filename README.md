# Bitbox - Mini Dropbox Clone

Obviously not a dropbox replacement but a good foundation. This app was mainly built to get a good grasp on Livewire and what it can do.

<p align="center">
  <img align="center" src="https://github.com/keisto/bitbox/blob/production/public/images/folders.png?raw=true">
</p>
<p align="center">
  <img align="center" src="https://github.com/keisto/bitbox/blob/production/public/images/rename.png?raw=true">
</p>

### File Browser functionality built using [Livewire](https://laravel-livewire.com) and [AlpineJs](https://github.com/alpinejs/alpine)

<p align="center">
  <img align="center" src="https://github.com/keisto/bitbox/blob/production/public/images/search.png?raw=true">
</p>

### Search using [TNTSearch](https://github.com/teamtnt/tntsearch)

<p align="center">
  <img align="center" src="https://github.com/keisto/bitbox/blob/production/public/images/uploading.png?raw=true">
</p>

### Uploading using [FilePond](https://github.com/pqina/filepond)

## Getting started

⚠️ I'm assuming you already have a local machine set up for [Laravel](https://laravel.com) applications

You'll need to run:

-   `composer install`
-   `npm install`
-   `php artisan key:generate`

Copy the `.env.example` and rename `.env`
Set up the database on your local machine

```
DB_DATABASE=bitbox
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

-   `php artisan migrate`
-   `php artisan serve`

Should be able to view @ `localhost:8000`

Search won't work until you run:
`php artisan scout:import "App\Models\Item"`
But you'll have to run this **after you add some items** a command like this can be replaced with a event listener or cron job depending on how you build this out.

### 🤷🏽‍♂️ I'm just going off memory, but if you have trouble let me know!
