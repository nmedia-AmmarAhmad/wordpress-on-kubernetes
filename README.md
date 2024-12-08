# wordpress-on-kubernetes
Demo Project: CI/CD Pipeline for WordPress Site with Jenkins and SonarQube
Overview
This project demonstrates how to set up a local WordPress site and integrate a CI/CD pipeline using Jenkins and SonarQube. The pipeline automates the process of code analysis with SonarQube and deployment to a local WordPress site hosted in a Docker container.

Setup Instructions
1. Set Up WordPress, MySQL, and phpMyAdmin Using Docker Compose
This YAML configuration sets up WordPress, a MySQL database, and phpMyAdmin in separate Docker containers.

Steps:
Create a docker-compose.yml file in your project directory with the following content:
yaml
Copy code
version: '3.8'

services:
  wordpress:
    image: wordpress:latest
    container_name: wordpress
    restart: always
    ports:
      - "8085:80"  # Expose WordPress on localhost:8085
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_USER: wp_user
      WORDPRESS_DB_PASSWORD: wp_password
      WORDPRESS_DB_NAME: wp_database
    volumes:
      - ./new-wordpress:/var/www/html  # Mount local WordPress files for editing

  db:
    image: mysql:5.7
    container_name: wordpress_db
    restart: always
    environment:
      MYSQL_DATABASE: wp_database
      MYSQL_USER: wp_user
      MYSQL_PASSWORD: wp_password
      MYSQL_ROOT_PASSWORD: root_password
    volumes:
      - db_data:/var/lib/mysql  # Persist database data

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: phpmyadmin
    restart: always
    ports:
      - "8081:80"  # Expose phpMyAdmin on localhost:8081
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: root_password

volumes:
  db_data:  # This volume persists your database data
Run the following command in your terminal to start the services:
bash
Copy code
docker-compose up -d
WordPress will be available at http://localhost:8085
phpMyAdmin will be available at http://localhost:8081
2. Set Up Jenkins and SonarQube Using Docker Compose
This YAML configuration sets up Jenkins for Continuous Integration (CI) and SonarQube for static code analysis.

Steps:
Create a docker-compose.yml file for Jenkins and SonarQube in your project directory:
yaml
Copy code
version: '3.8'

services:
  sonarqube:
    image: sonarqube:latest
    container_name: sonarqube
    ports:
      - "9000:9000"  # SonarQube Web UI
      - "9092:9092"  # Optional additional services
    environment:
      - SONAR_ES_BOOTSTRAP_CHECKS_DISABLE=true  # Disables Elasticsearch bootstrap checks for local testing
    volumes:
      - sonarqube_data:/opt/sonarqube/data
      - sonarqube_logs:/opt/sonarqube/logs
      - sonarqube_extensions:/opt/sonarqube/extensions

  jenkins:
    image: jenkins/jenkins:lts
    container_name: jenkins
    privileged: true
    user: root
    ports:
      - "8080:8080"  # Jenkins Web UI
      - "50000:50000"  # Jenkins agent communication
    volumes:
      - /home/${myname}/jenkins_compose/jenkins_configuration:/var/jenkins_home
      - /var/run/docker.sock:/var/run/docker.sock

volumes:
  sonarqube_data:
  sonarqube_logs:
  sonarqube_extensions:
Run the following command to start Jenkins and SonarQube:
bash
Copy code
docker-compose up -d
Jenkins will be available at http://localhost:8080
SonarQube will be available at http://localhost:9000
3. Jenkins Pipeline for Continuous Integration and Deployment
Now that Jenkins and SonarQube are set up, the next step is to create a Jenkins pipeline that performs Continuous Integration (CI) and Continuous Deployment (CD).

Steps:
Push Your Code to GitHub:

Upload your WordPress plugin or project code to GitHub (or another Git repository).
Create a Jenkins Pipeline:

In Jenkins, create a new Pipeline job.
Connect Jenkins to your GitHub repository by configuring the pipeline to pull the code.
Add SonarQube Analysis to Your Pipeline:

Configure Jenkins to run SonarQube analysis on your code. This step will analyze the code for quality and potential issues before proceeding with deployment.
Create a Deployment Script (deploy.sh):

The script will deploy the code to your local WordPress container by copying the necessary plugin files and restarting the Apache server to make sure the new plugin is activated.
bash
Copy code
#!/bin/bash

# Define variables
WP_PLUGIN_DIR="/var/www/html/wp-content/plugins"
JENKINS_WORKSPACE="/var/jenkins_home/workspace/CI-CD-Project"
PLUGIN_NAME="my-wordpress-plugin"
WP_CONTAINER_NAME="wordpress"
ADMIN_EMAIL="your_email@example.com"

# Define paths
LOCAL_PLUGIN_PATH="$JENKINS_WORKSPACE/$PLUGIN_NAME"

echo "Starting deployment to local WordPress site..."

# Ensure the WordPress container is running
if ! docker ps | grep -q $WP_CONTAINER_NAME; then
    echo "Error: WordPress container '$WP_CONTAINER_NAME' is not running."
    echo "Deployment failed. Sending email to $ADMIN_EMAIL."
    echo "Subject: Deployment Failed" | sendmail -v $ADMIN_EMAIL
    exit 1
fi

# Copy plugin files into the WordPress container
if docker cp "$LOCAL_PLUGIN_PATH" "$WP_CONTAINER_NAME:$WP_PLUGIN_DIR/"; then
    echo "Plugin files copied successfully."
else
    echo "Error copying plugin files."
    echo "Deployment failed. Sending email to $ADMIN_EMAIL."
    echo "Subject: Deployment Failed" | sendmail -v $ADMIN_EMAIL
    exit 1
fi

# Restart Apache to ensure WordPress loads the updated plugin
if docker exec $WP_CONTAINER_NAME service apache2 restart; then
    echo "Apache restarted successfully."
    echo "Deployment successful. Sending email to $ADMIN_EMAIL."
    echo "Subject: Deployment Successful" | sendmail -v $ADMIN_EMAIL
else
    echo "Error restarting Apache."
    echo "Deployment failed. Sending email to $ADMIN_EMAIL."
    echo "Subject: Deployment Failed" | sendmail -v $ADMIN_EMAIL
    exit 1
fi

echo "Deployment complete! Check your plugin at http://localhost:8085/wp-admin/plugins.php"
Configure Jenkins to Run the Deployment Script:

After the SonarQube analysis is successful, configure Jenkins to run the deploy.sh script to deploy the code to the WordPress container.
Post-Build Actions:

Jenkins will send you an email notification after the deployment, letting you know if it was successful or failed.
Conclusion
Now you have a fully automated CI/CD pipeline that:

Analyzes your code with SonarQube.
Deploys the code to a local WordPress site.
Notifies you about the success or failure of the deployment.
Steps Summary:
Set up a local WordPress, MySQL, and phpMyAdmin environment using Docker.
Set up Jenkins and SonarQube for CI/CD using Docker.
Upload your code to GitHub and create a Jenkins pipeline to analyze the code with SonarQube.
Add a deployment script (deploy.sh) to copy files to the WordPress container and restart Apache.
Jenkins will automatically deploy your plugin to the local WordPress site.

