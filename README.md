#Project Title: Catalog-data-parser

Project Overview
This project is designed to handle the importation of data from irregularly structured and potentially corrupted XLSX files into a database. It streamlines the process of parsing complex spreadsheets and ensures that the extracted data is accurately stored in the database, supporting a robust data management system.

Installation Instructions and Setup
1) Lift the Docker containers using Laravel Sail.
2) Copy the .env_example file to create a .env file.

3) Run the database migrations to set up the required database.
4) To start the import process of the XLSX file and store the data into the database, run:
   ./vendor/bin/sail artisan file:download

Usage
Once installed, the application will be ready to handle XLSX file imports as configured. You simply need to execute the command provided above whenever you need to import new data files. The system is designed to manage discrepancies in the data structure gracefully and ensures that all importable data is accurately processed and stored.
