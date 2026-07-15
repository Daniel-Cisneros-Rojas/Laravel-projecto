<p align="center"><img width="554" height="554" alt="image" src="https://github.com/user-attachments/assets/58ad7669-870c-44f8-b23f-0f3b28f06ef1" />
</a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
## HanziPlay&Learn
HanziPlay&Learn es una aplicación web desarrollada con Laravel 12 cuyo objetivo es facilitar el aprendizaje de caracteres chinos (Hanzi) mediante una experiencia de aprendizaje basada en la gamificación. La plataforma integra diferentes minijuegos que ayudan a reforzar el reconocimiento visual de los caracteres, su pronunciación (pinyin) y su significado, proporcionando una forma interactiva y progresiva de adquirir vocabulario en chino mandarín.

El proyecto fue diseñado siguiendo una arquitectura MVC, aplicando principios de SOLID, Clean Code y una estructura modular que facilita el mantenimiento y la incorporación de nuevos juegos sin afectar la funcionalidad existente.

# Tecnologías
Laravel 12
PHP 8.3
MySQL
Blade
Tailwind CSS
JavaScript
HanziWriter API

# Características
Arquitectura modular y escalable.
Interfaz moderna y responsiva.
Minijuegos educativos orientados al aprendizaje de Hanzi.
Diseño basado en componentes reutilizables.
Preparado para incorporar nuevos juegos y categorías.
## Juegos disponibles

<img width="1566" height="729" alt="image" src="https://github.com/user-attachments/assets/bd616d66-c72c-43e5-ab01-31bfa2be6265" />

# Memory Hanzi

Juego de memoria donde el usuario debe encontrar las parejas correctas relacionando caracteres chinos con su correspondiente pinyin o significado, fortaleciendo la asociación entre escritura y vocabulario.

Captura de pantalla

<img width="1918" height="861" alt="image" src="https://github.com/user-attachments/assets/89a861ce-9b69-4c52-a043-a237c080240b" />

<img width="1918" height="861" alt="image" src="https://github.com/user-attachments/assets/317de01a-c358-4619-8acf-05049c45e922" />



# Stroke Quiz (Orden de trazos)

Juego basado en la integración con HanziWriter que permite practicar el orden correcto de los trazos de los caracteres chinos mediante ejercicios interactivos de escritura.

Captura de pantalla

<img width="1918" height="861" alt="image" src="https://github.com/user-attachments/assets/ca1522cb-106b-424e-ab8b-a0d77ac34b9f" />

# Catch the Character

El jugador selecciona una categoría temática y debe identificar únicamente los caracteres que pertenecen a ella mientras caen por la pantalla. El juego evalúa aciertos, errores, precisión y tiempo de respuesta para reforzar el reconocimiento rápido de vocabulario.

Captura de pantalla

<img width="1918" height="861" alt="image" src="https://github.com/user-attachments/assets/8af96c5f-5189-4a01-9db6-21a2ad7a23bb" />

# Instalación
git clone 

cd HanziPlay

composer install

npm install

cp .env.example .env

php artisan key:generate

# Configurar las credenciales de MySQL en el archivo .env

php artisan migrate --seed

php artisan serve

# Arquitectura

El proyecto organiza la lógica mediante una arquitectura basada en MVC, manteniendo una separación clara entre la presentación, la lógica de negocio y el acceso a datos. La estructura modular permite extender la plataforma con nuevos minijuegos y funcionalidades reutilizando servicios y componentes existentes.
