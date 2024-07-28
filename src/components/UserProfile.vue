<template>
  <div>
    <header>
      <div class="header-content">
        <h1 class="logo">SkillBridge</h1>
        <nav>
          <button @click="logout">Logout</button>
        </nav>
      </div>
    </header>
    <div class="profile-container">
      <h1>Welcome, {{ username }}</h1>
      <p>Your personal information goes here.</p>
      <div class="button-group">
        <button @click="navigateTo('liked-courses')">Liked Courses</button>
        <button @click="navigateTo('buy-courses')">Buy Courses</button>
        <button @click="navigateTo('enrolled-courses')">Enrolled Courses</button>
        <button @click="navigateTo('bought-courses')">Bought Courses</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['username'],
  methods: {
    navigateTo(section) {
      this.$router.push({ name: section, params: { username: this.username } });
    },
    logout() {
      // Clear any session data if necessary
      this.$router.push({ name: 'home' });
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

/* Profile container styles */
.profile-container {
  max-width: 800px;
  margin: 2rem auto;
  padding: 2rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
  text-align: center;
}

.profile-container h1 {
  font-size: 2rem;
  color: #4CAF50;
}

.profile-container p {
  font-size: 1.2rem;
  color: #666;
  margin-bottom: 2rem;
}

.button-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: 2rem;
}

button {
  padding: 0.75rem 1.5rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1rem;
}

button:hover {
  background-color: #0056b3;
}
</style>
