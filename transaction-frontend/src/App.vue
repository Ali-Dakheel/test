<template>
  <div class="transaction-app">
    <div class="app-container">
      
      <!-- Transaction Form Section -->
      <div class="form-section">
        <h2>Submit new transaction</h2>
        
        <form @submit.prevent="submitTransaction" class="transaction-form">
          <div class="form-group">
            <label for="account-id">Account ID:</label>
            <div class="input-group">
              <input 
                id="account-id"
                v-model="formData.account_id"
                data-type="account-id" 
                type="text" 
                placeholder="e.g., 507489c9-e435-9354-634b-4f4c60456514"
                required 
              />
              <button 
                type="button" 
                @click="generateSampleUUID" 
                class="uuid-btn"
                title="Generate sample UUID"
              >
                📝
              </button>
            </div>
          </div>
          
          <div class="form-group">
            <label for="amount">Amount:</label>
            <input 
              id="amount"
              v-model.number="formData.amount"
              data-type="amount" 
              type="number" 
              step="0.01"
              placeholder="Enter amount (+ for deposit, - for withdrawal)"
              required 
            />
          </div>
          
          <input 
            data-type="transaction-submit" 
            type="submit" 
            value="Submit"
            :disabled="isLoading"
            class="submit-btn"
          />
        </form>
        
        <div v-if="message" :class="['message', messageType]">
          {{ message }}
        </div>
      </div>
      
      <!-- Transaction History Section -->
      <div class="history-section">
        <h2>Transaction History</h2>
        
        <div class="transactions-list">
          <!-- Individual Transaction Items - Following exact HTML structure from specification -->
          <div 
            v-for="(transaction, index) in transactions" 
            :key="transaction.id"
            data-type="transaction"
            :data-account-id="transaction.account_id"
            :data-amount="transaction.amount"
            :data-balance="index === 0 ? currentBalance : undefined"
            class="transaction-item"
          >
            <!-- Transaction account ID -->
            <div class="transaction-account">
              Transaction account ID: {{ transaction.account_id }}
            </div>
            
            <!-- The current account balance (only for most recent transaction) -->
            <div v-if="index === 0" class="current-balance">
              The current account balance is {{ currentBalance.toFixed(2) }}
            </div>
            
            <!-- Transaction amount with type -->
            <div class="transaction-amount" :class="getAmountClass(transaction.amount)">
              Transaction amount ({{ getTransactionLabel(transaction.amount) }}): {{ Math.abs(parseFloat(transaction.amount)).toFixed(2) }}
            </div>
            
            <!-- Additional transaction info -->
            <div class="transaction-details">
              {{ getTransactionDescription(transaction) }}
            </div>
          </div>
          
          <!-- Empty state -->
          <div v-if="transactions.length === 0" class="no-transactions">
            <p>No transactions yet. Submit your first transaction above!</p>
          </div>
          
          <!-- Loading state -->
          <div v-if="isLoadingTransactions" class="loading">
            <p>Loading transactions...</p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'TransactionApp',
  
  data() {
    return {
      // Form data
      formData: {
        account_id: '',
        amount: ''
      },
      
      // Transaction data
      transactions: [],
      currentBalance: 0,
      
      // UI state
      isLoading: false,
      isLoadingTransactions: true,
      message: '',
      messageType: 'success' // 'success' or 'error'
    }
  },
  
  mounted() {
    // Load transactions when component mounts
    this.fetchTransactions()
  },
  
  methods: {
    /**
     * Generate a sample UUID for testing
     */
    generateSampleUUID() {
      console.log('Generating UUID...') // Debug log
      
      try {
        // Try modern crypto API first
        if (crypto && crypto.randomUUID) {
          this.formData.account_id = crypto.randomUUID()
        } else {
          // Fallback UUID v4 generator
          const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0
            const v = c === 'x' ? r : (r & 0x3 | 0x8)
            return v.toString(16)
          })
          this.formData.account_id = uuid
        }
        
        console.log('Generated UUID:', this.formData.account_id) // Debug log
        this.showMessage('Sample UUID generated!', 'success')
        
      } catch (error) {
        console.error('UUID generation error:', error)
        // Simple fallback
        this.formData.account_id = '507489c9-e435-9354-634b-4f4c60456514'
        this.showMessage('Using sample UUID', 'success')
      }
    },
    
    /**
     * Fetch all transactions from the API
     */
    async fetchTransactions() {
      console.log('🔄 Fetching transactions...')
      this.isLoadingTransactions = true
      
      try {
        console.log('📡 Making API request to:', 'http://localhost:8000/api/transactions')
        const response = await axios.get('http://localhost:8000/api/transactions')
        
        console.log('✅ API Response received:', response.data)
        console.log('📊 Transactions count:', response.data.transactions?.length || 0)
        console.log('💰 Current balance:', response.data.current_balance)
        
        this.transactions = response.data.transactions || []
        // Convert string balance to number for calculations
        this.currentBalance = parseFloat(response.data.current_balance) || 0
        
        if (this.transactions.length === 0) {
          console.log('ℹ️ No transactions found - this is normal for a fresh install')
        }
        
      } catch (error) {
        console.error('❌ Error fetching transactions:', error)
        console.error('📋 Error details:', {
          message: error.message,
          response: error.response?.data,
          status: error.response?.status,
          statusText: error.response?.statusText
        })
        
        this.showMessage('Failed to load transactions. Check console for details.', 'error')
      } finally {
        this.isLoadingTransactions = false
        console.log('🏁 Transaction loading finished')
      }
    },
    
    /**
     * Submit a new transaction
     */
    async submitTransaction() {
      if (!this.validateForm()) {
        return
      }
      
      console.log('📤 Submitting transaction:', this.formData)
      this.isLoading = true
      this.clearMessage()
      
      try {
        const response = await axios.post('http://localhost:8000/api/transactions', this.formData)
        
        console.log('✅ Transaction created:', response.data)
        
        // Clear form on successful submission
        this.formData.account_id = ''
        this.formData.amount = ''
        
        // Refresh transactions to show the new one
        await this.fetchTransactions()
        
        this.showMessage('Transaction submitted successfully!', 'success')
        
      } catch (error) {
        console.error('❌ Transaction submission error:', error)
        console.error('📋 Error details:', {
          message: error.message,
          response: error.response?.data,
          status: error.response?.status
        })
        
        if (error.response && error.response.data && error.response.data.errors) {
          // Handle validation errors from Laravel
          const errors = Object.values(error.response.data.errors).flat()
          this.showMessage(`Validation error: ${errors.join(', ')}`, 'error')
        } else if (error.response?.status === 500) {
          this.showMessage('Server error. Check Laravel logs for details.', 'error')
        } else {
          this.showMessage('Failed to submit transaction. Check console for details.', 'error')
        }
      } finally {
        this.isLoading = false
      }
    },
    
    /**
     * Basic form validation
     */
    validateForm() {
      if (!this.formData.account_id.trim()) {
        this.showMessage('Please enter an account ID', 'error')
        return false
      }
      
      if (!this.formData.amount || this.formData.amount === 0) {
        this.showMessage('Please enter a valid amount (cannot be zero)', 'error')
        return false
      }
      
      return true
    },
    
    /**
     * Get transaction label (deposit/withdrawal)
     */
    getTransactionLabel(amount) {
      const numAmount = parseFloat(amount)
      return numAmount > 0 ? 'deposit' : 'withdrawal'
    },
    
    /**
     * Get transaction type string based on amount
     */
    getTransactionType(amount) {
      const numAmount = parseFloat(amount)
      return numAmount > 0 ? 'Deposited' : 'Withdrawn'
    },
    
    /**
     * Get detailed transaction description
     */
    getTransactionDescription(transaction) {
      const amount = parseFloat(transaction.amount)
      const verb = 'Transferred'
      const absAmount = Math.abs(amount).toFixed(2)
      const direction = amount > 0 ? 'to' : 'from'
      
      return `${verb} ${absAmount} ${direction} account ${transaction.account_id}`
    },
    
    /**
     * Get CSS class for amount styling
     */
    getAmountClass(amount) {
      const numAmount = parseFloat(amount)
      return numAmount > 0 ? 'deposit' : 'withdrawal'
    },
    
    /**
     * Get CSS class for amount styling
     */
    getAmountClass(amount) {
      return amount > 0 ? 'deposit' : 'withdrawal'
    },
    
    /**
     * Format date for display
     */
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },
    
    /**
     * Show a message to the user
     */
    showMessage(text, type = 'success') {
      this.message = text
      this.messageType = type
      
      // Auto-clear success messages after 3 seconds
      if (type === 'success') {
        setTimeout(() => {
          this.clearMessage()
        }, 3000)
      }
    },
    
    /**
     * Clear the current message
     */
    clearMessage() {
      this.message = ''
    }
  }
}
</script>

