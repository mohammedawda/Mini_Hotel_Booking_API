# 🏨 Mini Hotel Booking API

A modularized Laravel API for a hotel booking system, built with **Domain-Driven Design (DDD)** and **HMVC (Hierarchical Model-View-Controller)** principles.

## 🚀 Architecture Overview

This project follows a **Modular Monolith** approach to ensure scalability and maintainability.

### Key Principles:
- **HMVC**: Each module is a self-contained unit with its own logic, routes, and providers.
- **DDD**: Business logic is separated into Application and Domain layers within each module.
- **Sanctum Authentication**: Secure API-based authentication.

## 📂 Directory Structure

The project logic is organized into modules under `app/Modules/`.

```bash
app/Modules/
└── [ModuleName]/
    ├── Application/       # Service classes and business actions
    ├── Contracts/         # Interfaces and API definitions
    ├── Domain/            # Entities (Models) and Domain logic
    ├── Http/              # Controllers, Requests, and Resources
    ├── Infrastructure/    # Repositories and external service implementations
    └── [Module]ServiceProvider.php # Module registration
```

---

## 🛠️ Modules Status

### 👤 Users Module
Handles user registration, authentication, and profile management.
- **Implemented Features**:
    - Registration with validation.
    - Login with Token-based authentication (Sanctum).
    - Logout and Revoke authentication.
    - Fetch authenticated user data.

---

## 🔗 API Endpoints

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Register a new user | No |
| `POST` | `/api/login` | Log in and receive a token | No |
| `POST` | `/api/logout` | Log out and revoke token | Yes |
| `GET` | `/api/user` | Get authenticated user data | Yes |

---

## ⚙️ Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone [repository-url]
   cd Mini_Hotel_Booking_API
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Migration**:
   ```bash
   php artisan migrate
   ```

5. **Start the server**:
   ```bash
   php artisan serve
   ```

---

## 🛡️ License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
