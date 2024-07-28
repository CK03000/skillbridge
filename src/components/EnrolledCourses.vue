<template>
  <div>
    <h1>Enrolled Courses</h1>
    <div v-if="loading">Loading enrolled courses...</div>
    <div v-else-if="error">{{ error }}</div>
    <div v-else class="course-list">
      <div v-for="course in enrolledCourses" :key="course.id" class="course-item">
        <h2>{{ course.title }}</h2>
        <p>{{ course.description }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['username'],
  data() {
    return {
      enrolledCourses: [],
      loading: true,
      error: null
    };
  },
  methods: {
    fetchEnrolledCourses() {
      fetch('https://mercury.swin.edu.au/cos30043/s104202680/6.2/fetch_enrolled_courses.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username: this.username })
      })
        .then(response => response.json())
        .then(data => {
          this.enrolledCourses = data;
          this.loading = false;
        })
        .catch(error => {
          this.error = 'Error fetching enrolled courses';
          this.loading = false;
        });
    }
  },
  created() {
    this.fetchEnrolledCourses();
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
</style>
