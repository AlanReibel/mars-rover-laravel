# Mars Rover - Laravel Kata

## 📚 Descripción

Se trata de una API desarrollada en Laravel para controlar el movimiento de un rover sobre una malla de 200x200 unidades.
La Api recibe peticiones POST a la ruta /api/rover/move con los siguientes parámetros:

- x: posición horizontal del rover
- y: posición vertical del rover
- direction: dirección del rover (N, S, E, W)
- commands: cadena de comandos (F, L, R)
- obstacles: array de obstáculos

## 🛠️ Funcionalidad

Para probar la API de una manera mas interactiva he creado una interface de usuario simple en vue.
Incluye un formulario para enviar los datos al controlador y un display de los resultados.
Esta creada la build y vinculada al la vista blade de vienvendia por lo que no es necesario ejecutar el servidor de vue.
Es accesible en la 127.0.0.1:8000 una vez arrancado el servidor de php artisan.

## 🚀 Instalación

1. Clona el repositorio:
    ``` sh
    git clone https://github.com/AlanReibel/mars-rover-laravel.git
    cd mars-rover-laravel
    ```
2. Instalar dependencias:
    ```sh
    composer install
    ```
    
3. Copiar .env de ejemplo:
    ```sh
    cp .env.example .env
    ```

4. Generar clave:
    ```sh
    php artisan key:generate
    ```

5. Arrancar el servidor:
    ```sh
    php artisan serve
    ```


## Archivos clave

Los archivos principales donde se encuentra la lógica del rover son:

### Laravel
- `app/Http/Controllers/RoverController.php` → Controlador que recibe las peticiones y maneja la validación.
- `app/Services/RoverService.php` → Servicio que implementa la lógica de movimiento del rover.

### Vue
- `rover-front\src\App.vue` → Interfaz de usuario simple en vue y llamadas a la API.
- `rover-front\src\components\PlanetGrid.vue` → Componente que representa la malla y el Rover, obstaculos y controles.

## 🧪 Pruebas

Se han creado pruebas de funcionalidad en:

- `tests/Feature/RoverTest.php` → Pruebas de funcionalidad para validar el comportamiento esperado.

Correr tests:
```sh
php artisan test
```