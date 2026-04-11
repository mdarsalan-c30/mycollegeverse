# 🧱 3. TECH STACK (HOSTINGER FRIENDLY)

Since the environment is optimized for PHP/MySQL (Hostinger), we avoid heavy Node.js runtimes.

## 🎨 FRONTEND
*   **Structure:** HTML5 Semantic Tags.
*   **Styling:** Tailwind CSS (Modern, utility-first UI).
*   **Reactivity:** Vanilla JS or Alpine.js (Lightweight and fast).
*   **Deployment Advantage:** Use CDN Tailwind for rapid setup and zero-build overhead on Hostinger.

## ⚙️ BACKEND
*   **Framework:** **Laravel** (Highly recommended for scalability).
*   **Alternative:** Core PHP (Only if Laravel is not preferred).
*   **Features:** Eloquent ORM, Blade Templates, Middleware for Auth.

## 🗄️ DATABASE
*   **Engine:** MySQL (Hostinger default).
*   **Design:** Relational structure with foreign key constraints.

## 📦 STORAGE
*   **Local Storage:** `/uploads/notes/` and `/public/storage`.
*   **Optimization:** Laravel's standard storage link system.

## 🔐 AUTHENTICATION
*   **Standard:** Laravel Sanctum (For API or SPA setups).
*   **Legacy:** Session-based authentication for Blade-heavy structures.
