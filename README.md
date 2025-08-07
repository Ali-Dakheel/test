# 🏦 Financial Transaction Management System

A full-stack web application for recording and viewing financial transactions with real-time balance tracking. Built with **Laravel** (PHP) backend API and **Vue.js** (JavaScript) frontend.

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

## 📋 Project Overview

This application demonstrates modern full-stack development practices by implementing a comprehensive financial transaction system that allows users to:

- ✅ Record financial transactions (deposits and withdrawals)
- ✅ View transaction history in reverse chronological order
- ✅ Track running account balances automatically
- ✅ Handle multiple accounts with UUID-based identification
- ✅ Validate data on both frontend and backend
- ✅ Access interactive API documentation

## 🎯 Live Demo

- **Frontend Application**: `http://localhost:5173`
- **API Endpoints**: `http://localhost:8000/api`
- **Interactive API Documentation**: `http://localhost:8000/api/documentation`

## 🚀 Features Implemented

### **Core Functionality**
- **Transaction Recording**: Submit deposits (+) and withdrawals (-) with automatic balance calculation
- **Transaction History**: View all transactions ordered by newest first
- **Balance Tracking**: Real-time current balance display for each account
- **Multi-Account Support**: Handle multiple accounts using UUID identifiers
- **Data Persistence**: Full database integration with proper schema design

### **Technical Excellence**
- **RESTful API**: Clean, documented API following OpenAPI 3.0 standards
- **Input Validation**: Comprehensive client-side and server-side validation
- **Error Handling**: Proper error responses and user feedback
- **CORS Configuration**: Proper cross-origin resource sharing setup
- **Responsive Design**: Works seamlessly on desktop and mobile devices

### **Professional Standards**
- **API Documentation**: Interactive Swagger UI documentation
- **Code Quality**: Clean, commented, and well-structured code
- **Security**: SQL injection prevention, input sanitization
- **User Experience**: Loading states, success/error messages, form validation

## 🛠️ Technology Stack

### **Backend**
- **Framework**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL/SQLite with Eloquent ORM
- **API**: RESTful endpoints with JSON responses
- **Validation**: Laravel Form Requests with custom rules
- **Documentation**: OpenAPI 3.0 specification with Swagger UI

### **Frontend**
- **Framework**: Vue.js 3 with Composition API
- **HTTP Client**: Axios for API communication
- **Build Tool**: Vite for development and bundling
- **Styling**: Modern CSS with responsive design
- **Form Handling**: Reactive form validation and submission

### **Development Tools**
- **API Documentation**: Swagger UI for interactive docs
- **CORS**: Configured for local development
- **Error Logging**: Laravel logging for debugging
- **Console Debugging**: Detailed browser console logging

## 📁 Project Structure

```
financial-transaction-system/
├── README.md                            # Project documentation
├── .gitignore                           # Git ignore rules
├── backend/ (Laravel)
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   │   └── TransactionController.php     # API endpoints
│   │   └── Models/
│   │       └── Transaction.php               # Database model
│   ├── database/migrations/
│   │   └── create_transactions_table.php     # Database schema
│   ├── routes/
│   │   ├── api.php                          # API routes
│   │   └── web.php                          # Documentation routes
│   ├── public/api-docs/
│   │   └── accounting-api.yaml              # OpenAPI specification
│   ├── resources/views/
│   │   └── api-documentation.blade.php      # Swagger UI page
│   ├── config/cors.php                      # CORS configuration
│   ├── .env.example                         # Environment template
│   └── composer.json                        # PHP dependencies
│
└── frontend/ (Vue.js)
    ├── src/
    │   ├── App.vue                          # Main application component
    │   └── main.js                          # Application entry point
    ├── package.json                         # Node dependencies
    ├── vite.config.js                       # Build configuration
    └── .env.example                         # Environment template
```

## 🚀 Quick Start

### **Prerequisites**
- PHP 8.1+ with Composer
- Node.js 16+ with npm
- MySQL or SQLite database

