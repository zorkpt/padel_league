# Padel League Management Platform

## Objective

The objective of the Padel League Management Platform is to create a web-based solution aimed at casual, non-professional padel players. It seeks to 'professionalize' friendly padel matches by simplifying the organization, participation, and management of local padel leagues. The platform aims to provide an accessible, efficient, and user-friendly tool for the grassroots padel community, enhancing the social aspect of the game while adhering to good software development practices.

## Development Goals

1. **Robust User Management:** Implement comprehensive user management with secure registration and login processes. Allow users to create and update their profiles with avatar images, calculate and display user win/loss ratios and other relevant stats.

2. **Dynamic League Management:** Enable users to create, join, and manage leagues. Track active league members and their rankings within the league.

3. **Messaging:** Implement a messaging system that enables users to communicate with each other within the platform, contributing to a more collaborative and interactive user experience.

4. **League-Specific Chat System:** Each league should have its own dedicated chat for discussions related to matches, strategies, and other league-specific topics.

5. **Notification System:** Develop a notification system to alert users about upcoming matches, changes in the league, and other important updates.

6. **Test Coverage:** Strive for comprehensive test coverage to ensure the reliability and stability of the application.

7. **Code Quality:** Aim for clean, maintainable code following industry best practices.

## Tech Stack

- **PHP:** Server-side programming.
- **MySQL:** Database management.
- **Tailwind CSS:** Styling and layout.
- **JavaScript:** Client-side programming.

## Getting Started
### Prerequisites

#### Traditional Setup
- A local PHP environment
- MySQL server

#### Docker Setup
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Installation

#### Traditional Setup
Detailed instructions on how to set up your traditional PHP environment and install the platform will be provided soon.

#### Docker Setup
1. **Clone the Repository**
   ```bash
   git clone git@github.com:zorkpt/padel_league.git
   cd padel_league


2. **Create a `.env` File**
   ```bash
    cp .env.example .env
    ```
3. **Build the Docker Containers**
    ```bash
    docker-compose up --build -d
    ```
   - The application should now be running, and the database will be initialized using the backup.sql file.
4. **Acess phpMyadmin**
    ```bash
    http://localhost:8080/
    ```
5. **Acess the application**
    ```bash
    http://localhost:8000/
    ```
   
6. **Login**
    ```bash
    username: admin
    password: admin
    ```