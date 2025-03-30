# Mars Rover - Laravel Kata

## 🚀 Instalación

1. Clona el repositorio:
    ```sh
    git clone https://github.com/AlanReibel/mars-rover-laravel.git
    cd mars-rover-laravel
    ```
2. Instalar dependencias:
    ```sh
    composer install
    ```
3. Arrancar el servidor:
    ```sh
    php artisan serve
    ```
4. Correr tests:
    ```sh
    php artisan test
    ```

## Archivos clave

Los archivos principales donde se encuentra la lógica del rover son:

- `app/Http/Controllers/RoverController.php` → Controlador que recibe las peticiones y maneja la validación.
- `app/Services/RoverService.php` → Servicio que implementa la lógica de movimiento del rover.
- `tests/Feature/RoverTest.php` → Pruebas de funcionalidad para validar el comportamiento esperado.