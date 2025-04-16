# Scandi E-commerce Project

A modern, full-stack e-commerce application built with React, PHP, and MySQL. This project demonstrates a clean architecture and modern development practices.

## 🚀 Features

- Modern React frontend with TypeScript
- GraphQL API backend with PHP
- MySQL database with Docker support
- Containerized development environment
- Real-time product management
- Responsive design
- Type-safe development

## 🏗️ Architecture

### Frontend (React + TypeScript)
- Built with React 19 and TypeScript
- Vite for fast development and building
- Apollo Client for GraphQL integration
- React Router for navigation
- SASS for styling

### Backend (PHP)
- PHP 8.1 with Composer
- GraphQL implementation using webonyx/graphql-php
- Fast routing with nikic/fast-route
- PSR-4 autoloading
- MySQL database integration

### Database (MySQL)
- MySQL 8.0
- Docker volume for data persistence
- Initialization scripts support

## 📋 Prerequisites

- Docker and Docker Compose
- Node.js 18+ (for local development)
- Git

## 🛠️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/scandi-ecommerce-test.git
   cd scandi-ecommerce-test
   ```

2. Create environment file:
   ```bash
   cp .env.example .env
   ```
   Edit `.env` with your configuration:
   ```
   DBHOST=mysql:3306
   DBNAME=data
   DBUSER=user
   DBPASSWORD=mysqlpasswordDB
   ```

3. Start the application:
   ```bash
   docker-compose up -d
   ```

## 🔧 Development Setup

### Frontend Development

1. Navigate to frontend directory:
   ```bash
   cd frontend
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Start development server:
   ```bash
   npm run dev
   ```

### Backend Development

The backend runs in a Docker container with hot-reloading enabled. Changes to PHP files will be reflected immediately.

### Database Management

The MySQL database is accessible at:
- Host: localhost
- Port: 3306
- Database: data
- User: user

## 📦 Available Scripts

### Frontend
- `npm run dev`: Start development server
- `npm run build`: Build for production
- `npm run lint`: Run ESLint
- `npm run preview`: Preview production build

### Backend
- `composer install`: Install PHP dependencies
- `composer dump-autoload`: Update autoloader

## 🔍 Project Structure

```
scandi-ecommerce-test/
├── frontend/              # React frontend
│   ├── src/              # Source files
│   ├── public/           # Static files
│   └── package.json      # Frontend dependencies
├── backend/              # PHP backend
│   ├── src/             # PHP source files
│   ├── public/          # Public files
│   └── composer.json    # PHP dependencies
├── mysql/               # Database scripts
├── docker-compose.yml   # Docker configuration
└── .env                 # Environment variables
```

## 🔌 API Documentation

The backend exposes a GraphQL API at `http://localhost:8000/graphql`. Use the GraphQL playground to explore the API schema and test queries.

## 🧪 Testing

### Frontend Tests
```bash
cd frontend
npm test
```

### Backend Tests
```bash
docker-compose exec php vendor/bin/phpunit
```

## 🚫 Troubleshooting

1. Database Issues:
   ```bash
   docker-compose logs mysql
   ```

2. Frontend Issues:
   ```bash
   docker-compose logs frontend
   ```

3. Backend Issues:
   ```bash
   docker-compose logs php
   ```

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For support, please open an issue in the GitHub repository.

## 🙏 Acknowledgments

- [React](https://reactjs.org/)
- [PHP](https://www.php.net/)
- [MySQL](https://www.mysql.com/)
- [Docker](https://www.docker.com/)
