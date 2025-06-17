# FlightPHP REST API Boilerplate

> A lightweight and scalable RESTful API backend written in **pure PHP** using [FlightPHP](https://flightphp.com/), with JWT authentication, Redis caching, logging, Docker, and rate limiting.

---

## 📦 Features

- 🧱 **FlightPHP** for minimal routing
- 🔐 **JWT Authentication** with `firebase/php-jwt`
- 🗃 **MySQL** database with PDO
- ⚡ **Redis** for caching & rate-limiting
- 📊 **Monolog** logging with UTC+7 timezone
- 🐳 **Docker + Docker Compose** setup
- 🌐 **Nginx** as reverse proxy + load balancer
- ⛔ **Rate limiter** (middleware + Nginx level) for anti-DDoS
- 📂 Modular folder structure (Controllers, Routes, Config, Middleware)

---

## 🗂 Folder Structure
```
.
├── public/ # Entry point (index.php)
├── src/
│ ├── config/ # Logger, DB, Redis config
│ ├── controllers/ # AuthController, UserController
│ ├── middleware/ # AuthMiddleware, RateLimiter
│ ├── routes/ # api.php
│ └── utils/ # JWTUtils, LoggerTrait, Response handler
├── logs/ # Monolog output (app.txt)
├── docker-compose.yml
├── Dockerfile
├── .env
├── .gitignore
└── README.md
```

---

## ⚙️ Environment Configuration (`.env`)

```env
APP_ENV=development
APP_KEY=your-app-key

DB_HOST=db
DB_PORT=3306
DB_NAME=test
DB_USER=root
DB_PASS=

JWT_SECRET=your-jwt-secret
JWT_EXPIRE=3600

REDIS_HOST=redis
REDIS_PORT=6379
```
## 🚀 Run Project

```bash
# Start Docker containers
docker-compose up --build --scale app=3

# Access the app
curl http://localhost:8081/api/ping
```
