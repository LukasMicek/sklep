# Sklep (Laravel) – instrukcja uruchomienia lokalnie (Windows + XAMPP)

Projekt: prosty sklep internetowy z rolami:
- klient: rejestracja/logowanie, przeglądanie produktów, koszyk, checkout, podgląd zamówień
- sprzedawca: panel `/seller` (produkty, kategorie, zamówienia, zmiana statusów + historia)


## Wymagania
1. XAMPP (PHP 8.2, MySQL)
2. Composer
3. Node.js (LTS) + npm
4. Git

## Pierwsze uruchomienie (checklista)
1. XAMPP: włącz Apache + MySQL
2. phpMyAdmin: utwórz bazę `sklep`
3. W katalogu projektu:
   ```bash
   composer install
   npm install
   npm run build
   copy .env.example .env
   php artisan key:generate
   php artisan migrate:fresh --seed
   php artisan serve
