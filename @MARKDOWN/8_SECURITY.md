# 🔐 8. SECURITY (CRITICAL)

To ensure the safety of student data and system integrity, follow these best practices:

*   **File Validation:** Check MIME types (PDF, JPG, PNG) and limit file sizes (e.g., 10MB per note).
*   **Duplicate Prevention:** Use file hashing (SHA-256) to prevent uploading the exact same note multiple times.
*   **Rate Limiting:** Implement rate limits on Auth and Search APIs to prevent brute-force or scraping.
*   **Input Sanitization:** Use Laravel's built-in protection against SQL Injection and XSS (Cross-Site Scripting).
*   **CSRF Protection:** Ensure all forms have `@csrf` tokens enabled.
