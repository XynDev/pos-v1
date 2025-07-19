<div align="center">
  <h1 align="center">XYN POS v1</h1>
  <p align="center">
    A powerful, open-source Point of Sales (POS) system built with the TALL stack (Tailwind, Alpine.js, Laravel, Livewire).
  </p>

  <p align="center">
    <a href="https://github.com/XynDev/pos-v1/stargazers"><img src="https://img.shields.io/github/stars/XynDev/pos-v1?style=for-the-badge" alt="Stars"></a>
    <a href="https://github.com/XynDev/pos-v1/network/members"><img src="https://img.shields.io/github/forks/XynDev/pos-v1?style=for-the-badge" alt="Forks"></a>
    <a href="https://github.com/XynDev/pos-v1/issues"><img src="https://img.shields.io/github/issues/XynDev/pos-v1?style=for-the-badge" alt="Issues"></a>
    <a href="https://github.com/XynDev/pos-v1/blob/main/LICENSE"><img src="https://img.shields.io/github/license/XynDev/pos-v1?style=for-the-badge" alt="License"></a>
  </p>
</div>

---

## 🎯 About The Project

XYN POS is designed to be a modern, intuitive, and feature-rich Point of Sales application, perfect for small to medium-sized businesses. Built on top of the robust Laravel framework and the dynamic Livewire, it provides a seamless, single-page-app-like experience without complex JavaScript frameworks.

This project aims to provide a reliable, open-source alternative that developers can easily install, customize, and deploy for their clients or their own businesses.

### ✨ Key Features

* **Product Management:** Easily add, edit, and categorize products.
* **Dynamic POS Interface:** A fast and responsive cashier interface for processing transactions.
* **Order & Transaction History:** Track all sales and review past orders.
* **User & Role Management:** Built-in user authentication and authorization using Laravel Jetstream & Spatie Permissions.
* **Customer Management:** Keep a record of your customers.
* **Simple & Clean UI:** A clean user interface built with Tailwind CSS.

### 🛠️ Tech Stack

This project is built with a modern tech stack:
* [Laravel](https://laravel.com/)
* [Livewire](https://laravel-livewire.com/)
* [Alpine.js](https://alpinejs.dev/)
* [Tailwind CSS](https://tailwindcss.com/)
* [Laravel Jetstream](https://jetstream.laravel.com/)
* [Spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v1/introduction)

---

### 🚀 Getting Started

Follow these steps to get a local copy up and running.

#### Prerequisites
* PHP >= 8.1
* Composer
* Node.js & NPM
* A database (e.g., MySQL, PostgreSQL)

#### Installation

1.  **Clone the repository**
    ```sh
    git clone [https://github.com/XynDev/pos-v1.git](https://github.com/XynDev/pos-v1.git)
    cd pos-v1
    ```

2.  **Install dependencies**
    ```sh
    composer install
    npm install
    ```

3.  **Setup environment file**
    ```sh
    cp .env.example .env
    ```
    Then, open the `.env` file and configure your database connection (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

4.  **Generate application key**
    ```sh
    php artisan key:generate
    ```

5.  **Run database migrations and seeders**
    ```sh
    php artisan migrate --seed
    ```

6.  **Build assets**
    ```sh
    npm run dev
    ```

7.  **Run the development server**
    ```sh
    php artisan serve
    ```
    Your application should now be running on `http://127.0.0.1:8000`.

---

## ❤️ Supporting the Project

XYN POS is a free, open-source project. Its ongoing development is made possible thanks to the support of our amazing backers.

If you find this project useful, please consider [**becoming a sponsor**](https://github.com/sponsors/XynDev) to help us continue our mission.

### Our Sponsors

<a href="https://github.com/sponsors/XynDev"><img src="https://opencollective.com/laradock/sponsors.svg?width=890"></a>

A huge thank you to all our backers! You can see the full list of our wonderful supporters in our [**`BACKERS.md` file**](BACKERS.md).

---

##
