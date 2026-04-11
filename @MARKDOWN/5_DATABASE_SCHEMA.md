# 🗄️ 5. DATABASE SCHEMA

## Data Relationships
*   `Users` belong to `Colleges`.
*   `Notes` belong to `Subjects`, `Users` (Creator), and `Colleges`.
*   `Ratings` and `Comments` are linked to `Notes`, `Professors`, or `Posts`.

## Tables

### 👤 USERS
- `id` (PK)
- `name`
- `email` (Unique)
- `password`
- `role` (Admin, Contributor, Student)
- `college_id` (FK)
- `created_at`

### 🏫 COLLEGES
- `id` (PK)
- `name`
- `location`
- `created_at`

### 📚 SUBJECTS
- `id` (PK)
- `name`
- `course`
- `semester`

### 📄 NOTES
- `id` (PK)
- `title`
- `file_path`
- `subject_id` (FK)
- `user_id` (FK - Creator)
- `college_id` (FK)
- `downloads` (Counter)
- `created_at`

### ⭐ NOTE RATINGS
- `id` (PK)
- `note_id` (FK)
- `user_id` (FK)
- `rating` (1-5)

### 💬 POSTS (COMMUNITY)
- `id` (PK)
- `title`
- `content`
- `user_id` (FK)
- `college_id` (FK)
- `created_at`

### 💭 COMMENTS
- `id` (PK)
- `post_id` (FK)
- `user_id` (FK)
- `content`
- `parent_id` (Self-referential for threading)

### 💬 CHAT MESSAGES
- `id` (PK)
- `sender_id` (FK)
- `receiver_id` (FK)
- `message`
- `file_path` (Optional for sharing)
- `created_at`

### 👨‍🏫 PROFESSORS
- `id` (PK)
- `name`
- `college_id` (FK)
- `subject`

### ⭐ PROFESSOR RATINGS
- `id` (PK)
- `professor_id` (FK)
- `user_id` (FK)
- `teaching` (Rating)
- `strictness` (Rating)
- `marks` (Rating)
- `attendance` (Rating)
- `review` (Text)

### 🏫 COLLEGE REVIEWS
- `id` (PK)
- `college_id` (FK)
- `user_id` (FK)
- `rating` (1-5)
- `review` (Text)
