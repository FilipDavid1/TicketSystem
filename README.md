# README — Laravel + Docker + MariaDB

## 1. Popis projektu

Tento projekt používa Docker na spustenie Laravel aplikácie s PHP 8.2 + Apache a databázou MariaDB 11. Prostredie je plne izolované a nevyžaduje lokálnu inštaláciu PHP ani MariaDB.

---

## 2. Požiadavky

Pred spustením musí byť nainštalované:

- Docker
- Docker Compose

---

## 3. Inštalácia a prvé spustenie

### 1. Klonovanie projektu

```
git clone <repo-url>
cd <project-folder>
```

### 2. Konfigurácia `.env`

Súbor `.env` musí obsahovať:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=xxx
DB_USERNAME=xxx
DB_PASSWORD=xxx
```

### 3. Spustenie Docker kontajnerov

```
docker-compose up -d --build
```

### 4. Spustenie migrácií

```
docker exec -it laravel_app bash
php artisan migrate
exit
```

---

## 4. Prístup k službám

Aplikácia:
- http://localhost:8000

Databáza (MariaDB):
- Host: localhost
- Port: 3307
- Database: ticket_system_db
- User: ticket_user
- Password: secret

---

## 5. Práca s kontajnermi

### Vstup do Laravel kontajnera

```
docker exec -it laravel_app bash
```

### Stop kontajnerov

```
docker-compose down
```

### Stop kontajnerov a zmazanie databázy (volume)

```
docker-compose down -v
```

### Rebuild kontajnerov

```
docker-compose up -d --build
```

---

## 6. Vysvetlenie Docker súborov

### Dockerfile
Obsahuje nastavenie PHP 8.2 + Apache, inštaláciu PHP rozšírení, aktiváciu `mod_rewrite` a nastavenie DocumentRoot na `public`.

### docker-compose.yml
Definuje dve služby:
- `app` — Laravel + Apache
- `db` — MariaDB 11

### apache.conf
Apache konfigurácia smerujúca DocumentRoot na `public` adresár projektu.

---

## 7. Reset prostredia

V prípade problémov:

```
docker-compose down -v
docker-compose up -d --build
```

Tým sa vytvorí nové čisté prostredie aj s databázou.