### **Backend Setup (Laravel)**

```bash
# Clone the repository
git clone [your-repo-url]
cd financial-transaction-system/backend

# Install PHP dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Setup database (SQLite for simplicity)
touch database/database.sqlite

# Update .env for SQLite
sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
sed -i 's/DB_DATABASE=laravel/DB_DATABASE=/' .env

# Run migrations
php artisan migrate

# Start Laravel server
php artisan serve
# Access at: http://localhost:8000
```

### **Frontend Setup (Vue.js)**

```bash
# Navigate to frontend directory
cd ../frontend

# Install Node dependencies
npm install

# Configure environment
cp .env.example .env

# Start development server
npm run dev
# Access at: http://localhost:5173
```

## 📖 API Documentation

### **Interactive Documentation**
Visit `http://localhost:8000/api/documentation` for full interactive API docs with "Try it out" functionality.

### **API Endpoints**

#### **GET /api/transactions**
Retrieve all transactions with current balance.

**Response:**
```json
{
  "transactions": [
    {
      "id": 1,
      "account_id": "507489c9-e435-9354-634b-4f4c60456514",
      "amount": 500.00,
      "balance": 1250.75,
      "created_at": "2024-08-07T10:30:00Z",
      "updated_at": "2024-08-07T10:30:00Z"
    }
  ],
  "current_balance": 1250.75
}
```

#### **POST /api/transactions**
Create a new transaction.

**Request:**
```json
{
  "account_id": "507489c9-e435-9354-634b-4f4c60456514",
  "amount": 100.50
}
```

**Response:**
```json
{
  "message": "Transaction created successfully",
  "transaction": {
    "id": 2,
    "account_id": "507489c9-e435-9354-634b-4f4c60456514",
    "amount": 100.50,
    "balance": 1351.25,
    "created_at": "2024-08-07T11:00:00Z",
    "updated_at": "2024-08-07T11:00:00Z"
  }
}
```

## 🧪 Testing the Application

### **Manual Testing Steps**

1. **Start both servers** (Laravel on :8000, Vue on :5173)
2. **Open the frontend** at `http://localhost:5173`
3. **Generate a UUID** using the 📝 button next to Account ID
4. **Submit a deposit** (positive amount, e.g., 500.00)
5. **Submit a withdrawal** (negative amount, e.g., -200.50)
6. **Verify balance calculation** in the transaction list
7. **Test API directly** at `http://localhost:8000/api/transactions`
8. **Try API documentation** at `http://localhost:8000/api/documentation`

### **Sample Test Data**
```
Account ID: 507489c9-e435-9354-634b-4f4c60456514
Deposit: 1000.00
Withdrawal: -250.75
Expected Balance: 749.25
```

### **Testing Different Scenarios**
- **Valid transactions**: Positive and negative amounts
- **Invalid data**: Empty fields, non-UUID account IDs, zero amounts
- **Error handling**: Server errors, network failures
- **Edge cases**: Very large amounts, multiple accounts

## 🔧 Configuration Details

### **Database Schema**
```sql
CREATE TABLE transactions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    account_id CHAR(36) NOT NULL,  -- UUID format
    amount DECIMAL(10,2) NOT NULL, -- Positive for deposits, negative for withdrawals
    balance DECIMAL(10,2) NOT NULL, -- Running balance after transaction
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX idx_account_id (account_id)
);
```

### **Environment Configuration**

**Backend (.env):**
```env
APP_NAME="Financial Transaction System"
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
DB_DATABASE=
```

**Frontend (.env):**
```env
VITE_API_BASE_URL=http://localhost:8000/api
```

## 🎯 Key Features for Review

### **Technical Implementation**
- **OpenAPI 3.0 Specification**: Complete, interactive API documentation
- **UUID Validation**: Proper account ID format validation with automatic generation
- **Decimal Precision**: Financial calculations with proper decimal handling
- **Error Handling**: Comprehensive error responses and user feedback
- **CORS Setup**: Properly configured cross-origin requests

