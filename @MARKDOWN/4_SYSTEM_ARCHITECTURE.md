# 🏗️ 4. SYSTEM ARCHITECTURE

## Workflow Diagram
```mermaid
graph TD
    Client[Browser / Client] --> Frontend[HTML + Tailwind + JS]
    Frontend --> Backend[PHP Backend / Laravel Controllers]
    Backend --> DB[(MySQL Database)]
    Backend --> FS[File Storage / Notes & Images]
```

## Communication Style
*   **API Style:** Even in PHP/Laravel, the backend should expose RESTful endpoints for future-proofing.
*   **Endpoints Examples:**
    *   `GET /api/notes` - List/Filter notes
    *   `POST /api/posts` - Create community interaction
    *   `GET /api/chat` - Retrieve messages
