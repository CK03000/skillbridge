<template>
    <div>
      <header>
        <div class="header-content">
          <h1 class="logo">SkillBridge Admin</h1>
          <nav>
            <button @click="navigateToAddCourse">Add Course</button>
            <button @click="navigateToEditCourse">Edit Course</button>
            <button @click="navigateToDeleteCourse">Delete Course</button>
            <button @click="logout">Logout</button>
          </nav>
        </div>
      </header>
      <div class="admin-container">
        <h1>Admin Panel</h1>
        
        <div v-if="showAddCourseForm">
          <h2>Add Course</h2>
          <form @submit.prevent="addCourse">
            <div class="form-group">
              <label for="title">Course Title:</label>
              <input type="text" id="title" v-model="newCourse.title" required />
            </div>
            <div class="form-group">
              <label for="description">Course Description:</label>
              <textarea id="description" v-model="newCourse.description" required></textarea>
            </div>
            <button type="submit">Add Course</button>
          </form>
          <div v-if="addCourseError" class="error">{{ addCourseError }}</div>
          <div v-if="addCourseSuccess" class="success">{{ addCourseSuccess }}</div>
        </div>
        
        <div v-if="showEditCourse">
          <h2>Edit Course</h2>
          <div v-if="loading">Loading courses...</div>
          <div v-else-if="error">{{ error }}</div>
          <div v-else class="course-list">
            <div v-for="course in paginatedCourses" :key="course.id" class="course-item">
              <h2>{{ course.title }}</h2>
              <p>{{ course.description }}</p>
              <button class="edit-button" @click="editCourse(course)">Edit</button>
            </div>
          </div>
          <div class="pagination">
            <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
            <span>Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
          </div>
        </div>
  
        <div v-if="showEditCourseForm">
          <h2>Edit Course</h2>
          <form @submit.prevent="updateCourse">
            <div class="form-group">
              <label for="editTitle">Course Title:</label>
              <input type="text" id="editTitle" v-model="currentCourse.title" required />
            </div>
            <div class="form-group">
              <label for="editDescription">Course Description:</label>
              <textarea id="editDescription" v-model="currentCourse.description" required></textarea>
            </div>
            <button type="submit">Update Course</button>
          </form>
          <div v-if="editCourseError" class="error">{{ editCourseError }}</div>
          <div v-if="editCourseSuccess" class="success">{{ editCourseSuccess }}</div>
        </div>
  
        <div v-if="showDeleteCourse">
          <h2>Delete Course</h2>
          <div v-if="loading">Loading courses...</div>
          <div v-else-if="error">{{ error }}</div>
          <div v-else class="course-list">
            <div v-for="course in paginatedCourses" :key="course.id" class="course-item">
              <h2>{{ course.title }}</h2>
              <p>{{ course.description }}</p>
              <button class="delete-button" @click="deleteCourse(course.id)">Delete</button>
            </div>
          </div>
          <div class="pagination">
            <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
            <span>Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        showAddCourseForm: false,
        showEditCourse: false,
        showDeleteCourse: false,
        showEditCourseForm: false,
        newCourse: {
          title: '',
          description: ''
        },
        currentCourse: {
          id: null,
          title: '',
          description: ''
        },
        courses: [],
        loading: true,
        error: null,
        addCourseError: null,
        addCourseSuccess: null,
        editCourseError: null,
        editCourseSuccess: null,
        currentPage: 1,
        itemsPerPage: 2
      };
    },
    computed: {
      paginatedCourses() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.courses.slice(start, end);
      },
      totalPages() {
        return Math.ceil(this.courses.length / this.itemsPerPage);
      }
    },
    methods: {
      navigateToAddCourse() {
        this.showAddCourseForm = true;
        this.showEditCourse = false;
        this.showDeleteCourse = false;
        this.showEditCourseForm = false;
      },
      navigateToEditCourse() {
        this.showEditCourse = true;
        this.showAddCourseForm = false;
        this.showDeleteCourse = false;
        this.showEditCourseForm = false;
        this.fetchCourses();
      },
      navigateToDeleteCourse() {
        this.showDeleteCourse = true;
        this.showAddCourseForm = false;
        this.showEditCourse = false;
        this.showEditCourseForm = false;
        this.fetchCourses();
      },
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
      addCourse() {
        const courseData = {
          title: this.newCourse.title,
          description: this.newCourse.description
        };
  
        fetch('https://mercury.swin.edu.au/cos30043/s104202680/6.2/add_course.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(courseData)
        })
          .then(response => response.text())
          .then(text => {
            console.log('Raw response:', text);
            try {
              const data = JSON.parse(text);
              if (data.success) {
                this.addCourseSuccess = 'Course added successfully!';
                this.addCourseError = null;
                this.newCourse.title = '';
                this.newCourse.description = '';
              } else {
                throw new Error(data.message || 'Failed to add course');
              }
            } catch (error) {
              throw new Error('Error parsing response: ' + text);
            }
          })
          .catch(error => {
            console.error('Error adding course:', error);
            this.addCourseError = error.message;
            this.addCourseSuccess = null;
          });
      },
      deleteCourse(courseId) {
        fetch(`https://mercury.swin.edu.au/cos30043/s104202680/6.2/delete_course.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ course_id: courseId })
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              this.courses = this.courses.filter(course => course.id !== courseId);
            } else {
              throw new Error(data.message || 'Failed to delete course');
            }
          })
          .catch(error => {
            console.error('Error deleting course:', error);
            this.error = error.message;
          });
      },
      editCourse(course) {
        this.currentCourse = { ...course };
        this.showEditCourseForm = true;
        this.showEditCourse = false;
      },
      updateCourse() {
        const courseData = {
          id: this.currentCourse.id,
          title: this.currentCourse.title,
          description: this.currentCourse.description
        };
  
        fetch('https://mercury.swin.edu.au/cos30043/s104202680/6.2/update_course.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(courseData)
        })
          .then(response => response.text())
          .then(text => {
            console.log('Raw response:', text);
            try {
              const data = JSON.parse(text);
              if (data.success) {
                this.editCourseSuccess = 'Course updated successfully!';
                this.editCourseError = null;
                this.fetchCourses();
              } else {
                throw new Error(data.message || 'Failed to update course');
              }
            } catch (error) {
              throw new Error('Error parsing response: ' + text);
            }
          })
          .catch(error => {
            console.error('Error updating course:', error);
            this.editCourseError = error.message;
            this.editCourseSuccess = null;
          });
      },
      prevPage() {
        if (this.currentPage > 1) {
          this.currentPage--;
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.currentPage++;
        }
      },
      logout() {
        this.$router.push('/');
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
  
  /* Admin container styles */
  .admin-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
  }
  
  .button-group {
    display: flex;
    flex-direction: row;
    gap: 1rem;
    margin-bottom: 2rem;
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
  
  .form-group {
    margin-bottom: 1rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
  }
  
  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  .course-list {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .course-item {
    border: 1px solid #ccc;
    padding: 1rem;
    flex: 1 1 calc(50% - 1rem);
    box-sizing: border-box;
  }
  
  .edit-button {
    background-color: #f0ad4e;
  }
  
  .edit-button:hover {
    background-color: #ec971f;
  }
  
  .delete-button {
    background-color: #ff4d4d;
  }
  
  .delete-button:hover {
    background-color: #cc0000;
  }
  
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 1rem;
  }
  
  .error {
    color: red;
    margin-top: 1rem;
  }
  
  .success {
    color: green;
    margin-top: 1rem;
  }
  </style>
  