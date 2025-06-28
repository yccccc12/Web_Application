# UECS2094 Assignment Group 1
## Project Title: Fashion Retail E-commerce (Clothings)

## Getting Started
### Prerequisites
- Install [WampServer](https://www.wampserver.com/) on your machine.
- Ensure you have the following dependencies installed:
    - PHP (version compatible with your project)
    - MySQL
    - Apache

### Installation
1. Clone this repository to your WampServer `www` directory or directly copy the project folder into the `www` directory:
    ```bash
    git clone https://github.com/yccccc12/Web_Application.git
    ```
2. If cloning, navigate to the project folder:
    ```bash
    cd /c:/wamp64/www/Web_Application
    ```

### Configuration
1. Open WampServer and start all services (Apache, MySQL).
2. Open your browser and go to `http://localhost/phpmyadmin`.
3. Import the database schema:
     - Go to `http://localhost/phpmyadmin`.
     - Import the SQL file, `database.sql` that located in the project.

### Running the Application
1. Open your browser and navigate to:
     ```
     http://localhost/Web_Application
     ```
2. Follow the instructions on the report to use the application.

### Troubleshooting
- If you encounter issues, ensure all WampServer services are running.
- Check the Apache and PHP error logs for more details.

### Important Notes
- Make sure to restart WampServer after making configuration changes.
- Ensure your device is connected to the internet as some libraries and dependencies are fetched online.