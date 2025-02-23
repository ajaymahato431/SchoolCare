# School Care

## Overview

**School Care** is a comprehensive school and college management system developed during the BIC Hackathon v4.0 by Team Deadline Junkies. This platform allows schools and colleges to track, manage, and report on every aspect of student life, including academics, extracurricular activities (ECA), behavioral activities, and more. The system provides detailed summarized reports accessible to teachers, students, and parents according to their roles.

### Key Features

-   **Role-Based Accessibility:** Tailored access for teachers, students, and parents.
-   **Detailed Reporting:** Summarized reports covering academics, behavioral activities, extracurricular activities, and more.
-   **Comprehensive Tracking:** Attendance, assignments, marks, activities, scholarships, positive and negative behaviors.
-   **Setup Management:** Class mappings, grades, sections, exam types, and batch years.
-   **Support for Case Studies:** Facilitates case studies, police cases, and job applications.
-   **Talent Recognition:** Highlights strengths beyond marksheets, providing opportunities for students talented in non-academic areas.

### Technology Stack

-   **Backend:** Laravel, Filament
-   **Frontend:** HTML, CSS, JavaScript
-   **Database:** MySQL

## Installation

### Prerequisites

-   PHP >= 8.1
-   Composer
-   Node.js and npm/yarn
-   MySQL

### Steps

1. **Clone the Repository**

    ```bash
    git clone https://github.com/ajaymahato431/SchoolCare.git
    cd learners-log
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Environment Configuration**
   Copy the `.env.example` file and update the environment variables:

    ```bash
    cp .env.example .env
    ```

    Update the following variables in `.env`:

    ```env
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

4. **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

5. **Run Migrations**

    ```bash
    php artisan migrate
    ```

6. **Seed Database (Optional)**

    ```bash
    php artisan db:seed
    ```

7. **Build Frontend Assets**

    ```bash
    npm run dev
    ```

    For production:

    ```bash
    npm run build
    ```

8. **Run the Server**
    ```bash
    php artisan serve
    ```
    The application will be available at `http://localhost:8000`.

## Usage

### Roles

-   **Teachers:** Manage student data, track progress, and generate reports.
-   **Students:** View personal academic, behavioral, and extracurricular data.
-   **Parents:** Monitor their children’s performance and activities.

### Key Modules

-   **Tracking:**

    -   Attendance
    -   Assignments
    -   Marks
    -   Activities
    -   Scholarships
    -   Positive and Negative Behaviors

-   **Setup:**
    -   Class Mappings
    -   Grades
    -   Sections
    -   Exam Types
    -   Batch Years

### Additional Features

-   Summarized reports to assist in:
    -   Police cases
    -   Case studies
    -   Job applications
-   Talent recognition for students with non-academic strengths.

## Contribution

We welcome contributions to improve this project! To contribute:

1. Fork the repository.
2. Create a new branch.
3. Make your changes.
4. Submit a pull request.

## Team Deadline Junkies from Balkumari College

-   **Ajay Mahato:** Full Stack Laravel Developer
-   **Roshan Kunwar:** Frontend Developer
-   **Sony Pokhrel:** Frontend Designer

## Frontend Design - Figma Link

https://www.figma.com/proto/BJItWcQ4IMEwz8mQ5SEp3P/Untitled?node-id=0-1&t=nTK3Gd3iwBI4a9R1-1

## Contact

For any queries or issues, please feel free to open an issue on the GitHub repository or contact us directly.