<style scoped>
/* Main container */
.transaction-app {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f8f9fa;
  min-height: 100vh;
}

.app-container {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 30px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

/* Form Section */
.form-section {
  padding: 30px;
  background: #ffffff;
  border-right: 1px solid #e9ecef;
}

.form-section h2 {
  margin: 0 0 20px 0;
  color: #343a40;
  font-size: 1.5rem;
  font-weight: 600;
}

.transaction-form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #495057;
}

.form-group input[type="text"],
.form-group input[type="number"] {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e9ecef;
  border-radius: 6px;
  font-size: 16px;
  transition: border-color 0.2s ease;
  box-sizing: border-box;
}

.input-group {
  display: flex;
  gap: 8px;
  align-items: center;
}

.input-group input {
  flex: 1;
}

.uuid-btn {
  background: #6c757d;
  color: white;
  border: none;
  padding: 12px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.2s ease;
  min-width: 50px;
}

.uuid-btn:hover {
  background: #5a6268;
}

.form-group input:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.submit-btn {
  background: #007bff;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s ease;
  margin-top: 10px;
}

.submit-btn:hover:not(:disabled) {
  background: #0056b3;
}

.submit-btn:disabled {
  background: #6c757d;
  cursor: not-allowed;
  opacity: 0.6;
}

