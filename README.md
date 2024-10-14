# Payment and Order Management System
This repository contains the code for a Payment and Order Management System built with Larave

## Features
- Create and manage orders with product details, quantity, price, and status.
- Integrate with PayPal for payment processing.
- Webhook handling for payment status updates.
- JWT-based authentication for API endpoints.
- Role-based access control (RBAC) for user permissions.
- Rate limiting to prevent brute-force attacks.

## Requirements
- PHP >= 8.1
- Composer
- MySQL or any other database supported by Laravel
- Laravel >= 10.x
- PayPal Developer Account
- ngrok (for local webhook testing)

## Installation
Clone the repository and install dependencies:
```bash
git clone https://github.com/your-username/payment-order-management-system.git
cd payment-order-management-system
composer install
cp .env.example .env
php artisan key:generate


## Generate Secret Key (JWT)
```bash
php artisan jwt:secret
``` 
- For JWT Secret 
```
JWT_SECRET= your_generated_secret_key
```
- Database Configuration
```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE
DB_USERNAME=
DB_PASSWORD=
```

- Paypal Configuration
```
Payment_Gateway=PayPal_Rest
PAYPAL_CLIENT_ID=
PAYPAL_SECRET=
PAYPAL_SANDBOX=true
```

## Run Feature and Unit Tesing
```bash
php artisan test
``` 


## Run Project
```bash
php artisan serve
``` 


## API Reference

#### Register

```http
  POST /api/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** |
| `email` | `string` | **Required**|**Unique**| 
| `password` | `string` | **Required**|

#### Login

```http
  Post /api/login
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `email` | `string` | **Required**| 
| `password` | `string` | **Required**|


#### Create Order

```http
  Post /api/orders
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `product_name` | `string` | **Required**| 
| `quantity` | `int` | **Required**|
| `price` | `double` | **Required**|



#### List Orders

```http
  GET /api/orders
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `product_name` | `string` | **filter**| 
| `status` | `string` | **filter**|

#### Update Status

```http
  Post /api/orders/{orderId}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `status` | `string` | **Required**| 

#### Payment Purchase

```http
  Post /api/payments/purchase/{orderId}
```

#### Payment Success

```http
  GET /api/orders
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `paymentId` | `string` | **Required**| 
| `payerId` | `string` | **Required**|


#### Payment Cancel

```http
  GET /api/orders
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `token` | `string` | **Required**| 


#### Create Role

```http
  POST /api/roles
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** |

#### List Roles

```http
  GET /api/roles
```

#### Show Role

```http
  GET /api/roles/{roleId}
```
#### Update Role

```http
  PUT /api/roles/{roleId}
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** |

#### Delete Role

```http
  DELETE /api/roles/{roleId}
```
#### Assign Permissions to Role

```http
  POST /api/roles/{roleId}/permissions
```
| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `permissions` | `json` | **Required** |




#### Create Permission

```http
  POST /api/permissions
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** |

#### List Permissions

```http
  GET /api/permissions
```

#### Show permissions

```http
  GET /api/permissions/{permissionId}
```
#### Update permissions

```http
  PUT /api/permissions/{permissionId}
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** |

#### Delete permissions

```http
  DELETE /api/permissions/{permissionId}
```


#### Assign Roles to User

```http
  POST /api/users/{userId}/roles
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `roles` | `json` | **Required** |
