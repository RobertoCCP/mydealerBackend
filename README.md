# Proyecto backend MyDealer

Este repositorio contiene la implementación del servidor basado en Laravel para el proyecto MyDealer.

## Tabla de contenido

- [Proyecto backend MyDealer](#proyecto-backend-mydealer)
  - [Tabla de contenido](#tabla-de-contenido)
  - [Instalación fácil con Docker](#instalación-fácil-con-docker)
    - [Requisitos](#requisitos)
    - [Pasos](#pasos)
  - [Instalación en local](#instalación-en-local)
    - [Requisitos](#requisitos-1)
    - [Pasos](#pasos-1)
  - [Consideraciones](#consideraciones)
  - [¿Como usar docker para desarrollo?](#como-usar-docker-para-desarrollo)
  - [Comandos útiles de docker](#comandos-útiles-de-docker)
  - [Flujo de trabajo](#flujo-de-trabajo)

## Instalación fácil con Docker

Se implementó contenedores de docker para facilitar la instalación y ejecución de la aplicación en desarrollo.

### Requisitos

Es importante tener instalado docker (docker desktop en windows) y docker compose.
Por favor verifique las herramientas con el siguiente comando:

```sh
docker -v
docker compose version
```

### Pasos

1. Clonar e ingresar al proyecto.

```sh
git clone https://github.com/myDealerWeb/mydealer-backend.git
cd mydealer-backend
```

2. Construir los contenedores de docker

```sh
docker compose build
docker compose up
```

3. Abra otra terminal y desde la carpeta raíz del proyecto `mydealer-backend` edite las siguientes variables en el archivo `.env`.

```sh
code .env
```

```sh
DB_HOST=host_de_la_base_de_datos
DB_DATABASE=nombre_de_la_base_de_datos
DB_USERNAME=nombre_de_usuario
DB_PASSWORD=contraseña_de_la_base_de_datos
DB_PORT=puerto_de_la_base_de_datos
```

4. Verificar que los contenedores estén en ejecución, para ello ejecute el siguiente comando

```sh
docker ps
```

O ingrese [aquí](http://localhost:8000) para ver la respuesta del servidor.

Puede finalizar la ejecución de los contenedores presionando `ctrl + c` en la terminal.

## Instalación en local

### Requisitos

Asegúrese de tener instalado PHP 8.1 o superior y Composer.

```sh
php -v
composer -V
```

### Pasos

1. Clonar e ingresar al proyecto.

```sh
git clone https://github.com/myDealerWeb/mydealer-backend.git
cd mydealer-backend
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
DB_HOST=host_de_la_base_de_datos
DB_DATABASE=nombre_de_la_base_de_datos
DB_USERNAME=nombre_de_usuario
DB_PASSWORD=contraseña_de_la_base_de_datos
DB_PORT=puerto_de_la_base_de_datos
```

4. Generar la clave de la aplicación.

```sh
php artisan key:generate
```

5. Ejecutar el servidor de desarrollo.

```sh
php artisan serve
```

## Consideraciones

Cuando se ejecute el proyecto en el servidor, se debe tener en cuenta que el servidor de desarrollo de Laravel se ejecuta en el puerto 8000 por defecto. Si se desea cambiar el puerto, se puede hacer con el siguiente comando:

```sh
php artisan serve --port=8080
```

## ¿Como usar docker para desarrollo?

Para trabajar en el proyecto, se va a hacer uso de los "devcontainers" de Visual Studio Code. Para ello, se debe tener instalado la extensión "Dev Containers" en Visual Studio Code.

Por favor asegúrese de haber realizado los pasos anteriores antes de continuar.

1. Abra el proyecto en Visual Studio Code y presione `ctrl + shift + p` y escriba `>Dev Containers: Open Folder in Container`.
2. Luego la carpeta del proyecto (`mydealer-backend`).
3. Espere a que se construya el contenedor y se instalen las extensiones.
4. Cuando termine, usted se encontrará dentro de un contenedor de docker con todas las herramientas necesarias para trabajar en el proyecto.
5. Para finalizar, presione `ctrl + shift + p` y escriba `>Remote: Close Remote Connection`.

## Comandos útiles de docker

- `docker ps` - Listar los contenedores en ejecución
- `docker ps -a` - Listar todos los contenedores
- `docker images` - Listar las imágenes
- `docker volume ls` - Listar los volúmenes
- `docker stop (docker ps -q)` - Detener todos los contenedores
- `docker rm (docker ps -aq)` - Eliminar todos los contenedores
- `docker rmi (docker images -q)` - Eliminar todas las imágenes
- `docker system prune` - Eliminar todos los contenedores, imágenes y volúmenes
- `docker compose up` - Construir y ejecutar los contenedores
- `docker compose up -d` - Construir y ejecutar los contenedores en segundo plano
- `docker compose restart <nombre_del_servicio>` - Reiniciar un contenedor
- `docker compose logs` - Ver los logs de los contenedores
- `docker compose start` - Iniciar todos los servicios
- `docker compose stop` - Detener todos los servicios
- `docker compose pause` - Pausar todos los servicios
- `docker compose unpause` - Continuar ejecutando todos los servicios
- `docker compose down` - Detener y eliminar todos los contenedores
- `docker compose build` - Construir o reconstruir todas las imágenes
- `docker compose exec <nombre_del_servicio> <comando>` - Ejecutar un comando en un contenedor

## Flujo de trabajo

El flujo de trabajo que recomiendo una vez que se encuentre instalado todo el proyecto es:

1. Ubicarse en la carpeta carpeta raíz del proyecto y verifique en que ramas se encuentra con el comando `git branch`. Si no se encuentra en su rama de trabajo, utilice `git pull origin <nombre_de_la_rama>` para traer los cambios de la rama remota y use `git checkout <nombre_de_la_rama>` para cambiar de rama.
2. Si esta utilizando docker, realice los siguientes pasos:
   1. Inicie los contenedores con el comando `docker compose start`.
   2. Utilice vscode para abrir el proyecto en un devcontainer, (como se mencionó anteriormente).
   3. Una vez realizado los cambios, cerrar el contenedor y detener todos los servicios con el comando `docker compose stop`.
3. Guardar los cambios con git y subirlos al repositorio.

> [!TIP]
> Utilizar `docker compose logs` para ver los logs de todos los contenedores.
> Utilice `docker compose logs -f` para ver los logs en tiempo real. Alternativamente,
> puede ver los logs de un contenedor en específico con `docker compose logs <nombre_del_servicio>`,
> para ver en tiempo real solo agregue `-f` al final del comando.
