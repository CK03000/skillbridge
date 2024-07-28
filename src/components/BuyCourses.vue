<template>
  <div>
    <h1>Buy Courses</h1>
    <div v-if="loading">Loading courses...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else class="course-list">
      <div v-for="course in courses" :key="course.id" class="course-item">
        <h2>{{ course.title }}</h2>
        <p>{{ course.description }}</p>
        <button @click="likeCourse(course.id)">Like</button>
        <button @click="enrollCourse(course.id)">Enroll</button>
        <button @click="buyCourse(course.id)">Buy</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['username'],
  data() {
    return {
      courses: [],
      loading: true,
      error: null
    };
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
    likeCourse(courseId) {
      this.updateCourseStatus(courseId, 'like');
    },
    enrollCourse(courseId) {
      this.updateCourseStatus(courseId, 'enroll');
    },
    buyCourse(courseId) {
      const password = prompt('Enter your password to confirm purchase:');
      if (password) {
        this.updateCourseStatus(courseId, 'buy', password);
      }
    },
    updateCourseStatus(courseId, action, password = null) {
      const requestData = {
        username: this.username,
        course_id: courseId,
        action: action,
        password: password
      };

      fetch('https://mercury.swin.edu.au/cos30043/s104202680/6.2/update_course_status.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(`${action.charAt(0).toUpperCase() + action.slice(1)} successful!`);
        } else {
          throw new Error(data.message || 'Action failed');
        }
      })
      .catch(error => {
        alert(`Error during ${action}: ${error.message}`);
      });
    }
  },
  created() {
    this.fetchCourses();
  }
};
</script>

<style scoped>
.course-list {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.course-item {
  border: 1px solid #ccc;
  padding: 1rem;
  width: 30%;
}

button {
  margin-right: 0.5rem;
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
</style>
