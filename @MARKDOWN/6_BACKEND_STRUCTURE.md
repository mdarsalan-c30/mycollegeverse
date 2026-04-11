# ⚙️ 6. BACKEND STRUCTURE (LARAVEL)

The project follows the standard Laravel directory structure for clarity and scalability.

```text
app/
 ├── Models/           # Database models (User, Note, College, etc.)
 ├── Http/
 │    ├── Controllers/ # Business logic handlers
 │    ├── Middleware/  # Auth & Security checks
 ├── Services/         # External APIs or complex logic

routes/
 ├── web.php           # Browser-based routes (Views)
 ├── api.php           # API-based routes (REST)

resources/
 ├── views/            # Blade templates (Frontend)

public/
 ├── uploads/          # Publicly accessible file uploads
```
