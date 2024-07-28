<template>
  <div>
    <header>
      <div class="header-content">
        <h1 class="logo">SkillBridge</h1>
        <nav>
          <button @click="navigateTo('signup')">Sign Up</button>
          <button @click="navigateTo('login')">Login</button>
          <button @click="navigateTo('admin-login')">Admin Login</button>
        </nav>
      </div>
    </header>
    <div class="container">
      <h1>All Courses</h1>
      <div class="search-bar">
        <input type="text" v-model="searchQuery" placeholder="Search Courses" />
        <button @click="searchCourses">Search</button>
      </div>
      <div v-if="loading" class="loading">Loading courses...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <div v-else class="course-list">
        <div v-for="course in filteredCourses" :key="course.id" class="course-item">
          <h2>{{ course.title }}</h2>
          <p>{{ course.description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      courses: [],
      searchQuery: '',
      loading: true,
      error: null
    };
  },
  computed: {
    filteredCourses() {
      return this.courses.filter(course =>
        course.title.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    }
  },
  methods: {
    fetchCourses() {
      fetch('https://mercury.swin.edu.au/cos30043/s104202680/6.2/fetch_courses.php')
        .then(response => response.json())
        .then(data => {
          this.courses = data;
          this.loading = false;
        })
        .catch(error => {
          this.error = 'Error fetching courses';
          this.loading = false;
        });
    },
    searchCourses() {
      // Implement search functionality if needed
    },
    navigateTo(route) {
      this.$router.push({ name: route });
    }
  },
  created() {
    this.fetchCourses();
  }
};
</script>

<style scoped>
.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(to right, #4caf50, #66bb6a);
  padding: 1rem;
}

.logo {
  margin: 0;
  font-size: 1.8rem;
  color: white;
  font-weight: bold;
}

nav button {
  margin-left: 1rem;
  padding: 0.5rem 1rem;
  background-color: #fff;
  color: #4CAF50;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

nav button:hover {
  background-color: #e0e0e0;
}

.container {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 2rem;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  text-align: center;
  margin-bottom: 2rem;
}

.search-bar {
  display: flex;
  justify-content: center;
  margin-bottom: 2rem;
}

.search-bar input {
  padding: 0.5rem;
  width: 300px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-right: 0.5rem;
}

.search-bar button {
  padding: 0.5rem 1rem;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.search-bar button:hover {
  background-color: #45a049;
}

.loading,
.error {
  text-align: center;
  font-size: 1.2rem;
  color: #333;
}

.course-list {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

.course-item {
  border: 1px solid #ccc;
  padding: 1rem;
  width: 280px;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.course-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.course-item h2 {
  margin-top: 0;
  color: #4CAF50;
}

.course-item p {
  color: #555;
}
</style>