### **Code Quality**
- **Clean Architecture**: Separation of concerns between frontend and backend
- **Input Validation**: Both client-side and server-side validation
- **Error Logging**: Detailed logging for debugging and monitoring
- **Responsive Design**: Mobile-friendly user interface
- **Professional Styling**: Modern, clean user interface

### **Business Logic**
- **Balance Calculation**: Automatic running balance per account
- **Transaction Types**: Support for both deposits and withdrawals
- **Data Integrity**: Proper database constraints and validation
- **Multi-Account Support**: Handle multiple accounts independently

## 🚀 Deployment Considerations

### **Production Readiness**
- Environment-specific configuration files
- Database connection pooling and optimization
- API rate limiting and throttling
- Error monitoring and logging systems
- Security headers and enhanced validation
- SSL/TLS certificate configuration

### **Potential Enhancements**
- User authentication and authorization (Laravel Sanctum)
- Transaction categories and descriptions
- Date range filtering and advanced search
- Export functionality (CSV, PDF reports)
- Real-time updates via WebSockets
- Pagination for large transaction datasets
- Multi-currency support
- Transaction history graphs and analytics

## 👨‍💻 Development Process

This project was built following modern development practices:

1. **Requirements Analysis**: Carefully reviewed specification images for exact requirements
2. **API Design**: Created comprehensive OpenAPI specification first
3. **Backend Development**: Laravel API with proper validation and error handling
4. **Frontend Development**: Vue.js with reactive components and modern UX
5. **Integration**: Connected frontend and backend with proper CORS configuration
6. **Testing**: Manual testing with various scenarios and edge cases
7. **Documentation**: Comprehensive API and project documentation

## 🔍 Troubleshooting

### **Common Issues**

**CORS Errors:**
- Ensure `config/cors.php` allows `http://localhost:5173`
- Run `php artisan config:clear` after changes

**Database Connection:**
- Check `.env` database configuration
- Ensure SQLite file exists or MySQL is running
- Run `php artisan migrate` to create tables

**Frontend Build Issues:**
- Delete `node_modules` and run `npm install`
- Check Node.js version compatibility
- Verify Vite configuration

**API Not Responding:**
- Confirm Laravel server is running on port 8000
- Check `storage/logs/laravel.log` for errors
- Verify API routes with `php artisan route:list`

### **Development Tools**
- **Laravel Logs**: `tail -f storage/logs/laravel.log`
- **Browser Console**: F12 → Console for frontend debugging
- **API Testing**: Use Postman or curl for direct API testing
- **Database**: Use phpMyAdmin, Sequel Pro, or Laravel Tinker

## 📞 Support

For questions or issues:
- Check the [Interactive API Documentation](http://localhost:8000/api/documentation)
- Review browser console for detailed error information
- Check Laravel logs in `storage/logs/laravel.log`
- Verify both servers are running on correct ports

## 🎓 Learning Outcomes

This project demonstrates proficiency in:

### **Backend Development**
- RESTful API design and implementation
- Database modeling and migrations
- Request validation and error handling
- API documentation with OpenAPI standards

### **Frontend Development**
- Modern JavaScript with Vue.js 3
- Reactive programming and component architecture
- HTTP client integration and error handling
- Responsive web design principles

### **Full-Stack Integration**
- Cross-origin resource sharing (CORS)
- Environment configuration management
- API versioning and documentation
- Professional development workflow

## 📄 License

This project is created for interview/assessment purposes and is available for review and evaluation.

---

**Built with ❤️ using Laravel & Vue.js for modern full-stack development**

### 🌟 **Key Highlights**
- **Complete OpenAPI 3.0 Documentation**
- **Interactive Swagger UI Interface**
- **UUID-based Account Management**
- **Real-time Balance Calculations**
- **Professional Error Handling**
- **Responsive Mobile-First Design**
