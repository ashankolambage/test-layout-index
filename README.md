# Restaurant Management System

### Check the Live Site

You can check the live site here: <https://layoutindex.ashankolambage.me/>

To access the system, use the following login credentials:

- **Email**: user@gmail.com
- **Password**: 123456789

## Description

This project is a **Restaurant Management System** that facilitates the efficient management of orders, concessions, and kitchen operations. The system is built using **Laravel** for the backend and **Vue.js** for the frontend. 

## Live Site Infrastructure

The live site is hosted and managed using the following infrastructure:

- **AWS EC2**: Used as the application server.
- **AWS RDS**: For database management.
- **AWS S3**: To store images and other assets.
- **AWS CloudFront**: For serving assets through a CDN (Content Delivery Network).
- **NGINX**: Configured as the web server and reverse proxy.
- **GitHub Actions**: Implemented for Continuous Integration and Continuous Deployment (CI/CD).

---

## Local Setup Guide

### 1. **Using XAMPP**

**Prerequisites**:
- XAMPP installed

**Steps**:
1. If you are using the zip file attached to the email, simply extract it.
2. If you cloned the repository, create the `.env` file from the `temp.env` file provided.
3. Copy the project folder into the `htdocs` directory of XAMPP.
4. Run migrations and seeders.

### 2. **Using Docker**

**Prerequisites**:
- Docker installed
- Docker Compose installed
- Make installed

**Steps**:

1. If you use zip fiule attached the email jus extract it
2. If you clone form the repository please crea env from all temp.env files
3. Go to docker > docker-dev fodler and open terminal then run make up
4. Then run make shell
5. Run migrations and seeders