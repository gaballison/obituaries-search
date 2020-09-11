# Obituaries Search

This is a PHP web application I created in 2015-2016 to provide a way for people to access the obituary indexes created by library volunteers in Excel spreadsheets. You can view it in action here: https://[jefflibrary.org/obituaries]

Because I hacked this together for my own internal use, it doesn't currently include any SQL to create a database table or functionality to allow people to upload CSV files directly into the database; I do those things manually in PhpMyAdmin. 

My goal now is to refactor this using Laravel to add functionality for our volunteers to upload their spreadsheets directly and make the code more extensible for others to use.