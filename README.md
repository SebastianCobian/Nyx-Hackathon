# 🌙 NYX Marketplace
### *"La noche que ilumina tus compras"*
> Hackathon TECNM × SOREDI — 24 horas para construir, competir y ganar.

---

## 👥 Equipo — Correctos F.C 2.0

| Nombre | No. Control | Rol |
|---|---|---|
| Oscar Francisco Alonso Sanchez | 23210539 | **Líder del equipo** — Dirección creativa del proyecto y desarrollo de NYX |
| Humberto Sebastian Cobian Beas | 23210569 | Documentación, creatividad y presentador del pitch |
| Gualberto Castro Castellanos | 23210564 | Configuración y deploy del servidor Raspberry Pi 4 |
| Esdras Xavier Vazquez Alvarez | 20212437 | Desarrollo principal de NYX y base de datos |

---

## 💡 El concepto: NYX

NYX lleva el nombre de la diosa griega de la noche — una fuerza primordial tan poderosa que hasta los propios dioses le temían. Elegimos su nombre porque nuestra tienda es ese universo infinito donde la luna guía tus compras y todo lo que buscas brilla con luz propia.

> *"No es solo una tienda. Es un cosmos donde cada producto tiene su lugar entre las estrellas."*

---

## 🎨 Identidad visual

| Elemento | Estado |
|---|---|
| Nombre | ✅ NYX |
| Eslogan | ✅ *"La noche que ilumina tus compras"* |
| Logo | ✅ Luna plateada con paquete y estrella |
| Paleta de colores | ✅ Tema oscuro / noche / plateado |
| Tipografía | ✅ Cinzel + DM Sans |
| Animaciones | ✅ Estrellas parpadeantes + logo flotante |
| Favicon | ✅ Logo NYX en pestaña del navegador |

---

## ✅ Funcionalidades implementadas — 99% completado

### Core del marketplace
- [x] Catálogo de productos con búsqueda, filtros y categorías
- [x] Autocompletado en buscador en tiempo real
- [x] Búsqueda por voz con Web Speech API
- [x] Historial de búsquedas recientes
- [x] Carrito de compras AJAX sin recargar página
- [x] Validación de stock en tiempo real
- [x] Selector de tallas para ropa (XS, S, M, G, XG, XXL, 3XL)
- [x] Lightbox para ver imágenes en grande
- [x] Sistema de reseñas con estrellas
- [x] Alerta de stock bajo (menos de 3 unidades)

### Autenticación y usuarios
- [x] Login y registro con hash bcrypt
- [x] Roles — admin y cliente
- [x] Admins no pueden realizar compras
- [x] Panel cliente — historial de pedidos

### Checkout y pagos
- [x] Checkout con datos de envío
- [x] Métodos de envío — Estándar y Exprés
- [x] Simulador de pago con tarjeta
- [x] Pago con $BG Token — integración Blink Galaxy
- [x] Animación de compra exitosa con lluvia de estrellas
- [x] Timeline visual de seguimiento de pedido
- [x] Código QR único por orden para confirmar entrega

### Panel Admin
- [x] Dashboard con estadísticas en tiempo real
- [x] CRUD completo de productos con subida de imágenes
- [x] Gestión de pedidos con cambio de estado
- [x] CRUD de categorías
- [x] Gestión de usuarios con opción de eliminar
- [x] Navbar exclusivo del panel admin

### Extras
- [x] Integración Blink Galaxy — Racerloop y Outer Ring
- [ ] ~~Asistente virtual Luna~~ — Se intentó integrar con Groq, Gemini y OpenAI pero las APIs requerían créditos. Se descartó para no arriesgar el deploy.
- [x] Diseño responsive mobile-first
- [x] Deploy en Raspberry Pi 4 accesible por IP en red local

### 🔄 Últimos detalles
- [ ] Pruebas finales en todos los dispositivos
- [ ] Ajustes menores de UI

---

## 🛠️ Stack tecnológico

| Capa | Tecnología |
|---|---|
| Hardware | Raspberry Pi 4 |
| Servidor | Apache + PHP 8.2 |
| Base de datos | MariaDB |
| Frontend | HTML5 + CSS3 + JavaScript vanilla |
| Tipografía | Google Fonts — Cinzel + DM Sans |
| Voz | Web Speech API nativa del navegador |

---

## 🤖 Uso de IA

| Herramienta | Uso |
|---|---|
| ChatGPT | Diseño del logo, configuración de Apache y dudas del servidor |
| Gemini | Ideas para el concepto de marca y naming |
| Claude | Integración Blink Galaxy, estructura de DB, dudas de PHP y CSS |
| Grok | Generación del eslogan "La noche que ilumina tus compras" |

---

## 🚀 Deploy

- **Hardware:** Raspberry Pi 4
- **SO:** Raspberry Pi OS
- **Servidor:** Apache + PHP 8.2
- **Base de datos:** MariaDB
- **URL local:** `http://172.16.225.22/index.php`
- **Acceso:** IP accesible desde la red del evento

---

*Correctos F.C 2.0 — Hackathon TECNM × SOREDI — Mayo 2026*
*Actualización final: madrugada del día 2 — casi listos para ganar* 🌙
