# 🏨 Mini Hotel Booking API

A modularized Laravel API for a hotel booking system, built with **Domain-Driven Design (DDD)** and **HMVC (Hierarchical Model-View-Controller)** principles.

## 🚀 Architecture Overview

This project follows a **Modular Monolith** approach to ensure scalability and maintainability.

### Key Principles:
- **HMVC**: Each module is a self-contained unit with its own logic, routes, and providers.
- **DDD**: Business logic is separated into Application and Domain layers within each module.
- **Sanctum Authentication**: Secure API-based authentication.

## 🛠️ Global Features

To ensure consistency and ease of development, the following global features are implemented:

- **Custom Timestamp Casting**: All models automatically format `created_at` and `updated_at` to `Y-m-d H:i:s` using a shared `TimestampDefaultFormat` cast.
- **Centralized Exception Handling**: A global exception renderer in `bootstrap/app.php` provides standardized JSON responses for all errors, including debug information in non-production environments.

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

## 🛠️ Modules Status & API Reference

### 👤 Users Module
Handles user registration, authentication, and profile management.
- **Implemented Features**:
    - Registration with validation.
    - Login with Token-based authentication (Sanctum).
    - Logout and Token Revocation.
- **Endpoints**:
| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Register a new user | No |
| `POST` | `/api/login` | Log in and receive a token | No |
| `POST` | `/api/logout` | Log out and revoke token | Yes |
| `GET` | `/api/user` | Get authenticated user data | Yes |

### 🏨 Hotels Module
Manages hotel properties and their statuses.
- **Implemented Features**:
    - Full CRUD (Create, Read, Update, Delete).
    - Enum-based status management (active/inactive).
    - Optimized filtering and searching via `HasFilter` trait.
- **Endpoints**:
| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/hotels` | List all hotels (with filtering/sorting) | No |
| `GET` | `/api/hotels/{id}` | Get specific hotel details | No |
| `POST` | `/api/hotels` | Create a new hotel | Yes |
| `PUT` | `/api/hotels/{id}` | Update a hotel | Yes |
| `DELETE` | `/api/hotels/{id}` | Delete a hotel | Yes |

### 🛏️ Room Types Module
Defines room categories and capacities for each hotel.
- **Implemented Features**:
    - Full CRUD linked to Hotels.
    - Capacity and pricing management.
    - Enum-based status management.
- **Endpoints**:
| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/room-types` | List all room types | No |
| `GET` | `/api/room-types/{id}` | Get specific room type | No |
| `POST` | `/api/room-types` | Create a new room type | Yes |
| `PUT` | `/api/room-types/{id}` | Update a room type | Yes |
| `DELETE` | `/api/room-types/{id}` | Delete a room type | Yes |

### 🔍 Search Module
Provides high-performance availability searching.
- **Implemented Features**:
    - Real-time availability calculation.
    - Caching for hotel and room type metadata per city.
    - Standardized filtering via `HasFilter` trait.
- **Endpoints**:
| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/search` | Search available rooms by city/dates | No |

### 📅 Bookings Module
Handles room reservations and status tracking.
- **Implemented Features**:
    - Secure booking creation.
    - Cancellation and status management.
    - **Overbooking Prevention Logic**.
- **Endpoints**:
| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/bookings` | Create a new booking | Yes |
| `GET` | `/api/bookings/{id}` | Get booking details | Yes |
| `DELETE` | `/api/bookings/{id}` | Cancel a booking | Yes |

---

## 🛡️ Booking Approach: Overbooking Prevention

To ensure system reliability and prevent overbooking (race conditions), we implemented the following approach:

1.  **Database Transactions**: All booking steps (availability check, occupancy validation, and record creation) are encapsulated within a single atomic database transaction.
2.  **Pessimistic Locking**: We use **Row-Level Locking (`lockForUpdate()`)** on the targeted `RoomType` during the availability check phase. 
    - This ensures that if two users attempt to book the last room simultaneously, the second user's request will wait until the first user's transaction is completed (committed or rolled back).
    - After the lock is acquired, the system recalculates available rooms by subtracting existing active bookings (Pending/Confirmed) from the total capacity.
3.  **Atomic Consistency**: By combining transactions with pessimistic locking, we guarantee that no more rooms can be booked than are actually available, even under high concurrency.

---

## 💰 Pricing Rules

The system automatically calculates stay prices based on the following rules:

1.  **Base Price**: Derived from the room type's base price.
2.  **Weekend Surcharge**: A **+20%** surcharge is applied to any night that falls on a weekend (Saturday or Sunday).
3.  **Long Stay Discount**: A **10% discount** is applied to the total price if the stay duration is **5 nights or more**.
4.  **Currency Integrity**: Prices are calculated server-side during both search and booking creation to ensure consistency.


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

5.  **Start the server**:
    ```bash
    php artisan serve
    ```

6.  **Seed the database** (Optional):
    Populate the system with initial test data (Hotels, Room Types, and an Admin user):
    ```bash
    php artisan db:seed
    ```
    - **Admin user**: `admin@example.com` / `password`

---

## 🐳 Docker Setup (Alternative)

If you prefer to run the project using a pre-configured Docker environment, follow these steps:

### 📂 Directory Structure
To ensure correct mounting, organize your directories as follows:
- `/home/hotel-docker`
- `/home/backend/Mini_Hotel_Booking_API`

### 🚀 Setup Steps

1. **Clone the Docker repository**:
   ```bash
   git clone https://github.com/mohammedawda/hotel-docker
   cd hotel-docker
   ```

2. **Build and start the containers**:
   ```bash
   docker compose up -d
   ```

3. **Access the Application container**:
   To run artisan commands or composer, enter the `hotel_app` container:
   ```bash
   docker exec -it hotel_app bash
   cd Mini_Hotel_Booking_API
   ```

---

## 🛡️ License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
