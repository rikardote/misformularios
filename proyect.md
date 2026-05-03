# 📦 PROYECTO: FORMULARIOS

## 🧠 Descripción

**Formularios** es una aplicación web inspirada en Google Forms que permite crear, gestionar y responder formularios dinámicos.

El sistema está diseñado para ser:

* Escalable
* Modular
* Fácil de extender
* Basado en componentes (Livewire)

---

## 🎯 Objetivo del MVP

Primera versión funcional del sistema:

* Crear formularios
* Agregar preguntas dinámicas
* Compartir formularios mediante URL pública
* Responder formularios
* Almacenar respuestas
* Visualizar resultados básicos

---

## 🧰 Stack Tecnológico

* Backend: Laravel
* Interactividad: Livewire
* UI: Tailwind CSS
* Base del proyecto: Laravel Boost
* Base de datos: MySQL / PostgreSQL

---

## 🏗️ Arquitectura General

### Entidades principales

* User
* Form
* Question
* Option
* Response
* Answer

---

## 🗃️ Modelo de Datos

### users

* id
* name
* email
* password
* timestamps

---

### forms

* id
* user_id
* uuid
* title
* description
* is_public
* timestamps

---

### questions

* id
* form_id
* type (text, radio, checkbox, select)
* question_text
* is_required
* order
* timestamps

---

### options

* id
* question_id
* option_text
* timestamps

---

### responses

* id
* form_id
* timestamps

---

### answers

* id
* response_id
* question_id
* answer_text (nullable)
* option_id (nullable)
* timestamps

---

## 🔗 Relaciones

* User → hasMany → Forms
* Form → hasMany → Questions
* Question → hasMany → Options
* Form → hasMany → Responses
* Response → hasMany → Answers

---

## ⚙️ Funcionalidades

### 1. Panel de usuario

* Crear formulario
* Editar formulario
* Listar formularios

---

### 2. Constructor de formularios (Builder)

Permite:

* Agregar preguntas
* Editar preguntas
* Definir tipo de pregunta:

  * Texto
  * Opción múltiple
  * Checkbox
  * Select
* Marcar preguntas como requeridas

---

### 3. Formulario público

* Acceso mediante URL única (UUID)
* Render dinámico de preguntas
* Validación de respuestas
* Envío de formulario

---

### 4. Respuestas

* Registro de cada envío
* Asociación con preguntas
* Almacenamiento flexible

---

### 5. Resultados

* Tabla de respuestas
* Conteo básico por opción

---

## 🧩 Componentes Livewire

### Forms/Builder

Responsabilidad:

* Crear y editar formularios
* Manejar estado de preguntas

---

### Forms/QuestionEditor

Responsabilidad:

* Editar una pregunta individual

---

### Forms/PublicForm

Responsabilidad:

* Mostrar formulario público
* Validar respuestas
* Guardar respuestas

---

### Forms/Results

Responsabilidad:

* Mostrar resultados del formulario

---

## 🧠 Flujo del Sistema

### Creación

Usuario crea formulario → agrega preguntas → guarda

---

### Publicación

Formulario disponible en:

```
/f/{uuid}
```

---

### Respuesta

Usuario responde → sistema guarda:

* response
* answers

---

### Visualización

Usuario consulta resultados desde panel

---

## 🧭 Rutas

```php
Route::middleware(['auth'])->group(function () {
    Route::resource('forms', FormController::class);
    Route::get('forms/{form}/builder', Builder::class);
    Route::get('forms/{form}/results', Results::class);
});

Route::get('f/{uuid}', PublicForm::class);
Route::post('f/{uuid}', SubmitFormController::class);
```

---

## 🔐 Seguridad

* Uso de UUID en formularios públicos
* Protección CSRF
* Validación backend obligatoria
* Formularios privados (futuro)

---

## ⚠️ Decisiones Técnicas

### Uso de UUID

Evita exposición de IDs secuenciales.

---

### Estado en Livewire

El builder trabaja en memoria:

* No guarda cambios hasta confirmar

---

### Tipos de preguntas extensibles

Preparado para crecimiento futuro.

---

## 🚀 Roadmap

### Fase 1 (MVP)

* CRUD de formularios
* Builder funcional
* Formulario público
* Guardado de respuestas
* Resultados básicos

---

### Fase 2

* Reordenamiento de preguntas
* Mejoras UI
* Validaciones avanzadas

---

### Fase 3

* Lógica condicional
* Estadísticas
* Exportación de datos

---

### Fase 4

* Multiusuario avanzado
* Permisos
* API pública

---

## 📁 Estructura del Proyecto

```
app/
 ├── Models/
 ├── Livewire/
 │    └── Forms/
 ├── Http/
 │    └── Controllers/
 ├── Services/ (futuro)
```

---

## 🧪 Estrategia de Desarrollo

1. Configuración del proyecto
2. Migraciones y modelos
3. CRUD básico de formularios
4. Implementación de Livewire Builder
5. Formulario público
6. Guardado de respuestas
7. Resultados

---

## 🧠 Buenas Prácticas

* No sobrecargar componentes Livewire
* Separar lógica en servicios cuando crezca
* Validar siempre en backend
* Mantener código modular

---

## 📌 Notas Finales

Este proyecto está diseñado como base escalable.

No intentar replicar todo Google Forms desde el inicio.

Construir por fases y validar cada etapa antes de avanzar.

---
