# Catalog-data-parser

#### This project is designed to handle the importation of data from irregularly structured and potentially corrupted XLSX files into a database. It streamlines the process of parsing complex spreadsheets and ensures that the extracted data is accurately stored in the database, supporting a robust data management system.

### Installation Instructions and Setup

1) Clone the repository using:

    ``` git clone <link to the GitHub repository>```


2) Lift the Docker containers using Laravel Sail:

   ```./vendor/bin/sail up -d```


3) Run the database migrations to set up the required database:

   ```./vendor/bin/sail artisan migrate```


4) To start the import process of the XLSX file and store the data into the database, run: 

    ```./vendor/bin/sail artisan file:download```
    ```./vendor/bin/sail artisan queue:work```


5) To run unit tests and validate file processing:

    ```./vendor/bin/sail artisan test```

### Technologies and Libraries Used
- **Laravel**
- **Laravel Sail**
- **Maatwebsite/Excel**
- **PHPUnit**
- **Docker**
- **Debugbar**

#### Once installed, the application will be ready to handle XLSX file imports as configured. Simply execute the commands provided above whenever you need to import new data files. The system is designed to manage discrepancies in the data structure gracefully and ensures that all importable data is accurately processed and stored.
