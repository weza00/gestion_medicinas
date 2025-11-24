# Sistema de Gestión y Entrega de Medicamentos

## 1. Descripción General del Proyecto
Este sistema es una aplicación web orientada a la gestión, validación y entrega de medicamentos para un entorno hospitalario o de clínica. Combina funcionalidades de catálogo público, validación clínica mediante receta médica y procesos de retiro seguro mediante un código único.

El objetivo principal es permitir que los pacientes puedan explorar medicamentos libremente y realizar pedidos controlados mediante verificación profesional.

El personal del hospital contará con una interfaz compartida, pero con accesos diferenciados según su rol.

---

## 2. Roles del Sistema
El sistema contempla cuatro roles principales:

### 2.1. **Paciente** (Vista independiente)
- Puede ver el catálogo completo sin necesidad de iniciar sesión.
- Para añadir productos al carrito debe autenticarse.
- Puede subir su receta médica en formato PDF o imagen.
- Envía un pedido que debe ser validado.
- Recibe un código único tras aprobación.
- Puede consultar el estado de su pedido.
- Acude presencialmente a retirar los medicamentos.

### 2.2. **Validador** (Interfaz hospitalaria)
- Revisa pedidos pendientes.
- Verifica que los medicamentos solicitados coincidan con la receta.
- Aprueba o rechaza pedidos.
- Deja observaciones al paciente (opcional).

### 2.3. **Farmacéutico** (Interfaz hospitalaria)
- Valida el código presentado por el paciente.
- Visualiza los medicamentos aprobados.
- Entrega los medicamentos físicamente.
- Marca el pedido como ENTREGADO.

### 2.4. **Administrador** (Interfaz hospitalaria)
- CRUD de medicamentos y categorías.
- Gestión de usuarios (creación y mantenimiento de validadores, farmacéuticos y otros administradores).
- Configuración general del sistema.
- Control de catálogo, precios y stock.
- Visualización de reportes y logs.

---

## 3. Flujo General del Sistema
### 3.1. **Paciente**
1. Visualiza catálogo público.
2. Inicia sesión para realizar compras.
3. Agrega medicamentos al carrito.
4. Sube receta médica.
5. Envía pedido.
6. Espera la validación del Validador.
7. Recibe código único.
8. Va a farmacia y presenta el código.

### 3.2. **Validador**
1. Revisa pedidos pendientes.
2. Verifica receta vs medicamentos solicitados.
3. Aprueba o rechaza.
4. Si aprueba → se genera código del pedido.

### 3.3. **Farmacéutico**
1. Recibe código del paciente.
2. Consulta pedido asociado.
3. Entrega medicamentos.
4. Marca como ENTREGADO.

### 3.4. **Administrador**
1. Administra catálogo, usuarios y configuraciones.
2. Revisa estadísticas e historial.

---

## 4. Base de Datos Completa
A continuación se define la estructura recomendada de tablas.

### **4.1. Tabla: usuarios**
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT PK AI | Identificador único |
| nombre | VARCHAR(100) | Nombre del usuario |
| email | VARCHAR(150) | Correo único |
| password | VARCHAR(255) | Contraseña en hash |
| rol | ENUM('paciente','validador','farmaceutico','admin') | Rol del usuario |
| creado_en | DATETIME | Fecha de registro |

---

### **4.2. Tabla: categorias**
| Campo | Tipo |
|--------|------|
| id | INT PK AI |
| nombre | VARCHAR(100) |
| descripcion | TEXT |

---

### **4.3. Tabla: medicamentos**
| Campo | Tipo |
|--------|------|
| id | INT PK AI |
| categoria_id | INT FK categorias(id) |
| nombre | VARCHAR(150) |
| descripcion | TEXT |
| presentacion | VARCHAR(100) |
| precio | DECIMAL(10,2) |
| stock | INT |
| estado | TINYINT(1) (1=activo,0=inactivo) |

---

### **4.4. Tabla: pedidos**
| Campo | Tipo |
|--------|------|
| id | INT PK AI |
| usuario_id | INT FK usuarios(id) |
| receta_archivo | VARCHAR(255) | Ruta o nombre del archivo |
| estado | ENUM('pendiente','aprobado','rechazado','entregado') |
| codigo_retiro | VARCHAR(20) NULL |
| creado_en | DATETIME |

---

### **4.5. Tabla: pedido_detalle**
| Campo | Tipo |
|--------|------|
| id | INT PK AI |
| pedido_id | INT FK pedidos(id) |
| medicamento_id | INT FK medicamentos(id) |
| cantidad | INT |

---

### **4.6. Tabla: logs** (opcional pero recomendable)
| Campo | Tipo |
|--------|------|
| id | INT PK AI |
| usuario_id | INT FK usuarios(id) |
| accion | VARCHAR(255) |
| fecha | DATETIME |
| referencia_id | INT NULL |

---

## 5. Estructura Recomendada de Carpetas (MVC)
A continuación se sugiere una estructura organizada para un proyecto PHP con MVC.

```
/gestion_medicinas
│
├── /app
│   ├── /controllers
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── CatalogoController.php
│   │   ├── PedidoController.php
│   │   ├── ValidacionController.php
│   │   ├── FarmaciaController.php
│   │   ├── AdminController.php
│   │
│   ├── /models
│   │   ├── Usuario.php
│   │   ├── Medicamento.php
│   │   ├── Pedido.php
│   │   ├── PedidoDetalle.php
│   │   ├── Categoria.php
│   │   ├── Log.php
│   │
│   ├── /views
│   │   ├── /publico
│   │   ├── /paciente
│   │   ├── /hospital
│   │   │   ├── /validador
│   │   │   ├── /farmaceutico
│   │   │   ├── /admin
│   │   ├── layout.php
│   │
│   ├── /core
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Auth.php
│
├── /public
│   ├── index.php
│   ├── /css
│   ├── /js
│   ├── /img
│   ├── /uploads (recetas)
│
├── /config
│   ├── config.php
│
├── /routes
│   ├── web.php
│
└── /sql
    ├── estructura_bd.sql
```

---

## 6. Explicación de Carpetas

### **/app/controllers**
Contiene los controladores que reciben solicitudes HTTP y coordinan la lógica.

### **/app/models**
Modelos conectados a la base de datos. Cada tabla tendrá su clase.

### **/app/views**
Vistas organizadas por tipo de usuario.

### **/app/core**
Clases base del framework casero: router, seguridad, base de datos.

### **/public**
Arquivos accesibles directamente desde el navegador.

### **/config**
Contiene configuraciones del sistema.

### **/routes**
Archivo con las rutas y controladores asignados.

### **/sql**
Scripts SQL como respaldo o instalación.

---

## 7. Conclusión
Este documento recoge la visión completa del sistema, roles, flujos, base de datos y estructura técnica. Está preparado para guiar directamente el desarrollo del proyecto final, manteniendo una arquitectura clara, escalable y alineada con un entorno hospitalario real.

