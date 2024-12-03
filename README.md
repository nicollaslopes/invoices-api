## Requirements

- PHP v8.1+
- Composer
- Docker

## How to test

- Install the extensions: `sudo apt-get install -y php8.1-mysql php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath`
- To download the project dependencies, run the command `composer install`
- Run the following command to start the containers:  `docker compose up -d`
- To find the MySQL container IP, run: `docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' invoices-api-db-1`
- Create a `.env` file and copy the contents from `.env.example`, updating the connection details.
- Once the containers are up, the laravel-api database will be created automatically.
- Run the command to create the migrations `php artisan migrate`
- Run the command to seed the database with records (optional) `php artisan db:seed`
- To start the project, run the command `php artisan serve`
- Visit the URL `http://localhost:8000`

## Endpoints

After you have run the seed command to populate the database, you will only change the email (the password remains "password") to generate the token.
### ```POST```
```
    http://localhost:8000/api/v1/login
```
```JSON
{
	"email": "woodrow.hoppe@example.com",
	"password": "password"
}
```

![image](https://github.com/user-attachments/assets/def3c860-0ff2-4bec-96ff-e535479f594d)

Once you have the token, you can use it to authenticate and get the route responses (use Bearer Authentication)

### ```GET```
```
    http://localhost:8000/api/v1/invoices
```

![image](https://github.com/user-attachments/assets/9982f6b1-ec15-40cf-a825-0af1ff8eaecd)

### ```POST```
```
    http://localhost:8000/api/v1/invoices
```
```JSON
{
	"user_id": "4",
	"type": "P",
	"paid": 1,
	"value": 5000.00
}
```
![image](https://github.com/user-attachments/assets/86ba988a-1b60-4d54-8059-2344765852dc)

### ```PUT```
```
    http://localhost:8000/api/v1/invoices/{id}
```
```JSON
{
	"user_id": "4",
	"type": "P",
	"paid": 1,
	"value": 7000.00,
	"payment_date": "2024-01-01 00:00:00"
}
```

![image](https://github.com/user-attachments/assets/44873596-f54e-4644-8f13-be2803a85a17)


### ```DELETE```
```
    http://localhost:8000/api/v1/invoices/{id}
```

![image](https://github.com/user-attachments/assets/bc7ae8ed-baa4-471e-90b6-edfcbc0e5c79)
