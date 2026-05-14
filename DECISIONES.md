# 🧠 Decisiones Técnicas — NYX Marketplace
### Equipo: Correctos F.C 2.0 | Hackathon TECNM × SOREDI

---

## 1. ¿Por qué el nombre NYX?

**Decisión:** Nombrar el marketplace NYX, inspirado en la diosa griega de la noche.

**Razón:** Queríamos un nombre que tuviera identidad propia y no sonara genérico. NYX es una figura mitológica poderosa — tan temida que hasta Zeus le tenía respeto. Eso refleja la filosofía del proyecto: una tienda que no pide permiso para destacar. Además conecta perfectamente con el tema visual de noche, luna y cosmos.

---

## 2. ¿Por qué un tema visual de noche y no un diseño claro?

**Decisión:** Tema oscuro con estrellas animadas, paleta plateada y tipografía Cinzel.

**Razón:** La mayoría de los marketplaces usan fondos blancos y colores genéricos. Quisimos diferenciarnos desde el primer segundo que el jurado abre la URL. El tema de noche refuerza la identidad de NYX, es más agradable a la vista en sesiones largas y genera una experiencia memorable. Las estrellas animadas en CSS puro no afectan el rendimiento.

---

## 3. ¿Por qué integrar Blink Galaxy?

**Decisión:** Agregar una sección dedicada a Blink Galaxy con sus juegos y opción de pago con $BG Token.

**Razón:** El hackathon es organizado por SOREDI, la misma empresa detrás de Blink Galaxy. Integrar su ecosistema Web3 no solo suma puntos de creatividad — demuestra que entendemos el contexto del organizador y pensamos más allá del reto básico. Fue una decisión estratégica, no técnica.

---

## 4. ¿Por qué búsqueda por voz?

**Decisión:** Agregar búsqueda por voz con la Web Speech API nativa del navegador.

**Razón:** Es una feature que ningún equipo va a tener en un hackathon de PHP. Quisimos agregar algo que sorprendiera al jurado en la demo en vivo. Que el presentador diga "audífonos" y los productos aparezcan solos es más impactante que cualquier diseño. Y lo mejor: funciona sin librerías ni internet, solo con el navegador.

---

## 5. ¿Por qué animación de compra exitosa con estrellas?

**Decisión:** Al confirmar un pedido, mostrar una pantalla de celebración con lluvia de estrellas antes del resumen.

**Razón:** La experiencia post-compra es lo que más recuerdan los usuarios. La mayoría de las tiendas solo muestran un "gracias por tu compra" aburrido. Quisimos que completar una compra en NYX se sintiera como un momento especial — coherente con el universo de la marca.

---

## 6. ¿Por qué QR de entrega?

**Decisión:** Generar un código QR único por orden para que el repartidor confirme la entrega.

**Razón:** Es una solución práctica a un problema real de logística. En lugar de llamadas o confirmaciones manuales, el repartidor solo escanea el QR. Además conecta con la idea de identidad digital única por orden — similar a los tokens del ecosistema Web3 de Blink Galaxy.

---

## 7. ¿Por qué timeline visual de seguimiento?

**Decisión:** Mostrar el estado del pedido como un timeline animado con 4 etapas.

**Razón:** Los usuarios de hoy esperan transparencia en sus compras. Un simple texto "pendiente" no comunica nada. El timeline hace que el cliente sepa exactamente dónde está su pedido de un vistazo, y el paso actual tiene una animación de brillo que hace la experiencia más viva.

---

## 8. ¿Por qué autocompletado en la búsqueda?

**Decisión:** Mostrar sugerencias de productos en tiempo real mientras el usuario escribe.

**Razón:** Reduce la fricción en la experiencia de compra. Si el usuario no sabe exactamente cómo se llama el producto, las sugerencias lo guían sin que tenga que hacer una búsqueda completa. Es un detalle pequeño que hace la tienda sentirse profesional.

---

## 9. ¿Por qué panel admin con navbar propio?

**Decisión:** Separar completamente la interfaz del admin de la del cliente con su propio navbar.

**Razón:** Mezclar las vistas de admin y cliente crea confusión. Un admin no necesita ver el carrito ni "Mis Pedidos". Tener un panel dedicado hace que el flujo de trabajo sea más claro, más rápido y más profesional — igual que en plataformas reales como Shopify o WooCommerce.

---

## 10. ¿Por qué permitir subida de imágenes desde el admin?

**Decisión:** El admin puede subir imágenes de productos directamente desde el panel sin tocar el servidor.

**Razón:** En un marketplace real, el administrador no debería necesitar acceso SSH al servidor para agregar una foto. Quisimos que NYX fuera autosuficiente — que cualquier persona sin conocimientos técnicos pudiera gestionar la tienda completa desde el navegador.

---

## 11. ¿Por qué selector de tallas para ropa?

**Decisión:** Mostrar tallas disponibles en productos de ropa, con tallas agotadas tachadas.

**Razón:** Una tienda de ropa sin selector de tallas no es una tienda real. Quisimos que NYX tuviera la misma experiencia que Amazon o Liverpool — el cliente sabe qué tallas hay antes de agregar al carrito, evitando frustración post-compra.

---

## 12. ¿Por qué historial de búsquedas?

**Decisión:** Guardar las últimas 6 búsquedas del usuario como tags clickeables.

**Razón:** Los usuarios repiten búsquedas con frecuencia. El historial reduce los pasos para encontrar lo que ya buscaron antes, mejorando la experiencia sin necesidad de base de datos — se guarda en sessionStorage del navegador, sin costo de servidor.

---

*Correctos F.C 2.0 — Hackathon TECNM × SOREDI — Mayo 2026*
