<template>
  <div>
    <header>
      <div class="header-content">
        <h1 class="logo">SkillBridge</h1>
        <nav>
          <button @click="navigateToHome">Home</button>
          <button @click="navigateToLogin">Login</button>
        </nav>
      </div>
    </header>
    <div class="signup-container">
      <h1>Sign Up</h1>
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" v-model="username" required />
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email" required />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <button type="submit">Sign Up</button>
      </form>
      <div v-if="error" class="error">{{ error }}</div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      email: '',
      password: '',
      error: null
    };
  },
  methods: {
    submitForm() {
      const userData = {
        username: this.username,
        email: this.email,
        password: this.password
      };

      fetch('https://mercury.swin.edu.au/cos30043/s104202680/6.2/signup.php', {
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
          if (data.success) {
            alert('Signup successful!');
          } else {
            throw new Error(data.message || 'Signup failed');
          }
        } catch (error) {
          throw new Error('Error parsing response: ' + text);
        }
      })
      .catch(error => {
        console.error('Error during signup:', error);
        this.error = error.message;
      });
    },
    navigateToHome() {
      this.$router.push('/');
    },
    navigateToLogin() {
      this.$router.push('/login');
    }
  }
};
</script>

<style scoped>
/* General styles */
body {
  font-family: Arial, sans-serif;
  color: #333;
  margin: 0;
  padding: 0;
}

header {
  background-color: #4CAF50;
  color: white;
  padding: 1rem 0;
  position: sticky;
  top: 0;
  width: 100%;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.logo {
  margin: 0;
  font-size: 1.5rem;
}

nav button {
  margin-left: 1rem;
  padding: 0.5rem 1rem;
  background-color: #fff;
  color: #4CAF50;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

nav button:hover {
  background-color: #e0e0e0;
}

/* Signup container styles */
.signup-container {
  max-width: 400px;
  margin: 2rem auto;
  padding: 2rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #fff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.signup-container h1 {
  margin-top: 0;
  color: #4CAF50;
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
