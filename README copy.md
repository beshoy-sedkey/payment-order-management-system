# chat-app
This repository contains the code for Chat App built with Laravel.
## Features
- Connect and disconnect notifications for users.
- Send and receive messages in real-time.
- Support for private messages and public chat rooms.
- Update last_seen timestamp when a user disconnects or goes offline.
- Allow senders to mark messages as seen by recipients.
## Requirements
- PHP >= 8.2.23
- Composer
- MySQL or any other database supported by Laravel
- Laravel >= 10.x
- node 
- pusher  
## Installation
Clone the repository and install dependencies:
```bash
git clone https://github.com/beshoy-sedkey/dayra-task.git
cd authService
cp .env.example .env
```
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
DB_HOST=auth_db
DB_PORT=3306
DB_DATABASE=authService
DB_USERNAME=root
DB_PASSWORD=123456789
```

- Pusher Configuration
```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=eu
```

## Migrate Tables In Database
```bash
RUN php artisan migrate
```
## Runing Test
```bash
RUN php artisan test
```


## API Reference

#### Register

```http
  POST /api/users/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** |
| `email` | `string` | **Required**|**Unique**| 
| `password` | `string` | **Required**|

#### Login

```http
  Post /api/users/login
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `email` | `string` | **Required**| 
| `password` | `string` | **Required**|

#### List Messages

```http
  GET /api/messages
```

#### Send Messages

```http
  Post /api/messages
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `contenet` | `string` | **Required**| 
| `receiver_id` | `string` | **Required**|



#### Mark As Seen

```http
  Post /api/messages/seen
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `Message_ids` | `json` | **Required**| 
