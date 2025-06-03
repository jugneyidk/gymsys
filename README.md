# Gymsys - Sistema de Gestión para el Gimnasio de Halterofilia Eddie Suárez

## Descripción

Gymsys es un sistema de información diseñado específicamente para gestionar las operaciones del gimnasio de halterofilia Eddie Suárez de la UPTAEB. Esta aplicación web permite administrar atletas, entrenadores, asistencias, eventos, pagos de mensualidades y generar reportes esenciales para la toma de decisiones.

## Características Principales

- **Gestión de Usuarios y Roles**: Sistema de permisos dinámico para diferentes tipos de usuarios
- **Gestión de Atletas**: Registro, actualización y seguimiento de información de atletas
- **Gestión de Entrenadores**: Administración de datos y asignaciones de entrenadores
- **Control de Asistencias**: Registro y seguimiento de asistencias de atletas y personal
- **Gestión de Eventos**: Programación y administración de competencias y eventos
- **Control de Mensualidades**: Registro y seguimiento de pagos
- **Sistema WADA**: Implementación de control de vigencia de la Agencia Mundial Antidopaje
- **Generación de Reportes**: Producción de informes para la toma de decisiones
- **Bitácora de Actividades**: Registro completo de acciones realizadas en el sistema

## Requisitos del Sistema

- PHP 8.2 o superior
- MySQL 5.7 o superior
- Servidor web (Apache)
- Composer

## Instalación

1. **Clonar el repositorio**

```bash
git clone https://github.com/jugneyidk/gymsys.git
cd gymsys
```

2. **Instalar dependencias**

```bash
composer install
```

3. **Configurar variables de entorno**

```bash
cp .env.example .env
```

Edita el archivo `.env` con la configuración de base de datos y otras variables de entorno necesarias.

4. **Importar la base de datos**

```bash
mysql -u username -p gymsys < gymsys_secure.sql
mysql -u username -p gymsys_secure < gymsys.sql
```

5. **Configurar el servidor web**

Configura tu servidor web para apuntar al directorio público del proyecto.

## Estructura del Proyecto

```
gymsys/
├── config/         # Archivos de configuración
├── public/         # Punto de entrada y assets públicos
├── src/            # Código fuente
│   ├── controller/ # Controladores
│   ├── model/      # Modelos
│   ├── utils/      # Utilidades
│   └── view/       # Vistas
├── tests/          # Tests unitarios
└── vendor/         # Dependencias de Composer
```

## Tecnologías Utilizadas

- PHP (Framework personalizado con estructura MVC)
- MySQL
- JavaScript/HTML/CSS
- PHPMailer para envío de correos electrónicos
- JWT para autenticación
- DOMPDF para generación de documentos PDF
- chillerlan/php-qrcode para generación de códigos QR

## Comandos Útiles

- **Ejecutar tests**:
```bash
./vendor/bin/phpunit
```

- **Limpiar caché de intentos de login**:
```bash
php limpiarLoginAttempts.php
```

- **Ejecutar notificaciones programadas**:
```bash
php notificacionesCronJob.php
```

## Autores

- Diego Salazar
- Jugney Vargas

## Licencia

Este proyecto es privado y su uso está restringido al Gimnasio de Halterofilia Eddie Suárez de la UPTAEB.
