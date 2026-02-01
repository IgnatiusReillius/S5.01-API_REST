## miBiblio – Book Management Application

A RESTful API Laravel-based application for managing a personal library, where users can add the books they own and add a personal review.

## Features

- User registration and authentication
- Role-based access control, with admin and user roles
- Add personal reviews to your books
- Full CRUD for books, users and reviews 

## Prerequisites

Before cloning the project, ensure the following tools are installed:

- **[Git](https://git-scm.com/install/windows)**
- **[PHP via XAMPP](https://codersfree.com/posts/como-instalar-php-en-windows-usando-xampp)**
- **[Composer](https://getcomposer.org/download/)**
- **[Postman](https://www.postman.com/downloads/)**

## Installation

### 1. Clone the repository
```
git clone https://github.com/IgnatiusReillius/S5.01-API_REST.git
cd S5.01-API_REST
```

### 2. Install PHP dependencies
```
composer install
```

### 3. Create your environment file
```
cp .env.example .env
```

### 4. Generate the application key
```
php artisan key:generate
```

### 5. Environment Configuration

Make sure your `/.env` file contains the following:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sprint5
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run Migrations and Seeders

This will rebuild your database and load initial data for make easier to test the API later:
```
php artisan migrate --seed
```

### 7. Install Passport

```
php artisan passport:install
```


### 8. Start the Development Servers

```
php artisan serve
```

This command will return a message similar to:

```
Server running on [http://127.0.0.1:8000].
```

With this URL, you will be able to access the application.

## Testing the Application

Now you can test the API by starting Apache and MySQL in Xampp and making requests in Postman.
You can upload the file with the requests to test them quickly. You will see that they are separated by administrator and user. 

![alt text](/screenshots/1.jpg)


To run them correctly, you will need to set the environment:

![alt text](/screenshots/2.jpg)

And the URL that appeared when you ran the server in the terminal earlier:

![alt text](/screenshots/3.jpg)

Now you can make requests and test all endpoints.

Some of them already come with the necessary data, such as when registering a user:

![alt text](/screenshots/4.jpg)

And to test the protected requests, you will first have to execute the administrator and user login requests. This way, you automatically save the access tokens for each role in variables. 

All requests are separated into administrator, who can perform most actions, such as viewing the entire list of users:

![alt text](/screenshots/5.jpg)

Or user ones:

![alt text](/screenshots/6.jpg)

## Roles y permissions

| Rol | Permissions |
|-----|----------|
| **user** | Can register and delete your account <br> Can create, view, update, and delete your book reviews <br> Can search for books|
| **admin** | Can register, view, modify and delete users <br> Can view, update and delete reviews <br> Can create, view, update and delete books |

## Endpoints

### Autenticación

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Create user |
| POST | `/api/login` | Obtain token |
| POST | `/api/refresh` | Refresh token |
| GET | `/api/me` | Get user info |
| POST | `/api/logout` | Logout |

### Users

| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| POST | `/api/users` | Create user | Admin for anyone <br> User for itself  |
| GET | `/api/users` | List all users | Admin |
| GET | `/api/users/{id}` | List specific user | Admin |
| PUT | `/api/users/{id}` | Update specific user | Admin for anyone <br> User for itself |
| DELETE | `/api/users/{id}` | Delete specific user | Admin for anyone <br> User for itself |
| DELETE | `/api/users` | Delete all users | Admin |

### Books

| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| POST | `/api/books` | Create book | Admin |
| GET | `/api/books` | List books | Both |
| GET | `/api/books/{id}` | List specific book | Both |
| PUT | `/api/books/{id}` | Update specific book | Admin |
| DELETE | `/api/books/{id}` | Delete specific book | Admin |
| DELETE | `/api/books` | Delete all books | Admin |

### Reviews

| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| POST | `/api/users/{id_user}/books/{id_book}/reviews` | Create review for a book | User |
| GET | `/api/users/books/reviews` | List all reviews | Admin |
| GET | `/api/users/books/{id_book}/reviews` | List all reviews by book | Admin |
| GET | `/api/users/{id_user}/reviews` | List all reviews by user | Admin for anyone <br> User for itself  |
| GET | `/api/users/{id_user}/books/{id_book}/reviews` | List specific review by book | Admin for anyone <br> User for itself  |
| PUT | `/api/users/{id_user}/books/{id_book}/reviews` | Update specific user | Admin for anyone <br> User for itself |
| DELETE | `/api/users/books/reviews` | Delete all reviews | Admin |
| DELETE | `/api/users/books/{id_book}/reviews` | Delete all reviews by book | Admin |
| DELETE | `/users/{id_user}/reviews` | Delete all reviews by user | Admin |
| DELETE | `/users/{id_user}/books/{id_book}/reviews` | Delete specific review by user | Admin for anyone <br> User for itself  |

## License

This project is for educational purposes and part of the IT Academy exercises.