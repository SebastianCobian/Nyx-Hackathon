# 🚀 Guía de Instalación — NYX Marketplace
### Equipo: Correctos F.C 2.0 | Hackathon TECNM × SOREDI

---

## Requisitos

- Raspberry Pi 4
- MicroSD de 16GB o más
- Computadora con acceso a internet
- Misma red WiFi para acceder por IP

---

## 1. Descargar Raspberry Pi Imager

1. Ve a [https://www.raspberrypi.com/software](https://www.raspberrypi.com/software)
2. Descarga la versión para tu sistema operativo
3. Instala Raspberry Pi Imager

---

## 2. Preparar la microSD

1. Abre Raspberry Pi Imager
2. Selecciona:
   - **OS:** Raspberry Pi OS Lite (64-bit)
   - **Device:** tu modelo de Raspberry Pi
   - **Storage:** tu microSD
3. Abre el menú avanzado con `Ctrl + Shift + X` y configura:
   - ✅ Activar SSH
   - Usuario y contraseña
   - WiFi (SSID, contraseña, país)
   - Zona horaria y teclado
4. Haz clic en **Write** y espera la verificación
5. Expulsa la microSD de forma segura

---

## 3. Primer arranque

1. Inserta la microSD en la Raspberry Pi
2. Conecta la alimentación
3. Espera **30-60 segundos** para que arranque

---

## 4. Conexión por SSH

Obtén la IP desde tu router o usando `ping raspberrypi.local` y conéctate:

```bash
ssh usuario@IP_DE_TU_PI
```

---

## 5. Instalar Apache2 y PHP

```bash
sudo apt update
sudo apt install apache2 php php-mysql php-mbstring -y
```

Verificar que Apache está corriendo:

```bash
systemctl status apache2
```

Probar en el navegador:

```
http://IP_DE_TU_PI
```

---

## 6. Instalar MariaDB

```bash
sudo apt install mariadb-server -y
sudo mysql_secure_installation
```

Crear la base de datos:

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE nyx CHARACTER SET utf8mb4;
CREATE USER 'nyx'@'localhost' IDENTIFIED BY 'tu_password';
GRANT ALL PRIVILEGES ON nyx.* TO 'nyx'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Importar el esquema:

```bash
sudo mysql nyx < /var/www/html/nyx/database.sql
```

---

## 7. Clonar el repositorio

```bash
cd /var/www/html
git clone URL_DEL_REPO
```

---

## 8. Solucionar permisos

Si aparece error de permisos con Git:

```bash
sudo chown -R pi:pi /var/www/html
```

Para la carpeta de imágenes:

```bash
chmod 755 /var/www/html/nyx/assets/images/products/
```

---

## 9. Configurar la base de datos

Edita el archivo `includes/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'nyx');
define('DB_USER', 'nyx');
define('DB_PASS', 'tu_password');
```

---

## 10. Reiniciar Apache

```bash
sudo systemctl restart apache2
```

---

## 11. Actualizar el proyecto

Cuando hagas cambios en el repositorio:

```bash
cd /var/www/html/nyx
git pull
sudo systemctl restart apache2
```

---

## 12. Acceder a NYX

Abre en el navegador desde cualquier dispositivo en la misma red:

```
http://IP_DE_TU_PI/nyx/index.php
```

**Admin por defecto:**
- Correo: `admin@nyx.com`
- Contraseña: `password`

---

*Correctos F.C 2.0 — Hackathon TECNM × SOREDI — Mayo 2026*
