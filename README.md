### App Trading

![Image text](https://i.imgur.com/kBzvcnQ.png)

## Table of Contents
1. [Description](#description)
2. [Technologies](#technologies)
3. [Installation](#installation)


## Description
***
Trading operations journal.

This application allows you to track and analyze your trading operations history. It features a responsive design that enables you to monitor your trades from any device. The application includes a small account settings section and a detailed data entry form.

![Image text](https://i.imgur.com/WcnsoA6.png)

It includes a comprehensive set of charts to analyze your trading results.

![Image text](https://i.imgur.com/7cli6QK.png)



## Technologies
***
List of technologies used in this project:

### Backend
* [PHP](https://www.php.net): 8.0
* [Laravel](https://laravel.com): 8.0
* [MySQL](https://www.mysql.com): 8.0
* [Composer](https://getcomposer.org): Latest

### Frontend
* [Vue.js](https://vuejs.org): 2.5.17
* [Vuex](https://vuex.vuejs.org): 3.6.2
* [Vue-chartjs](https://vue-chartjs.org): 3.5.1
* [Chart.js](https://www.chartjs.org): 2.9.3
* [Bootstrap](https://getbootstrap.com): 4.5.0
* [jQuery](https://jquery.com): 3.2
* [Laravel Mix](https://laravel-mix.com): 5.0.5

### Development Tools
* [Node.js](https://nodejs.org): 18.x LTS
* [Docker](https://www.docker.com): Latest
* [Docker Compose](https://docs.docker.com/compose): Latest

## Installation
***
### Prerequisites
* Docker and Docker Compose installed

### Installation Steps

1. Clone the repository:
```bash
git clone <repository-url>
cd apptrading
```

2. Build and start the containers:
```bash
docker compose up -d --build
```

3. Install PHP dependencies:
```bash
docker compose exec www composer install
```

4. Install Node.js dependencies:
```bash
docker compose exec www npm install
```

5. Compile assets:
```bash
docker compose exec www npm run dev
```

6. Configure environment variables:
```bash
cp .env.example .env
docker compose exec www php artisan key:generate
```

7. Run database migrations:
```bash
docker compose exec www php artisan migrate
```

### Application Access
* **Web Application**: http://localhost:8001
* **phpMyAdmin**: http://localhost:8081
* **MySQL Database**: localhost:3307

### Useful Commands
```bash
# View logs
docker compose logs -f

# Stop containers
docker compose down

# Restart containers
docker compose restart

# Access PHP container
docker compose exec www bash
```
