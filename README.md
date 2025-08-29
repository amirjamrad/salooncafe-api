
````markdown
# SaloonCafe-API

SaloonCafe-API is a backend RESTful API for managing a café system. Built with **Laravel**, it supports role-based access control, activity logging, category & item management, orders, payments, and user authentication.

---

## Features

- User authentication and registration
- Role and permission management (Spatie Permission)
- Activity logging (Spatie Activitylog)
- CRUD operations for categories, items, orders
- Soft deletes and restoration
- Payment callback handling
- JSON API responses

---

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/yourusername/SaloonCafe-API.git
cd SaloonCafe-API
````

2. **Install dependencies**

```bash
composer install
```

3. **Set up environment**

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` for database, payment, and authentication settings.

4. **Run migrations and seeders**

```bash
php artisan migrate --seed
```

5. **Start the server**

```bash
php artisan serve
```

Default URL: `http://127.0.0.1:8000`

---

## API Endpoints

### Authentication

| Method | Endpoint               | Description             |
| ------ | ---------------------- | ----------------------- |
| POST   | `/api/register`        | Register a new user     |
| POST   | `/api/login`           | Login user              |
| POST   | `/api/logout`          | Logout user             |
| GET    | `/sanctum/csrf-cookie` | CSRF cookie for Sanctum |

---

### Categories (Admin)

| Method | Endpoint                                      | Description                     |
| ------ | --------------------------------------------- | ------------------------------- |
| GET    | `/api/admin/category`                         | List all categories             |
| POST   | `/api/admin/category/store`                   | Create a new category           |
| PATCH  | `/api/admin/category/update/{category}`       | Update a category               |
| DELETE | `/api/admin/category/delete/{category}`       | Soft delete a category          |
| DELETE | `/api/admin/category/force-delete/{category}` | Permanently delete a category   |
| GET    | `/api/admin/category/restore/{id}`            | Restore a soft-deleted category |
| GET    | `/api/admin/category/show/{category}`         | Get category details            |
| GET    | `/api/admin/category/{category}/items`        | List items under a category     |
| GET    | `/api/categories`                             | List categories for users       |

---

### Items (Admin)

| Method | Endpoint                              | Description                 |
| ------ | ------------------------------------- | --------------------------- |
| GET    | `/api/admin/item`                     | List all items              |
| POST   | `/api/admin/item/store`               | Create a new item           |
| POST   | `/api/admin/item/update/{item}`       | Update an item              |
| DELETE | `/api/admin/item/delete/{item}`       | Soft delete an item         |
| DELETE | `/api/admin/item/force-delete/{item}` | Permanently delete an item  |
| GET    | `/api/admin/item/restore/{id}`        | Restore a soft-deleted item |
| GET    | `/api/admin/item/show/{item}`         | Get item details            |
| GET    | `/api/items`                          | List items for users        |

---

### Permissions (Admin)

| Method | Endpoint                                    | Description             |
| ------ | ------------------------------------------- | ----------------------- |
| GET    | `/api/admin/permission`                     | List all permissions    |
| POST   | `/api/admin/permission/store`               | Create a new permission |
| POST   | `/api/admin/permission/update/{permission}` | Update a permission     |
| DELETE | `/api/admin/permission/delete/{permission}` | Delete a permission     |

---

### Roles (Admin)

| Method | Endpoint                        | Description       |
| ------ | ------------------------------- | ----------------- |
| GET    | `/api/admin/role`               | List all roles    |
| POST   | `/api/admin/role/store`         | Create a new role |
| POST   | `/api/admin/role/update/{role}` | Update a role     |
| DELETE | `/api/admin/role/delete/{role}` | Delete a role     |

---

### Users (Admin)

| Method | Endpoint                              | Description                 |
| ------ | ------------------------------------- | --------------------------- |
| GET    | `/api/admin/user`                     | List all users              |
| POST   | `/api/admin/user/update/{user}`       | Update a user               |
| POST   | `/api/admin/user/{user}/assign-role`  | Assign roles to a user      |
| DELETE | `/api/admin/user/delete/{user}`       | Soft delete a user          |
| DELETE | `/api/admin/user/force-delete/{user}` | Permanently delete a user   |
| GET    | `/api/admin/user/restore/{id}`        | Restore a soft-deleted user |

---

### Orders

| Method | Endpoint          | Description                 |
| ------ | ----------------- | --------------------------- |
| POST   | `/api/order/item` | Create an order for an item |

---

### Payments

| Method | Endpoint                          | Description              |
| ------ | --------------------------------- | ------------------------ |
| POST   | `/api/payment/pay/{order}`        | Pay for an order         |
| GET    | `/api/payment/callback/{payment}` | Payment gateway callback |

---

### Storage & Health

| Method | Endpoint          | Description                |
| ------ | ----------------- | -------------------------- |
| GET    | `/storage/{path}` | Access local storage files |






