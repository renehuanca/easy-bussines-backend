# Easy Business Backend

![Swagger Documentation](https://github.com/renehuanca/easy-bussines-backend/blob/5dcbbc929ca4837b0c5ca768eb5706bb4b0e8e18/public/images/swagger.png)

[![Downloads](https://img.shields.io/github/downloads/renehuanca/easy-bussines-backend/total.svg)](https://github.com/renehuanca/easy-bussines-backend/releases)
[![License](https://img.shields.io/github/license/renehuanca/easy-bussines-backend.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-8.2-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-12.x-red.svg)](https://laravel.com)

## Descripción
Easy Business desarrollado con Laravel 12 y PHP 8.2.

## Requisitos
- PHP 8.2
- Composer
- MySQL/PostgreSQL/Sqlite
- Node.js & NPM (para desarrollo)

## Tecnologias
- Laravel 12
- Sanctum
- l5-swagger
- Pest

## Instalación

1. Clonar el repositorio
```bash
git clone https://github.com/renehuanca/easy-bussines-backend.git
```

2. Instalar dependencias
```bash
composer install
```

3. Copiar el archivo de entorno
```bash
cp .env.example .env
```

4. Generar la clave de aplicación
```bash
php artisan key:generate
```

5. Configurar la base de datos en el archivo .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

6. Ejecutar las migraciones
```bash
php artisan migrate
```

7. Iniciar el servidor
```bash
composer run dev
```

## Documentación API
La documentación de la API está disponible en `/api/documentation` cuando el servidor está en ejecución.

### Configuración de L5-Swagger

1. Configurar en el archivo `.env` para poder generar la documentación en Swagger de manera automatica:
```env
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000/api/v1
```

3. Generar la documentación de manera manual
```bash
php artisan l5-swagger:generate
```

4. Acceder a la documentación:
- UI de Swagger: `http://localhost:8000/api/documentation`
- JSON de Swagger: `http://localhost:8000/api/documentation.json`

## Testing
Para ejecutar los tests hechos en Pest:
```bash
php artisan test
```

## Contributing

1. Fork el proyecto
2. Crea tu Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push al Branch (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Licencia
Este proyecto está licenciado bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.
