
# election-party
A responsive Election Party laravel web application with Votes enrolling &amp; Results for managing election parties and candidates where calculating votes dynamically, and showcasing results based on cities.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Folder Structure](#folder-structure)
- [Database](#database)
- [Contributing](#contributing)
- [License](#license)

## Features

- CRUD operations for parties and candidates
- User-defined Pagination
- Dynamic vote calculation based on cities with percentage
- Unique constraints for candidate enrollment and party names
- One-to-one and one-to-many relationships between tables (address, candidate, party, state, city)
- State and city dependent dropdowns
- Leading candidate calculation and display
- Marquee tag for showing results like news channels
- Buttons for easy navigation: party registration, party details, candidate registration, candidate details, votes
- Live vote updates with dynamic result recalculation
- Blade templating engine
- Validations
- Service Logic
- Dependent Dropdowns
- Image Handling
- Error Handling
- Drag & Drop Functionality
- CSRF Protection
- Eloquent ORM
- Grid sorting,  Search and Sort Functionality
- Bootstrap integration
- jQuery Integration
- AJAX, event listeners, and DOM manipulation

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/Gurudev11/election-party.git
    ```

2. Navigate to the project directory:
    ```bash
    cd election-party
    ```

3. Install dependencies:
    ```bash
    composer install
    npm install
    ```

4. Copy the `.env.example` file to `.env`:
    ```bash
    cp .env.example .env
    ```

5. Generate an application key:
    ```bash
    php artisan key:generate
    ```

6. Set up your database credentials in the `.env` file.

7. Run the database migrations:
    ```bash
    php artisan migrate
    ```

8. Start the development server:
    ```bash
    php artisan serve
    ```
9. Access the application in your web browser at http://localhost:8000/index.

## Usage

### CRUD Operations

- **Create**: Register new parties and candidates.
- **Read**: View details of parties and candidates.
- **Update**: Edit existing party and candidate details.
- **Delete**: Remove parties and candidates.

### Pagination, Search, and Sort

- Pagination to see the number of records in party & candidate details, search to fetch the records, and  grid sort features to rearrange & view the records.

### Validations

- Form validations and rules for conventions to store accurate details in database in both frontent & backend.

### Service Logic

- We do not have same names for the party.
- We can calculate age from the candidate dob and to store in database.
- We can not have more than one candidate from same city to enroll in one party.
- We’re calculating votes, who’s leading and with the percentage dynamically from wherever we’re checking.

### Vote Calculation

- Votes are calculated dynamically when added.
- Results are recalculated to determine the leading candidate.
- Display leading candidates based on selected city.

### Navigation Buttons

- **Party Registration**: Register new parties.
- **Party Details**: View and manage party details.
- **Candidate Registration**: Register new candidates.
- **Candidate Details**: View and manage candidate details.
- **Votes**: View all votes and results.

### Image Handling  

- When you upload images in candidate form, that will be stored in public/photo folder in your project and the path is stored as string in database.
- And for the party logos, public/logos folder.
- If you like to make this as large set of application, you can store the images as binary data, also known as BLOB (Binary Large OBject) data but this has some disadvantageous.

### Error Handling

- Pass the error from backend to the view and handle the form perfectly to show the message the view.

### Drag & Drop

- Drag & drop functionality are included in party & candidate tables.

### CSRF Protection

- This application implements CSRF protection using Laravel's built-in CSRF token feature, ensuring that forms include a unique token to prevent cross-site request forgery attacks. This token is automatically verified on form submission to enhance security.

### Marquee Tag

- Results are displayed in a marquee tag at the top of the main page, similar to news channels.

### Dependent Dropdowns

- State and relative cities dropdowns on the main page to filter and view results.

## Folder Structure

Brief explanation of the important folders and files in my project.
├── app
│ ├── Http
│ │ └── Controllers # Application controllers
│ └── Models # Application models
├── bootstrap # Bootstrap files
├── config # Configuration files
├── database # Database migrations and seeds
├── public
│ ├── css # CSS files
│ └── storage # Public storage for uploaded files
├── resources
│ ├── views # Blade template files
│ └── assets # Other frontend assets (JS, images, etc.)
├── routes # Route definitions
├── storage # Storage for logs, cache, etc.
├── tests # Test files
└── vendor # Composer dependencies

## Database

- Change your username, password, database name in .env file to set up the database.

## Contributing

If you wish to contribute to the project, follow these steps:

1. Fork the repository.
2. Create a new branch:
    ```bash
    git checkout -b feature/your-feature-name
    ```
3. Make your changes and commit them:
    ```bash
    git commit -m "Add your commit message here"
    ```
4. Push to the branch:
    ```bash
    git push origin feature/your-feature-name
    ```
5. Open a pull request.

## License

This project is licensed under the MIT License.

## Contact

If you have any questions or suggestions, feel free to contact us at [gurudevz237@gmail.com].

