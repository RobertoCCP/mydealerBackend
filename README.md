## Instalación en local

Necesita jdk 17.0.10+7:
https://www.openlogic.com/openjdk-downloads?field_java_parent_version_target_id=807&field_operating_system_target_id=All&field_architecture_target_id=All&field_java_package_target_id=All&page=1

- Flutter 3.27.3

- Emulador de android api 33 en adelante

- XAMPP 8.2.12 / PHP 8.2.12 e instalarla


- Composer ultima version e instalarla

- Visual code 

- Extesiones de visual code:

	* Flutter
	* Flutter Widget Snippets
	* Flutter widget wrap
	* Gradle for Java
	* Laravel Blade formatter
	* Laravel Blade Spacer
	* Laravel Extra Intellisense
	* PHP Constructor
	* PHP Debug
	* PHP Intelephense
	* PHP Namespace Resolver
	* PHP Create Class
	* Postman

### Requisitos

```sh
php -v
composer -V
```

### Pasos

1. Clonar e ingresar al proyecto.

```sh
git clone https://github.com/RobertoCCP/mydealerBackend.git
cd en la carpeta que crearon
```

2. Instalar las dependencias de composer.

```sh
composer install
```

3. Configurar las variables de entorno para la conexión a la base de datos. Copie el archivo `.env.example` como `.env`, luego abra el archivo en vscode o cualquier editor de texto.

```sh
cp .env.example .env
code .env
```

Edite las siguientes variables en el archivo `.env`.

```sh
APP_NAME="My Dealer"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
FRONTEND_URL=
TEST_FRONTEND_URL=

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mydealer
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ddmqcorreoprueba@gmail.com
MAIL_PASSWORD=yfqzmbmwskignypt
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ddmqcorreoprueba@gmail.com
MAIL_FROM_NAME="MyDealear"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

4. Generar la clave de la aplicación.

```sh
php artisan key:generate
```

```sh
php artisan jwt:secret
```

5. Ejecutar el servidor de desarrollo.

```sh
php artisan serve
```

### Pasos Flutter:
```sh
flutter pub get
```
```sh
flutter run
```
