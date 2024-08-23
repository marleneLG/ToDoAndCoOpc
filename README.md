# Project 8 : TodoAndCo

This project was created as part of my training with openclassrooms to present you my skills obtained through my learning.

## Technologies

- Symfony 6.4.1
- Composer 2.2.6
- WampServer
  - Apache 2.4.54
  - PHP 8.2.0
  - MySQL 8.0.31

## Getting started

In order to install the project, follow these simple steps.

### Prerequisite

- PHP > 8.2.0
- Symfony
- SMTP server WAMP/MAMP for local use
- MySQL 8.0.31

## Installation

### Clone

- Clone the project with this command:

```shell
git clone https://github.com/marleneLG/ToDoAndCoOpc.git
```

- For more information: [GitHub Documentation](https://docs.github.com/fr/repositories/creating-and-managing-repositories/cloning-a-repository)

### Configuration

- Perform the "composer install" command in order to install the necessary back dependencies:

```shell
composer install
```

- Configure environment variables such as database connection in file `.env` :

```shell
  `DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"`
```

- Create database:

```shell
php bin/console doctrine:database:create
```

- Apply migration:

```shell
php bin/console doctrine:migrations:migrate
```

- Apply tests:

```shell
php vendor/bin/phpunit
```