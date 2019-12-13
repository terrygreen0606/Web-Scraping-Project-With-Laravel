# Web-Scraping-Project-With-Laravel
Full project using API, PHP(Laravel), MySQL and CSV 

# Install
composer install

# Migrate Database (after giving the database name)
php artisan migrate

# Run
php artisan serve

# Functionalities
- This project uses MoneyRobot.com API, OnehourIndexing.co Api AND Dropbox API.
- This project is for a user who wants to find his website address or its anchor text in the other websites and store them in Dropbox with the formate of CSV.

    a. For scraping website addresses and anchor texts, MoneyRobot.com API was used.
    
    b. Send the result to OnehourIndexing.co as the format of batches.
    
    c. Store the result as the format of CSV to Dropbox.
- This is real complicated that it has to integrate several APIs and also scraping from a multi-function websites is required.
