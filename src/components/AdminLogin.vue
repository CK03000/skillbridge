<template>
    <div class="admin-login-container">
      <h1>Admin Login</h1>
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email" required />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <button type="submit">Login</button>
      </form>
      <div v-if="error" class="error">{{ error }}</div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: '',
        password: '',
        error: null
      };
    },
    methods: {
      submitForm() {
        const userData = {
          email: this.email,
          password: this.password
        };
  
        fetch('https://mercury.swin.edu.au/cos30043/s104202680/6.2/admin_login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(userData)
        })
        .then(response => response.text())
        .then(text => {
          console.log('Raw response:', text);
          try {
            const data = JSON.parse(text);
            if (data.success && data.role === 'admin') {
              this.$router.push({ name: 'admin' });
            } else {
              throw new Error(data.message || 'Login failed');
            }
          } catch (error) {
            throw new Error('Error parsing response: ' + text);
          }
        })
        .catch(error => {
          console.error('Error during login:', error);
          this.error = error.message;
        });
      }
    }
  };
  </script>
  
  <style scoped>
  /* Add styles to match the theme */
  .admin-login-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 1rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
  }
  
  .form-group {
    margin-bottom: 1rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
  }
  
  .form-group input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  button {
    padding: 0.5rem 1rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  
  .error {
    color: red;
    margin-top: 1rem;
  }
  </style>
  