/* Message styling */
.message {
  margin-top: 15px;
  padding: 12px 16px;
  border-radius: 6px;
  font-weight: 500;
}

.message.success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.message.error {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

/* History Section */
.history-section {
  padding: 30px;
  background: #f8f9fa;
}

.history-section h2 {
  margin: 0 0 20px 0;
  color: #343a40;
  font-size: 1.5rem;
  font-weight: 600;
}

.transactions-list {
  background: white;
  border-radius: 8px;
  border: 1px solid #e9ecef;
  max-height: 600px;
  overflow-y: auto;
}

/* Transaction Items - Updated for exact HTML structure compliance */
.transaction-item {
  border-bottom: 1px solid #e9ecef;
  transition: background-color 0.2s ease;
  padding: 20px;
}

.transaction-item:hover {
  background: #f8f9fa;
}

.transaction-item:last-child {
  border-bottom: none;
}

.transaction-account {
  font-weight: 600;
  color: #343a40;
  margin-bottom: 8px;
  font-size: 1.1rem;
}

.current-balance {
  color: #6c757d;
  font-weight: 500;
  margin-bottom: 8px;
  padding: 8px 12px;
  background: #e3f2fd;
  border-radius: 4px;
  border-left: 4px solid #2196f3;
}

.transaction-amount {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 8px;
}

.transaction-amount.deposit {
  color: #28a745;
}

.transaction-amount.withdrawal {
  color: #dc3545;
}

.transaction-details {
  color: #6c757d;
  font-size: 0.95rem;
  font-style: italic;
}

/* Empty and loading states */
.no-transactions, .loading {
  padding: 40px 20px;
  text-align: center;
  color: #6c757d;
}

.loading {
  font-style: italic;
}

/* Responsive design */
@media (max-width: 768px) {
  .app-container {
    grid-template-columns: 1fr;
    gap: 0;
  }
  
  .form-section {
    border-right: none;
    border-bottom: 1px solid #e9ecef;
  }
  
  .transaction-item {
    padding: 15px;
  }
  
  .transaction-account {
    font-size: 1rem;
  }
  
  .current-balance {
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .transaction-app {
    padding: 10px;
  }
  
  .form-section, .history-section {
    padding: 20px;
  }
  
  .transaction-item {
    padding: 12px;
  }
}
</style>